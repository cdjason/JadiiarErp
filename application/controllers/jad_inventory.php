<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_inventory extends CI_Controller {
    function __construct() 
    {
        parent::__construct();
		    // To load the CI benchmark and memory usage profiler - set 1==1.
		    if (1==2) 
		    {
			     $sections = array(
				    'benchmarks' => TRUE, 'memory_usage' => TRUE, 
				    'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE, 
				    'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => FALSE
			     ); 
			     $this->output->set_profiler_sections($sections);
			     $this->output->enable_profiler(TRUE);
		    }
		 
		    // Load required CI libraries and helpers.
		    $this->load->database();
 		    $this->load->helper('url');
 		    $this->load->helper('form');
 		 
		    $this->config->load('jadiiar_conf');

  		  // IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		    // It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		    $this->auth = new stdClass;

		    // Load 'standard' flexi auth library by default.
		    $this->load->library('flexi_auth');
        //检查用户是否通过密码登陆
        if (! $this->flexi_auth->is_logged_in_via_password()){
			    $this->flexi_auth->set_error_message('尚未登陆，禁止访问！', TRUE);
			    $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			    redirect('jad_auth');
        }
		    // Define a global variable to store data that is then used by the end view page.
		    $this->load->vars('base_url', $this->config->item('base_url'));
		    $this->load->vars('includes_dir', $this->config->item('includes_dir'));
		    $this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		    $this->data = null;
 	  }
 	/**
 	 * inventory_search
 	 * 搜索已购买过的商品信息
 	 */
    function inventory_search()
    {
		    $this->load->model('jad_inventory_model');
		    // 当有提交操作时
		    if ($this->input->post('search_missions')) 
		    {
			    // Convert uri ' ' to '-' spacing to prevent '20%'.
			    // Note: Native php functions like urlencode() could be used, but by default, CodeIgniter disallows '+' characters.
			    //组合查询字符串***************************************************************************************************
			    //获取分店ID
			    $bId = $this->input->post('select_location');
			    //获取编码
			    if (!$this->input->post('code_search_query'))
			      $code = 'noinputvalue';
			    else
			      $code = $this->input->post('code_search_query');
			    
			    $search_query = $bId.'/search_code/'.urlencode($code);
			    redirect('jad_inventory/inventory_search/search_b_id/'.$search_query.'/page/');
		    }
		    // Check user has privileges to view suppliers info, else display a message to notify the user they do not have valid privileges.
		    /*
		    if (! $this->flexi_auth->is_privileged('View Branches'))
		        {
		        	  $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view branches info.</p>');
			          redirect('jad_auth');
		        }
        
        if ($this->input->post('update_branches')) 
		    {
			    $this->jad_dictionary_model->update_branches();
		    }
        */
		    // 获取商品库存信息
		    $this->jad_inventory_model->search_inventory();
		    //获取库存位置
		    $this->data['info_branch'] = $this->jad_global_model->get_branchesinfo();
		    //获取商品状态信息
		    $this->data['m_existed_type'] = $this->config->item('m_existed_type');
        //获取主任务类型信息
		    $this->data['m_type'] = $this->config->item('m_type');
		    // Set any returned status/error messages.
		    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		    $this->load->view('jadiiar/jad_inventory/search_inventory_view', $this->data);		
    }	
  /**
 	 * inventory_merch_details
 	 * 显示商品详细信息
 	 */
 	 function inventory_merch_details($merchId){
	  $this->load->model('jad_inventory_model');
		$this->jad_inventory_model->inventory_merch_details($merchId);
		
	  //获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');			
		$this->data['pm_status'] = $this->config->item('pm_status');
		$this->data['mm_status'] = $this->config->item('mm_status');
    // Set any returned status/error messages.
    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	  $this->load->view('jadiiar/jad_inventory/inventory_merch_details_view', $this->data);		
 	 }
}