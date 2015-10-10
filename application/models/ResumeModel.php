<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ResumeModel extends CI_Model {	
	
	public function __construct()
	{	
		$this->db = $this->load->database('Labs',true);	
	}
	
	public function save($userName,$passWord)
	{
		
		if ( $this->_check($userName))
		{
			$data = array(
					'passWord'	=>	$passWord,
			);
			$this->db->where('userName', $userName);
			$this->db->update('Resume',$data);
		}
		else 
		{
			$data = array(
					'userName'	=>	$userName,
					'passWord'	=>	$passWord,
			);
			$this->db->insert('Resume',$data); 
		}		
	}
	public function read()
	{
		return $this->db->get('Resume')->result();
	}
	private function _check($userName)
	{

		$query = $this->db->get_where('Resume', array('userName' => $userName));
		if ( $query->num_rows() == 0)
			return false;
		return true;
	}
}	