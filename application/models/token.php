<?php
	class Token extends CI_Model {

		function __construct() {
			parent::__construct();
		}

		function getToken($account) {
			$this->load->database();
			$query = $this->db->query("SELECT * FROM AccessToken WHERE nick_name='$account'");
			$itemexist = false;
			if ($query->num_rows() == 1)  {
				$itemexist = true;
				$addtimestamp = $query->row()->add_timestamp;
				$token = $query->row()->access_token;
				$expire = $query->row()->expire;
				if ($addtimestamp + $expire - 30 > time()) {
					return $token;
				}
			}
			$query = $this->db->query("SELECT * FROM AppInfo WHERE nick_name='$account'");

			$para = array(
					"grant_type" => "client_credential",
					"appid" => $query->row()->app_id,
					"secret" => $query->row()->secret
				);
			$this->load->library('curl');
			$retStr = $this->curl->simple_get(WX_API_URL.'token', $para);
			$retData = json_decode($retStr, true);
			//echo $retStr."<br />";
			//echo WX_API_URL."token?".var_export($para, true);
			if (!$retData || isset($retData['errcode'])) {
				log_message("error", "access token get error code : ".$retData['errcode']);
				return false;
			}
			$app_id = $query->row()->app_id;
			$token = $retData['access_token'];
			$expire = $retData['expires_in'];
			$para = array(
					"app_id" => $app_id,
					"access_token" => $token,
					"expire" => $expire,
					"add_timestamp" => time()
				);
			if ($itemexist) {
				$this->db->update('AccessToken', $para, array("nick_name"=>$account));
			} else {
				$para['nick_name'] = $account;
				$this->db->insert('AccessToken', $para);
			}
			return $token;
		}
		
	}
