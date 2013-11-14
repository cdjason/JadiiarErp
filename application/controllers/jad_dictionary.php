<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_dictionary extends CI_Controller {
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
		
		
		    $this->config->load('jadiiar_conf');
		    // Load required CI libraries and helpers.
		    $this->load->database();
		    $this->load->library('session');
 		    //$this->load->library('pagination');
 		    $this->load->helper('url');
 		    $this->load->helper('form');

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
        
		    // Check user is logged in as an admin.
		    // For security, admin users should always sign in via Password rather than 'Remember me'.
		    /*
		    if (! $this->flexi_auth->is_logged_in_via_password() || ! $this->flexi_auth->is_admin()) 
		    {
			    // Set a custom error message.
			    $this->flexi_auth->set_error_message('You must login as an admin to access this area.', TRUE);
			    $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			    redirect('jad_auth');
		    }
        */
        	
		    // Define a global variable to store data that is then used by the end view page.
		   $this->load->vars('base_url', $this->config->item('base_url'));
		   $this->load->vars('includes_dir', $this->config->item('includes_dir'));
		   $this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		   $this->data = null;
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 供应商信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * add_supplier
	 * 新增供应商信息
	 */ 
	function add_supplier()
	{ 
		if ($this->input->post('add_supplier'))
		{
			$this->load->model('jad_dictionary_model');
			$this->jad_dictionary_model->add_supplier();
		}
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
        $this->load->view('jadiiar/jad_dictionary/supp_add_view',$this->data);
	}	
	
 	/**
 	 * manage_suppliers
 	 * 买手或管理员维护供应商信息列表
 	 * 买手只能维护自己的供应商信息，客服能维护所有的供应商信息
 	 * The function also includes a search tool to lookup suppliers via either their email, first name or last name. 
 	 */
    function manage_suppliers()
    {
		    $this->load->model('jad_dictionary_model');
		    // 检查权限
		    if (! $this->flexi_auth->is_privileged('View Suppliers'))
		        {
		        	  $this->session->set_flashdata('message', '<p class="error_msg">您没有权限查看该页面</p>');
			          redirect('jad_auth');
		        }
		    // 搜索页面
		    if ($this->input->post('search_suppliers') && $this->input->post('search_query')) 
		    {
			    $search_query = str_replace(' ','-',$this->input->post('search_query'));
		      redirect('jad_dictionary/manage_suppliers/search/'.$search_query.'/page/');
		    }
		    else if ($this->input->post('update_suppliers')) 
		    {
		      $this->jad_dictionary_model->update_suppliers();
		    }

		    $this->jad_dictionary_model->get_suppliers();
		
	      //检查当前用户是否是买手
		    $this->data['IsBuyer'] = $this->flexi_auth->in_group('buyer');
		
		    // Set any returned status/error messages.
            $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
            // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 	
		    $this->load->view('jadiiar/jad_dictionary/supp_view', $this->data);		
    }	

 	/**
 	 * update_supplier
 	 * Update the info details of a specific supplier.
 	 */
	function update_supplier($supplier_id)
	{
		// If 'Update User Account' form has been submitted, update the users account details.
		if ($this->input->post('update_supplier')) 
		{
			$this->load->model('jad_dictionary_model');
			$this->jad_dictionary_model->update_supplier($supplier_id);
		}
	  $query = $this->db->get_where('info_supplier', array('suppl_id' => $supplier_id));
	  $this->data['supplier'] = $query->row_array();
		
		// Set any returned status/error messages.
      $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
      // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
		$this->load->view('jadiiar/jad_dictionary/supp_update_view', $this->data);
	}


	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 分店信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	

	/**
	 * add_branch
	 */ 
	function add_branch()
	{
		if ($this->input->post('add_branch'))
		{
			$this->load->model('jad_dictionary_model');
			$this->jad_dictionary_model->add_branch();
		}
		
	  //获取分店负责人名单
	  $this->data['managers'] = $this->jad_global_model->get_staff_list_by_role_id(2);
	  $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
      // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
		$this->load->view('jadiiar/jad_dictionary/bran_add_view',$this->data);		
	}

 	/**
 	 * manage_branches
 	 * 管理员维护分店信息列表
 	 */
    function manage_branches()
    {
		    $this->load->model('jad_dictionary_model');
		    if (! $this->flexi_auth->is_privileged('View Branches'))
		        {
		        	  $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view branches info.</p>');
			          redirect('jad_auth');
		        }
    
        if ($this->input->post('update_branches')) 
		    {
			    $this->jad_dictionary_model->update_branches();
		    }

		    $this->jad_dictionary_model->get_branches();
		
		    // Set any returned status/error messages.
		    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
      // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
		    $this->load->view('jadiiar/jad_dictionary/bran_view', $this->data);		
    }	

 	/**
 	 * update_branch
 	 * 更新分店信息
 	 */
	function update_branch($branch_id)
	{
		if ($this->input->post('update_branch')) 
		{
			$this->load->model('jad_dictionary_model');
			$this->jad_dictionary_model->update_branch($branch_id);
		}
		$query = $this->db->get_where('info_branch', array('branch_id' => $branch_id));
	  $this->data['branch'] = $query->row_array();
	  
	  //获取分店负责人名单
	  $this->data['managers'] = $this->jad_global_model->get_staff_list_by_role_id(2);
	  
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
      // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
		$this->load->view('jadiiar/jad_dictionary/bran_update_view', $this->data);
	}

	
}


/* End of file jad_dictionary.php */
/* Location: ./application/controllers/jad_dictionary.php */
