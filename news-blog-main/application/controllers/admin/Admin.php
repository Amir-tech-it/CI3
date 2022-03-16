<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller{

  public function __construct(){

    parent::__construct();
    $this->load->model('admin_model', 'admin');

  }

  public function index(){

    $data = [];
    $data['direct_access_error'] = $this->session->flashdata('direct_access_error');
    if($this->session->has_userdata('admin_email')){
      $this->load->view('admin/dashboard/index');
    }else {
      $this->load->view('admin/login/index', $data);
    }

  }

  public function dashboard(){

    if($this->session->has_userdata('admin_email')){
      $this->load->view('admin/dashboard/index');
    }else {
      $this->session->set_flashdata('direct_access_error', 'Please Login First For Dashboard Access!');
      redirect('admin');
    }

  }

  public function is_email_exists($str){

    if(!empty($str)){
      $where = "admin.email='".$str."'";
      $results = $this->admin->get_where('*', $where, true, '', '1', '');
      if(!empty($results)){
        return true;
      }else{
        $this->form_validation->set_message('is_email_exists', 'Unregistered %s Entered!');
        return false;
      }
    }

  }

  public function admin_login_process(){

    $data = [];
    $data['response'] = false;

    if(!$this->input->is_ajax_request()) {
      exit('No direct script access allowed');
    }
    
    $validationRules = array(
      array(
        'field' => 'email',
        'label' => 'Admin Email',
        'rules' => 'required|valid_email|callback_is_email_exists',
        'errors'=> array(
          'required' => 'Please Enter Your %s.',
          'valid_email' => 'Please Enter Your Valid %s.'
        )
      ),
      array(
        'field' => 'password',
        'label' => 'Admin Password',
        'rules' => 'required',
        'errors'=> array(
          'required' => 'Please Enter Your %s.'
        )
      )
    );
    $this->form_validation->set_rules($validationRules);
    if($this->form_validation->run()==true){
      $formData = $this->input->post();
      $where = "admin.email='".$formData['email']."' AND admin.password='".$formData['password']."'";
      $results = $this->admin->get_where('*', $where, true, '', '1', '');
      if(!empty($results)){
        $data['response'] = true;
        $data['redirect_url'] = "dashboard";
        $sessionData = array(
          'admin_email' => $results[0]['email'],
          'admin_id' => $results[0]['id']
        );
        $this->session->set_userdata($sessionData);
      }else{
        $data['password_error'] = 'Incorrect Password!';
      }
    }else{
      $data['errors'] = $this->form_validation->error_array();
    }
    echo json_encode($data);
    exit;

  }

  public function admin_logout_process(){
    
    $this->session->sess_destroy();
    redirect('admin');
    
  }

}

?>