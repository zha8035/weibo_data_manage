<?php
class Book extends CI_Controller {

	public function main() {
		$data = array();
		$data['title'] = "热门";
		$data['js'] = "Book.main.js";
		$this->load->view('templates/header', $data);
		
		
		$this->load->database();
		
		$categorys = $this->db->get("category");
		$book = array();
		foreach($categorys->result_array() as $row) {
			$book[$row['kind']] = array();
		}
		$bookinfos = $this->db->get("bookinfo");
		foreach($bookinfos->result_array() as $row) {
			$book[$row['category']][] = $row;
		}
		$data['books'] = $book;
//		log_message('debug', print_r($data));
		//var_export($data);
		$this->load->view('Book/PageHeader', $data);
		$this->load->view('Book/HotContent', $data);
		$this->load->view('Book/PageFooter', $data);
		$this->load->view('templates/footer');

	}
}
