<?php
class Vip extends CI_Controller {

	public function show($username) {
		
		$data = array();
		$data['title'] = "Viper";
		$data['username'] = $username;
		$data['scores'] = 100;
		$data['aviable_date'] = date("Y-m-d");
		$data['QRcode'] = "Unknow";
		$data['imgurl'] = '/application/resource/QRcode.jpg';
		$data['css'] = "viper.css";
		$this->load->view('templates/header', $data);
		$this->load->view('viper', $data);
		$this->load->view('templates/footer');

	}
}
