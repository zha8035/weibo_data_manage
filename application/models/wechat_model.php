<?php

	define("RESPONSE_HEADER", 
		"<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>");
	define("RESPONSE_FOOTER", '</xml>');

	define("RESPONSE_TEXT", "<Content><![CDATA[%s]]></Content>");
	define("RESPONSE_IMAGE", 
		"<Image>
			<MediaId><![CDATA[%s]]></MediaId>
		</Image>");
	define("RESPONSE_ARTICLE_COUNT", "<ArticleCount>%s</ArticleCount>");
	define("RESPONSE_ARTICLE", "<Articles>%s</Articles>");
	define("RESPONSE_ARTICLE_ITEM", 
		"<item>
			<Title><![CDATA[%s]]></Title> 
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
		</item>");


	class Wechat_model extends CI_Model {
		protected $_postObject;
		protected $_fromUserName;
		protected $_toUserName;
		protected $_createTime;
		protected $_msgType;
		protected $_msgId;
		protected $_time;

		public function init($postObj) {
			$this->_postObject = $postObj;
			if ($postObj == false) {
				log_message('debug', "post Object is NULL");
				return false;
			}
			$this->_fromUserName = (string)trim($postObj->FromUserName);
			$this->_toUserName = (string)trim($postObj->ToUserName);
			$this->_createTime = (int)trim($postObj->CreateTime);
			$this->_msgType = (string)trim($postObj->MsgType);
			$this->_msgId = (int)trim($postObj->MsgId);
			$this->_time = time();

			return true;
		}

		public function process() {
			return "Do not implement yet";
		}

		protected function getHeader($type) {
		//	log_message('debug', "HEADER: ".$this->_fromUserName.", ".$this->_toUserName);
			return sprintf(RESPONSE_HEADER, 
							$this->_fromUserName, $this->_toUserName, time(), $type);
		}

		protected function getContent($type) {
			return RESPONSE_TEXT;
		}

		protected function getResponse($type, $content) {
			//log_message("debug", var_export($this->_postObject, true));
			$header = $this->getHeader($type);
//			log_message("debug", $content);
			if ($type != "news") {
				$content =  sprintf($this->getContent($type), $content);
			} else {
				$count = sprintf(RESPONSE_ARTICLE_COUNT, count($content));
				$items = "";
				foreach ($content as $cont) {
					$items .= sprintf(RESPONSE_ARTICLE_ITEM, $cont['title'], $cont['description'], $cont['imgurl'], $cont['url']);
				}
				$content = $count.sprintf(RESPONSE_ARTICLE, $items);
				
				
			}
			
			return $header.$content.RESPONSE_FOOTER;
		}
	}

