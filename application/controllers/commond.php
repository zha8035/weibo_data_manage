<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commond extends CI_Controller {
	
	private function _reqMenu($account, $interface, $data) {
		$this->load->model("Token");
		$token = $this->Token->getToken($account);
		if ($token) {
			$this->load->library("curl");
			$url = WX_API_URL."$interface?access_token=".$token;
			$ret = $this->curl->simple_post($url, $data);
			echo $url."call response ". var_export($ret, true);
		}
	}


	public function createMenu($account) {
		$data = file_get_contents("php://input");
		if (!$data) {
			$default = dirname(__FILE__)."/../resource/menu_template/default.json";
			$data = file_get_contents($default);
			echo $data."<br />";
		}
		$this->_reqMenu($account, "menu/create", $data);
	}

	public function deleteMenu($account) {

	}

	public function getMenu($account) {

	}
}

/* End of file commond.php */
/* Location: ./application/controllers/commond.php */
