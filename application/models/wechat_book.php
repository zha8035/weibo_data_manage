<?php
	require_once(dirname(__FILE__).'/wechat_model.php');
	class Wechat_book extends Wechat_model {
		
		protected $_event;
		protected $_eventKey;
		protected $_content;

		public function init($postObj) {
			parent::init($postObj);

			if ($this->_msgType == "event") {
				$this->_event = (string) $postObj->Event;
				$this->_eventKey = (string) $postObj->EventKey;
			}

			if ($this->_msgType == 'text') {
				$this->_content = (string) $postObj->Content;
			}

			return true;
		}

		public function process() {
			$ret = $this->getResponse("text", "No response returned, something wrong on service");
			$this->load->model("Med", "Med");
			if ($this->_msgType == 'text') {
				if ($this->_content == "今日停诊") {
					$ret = $this->getResponse("news", $this->Med->getStop());
				} else {
					$ret = $this->getResponse("text", $this->_content);
				}
			} else if ($this->_msgType == "event") {
				switch ($this->_event) {
				case  "subscribe" : 
/*     cant get user info as authorizion
					$this->load->model("Token");
					$token = $this->Token->getToken("book");
					$this->load->library('curl');
					$para = array(
							"access_token"	=> $token,
							"openid" 	=> $this->_fromUserName,
							"lang" 		=> "zh_CN"
						);
		                        $retStr = $this->curl->simple_get(WX_API_URL.'user/info?', $para);
					log_message("debug", var_export($para, true));
*/
					$ret = $this->getResponse("text", "欢迎~ 输入 今日停诊  返回今日的停诊信息".$retStr);
					break;
				case  "unsubscribe" :
					$ret = "Byeee~~~";
					break;
				}
				switch($this->_eventKey) {
				case  "key_VIP_card" :
					log_message("debug", "eventKey : key_VIP_card, should return url");
					$ret = $this->getResponse("text", "http://weiwangzhan2014.duapp.com/index.php/vip/show/{$this->_fromUserName}");
					break;
				case  "key_med_news" :
					log_message('debug', "eventKey : key_med_news, should return news message");
				//	log_message('debug', var_export($news))	;
					$ret = $this->getResponse("news", $this->Med->getNews());
					break;
				}
			}
			return $ret;
		}
	}

