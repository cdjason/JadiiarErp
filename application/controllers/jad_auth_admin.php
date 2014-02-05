<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_auth_admin extends CI_Controller {

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
			     //$this->output->enable_profiler(TRUE);
		    }
		 		//获取jadiiar ERP的配置信息
		    $this->config->load('jadiiar_conf');
		    // Load required CI libraries and helpers.
		    $this->load->database();
		    $this->load->library('session');
 		    $this->load->helper('url');
 		    $this->load->helper('form');

  		  // IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
 		    // It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
		    $this->auth = new stdClass;

		    // Load 'standard' flexi auth library by default.
		    $this->load->library('flexi_auth');	

		    // Check user is logged in as an admin.
		    // For security, admin users should always sign in via Password rather than 'Remember me'.
		    
		
		    if (! $this->flexi_auth->is_logged_in_via_password() || ! $this->flexi_auth->is_admin()) 
		    {
			    // Set a custom error message.
			    $this->flexi_auth->set_error_message('您必须登陆管理员帐户以管理该页面', TRUE);
			    $this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			    redirect('jad_auth');
		    }
        		
		    // Define a global variable to store data that is then used by the end view page.
		    $this->load->vars('base_url', $this->config->item('base_url'));
		    $this->load->vars('includes_dir', $this->config->item('includes_dir'));
		    $this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		    $this->data = null;

		    //调试使用
		   //$this->output->enable_profiler(TRUE);
	}
	
	

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Dashboard
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * index
	 * Forwards to the admin dashboard.
	 */ 
	function index()
    {
		$this->dashboard();
	}
 	/**
 	 * dashboard (Admin)
 	 * The public account dashboard page that acts as the landing page for newly logged in public users.
 	 * The dashboard provides links to some examples of the features available from the flexi auth library.  
 	 */
    function dashboard(){
    	
		    $this->data['message'] = $this->session->flashdata('message');
		    $this->load->view('jadiiar/jad_admin/dashboard_view', $this->data);
	  }
	  

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Accounts
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
 /**
 	 * 新增管理员更改用户密码的功能 change_password
 	 * Manually update the logged in public users password, by submitting the current and new password.
 	 * This example requires that the length of the password must be between 8 and 20 characters, containing only alpha-numerics plus the following 
 	 * characters: periods (.), commas (,), hyphens (-), underscores (_) and spaces ( ). These customisable validation settings are defined via the auth config file.
 	 */
	function change_password($uId)
	{
		// If 'Update Password' form has been submitted, validate and then update the users password.
		if ($this->input->post('change_password'))
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->change_password($uId);
		}
				
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->load->view('jadiiar/jad_admin/password_update_view', $this->data);
	}

	/**
	 * 增加用户账户
	 * 管理员添加新用户时使用。
	 */ 
	function add_user_account()
	{
		// Redirect user away from registration page if already logged in.
		
		//if ($this->flexi_auth->is_logged_in()) 
		//{
		//	redirect('jad_auth');
		//}
		// If 'Registration' form has been submitted, attempt to register their details as a new account.
		//else 
		if ($this->input->post('add_new_user'))
		{		
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->add_user_account();
		}
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
		$this->load->view('jadiiar/jad_admin/user_account_add_view_new',$this->data);
	}
 	/**
 	 * manage_user_accounts
 	 * View and manage a table of all users.
 	 * This function allows accounts to be suspended or deleted via checkoxes within the page.
 	 * The function also includes a search tool to lookup users via either their email, first name or last name. 
 	 */
    function manage_user_accounts()
    {
		    $this->load->model('jad_auth_admin_model');
		    // Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		    if (! $this->flexi_auth->is_privileged('View Users'))
		        {
		        	  $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			          redirect('jad_auth_admin');
		        }
    
		    // If 'Admin Search User' form has been submitted, this example will lookup the users email address and first and last name.
		    if ($this->input->post('search_users') && $this->input->post('search_query')) 
		    {
			    $search_query = str_replace(' ','-',urlencode($this->input->post('search_query')));
			    // Assign search to query string.
			    redirect('jad_auth_admin/manage_user_accounts/search/'.$search_query.'/page/');
		    }// If 'Manage User Accounts' form has been submitted and user has privileges to update user accounts, then update the account details.
		    else if ($this->input->post('update_users') && $this->flexi_auth->is_privileged('Update Users')) 
		    {
			    $this->jad_auth_admin_model->update_user_accounts();
		    }

		    // Get user account data for all users. 
		    // If a search has been performed, then filter the returned users.
		    $this->jad_auth_admin_model->get_user_accounts();
		    // Set any returned status/error messages.
		    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
 // 对系统报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
		    $this->load->view('jadiiar/jad_admin/user_accounts_view_new', $this->data);		
    }
    
 	/**
 	 * 普通用户更新自己的账户信息
 	 * Update the account details of a specific user.
 	 */
	function update_user_account($user_id)
	{
        $this->load->model('jad_auth_admin_model');
		if (! $this->flexi_auth->is_privileged('Update Users'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user accounts.</p>');
			redirect('jad_auth_admin');		
		}
		// If 'Update User Account' form has been submitted, update the users account details.
		if ($this->input->post('update_user_account')) 
		{
			$this->jad_auth_admin_model->update_user_account($user_id);
        }
        else if($this->input->post('change_password'))
        {
			$this->jad_auth_admin_model->change_password($user_id);
        }
		//获取地域信息
		$this->data['areas_info'] = $this->jad_global_model->get_areas_info();

		// Get users current data.
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array(FALSE, $sql_where);
		// Get user groups.
		$this->data['groups'] = $this->flexi_auth->get_groups_array();
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
		$this->load->view('jadiiar/jad_admin/user_account_update_view_new', $this->data);
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Groups
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_user_groups
 	 * View and manage a table of all user groups.
 	 * This example allows user groups to be deleted via checkoxes within the page.
 	 */
    function manage_user_groups()
    {
		// Check user has privileges to view user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user groups.</p>');
			redirect('jad_auth_admin');		
		}

		// If 'Manage User Group' form has been submitted and user has privileges, delete user groups.
		if ($this->input->post('delete_group') && $this->flexi_auth->is_privileged('Delete User Groups')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->manage_user_groups();
		}

		// Define the group data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_group', 'id'),
			$this->flexi_auth->db_column('user_group', 'name'),
			$this->flexi_auth->db_column('user_group', 'description'),
			$this->flexi_auth->db_column('user_group', 'admin')
		);
		$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select);
				
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
		$this->load->view('jadiiar/jad_admin/user_groups_view_new', $this->data);		
    }

 	/**
 	 * insert_user_group
 	 * Insert a new user group.
 	 */
	function insert_user_group()
	{
		// Check user has privileges to insert user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Insert User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to insert new user groups.</p>');
			redirect('jad_auth_admin/manage_user_groups');		
		}

		// If 'Add User Group' form has been submitted, insert the new user group.
		if ($this->input->post('insert_user_group')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->insert_user_group();
		}
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
// 对系统报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 

		$this->load->view('jadiiar/jad_admin/user_group_insert_view_new', $this->data);
	}    
 	/**
 	 * update_user_group
 	 * Update the details of a specific user group.
 	 */
	function update_user_group($group_id)
	{
		// Check user has privileges to update user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update User Groups'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user groups.</p>');
			redirect('jad_auth_admin/manage_user_groups');		
		}

		// If 'Update User Group' form has been submitted, update the user group details.
		if ($this->input->post('update_user_group')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->update_user_group($group_id);
		}

		// Get user groups current data.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 

		$this->load->view('jadiiar/jad_admin/user_group_update_view_new', $this->data);
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Privileges
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_privileges
 	 * View and manage a table of all user privileges.
 	 * This example allows user privileges to be deleted via checkoxes within the page.
 	 */
    function manage_privileges()
    {
		// Check user has privileges to view user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('View Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view user privileges.</p>');
			redirect('jad_auth_admin');		
		}
		
		// If 'Manage Privilege' form has been submitted and the user has privileges to delete privileges.
		if ($this->input->post('delete_privilege') && $this->flexi_auth->is_privileged('Delete Privileges')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->manage_privileges();
		}

		// Define the privilege data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
				
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
		$this->load->view('jadiiar/jad_admin/privileges_view_new', $this->data);
	}
 	/**
 	 * insert_privilege
 	 * Insert a new user privilege.
 	 */
	function insert_privilege()
	{
		// Check user has privileges to insert user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Insert Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to insert new user privileges.</p>');
			redirect('jad_auth_admin/manage_privileges');		
		}

		// If 'Add Privilege' form has been submitted, insert the new privilege.
		if ($this->input->post('insert_privilege')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->insert_privilege();
		}
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
         // 对系统报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
		$this->load->view('jadiiar/jad_admin/privilege_insert_view_new', $this->data);
	}
 	/**
 	 * update_privilege
 	 * Update the details of a specific user privilege.
 	 */
	function update_privilege($privilege_id)
	{
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('jad_auth_admin/manage_privileges');		
		}

		// If 'Update Privilege' form has been submitted, update the privilege details.
		if ($this->input->post('update_privilege')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->update_privilege($privilege_id);
		}
		
		// Get privileges current data.
		$sql_where = array($this->flexi_auth->db_column('user_privileges', 'id') => $privilege_id);
		$this->data['privilege'] = $this->flexi_auth->get_privileges_row_array(FALSE, $sql_where);
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
     // 对报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
		$this->load->view('jadiiar/jad_admin/privilege_update_view_new', $this->data);
	}
	
 	/**
 	 * update_user_privileges
 	 * Update the access privileges of a specific user.
 	 */
    function update_user_privileges($user_id)
    {
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('jad_auth_admin/manage_user_accounts');		
		}

		// If 'Update User Privilege' form has been submitted, update the user privileges.
		if ($this->input->post('update_user_privilege')) 
		{
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->update_user_privileges($user_id);
		}

		// Get users profile data.
		$sql_select = array(
			'upro_uacc_fk', 
			'upro_full_name', 
			$this->flexi_auth->db_column('user_acc', 'group_id'),
			$this->flexi_auth->db_column('user_group', 'name')
        );
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array($sql_select, $sql_where);		

		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get user groups current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $this->data['user'][$this->flexi_auth->db_column('user_acc', 'group_id')]);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
        $this->data['group_privileges'] = array();
        foreach($group_privileges as $privilege)
        {
            $this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
        }
                
		// Get users current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'user_id') => $user_id);
		$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);
	
		// For the purposes of the example demo view, create an array of ids for all the users assigned privileges.
		// The array can then be used within the view to check whether the user has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['user_privileges'] = array();
		foreach($user_privileges as $privilege)
		{
			$this->data['user_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_users', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
		$this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
		$this->load->view('jadiiar/jad_admin/user_privileges_update_view_new', $this->data);		
    }
    
 	/**
 	 * update_group_privileges 
 	 * Update the access privileges of a specific user group.
 	 */
    function update_group_privileges($group_id)
    {
		// Check user has privileges to update group privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged('Update Privileges'))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update group privileges.</p>');
			redirect('jad_auth_admin/manage_user_accounts');		
		}

		// If 'Update Group Privilege' form has been submitted, update the privileges of the user group.
		if ($this->input->post('update_group_privilege')) 
        {
			$this->load->model('jad_auth_admin_model');
			$this->jad_auth_admin_model->update_group_privileges($group_id);
		}
		
		// Get data for the current user group.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
                
		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
		// Get data for the current privilege group.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $group_id);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
		// For the purposes of the example demo view, create an array of ids for all the privileges that have been assigned to a privilege group.
		// The array can then be used within the view to check whether the group has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['group_privileges'] = array();
		foreach($group_privileges as $privilege)
		{
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
	
        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
        $this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
		$this->load->view('jadiiar/jad_admin/user_group_privileges_update_view_new', $this->data);		
    }
}
