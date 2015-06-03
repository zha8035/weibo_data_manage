<?php
	require_once(APPPATH."third_party/qiniu/rs.php");
	require_once(APPPATH."third_party/qiniu/io.php");
	define("BUCKET", 'uestcmed');
	define("accessKey", '8Y7Xt-zl_T8jCMl3rJt77_aYDO5vJrUA-1OMknUb');
	define("secretKey", 'aMpEqPxOxhj0mlQd17jDWxHp_spdo-fh0ekczDpS');

	class Qiniu extends CI_Model { 
		
		public $putPolicy;
		public $getPolicy;

		function __construct() {
			parent::__construct();
			Qiniu_SetKeys(accessKey, secretKey);
			$this->putPolicy = new Qiniu_RS_PutPolicy(BUCKET);
			$this->getPolicy = new Qiniu_RS_GetPolicy();
		}
		

		function download_public($key, $domain) {
			return Qiniu_RS_MakeBaseUrl($domain, $key);
		}

		function download_private($key, $domain) {
			$baseUrl = $this->download_public($key, $domain);
			$privateUrl = $this->getPolicy->MakeRequest($baseUrl, NULL);
			return $privateUrl;
		}

		//strname, string, callbackUrl, callBack Data,
		function upload_str($key, $content, $callBackUrl='', $callBackBody='') {
			$this->putPolicy->CallbackUrl = $callBackUrl;
			$this->putPolicy->CallbackBody = $callBackBody;

			$upToken = $this->putPolicy->Token(NULL);
			list($ret, $err) = Qiniu_Put($upToken, $key, $content, NULL);

			return  $ret;
		}


		//filename, filepath, 
		function upload_file($key, $filepath, $callBackUrl='', $callBackBody='') {
			$this->putPolicy->CallbackUrl = $callBackUrl;
			$this->putPolicy->CallbackBody = $callBackBody;

			$upToken = $this->putPolicy->Token(NULL);
			$putExtra = new Qiniu_putExtra();
			$putExtra->Crc32 = 1;

			list($ret, $err) = Qiniu_PutFile($upToken, $key, $filepath, $putExtra);

			if ($err != NULL) {
				return $err;
			} else {
				return $ret;
			}
			return true;
		}

	}
