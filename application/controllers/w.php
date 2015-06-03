<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class W extends CI_Controller {

	public function index()
	{
		log_message('debug', time().": ".var_export($_GET, true));
		$this->load->model("Wechat_api");
		if ($this->Wechat_api->checkSignature()) {
			if (isset($_GET['echostr'])) {
				echo $_GET['echostr'];
				exit(0);
			}
		} else {
			log_message('debug', "Check Signature failed");
		}
		$postStr = file_get_contents( "php://input" );
		if ( empty( $postStr) ) {
			log_message('debug', "No postStr");
			exit(0);
		}
		$postObj = simplexml_load_string( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		$toUsrName = (string)trim($postObj->ToUserName);
		if (!$toUsrName) {
			log_message('debug', "Usr name is NULL");
			exit(0);
		} else {
			$wechatObj = $this->Wechat_api->getWeChatObj($postObj);
		}
		$retStr = $wechatObj->process();
		log_message('debug', $retStr);
		echo $retStr;

		log_message('debug', "end this process");
	}

}

/* End of file w.php */
/* Location: ./application/controllers/w.php */
