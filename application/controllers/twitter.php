<?php
class Twitter extends CI_Controller {
	function __construct() {
		parent::__construct();
		session_start();
	}


	private function isAuthorize() {

		if (!empty($_POST['user']) && !empty($_POST['password'])) {
			$user = $_POST['user'];
			$pwd = $_POST['password'];
			$this->load->database();
			$query = $this->db->query("SELECT name FROM user WHERE name='$user' && password='$pwd'");
			if ($query->num_rows() == 1) {
				$_SESSION['user'] = $user;
				return 2;
			} else {
				echo "wrong username or password";
			}
		} 
		if (!empty($_SESSION['user'])) {
			return 1;   ////////SESSION
		}
		return 0;
	}


	private function getAuthorize($user) {
		$this->load->database();
		$query = $this->db->query("SELECT auth FROM user WHERE name='$user'");
		$item = $query->result_array()[0];
		return $item['auth'];
	}

	public function index() {
		if ($this->isAuthorize() == 0) {
			header("Location: login");
			return;
		}

		$data = array("title"=>"index", "username"=>$_SESSION['user']);

		$this->load->view('templates/header', $data);

		$auth = $this->getAuthorize($data['username']);
		$data['list'] = array();
		if ($auth & 1) {
			$data['list'][] = array("href" => "doTask", "text" => "doTask");
		}
		if ($auth & 2) {
			$data['list'][] = array("href" => "orderTask", "text" => "orderTask");
			$data['list'][] = array("href" => "addTopic", "text" => "addTopic");
		}
		if ($auth & 4) {
			$data['list'][] = array("href" => "uploadData", "text" => "uploadData");
		}
		$data['list'][] = array("href" => "query", "text" => "Query");

		$this->load->view("twitter/index", $data);

		$this->load->view('templates/footer');
	}

	public function register() {
		if (!empty($_POST['name']) && !empty($_POST['password'])) {
			$this->load->database();
			$user = $_POST['name'];
			$pwd = $_POST['password'];
			$query = $this->db->query("SELECT name FROM user WHERE name = '$user'");
			if ($query->num_rows() == 1) {
				echo "user name had used";
			} else {
				$this->db->insert("user", array("name"=>$user, "password"=>$pwd, "auth" => 1));
				echo "ok";
			}
		}
		$this->login();
		//$data = array("title"=>"register");
		//$this->load->view('templates/header', $data);
		//$this->load->view("twitter/register");
		//$this->load->view('templates/footer');
	}

	public function changePassword() {
		if (!empty($_POST['name']) && !empty($_POST['old']) && !empty($_POST['new'])) {
			$this->load->database();
			$user = $_POST['name'];
			$old = $_POST['old'];
			$new = $_POST['new'];
			$query = $this->db->query("SELECT * FROM user WHERE name = '$user' && password = '$old'");
			if ($query->num_rows() == 0) {
				echo "wrong username or password";
			} else {
				$this->db->update("user", array("password"=>$new), "name = '$user'");
				echo "OK";
			}
		}
		$this->login();

	}

	public function uploadData() {
		$ok = true;
		if ($this->isAuthorize() == 0) {
			$ok = false;
		} else {
			$auth = $this->getAuthorize($_SESSION['user']);
			if (!($auth & 4)) {
				$ok = false;
			}
		}
		if (!$ok) {
			header("Location: index");
			return;
		}

		$this->load->database();

		if (!empty($_FILES['data']['tmp_name']) ) {
			$filepath = $_FILES['data']['tmp_name'];
			$msgs = file($filepath);
			
			$maxnum = $this->db->query("SELECT MAX(id) as maxnum FROM data")->result_array()[0];
			$maxnum = $maxnum['maxnum'];
			$para = array();
			$idx = 0;
			foreach ($msgs as $row) {
				$para[] = array( "message" => $row, "id"=>$maxnum+ ++$idx);
			}
			$this->db->insert_batch("data", $para);
			$this->db->insert("data_file", array(
					"name"	=> $_FILES['data']['name'],
					"from"	=> $maxnum+1,
					"to"	=> $maxnum+$idx,
					"date"	=> time()
				));
		}
		$data = array("title"=>"uploadData");
		$data['file'] = $this->db->get("data_file")->result_array();
		
		$this->load->view('templates/header', $data);
		$this->load->view('twitter/uploadData', $data);
		$this->load->view('templates/footer');

	}


	public function orderTask() {

		$ok = true;
		if ($this->isAuthorize() == 0) {
			$ok = false;
		} else {
			$auth = $this->getAuthorize($_SESSION['user']);
			if (!($auth & 2)) {
				$ok = false;
			}
		}
		if (!$ok) {
			header("Location: index");
			return;
		}

		$this->load->database();
		if (!empty($_POST['user']) && !empty($_POST['topic']) && !empty($_POST['range']) && trim($_POST['range']) !== "") {
			
			$range = $this->parseRange($_POST['range']);
			if (!(ctype_digit($range[0]) && ctype_digit($range[1]))) {
				echo "input 1-100, for example";
				return;
			}
			$this->db->insert("task", array(	
								'user'	=> $_POST['user'],
								'from' 	=> $range[0],
								'to' 	=> $range[1],
								'date'	=> time(),
								'topic' => $_POST['topic']));
			
		}
		$user = $this->db->get('user');
		$data = array();
		$data['title'] = "布置任务";
		$this->load->view('templates/header', $data);
		$data['users'] = $user->result_array();
		$data['tasks'] = $this->db->get("task")->result_array();
		$data['topics'] = $this->db->get("topic")->result_array();
		$this->load->view('twitter/orderTask', $data);
		$this->load->view('templates/footer');
	}
	
	public function login() {
		if ($this->isAuthorize() == 2) {
			header("Location: index");
		//	$this->index();
			return;
		}

		$data = array("title"=>"login");
		$this->load->view('templates/header', $data);
		
		$this->load->view('twitter/login');
		$this->load->view('twitter/register');
		$this->load->view('twitter/changePassword');
		$this->load->view('templates/footer');
	}

	public function addTopic() {
		$ok = true;
		if ($this->isAuthorize() == 0) {
			$ok = false;
		} else {
			$auth = $this->getAuthorize($_SESSION['user']);
			if (!($auth & 2)) {
				$ok = false;
			}
		}
		if (!$ok) {
			header("Location: index");
			return;
		}

		if (!empty($_POST['topic'])) {
			$this->load->database();
			$query = $this->db->query("SELECT * FROM topic WHERE topic = '".$_POST['topic']."'");
			if ($query->num_rows() > 0) {
				echo "topic had exist";
			} else {
				$this->db->insert("topic", array("topic"=>$_POST['topic']));
			}
		}
		$data = array("title" => "AddTopic");
		$this->load->view('templates/header', $data);
		
		$this->load->view('twitter/addTopic');

		$this->load->view('templates/footer');

	}

	public function acceptResult() {

		$data = json_decode($_POST['data'], true);
		//var_export($data);
		$user = $data['user'];

		$ok = true;
		$auth = $this->getAuthorize($user);
		if (!($auth & 1)) {
			$ok = false;
		}
		if (!$ok) {
			echo "not authorized";
		}


		$tasks = $data['data'];
		$topic = $data['topic'];
		$this->load->database();
		

		$query = $this->db->query("SELECT id FROM user WHERE name = '$user'")->result_array();
		$uid = $query[0]['id'];
		foreach($tasks as $task) {
			$score = $task['value'] == 'yes'?1:($task['value']=='no'?0:-1);
			$this->db->insert("record", array(
					'score' => $score,
					'uid' => $uid,
					'mid' => $task['id'],
					'topic' => $topic));
		}

		$this->db->update("task", array("isDone" => 1), "user = '$user' && topic = '$topic'");
		echo "ok";

	}


	private function parseRange($range) {
		return explode('-', $range);
	}


	public function getSummary() {
		$topic = empty($_REQUEST['topic'])?"":$_REQUEST['topic'];

		$data = array();
		$this->load->database();
		$sql = "SELECT user.name as user, record.score as score FROM record, user WHERE user.id = record.uid";
		if (!empty($topic)) {
			$sql .= " && topic = '$topic'";
		} 
		$query = $this->db->query($sql)->result_array();
		foreach($query as $q) {
			$user = $q['user'];
			$score = $q['score'];
			if ( isset($data[$user]) ) {
				$data[$user][$score]++;
			} else {
				$data[$user] = array(0=>0, -1=>0, 1=>0);
			}
		}
		$result = array();
		foreach($data as $key=>$score) {
			$result[] = array("user"=>$key, "yes"=>$score[1], "no"=>$score[0], "unknow"=>$score[-1]);
		}

		echo json_encode($result);
	}

	public function query() {
		$data['lists'] = array();
		if (isset($_REQUEST['range']))  {
			$range = $this->parseRange($_REQUEST['range']);
			$from = $range[0];
			$to = $range[1];
			$topic = $_REQUEST['topic'];
			if (!(ctype_digit($range[0]) && ctype_digit($range[1]))) {
					echo "input 1-100, for example";
					return;
			}

			$this->load->database();
			$sql = "SELECT record.mid as id, user.name as user, record.score as score FROM record, user WHERE record.mid >= $from && record.mid <= $to && user.id = record.uid";
			if (!empty($_REQUEST['topic'])) {
				$sql .= " && topic = '$topic'";
			}
			$query = $this->db->query($sql);
			$data['lists'] = $query->result_array();
			$data['from'] = $from;
			$data['to'] = $to;
			$data['cyes'] = 0;
			$data['topic_value'] = $topic;
			$data['cno'] = 0;
			$data['cunknow'] = 0;
			$data['cnotyet']= $to - $from - count($data['lists']);
			foreach( $data['lists'] as $list) {
					if ($list['score'] == 1) {
						$data['cyes']++;
					} else if ($list['score'] == 0) {
						$data['cno']++;
					} else if ($list['score'] == -1) {
						$data['cunknow']++;
					}
			}
			


			$data['question_lists'] = $this->db->query("SELECT id, message FROM data WHERE id >= '$from' && id <= '$to'")->result_array();

			$data['query'] = true;
		
		}
		$this->load->database();
		$data['topics'] = $this->db->get("topic")->result_array();
		$data['title'] = "Query";
		$data['js'] = array("jquery.flot.min.js", "jquery.flot.stack.min.js", "jquery.flot.categories.min.js","Twitter.stackBar.js");
		$data['css'] = "Twitter.stackBar.css";
		$this->load->view('templates/header', $data);
		
		$this->load->view("twitter/query", $data);

		$this->load->view('templates/footer', $data);

	}


	public function doTask() {
		$ok = true;
		if ($this->isAuthorize() == 0) {
			$ok = false;
		} else {
			$auth = $this->getAuthorize($_SESSION['user']);
			if (!($auth & 1)) {
				$ok = false;
			}
		}
		if (!$ok) {
			header("Location: index");
			return;
		}

		$user = $_SESSION['user'];
		$this->load->database();
		
		$query = $this->db->query("SELECT * FROM task WHERE user='$user'")->result_array();
		$showtopic = true;
		$topics = array();
		foreach($query as $task) {
			$topics[] = $task['topic'];
		}
		$topics = array_unique($topics);
		if (!empty($_GET['topic']) && in_array($_GET['topic'], $topics) ) {
			$work = array();
			foreach($query as $task) {
				if ($task['topic'] != $_GET['topic']) {
					continue;
				}
				$query = $this->db->query("SELECT * FROM data WHERE id >= {$task['from']} && id <= {$task['to']}");
				$work = array_merge($work, $query->result_array());
			}
			if (count($work) == 0) {
				echo "No Task to do";
				return;
			}
			$data = array();
			$data['total'] = count($work);
			$data['works'] = array_chunk($work, 10);
			$data['title'] = "Task";
			$data['user'] = $user;
			$data['topic'] = $_GET['topic'];
			$data['js'] = "Twitter.main.js";
			$this->load->view('templates/header', $data);
			$this->load->view('twitter/doTask', $data);
			$this->load->view('templates/footer');
		} else {
			$data = array();
			$data['title'] = "Topic";
			$data['topics'] = $topics;
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$data['url'] = $url = preg_replace("/\?.*$/", "", $url);
			
			$this->load->view('templates/header', $data);
			$this->load->view("twitter/taskTopic", $data);
			$this->load->view('templates/footer');
		}		
	}

	private function getUri() {

	}

	
	public function install() {
		
	}
	

}
