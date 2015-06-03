<?php
	define("TOKEN", "weiwangzhan");

	class Wechat_api extends CI_Model { 
		

		function __construct() {
			parent::__construct();
		}
		
		public function checkSignature() {
			$signature = $_GET["signature"];
			$timestamp = $_GET["timestamp"];
			$nonce = $_GET["nonce"];
			
			$token = TOKEN;
			$tmparr = array($token, $timestamp, $nonce);
			sort($tmparr, SORT_STRING);
			$tmpstr = implode($tmparr);
			$tmpstr = sha1($tmpstr);
			
			if ($tmpstr == $signature) {
				return true;
			} else {
				return false;
			}
		}

		public function getWeChatObj($postStr) {
			$this->load->database();
			$query = $this->db->query("SELECT * FROM AppInfo WHERE username='".(string)trim($postStr->ToUserName)."'");
			$WechatClass = $query->row()->nick_name;
			$this->load->model("Wechat_".$WechatClass, "WechatModel");
			$this->WechatModel->init($postStr);
			return $this->WechatModel;
		}
		
	}
