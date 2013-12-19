<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_auth extends CI_Controller {

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
    
    // 将通过密码验证登陆的用户跳转到相应的主要面板 (However, not 'Remember me' users, as they may wish to login properly).
		// &&要两个都为T的时候结果才是T，uri_string()获取之前完整的相对地址
		if ($this->flexi_auth->is_logged_in_via_password() && uri_string() != 'jad_auth/logout') 
		{
			// Preserve any flashdata messages so they are passed to the redirect page.
			if ($this->session->flashdata('message')) { $this->session->keep_flashdata('message'); }
			
			// Redirect logged in admins (For security, admin users should always sign in via Password rather than 'Remember me'.
			if ($this->flexi_auth->is_admin()) 
			{
				redirect('jad_goods/manage_products');
			}
			else
			{
				redirect('jad_auth_public/dashboard');
			}
		}
		$this->load->vars('base_url', $this->config->item('base_url'));
		$this->load->vars('includes_dir', $this->config->item('includes_dir'));
		$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
		
		// Define a global variable to store data that is then used by the end view page.
		$this->data = null;
		//调试使用
		//$this->output->enable_profiler(TRUE);
  }
  function index(){
  	$this->login();
 	}
	/**
	 * login
	 * Login page used by all user types to log into their account.
	 * This demo includes 3 example accounts that can be logged into via using either their email address or username. The login details are provided within the view page.
	 * Note: This page is only accessible to users who are not currently logged in, else they will be redirected.
	 */ 
  function login()
  {	
		  //If 'Login' form has been submited, attempt to log the user in.
		  if ($this->input->post('login_user'))
		  {
			  $this->load->model('jad_auth_model');
			  $this->jad_auth_model->login();
		  }
			
		  // CAPTCHA Example
		  // Check whether there are any existing failed login attempts from the users ip address and whether those attempts have exceeded the defined threshold limit.
		  // If the user has exceeded the limit, generate a 'CAPTCHA' that the user must additionally complete when next attempting to login.
		  if ($this->flexi_auth->ip_login_attempts_exceeded())
		  {
            $js = "document.getElementById('captcha').src='jad_auth/g_captcha/'+Math.random();document.getElementById('captcha-form').focus();";
            $this->data['captcha'] = '<label>验证码</label><img onclick="'.$js.'" title="点击图片更换下一张！" src="jad_auth/g_captcha" id="captcha" /><input type="text" name="captcha" id="captcha-form" autocomplete="off" />'; 
		  	//echo 'exceeded';
			/**
			 * reCAPTCHA
			 * http://www.google.com/recaptcha
			 * To activate reCAPTCHA, ensure the 'recaptcha()' function below is uncommented and then comment out the 'math_captcha()' function further below.
			 *
			 * A boolean variable can be passed to 'recaptcha()' to set whether to use SSL or not.
			 * When displaying the captcha in a view, reCAPTCHA generates all html required for display. 
			 * 
			 * Note: To use this example, you will also need to enable the recaptcha examples in 'models/demo_auth_model.php', and 'views/demo/login_view.php'.
			*/
			  //$this->data['captcha'] = $this->flexi_auth->recaptcha(FALSE);
						
			/**
			 * flexi auths math CAPTCHA
			 * Math CAPTCHA is a basic CAPTCHA style feature that asks users a basic maths based question to validate they are indeed not a bot.
			 * For flexibility on CAPTCHA presentation, the 'math_captcha()' function only generates a string of the equation, see the example below.
			 * 
			 * To activate math_captcha, ensure the 'math_captcha()' function below is uncommented and then comment out the 'recaptcha()' function above.
			 *
			 * Note: To use this example, you will also need to enable the math_captcha examples in 'models/demo_auth_model.php', and 'views/demo/login_view.php'.
			*/
			# $this->data['captcha'] = $this->flexi_auth->math_captcha(FALSE);
		  }
				
		  // Get any status message that may have been set.
		  $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
          // 对系统报错信息（p标签包裹的数据）进行重新处理；        
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
          $this->load->view('jad_auth/login_view_new', $this->data);
		  //$this->load->view('demo/login_view_new');
  }

	/**
	 * g_captcha
	 * 利用google的captcha，创建图片验证码
     */
    function g_captcha()
    {
        $this->load->library('g_captcha');
        $this->g_captcha->CreateImage(); 
    }
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Logout
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * logout
	 * This example logs the user out of all sessions on all computers they may be logged into.
	 * In this demo, this page is accessed via a link on the demo header once a user is logged in.
	 */
	function logout() 
	{
		// By setting the logout functions argument as 'TRUE', all browser sessions are logged out.
		$this->flexi_auth->logout(TRUE);
		
		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());		
		redirect('jad_auth/login');
    }
 
	
	
}
	
/* End of file jad_auth.php */
/* Location: ./application/controllers/jad_auth.php */	
