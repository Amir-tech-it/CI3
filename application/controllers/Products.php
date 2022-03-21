<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

public function __construct(){

    parent::__construct();
    $this->load->model('products_model', 'pmodel');

 
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

public function dashboard(){  
  
      $this->load->view('welcome'); 

  }


  public function register12324(){
    $data= [];
    $data['response'] = false;
    // $data['image_errors'] ="";
    $formdata = $this->input->post();

    if(!$this->input->is_ajax_request()) {
        exit('No direct script access allowed');
    }

   

   	$this->form_validation->set_rules('email','Email','required');
   	$this->form_validation->set_rules('psw','Password','required');
     $this->form_validation->set_rules('psw-repeat', 'Password Confirmation', 'required|matches[psw]');
   	if($this->form_validation->run() == false){
      $data['form_errors'] = $this->form_validation->error_array();
   		 $this->load->view('pro',$data); 
   	 }
         $response=$this->pmodel->register_user($formdata);
          if($response==true){
            $data['response'] = true;
            $data['redirect_url'] = "dashboard";
            $data['success']  = "Registered Successfully!";
          }
        
      
   		
   	
    echo json_encode($data);
    exit;
   }


   public function register(){
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

    $this->form_validation->set_rules('email','Email','required');
    $this->form_validation->set_rules('psw','Password','required');
    $this->form_validation->set_rules('psw-repeat', 'Password Confirmation', 'required|matches[psw]');
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

     public function update(){
      // $id = $this->input->post('id');
      // echo $id;
      $formdata = $this->input->post();
     $id = $formdata['id'];
     unset($formdata['id']);
      $updateresponse=$this->pmodel->updaterecord($formdata,$id);
if($updateresponse==true){
              echo "Records Updated Successfully";
      }
      else{
          echo "Not Updated";
      }
      
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

}
