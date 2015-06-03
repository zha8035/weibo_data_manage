<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include('application/libraries/simple_html_dom.php');
class Search extends CI_Controller {
	

	public function baidu($search) {

		$html = new simple_html_dom();
		$html->load_file("http://www.baidu.com/s?wd=$search");
		$ret = $html->find("div[class=c-container]");
		$result = array();
		foreach($ret as $node) {
			$obj = array();
			$obj["text"] = urlencode($node->plaintext);
			$link = $node->find(".t", 0)->find('a', 0);
			$obj['linktext'] = urlencode($link->plaintext);
			$obj["url"] = urlencode($link->href);
			$result[] = $obj;
		}
		echo urldecode(json_encode($result,JSON_UNESCAPED_UNICODE));
		
	}

	public function google($search) {
		$this->load->library('curl');
		$this->curl->http_header("content-type: application/x-www-form-urlencoded; 
charset=UTF-8");
		$retStr = $this->curl->simple_get("http://www.google.com.tw/search?q=$search");
		echo $retStr;
		return;
		$html = new simple_html_dom();
		$html->load_file("https://www.google.tw/search?h/=zh-CH&q=$search");
		echo $html;
		$ret = $html->find("li[class=g]");
		foreach($ret as $link) {
			echo $link;
		}
	}
}

/* End of file commond.php */
/* Location: ./application/controllers/commond.php */
