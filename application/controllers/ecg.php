<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


define("QINIU_DOMAIN", "uestcmed.qiniudn.com");

class Ecg extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("qiniu");
	}



	private function upload($filepath, $uploadname, $username) {
		$ret = $this->qiniu->upload_file(
					$username.'/'.$uploadname,
					$filepath
		);
	}

	public function uploadWithProcess() {
	//	$this->load->view('upload_wav_file_demo');
		//var_export($_POST);
		//var_export($_FILES);
		$result = array(
				"error" => 0,
				"error_desc" => ""
			);

		if ($this->input->post("userid") == false || $_FILES['wav_file']['error'] > 0) {
			$this->load->view('upload_wav_file_demo');
		} else {
			$username = $this->input->post('userid');
			$pwd = md5($this->input->post('pwd'));
			if ($this->_checkAuth($username, $pwd) === False)  {
				$result['error'] = 1;
				$result['error_desc'] = "error username or password";
				
			}
//// upload file to qiniu service
			//// key, file
			$time = time();
			$filepath = $_FILES['wav_file']['tmp_name'];
			$outputpath = "/tmp/{$username}_$time/";

			$isWav= end(explode(".", $_FILES['wav_file']['name'])) == 'wav'?True:False;
			if (!$isWav) {
				$filepath = "/tmp/{$username}_$time.wav";
				$cmd = "ffmpeg -i ".$_FILES['wav_file']['tmp_name']. " $filepath";
				exec($cmd);
			}
			$uploadname = $username."/".$time.".wav";
			$ret = $this->qiniu->upload_file(
					$uploadname, 
					$filepath
				);
//// Store link in database
			if ($ret) {
				$this->load->database();
				$data = array(
						'username'=> $username,
						'data' => $time.".wav"
					);
				$this->db->insert('OriginData', $data);
			}
			$cmd = "python2_7 application/Execute/wavefft8k.py $filepath $outputpath 2>&1";
		//	echo $cmd;
			$arr = array();
			exec($cmd, $arr);
			$this->qiniu->upload_file("$username/$time/fft.png", "$outputpath/fft.png");	
			$this->qiniu->upload_file("$username/$time/peaks.png", "$outputpath/peaks.png");	
			$this->qiniu->upload_file("$username/$time/times.png", "$outputpath/times.png");	
			$this->load->database();
			$this->db->insert('ProcessedData', array(
								'username'	=>$username, 
								'data' 		=>$time
									)
							);	
			echo json_encode($result);
		}
		//$this->qiniu->upload_str(time(), time(), "http://weiwangzhan2014.duapp.com/index.php/ecg/callBack/", 'origin_name=$(fname)&tag_name=$(etag)&bucket=$(bucket)&mimeType=$(mimeType)&comment=This-is-just-demo');
		//$this->qiniu->upload_file(__FILE__, __FILE__, "http://weiwangzhan2014.duapp.com/", 'name=$(fname)');
	}

	public function register($username, $pwd_md5, $type) {
		$isDuplicate = $this->_checkUsername($username);
		if ($isDuplicate) {
			$result = array(
					'error' => 1,
					'error_desc' => 'Duplicate username'
				);
			echo json_encode($result);
			return ;
		}
		$this->load->database();
		$data = array(
				'username' => $username,
				'pwd'      => $pwd_md5,
				'type'	   => $type,
				'date'     => time()
			);
		$this->db->insert('UserInfo', $data);
		$result = array(
				'error' => 0,
				'inserted_data' => $data
			);	
		echo json_encode($result);
	}

	private function _getProcessedData($username, $limit) {
		$this->load->database();
		$query = $this->db->query("SELECT * from ProcessedData WHERE username='$username' limit $limit" );
                $data = array();
                foreach ($query->result_array() as $row) {
                	$data[]  = $row['data'];
                }
		return $data;
	}

	private function _getUserList($type) {
		$this->load->database();
		$query = $this->db->query("SELECT username FROM UserInfo WHERE type=$type");
		return $query->result_array();
	}

	public function query($username, $pwd) {
		$isAuth = $this->_checkAuth($username, $pwd);
		$data = array();
		if ($isAuth === False) {
			$result['error'] = 1;
			$result['error_desc'] = "user donot have auth";
		} else {
			$userInfo = $isAuth;
			if ($userInfo->type == 0) {
				$data[] = array(
						'username' => $username,
						'url' 	   => $this->_getProcessedData($username, 10)
					);
			} else if ($userInfo->type == 1) {
				$userlist = $this->_getUserList(0);
				foreach ($userlist as $user) {
					$data[] = array( 
						'username' => $user['username'],
						'url' => $this->_getProcessedData($user['username'], 10)
						);
				}
			}
			$result = array('data' => $data);
		}
		echo json_encode($result);
	}

	private function _checkUsername($username) {
		$this->load->database();
		$query = $this->db->query("SELECT type FROM UserInfo WHERE username='$username'");
		if ($query->num_rows() == 1) {
			return True;
		} else {
			return False;
		}
	}


	private function _checkAuth($username, $pwd) {
		$this->load->database();
        $query = $this->db->query("SELECT * from UserInfo WHERE username='$username' AND pwd='$pwd'");
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
			return False;
        }
	}
	public function checkAuth($username, $pwd) {
		$result = array();
		$result['error'] = 0;
		$check = $this->_checkAuth($username, $pwd);
		if ($check != False) {
			$result['type'] = $check->type;
			echo json_encode($result);
		} else {
			$result['error'] = 1;
			$result['error_desc'] = "cant find username, or password error";
			echo json_encode($result);
		}
	}


	public function download() {
		$filename = implode('/', func_get_args());
		echo "public url: ".$this->qiniu->download_public($filename, QINIU_DOMAIN);
		echo "private url: ".$this->qiniu->download_private($filename, QINIU_DOMAIN);
	}



	public function process() {
		$filename = implode('/', func_get_args());
		//TODO--- download file from qiniu, process through python script and save data, upload it to qiniu again.
		$url = $this->download($filename);
		echo $url;
		//$file = file_get_contents($url);
		//system("python wav_process.py file Objectfile prar");// to be done;
		$this->upload($filename);
		echo "process finished";
	}

	public function uploadEcgData() {
		//var_export($_FILES);

		/// show form 
		if ($this->input->post("username") == false || $_FILES['ecg_file']['error'] > 0) {
			$this->load->view('ecg/upload_ecg_file_demo');
		} else {

		// do uploadEcgData processs
			$result = array(
				"error" => 0,
				"error_desc" => ""
			);
			do {   // for in while break
				$username = $this->input->post('username');
				$pwd = md5($this->input->post('password'));
				$record_id = $this->input->post( 'record_id' );
				$status = $this->input->post( 'status' );

				if (empty($record_id) || empty($status)) {
					$result['error'] = 2;
					$result['error_desc'] = "no record_id or status set";
					break;
				}
				if ($this->_checkAuth($username, $pwd) === False)  {
					$result['error'] = 1;
					$result['error_desc'] = "error username or password";
					break;
					
				}
				
				$time = time();
				try {
					$filepath = $_FILES['ecg_file']['tmp_name'];
					//// no need copy to another place
					//$outputpath = "/tmp/{$username}_$time/";
					$uploadname = "ecg/$username/$time";
					//// upload file to qiniu service
					//// key, file
					$ret = $this->qiniu->upload_file( $uploadname, $filepath );
					if ($ret) {
						$this->load->database();
						$data = array(
								'username'=> $username,
								'upload_time' => $time,
								'record_id' => $record_id,
								'status' => $status
							);
						$this->db->insert('upload_ecg_record', $data);
					}
				} catch (Exception $e) {
					$result['error'] = 3;
					$result['error_desc'] = $e->getMessage();
				}
			} while (0);

			echo json_encode($result);
		}
		
	}

	public function getAviableRecordId() {
		$result['error'] = 0;
		do {
			$username = $_REQUEST['username'];
			$pwd = md5($_REQUEST['password']);
			if ($this->_checkAuth($username, $pwd) === False)  {
				$result['error'] = 1;
				$result['error_desc'] = "error username or password";
				break;
			}
			$this->load->database();
			$this->db->insert("ecg_record_index", array('user' => $username));
			$id = $this->db->insert_id();
			$result['id'] = $id;
		} while(0);
		echo json_encode($result);
	}


	public function recordList() {
		$this->load->database();
		$query = $this->db->query("SELECT username, record_id FROM upload_ecg_record WHERE status = '1'");
		$data = array('data' => $query->result_array());
		$this->load->view('ecg/recordList', $data);
	}

	public function ajaxEcgData($username, $timestamp) {
		$content = file_get_contents("http://uestcmed.qiniudn.com/ecg/$username/$timestamp");
		echo $content;
	}

	public function getEcgData($username, $record_id, $timestamp) {
		$this->load->database();
		$result = array( 'error' => 0 );
		$query = array();
		$sleepIntval = 0;
		$sleepTimes = -1;
		do {
			sleep($sleepIntval);
			$sleepIntval = pow(2, $sleepTimes++);
			$sql = "SELECT * FROM upload_ecg_record WHERE upload_time > '$timestamp' && username = '$username' && record_id = '$record_id'";
			$query = $this->db->query($sql);
		} while ($query->num_rows() == 0 && $sleepTimes < 3);
		
		if ($query->num_rows() == 0) {
			$result['error'] = 1;
			$result['error_desc'] = "no data Left";
		}
		$result['records'] = $query->result_array();
		echo json_encode($result);
	}


	public function realtimeEcg($record_id = 1) {
		$data = array( 'title' => "realtimeECG");
		$this->load->view("templates/header", $data);
		$data['js'] = array("jquery.flot.min.js");
		$this->load->view("ecg/realtimeEcg", $data);
		$this->load->view("templates/footer");
	}


	public function printview() {
		$data = array( 'title' => "print", 'username' => $_REQUEST['username'], "record_id" => $_REQUEST['record_id']);
		$this->load->view("templates/header", $data);
		$data['js'] = array("jquery.flot.min.js");
		$this->load->view("ecg/print", $data);
		$this->load->view("templates/footer");
	}


	public function callBack() {
		$ret = array(
			"origin_name" 	=> $this->input->post('origin_name'),
			"tag_name" 	=> $this->input->post('tag_name'),
			"bucket"	=> $this->input->post('bucket'),
			"mimeType"	=> $this->input->post('mimeType'),
			"comment"	=> $this->input->post('comment')
		);
		return json_encode($ret);
	}

}

/* End of file ecg.php */
/* Location: ./application/controllers/ecg.php */
