<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jad_auth_demo extends CI_Controller {
  function __construct() 
  {
    parent::__construct();   	
		// Load required CI libraries and helpers.
		//$this->load->database();
		$this->load->library('session');
 		$this->load->helper('url');
 		//$this->load->helper('form');
 		
    //$this->auth = new stdClass;
		//$this->load->library('flexi_auth');	
		
		$this->data = null;
		//调试使用
		$this->output->enable_profiler(TRUE);
  }
  function index(){
		// Get any status message that may have been set.
		//$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
    $this->load->view('demo_dashboard_view', $this->data);
 	}  
 	
  function other_method(){
  	
  	$this->session->set_flashdata('message', '<p class="error_msg">哈喽</p>');
		//redirect('jad_auth_demo');
		
		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
    $this->load->view('demo_method_view', $this->data);
 	} 

}