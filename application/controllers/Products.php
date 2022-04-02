<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

public function __construct(){

    parent::__construct();
    $this->load->model('products_model', 'pmodel');
     $this->load->model('admin_model', 'admin');
    $this->load->library('session');

 
  }  
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
   {
       $this->load->view('signup'); 
   }


   public function hopage(){
    $this->load->view('pro'); 
   }

public function dashboard(){

    if(

      $session123 =  $this->session->has_userdata('admin_email')

     ){
      // print_r($session123);
      $this->load->view('admin/dashboard');
    }else {
      $this->session->set_flashdata('direct_access_error', 'Please Login First For Dashboard Access!');
      // redirect('admin');
    }

  }

public function user_dashboard(){

    if(

      $session124 =  $this->session->has_userdata('admin_email')

     ){
      // print_r($session123);
      $this->load->view('admin/user_dashboard');
    }else {
      $this->session->set_flashdata('direct_access_error', 'Please Login First For Dashboard Access!');
      // redirect('admin');
    }

  }
   public function register(){
    $data= [];
    $data['response'] = false;
    // $data['image_errors'] ="";
   
    // echo $formdata;
    if(!$this->input->is_ajax_request()) {
        exit('No direct script access allowed');
    }
    $this->form_validation->set_rules('email','Email','required');
    $this->form_validation->set_rules('psw','Password','required');
    $this->form_validation->set_rules('psw-repeat', 'Password Confirmation', 'required|matches[psw]');


   	if($this->form_validation->run() == false){
      $data['form_errors'] = $this->form_validation->error_array();
   		$this->load->view('signup',$data); 
   	}
     else { 
       $formdata = $this->input->post();
          unset($formdata['psw-repeat']);
          $password = $formdata['psw'];
          unset($formdata['psw']);
          $formdata['psw'] = md5($password);
          
           $response=$this->pmodel->register_user($formdata);
          if($response==true){
            
 
        // $name = 'Dear '.$formData['username'];
                 $message = 'Your login Credentials are given below.<br/>';
                $message .= 'Email: '.$formdata['email'];
                $message .= '<br/>Password: '.$password;
                
              // $message .= '<br/> Please follow below link for login.<br/>';
              //   $message .= "<br/>Regards,<br/>Administration of Uflow";
             $sendemail =   send_email_to($formdata['email'],$message,'Login Credentials');
             print_r($sendemail);
          exit;
$data['response'] = true;
            // echo'okk';
            // exit;
            $data['redirect_url'] = "dashboard";
            $data['success']  = "Added Successfully!";
          }
        }
    echo json_encode($data);
    exit;
   }

   public function create(){
    $data= [];
    $data['response'] = false;
    $data['image_errors'] ="";
    $formdata = $this->input->post();

    if(!$this->input->is_ajax_request()) {
        exit('No direct script access allowed');
    }

    if(empty($_FILES['userfile']['tmp_name'])){
      $this->form_validation->set_rules('userfile','Image','required');
    }

   	$this->form_validation->set_rules('name','Name','required');
   	$this->form_validation->set_rules('email','Email','required|valid_email');
   	if($this->form_validation->run() == false){
      $data['form_errors'] = $this->form_validation->error_array();
   		// $this->load->view('pro',$data); 
   	}else { 

      if(!empty($_FILES['userfile']['tmp_name'])){
        $config['upload_path'] = './assets/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 10000;
        $config['max_height'] = 3000;
        $config['max_width'] = 3000;
        $config['overwrite'] = true;
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('userfile')){
          $data['image_errors'] = $this->upload->display_errors();
        }else {
          $formdata['image'] = $this->upload->data('file_name');
        }
      }


   	/// create user and save to database
      

   		// $data['name'] = $this->input->post('name');
   		// $data['email'] = $this->input->post('email');
   		// $data['created'] = date('Y-m-d');
        if(empty($data['image_errors'])){
         $response=$this->pmodel->saverecords($formdata);
          if($response==true){
            $data['response'] = true;
            $data['redirect_url'] = "dashboard";
            $data['success']  = "Added Successfully!";
          }
        } 
      // else{
      //    echo "not added ";
      // }
    
   		// $this->Pmodel->create($formarray);
   		// $this->session->set_flashdata('Success','Your data added successfully');
   		
   	}
    echo json_encode($data);
    exit;
   }

  
      public function view(){
        $result['data'] = $this->pmodel->showdata();
        $this->load->view('show',$result);
      }



   public function edit(){ 
     $data = "";
    $id = $this->input->get('edit');
    echo $id;
    $result['data'] = $this->pmodel->showdatabyid($id);

    // if($response ==true){
    //           echo "Record fetched Successfully";
    //   }
    //   else{
    //       echo "Not fetched DATA ";
    //   }

  $databyid = $this->load->view('update', $result);
   }




   // public function updateajax12345(){
   //  $data= [];
   //  $data['response'] = false;
   //  // $data['image_errors'] ="";
   //  $formdata = $this->input->post();
   //  $id = $formdata['id'];
   //  unset($formdata['id']);
   //  // echo $formdata;
   //  if(!$this->input->is_ajax_request()) {
   //      exit('No direct script access allowed');
   //  }
   //  $this->form_validation->set_rules('name','Name','required');
   // 	$this->form_validation->set_rules('email','Email','required|valid_email');
   // 	if($this->form_validation->run() == false){
   //    $data['form_errors'] = $this->form_validation->error_array();
   // 		$this->load->view('update',$data); 
   // 	}
   //   else { 
   //       $response=$this->pmodel->updaterecord($formdata,$id);
   //        if($response==true){
   //          $data['response'] = true;
            
   //          $data['redirect_url'] = "dashboard";
   //          $data['success']  = "Updated Successfully!";
   //        }
   //      }
   //  echo json_encode($data);
   //  exit;
   // }






   public function updateajaximage(){
    $data= [];
    $data['response'] = false;
    $data['image_errors'] ="";
    $formdata = $this->input->post();
    $id = $formdata['id'];
    unset($formdata['id']);
    if(!$this->input->is_ajax_request()) {
        exit('No direct script access allowed');
    }

    if(empty($_FILES['userfile']['tmp_name'])){
      $this->form_validation->set_rules('userfile','Image','required');
    }

   	$this->form_validation->set_rules('name','Name','required');
   	$this->form_validation->set_rules('email','Email','required|valid_email');
   	if($this->form_validation->run() == false){
      $data['form_errors'] = $this->form_validation->error_array();
   		// $this->load->view('pro',$data); 
   	}else { 

      if(!empty($_FILES['userfile']['tmp_name'])){
        $config['upload_path'] = './assets/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 10000;
        $config['max_height'] = 3000;
        $config['max_width'] = 3000;
        $config['overwrite'] = true;
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('userfile')){
          $data['image_errors'] = $this->upload->display_errors();
        }else {
          $formdata['image'] = $this->upload->data('file_name');
        }
      }


   	/// create user and save to database
      

   		// $data['name'] = $this->input->post('name');
   		// $data['email'] = $this->input->post('email');
   		// $data['created'] = date('Y-m-d');
        if(empty($data['image_errors'])){
         $response=$this->pmodel->updaterecord($formdata,$id);
          if($response==true){
            $data['response'] = true;
            $data['redirect_url'] = "dashboard";
            $data['success']  = "updated Successfully!";
          }
        } 
      // else{
      //    echo "not added ";
      // }
    
   		// $this->Pmodel->create($formarray);
   		// $this->session->set_flashdata('Success','Your data added successfully');
   		
   	}
    echo json_encode($data);
    exit;
   }


     public function delete(){
      $id = $this->input->get('del');
      $deldata = $this->pmodel->deletebyid($id);
      if($deldata==true){
              echo "Record deleted Successfully";
      }
      else{
          echo "Not deleted";
      }
     }
  
     public function login(){

    $data= [];
    $data['response'] = false;
    // $data['image_errors'] ="";
    $formdata = $this->input->post();
    $email = $formdata['email'];
    $password = $formdata['psw'];
    $password1 =  md5($password);
    // $role = $formdata['role'];
    
     // print_r($password1) ;
     
    if(!$this->input->is_ajax_request()) {
        exit('No direct script access allowed');
    }
    $this->form_validation->set_rules('email','Email','required|valid_email');
    $this->form_validation->set_rules('psw','Password','required');
    
    if($this->form_validation->run() == false){
      $data['form_errors'] = $this->form_validation->error_array();
      // $this->load->view('update',$data); 
    }
     else { 
     
            $where = "users.email='".$email."' AND users.psw='".$password1."'";
      $results = $this->admin->get_where('*', $where, true, '', '1', '');
         if(!empty($results)){
          print_r($results[0]['role']);
          
        $data['response'] = true;

        if($results[0]['role'] ==1 ){
          $data['redirect_url'] = "user_dashboard";
        }
        else{
        $data['redirect_url'] = "dashboard";
      }
        $data['success']  = "updated Successfully!";
        $sessionData = array(
          'admin_email' => $results[0]['email'],
          'admin_id' => $results[0]['user_id']
        );
      $set_sesion =  $this->session->set_userdata($sessionData);
      
      }else{
        $data['password_error'] = 'Incorrect Password!';
      }

        
        }
    echo json_encode($data);
    exit;
   
      
     }




public function login_view(){
  $this->load->view('login'); 
}


}
