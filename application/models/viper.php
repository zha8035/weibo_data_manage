<?php

	class Viper_model extends CI_Model {

		function getInfo($name) {
			$this->load->database();
			$query = $this->db->query('SELECT * from VipInfo where name="'.$name.'"');
			if ($query->num_rows() != 1) {
				return $query->row();
			}
			return NULL;
		}


		function checkLevel($name) {
			$this->load->database();
			$query = $this->db->query('SELECT level from VipInfo where name="'.$name.'"');
			if ($query->num_rows() != 1) {
				return -1;
			}
			$level = $query->row()->level;
			return $level;
		}



	}