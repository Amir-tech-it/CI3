<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Products_model extends CI_Model {
 function saverecords($formdata)
	{
        $this->db->insert('prodetails',$formdata);
        return true;
	}
	function register_user($formdata)
	{
        $this->db->insert('users',$formdata);
        return true;
	}
	function showdata(){
		 $query=$this->db->get("prodetails");
    return $query->result();
	}
  
	function showdatabyid($id){
		 
		 $this->db->where('id',$id);
		$query = $this->db->get("prodetails",);


		 // $query=$this->db->query("select * from prodetails where id='".$id."'");
	return $query->result();
	}

	function updaterecord($formdata,$id){

		$this->db->where('id',$id);
      $this->db->update('prodetails',$formdata);
       return true;
	}

function deletebyid($id){

		$this->db->where('id',$id);
      $this->db->delete('prodetails');
       return true;
	}

}