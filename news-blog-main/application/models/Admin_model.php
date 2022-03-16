<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once('Abstract_model.php');
class Admin_model extends Abstract_model{

  protected $table_name = "";
  public function __construct(){
    parent::__construct();
    $this->table_name = "admin";
  }

}

?>