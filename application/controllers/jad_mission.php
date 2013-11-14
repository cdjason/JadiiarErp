<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_mission extends CI_Controller {
   
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
		    $this->load->library('session');
 		    $this->load->helper('url');
 		    $this->load->helper('form');
 		    
 		    //获取jadiiar ERP的配置信息
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
		    //便于调试
		    //$this->output->enable_profiler(TRUE);
 	  }
 	/**
 	 * 下达代购、囤货任务
 	 * 
 	 */
	function new_mission($goodCode = FALSE)
	{ 
		$codeTemp = '';
		$goodCode = urldecode($goodCode);	
		$this->load->model('jad_goods_model');
		if ($this->input->post('new_mission')) 
		{
			//输入验证
			$this->load->library('form_validation');
			if ( $this->input->post('input_mission_type') ==1 ){	
				//代购任务			
		    $validation_rules = array(
			    array('field' => 'add_mission_desc', 'label' => '任务要求:', 'rules' => 'required|max_length[1000]'),
			    array('field' => 'add_client_name', 'label' => '客户名称:', 'rules' => 'required'),
			    array('field' => 'add_mission_client', 'label' => '客户联系方式:', 'rules' => 'required|max_length[1000]'),
			    array('field' => 'add_order_price', 'label' => '价格:', 'rules' => 'required|numeric')
		    );				
			}else{
				//囤货任务			
		    $validation_rules = array(
			    array('field' => 'add_mission_desc', 'label' => '任务要求:', 'rules' => 'required|max_length[1000]')
		    );
			}
			$this->form_validation->set_rules($validation_rules);
			if ($this->form_validation->run()){
		  
			  //提取需要预览的值，生成数组，序列化之后放入flash session
			  $mailMessage = array();
			  $mailMessage['select_mission_type'] = $this->input->post('input_mission_type');
			  $mailMessage['isFromMissionPage'] = $this->input->post('isFromMissionPage');
			  $mailMessage['select_mission_buyer'] = $this->input->post('select_mission_buyer');
			  $mailMessage['select_mission_seriesnum'] = $this->input->post('select_mission_seriesnum');
			  $mailMessage['select_mission_colour'] = $this->input->post('select_mission_colour');
			  $mailMessage['select_mission_size'] = $this->input->post('select_mission_size');
			  $mailMessage['input_mission_s_desc'] = trim($this->input->post('input_mission_s_desc'));
			  $mailMessage['add_mission_desc'] = trim($this->input->post('add_mission_desc'));
			  $mailMessage['mission_client'] = $this->input->post('add_client_name').'@'.$this->input->post('add_mission_client');
			  $mailMessage['add_order_price'] = $this->input->post('add_order_price');
			  $mailMessage['select_merch_unit'] = $this->input->post('select_merch_unit');
			  //获取物流路线信息的值，封装入 array
			  $sendData = $this->input->post('mail_sid');
			  $receiverData = $this->input->post('mail_rid');
			  for($i=0;$i<count($sendData);$i++){
				  $mailMessage['mailLine'][$i] = array($sendData[$i],$receiverData[$i]);
			  }			
			  $this->session->set_flashdata('mail_message', serialize($mailMessage));			
		    //提交到一个预览的页面进行任务下达前的信息预览，flashdata传值
		    redirect('jad_mission/mission_submit');
		  }
		  // Set validation errors.
		  $this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		  //return FALSE;
		}	  
	  //获取所有买手名称
	  $this->data['buyers'] = $this->jad_global_model->get_staff_list_by_role_id(4);
	  //获取所有邮寄中转的分店用户信息
	  $this->data['mail_branch_users'] = $this->jad_global_model->get_staff_list_by_role_id(2);
	  //获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');	  
	  //获取配置文件中的货币数组
	  $this->data['monetary_unit'] = $this->config->item('monetary_unit');	  
	  if (!$goodCode){
	     //获取当前未过期的商品货号
	     $this->data['goods'] = $this->jad_goods_model->get_seriesnums_unexpired();
	  }
	  else{
	     $codeTemp = $this->jad_global_model->get_goods_s_info_by_code($goodCode);
	     $this->data['goodCode'] = $goodCode;
	     $this->data['goods_s_desc'] = $codeTemp['goods_s_desc'];
	  }
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		$this->load->view('jadiiar/jad_mission/new_mission_view', $this->data);
	}
 	/**
 	 * 下达发货任务
 	 * 
 	 */
	function new_mission_spot($merchId,$uproId)
	{ 	
		$this->load->model('jad_goods_model');
		if ($this->input->post('new_mission_spot')){
			//输入验证
			$this->load->library('form_validation');
			if ( $this->input->post('select_mission_type') ==3 ){	
				//现货发货任务			
		    $validation_rules = array(
			    array('field' => 'add_client_name', 'label' => '客户名称:', 'rules' => 'required'),
			    array('field' => 'add_mission_client', 'label' => '客户联系方式:', 'rules' => 'required|max_length[1000]'),
			    array('field' => 'add_order_price', 'label' => '价格:', 'rules' => 'required|numeric')
		    );				
			}else{
				//调货任务，暂时没有约束			
		    $validation_rules = array(
		      array('field' => 'select_mission_type', 'label' => '任务类型:', 'rules' => 'required')
		    );
			}
			$this->form_validation->set_rules($validation_rules);
			if ($this->form_validation->run()){
			  //提取需要预览的值，生成数组，序列化之后放入flash session
			  $mailMessage = array();
			  $mailMessage['select_mission_type'] = $this->input->post('input_mission_type');
			  $mailMessage['input_merch_id'] = $this->input->post('input_merch_id');
			  $mailMessage['mission_client'] = $this->input->post('add_client_name').'@'.$this->input->post('add_mission_client');
			  $mailMessage['add_order_price'] = $this->input->post('add_order_price');
			  $mailMessage['select_merch_unit'] = $this->input->post('select_merch_unit');
			  //获取物流路线信息的值，封装入 array
			  $sendData = $this->input->post('mail_sid');
			  $receiverData = $this->input->post('mail_rid');
			  for($i=0;$i<count($sendData);$i++){
			  	$mailMessage['mailLine'][$i] = array($sendData[$i],$receiverData[$i]);
			  }			
			  $this->session->set_flashdata('mail_message', serialize($mailMessage));			
		    //提交到一个预览的页面进行任务下达前的信息预览，flashdata传值
		    redirect('jad_mission/mission_spot_submit');
		  }
		  // Set validation errors.
		  $this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		  //return FALSE;
		}	  	  
	  //获取所有邮寄中转的分店用户信息
	  $this->data['mail_branch_users'] = $this->jad_global_model->get_staff_list_by_role_id(2);
	  //获取所有买手用户信息
	  $this->data['mail_buyer_users'] = $this->jad_global_model->get_staff_list_by_role_id(4);
	  
	  //获取配置文件中的货币数组
	  $this->data['monetary_unit'] = $this->config->item('monetary_unit');	
	  //获取商品的所有信息
	  $this->data['info_merch'] = $this->jad_global_model->get_merch_info_by_merch_id($merchId);
	  $this->data['isBuyer'] = 0;
	  //根据员工ID判断该员工属于买手还是属于分店负责人，以便在物流信息中进行设置
	  if ($this->jad_global_model->is_in_group($uproId,4)){
	  	$this->data['isBuyer'] = 1;
	  }
	  $this->data['uproId'] = $uproId;
	  // Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		$this->load->view('jadiiar/jad_mission/new_mission_spot_view', $this->data);
	}	
 	/**
 	 * 下达退货任务
 	 * 
 	 */
	function new_mission_return($merchId,$supplId,$merchExistLocation)
	{ 	
		$this->load->model('jad_goods_model');
		if ($this->input->post('new_mission_return')){
			//提取需要预览的值，生成数组，序列化之后放入flash session
			$mailMessage = array();
			$mailMessage['input_merch_id'] = $this->input->post('input_merch_id');
			$mailMessage['mission_client'] = $this->input->post('add_mission_client');

			
			//获取物流路线信息的值，封装入 array
			$sendData = $this->input->post('mail_sid');
			$receiverData = $this->input->post('mail_rid');
			
			for($i=0;$i<count($sendData);$i++){
				$mailMessage['mailLine'][$i] = array($sendData[$i],$receiverData[$i]);
			}			
			
			$this->session->set_flashdata('mail_message', serialize($mailMessage));			
		  //提交到一个预览的页面进行任务下达前的信息预览，flashdata传值
		  redirect('jad_mission/mission_return_submit');
		  
		}
		//商品的供应商ID
		$this->data['supplId'] = $supplId;
		$this->data['branchId'] = '';
		$this->data['clientInfo'] = '';
		$this->data['buyerId'] = '';
		switch($merchExistLocation){
      case 1:
        //当前位于库存
        $this->data['branchId'] = $this->jad_global_model->get_branch_info_by_mId($merchId);
        break;
      case 3:
        //已售，当前位于客户处，获取客户信息
        $this->data['clientInfo'] = $this->jad_global_model->get_client_info_by_mId($merchId);
        break;
      case 5:
        //当前位于买手处暂存，获取买手信息
        $this->data['buyerId'] = $this->jad_global_model->get_buyer_info_by_mId($merchId);
        break;
		}
		//商品当前位置信息
		$this->data['merchExistLocation'] = $merchExistLocation;
	  
	  //获取所有邮寄中转的分店用户信息
	  $this->data['mail_branch_users'] = $this->jad_global_model->get_staff_list_by_role_id(2);
	  //获取所有供货商的信息
	  $this->data['mail_supplier'] = $this->jad_global_model->get_suppliersinfo();
	  //获取所有买手名称
	  $this->data['buyers'] = $this->jad_global_model->get_staff_list_by_role_id(4);
	  //获取商品的所有信息
	  $this->data['info_merch'] = $this->jad_global_model->get_merch_info_by_merch_id($merchId);
		//获取购买该商品的买手信息
	  $this->data['info_buyer'] = $this->jad_global_model->get_buyerinfo_by_id($merchId);
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		$this->load->view('jadiiar/jad_mission/new_mission_return_view', $this->data);
	}	
	/**
 	 * 退货任务预览页面
 	 * 
 	 */
	function mission_return_submit(){
		
		$mailInfo = unserialize($this->session->flashdata('mail_message'));
		$missionClient = $mailInfo['mission_client'];
		$merchId = $mailInfo['input_merch_id'];
		$this->data['merchId'] = $merchId;
		$this->data['missionClient'] = $missionClient;
		//对线路信息直接弄到前台去处理
		$mailLine = $mailInfo['mailLine'];
		$this->data['mailLine'] = $mailLine;
		$this->session->set_flashdata('mailLine', serialize($mailLine));	
		
		if ($this->input->post('mission_return_submit')) 
		{
			$this->load->model('jad_mission_model');
			$this->jad_mission_model->new_mission_return();
		}
		//主任务类型的标识
		
		$this->load->view('jadiiar/jad_mission/mission_return_submit_view', $this->data);
	}		
	/**
 	 * 发货任务预览页面
 	 * 
 	 */
	function mission_spot_submit(){
		
		$mailInfo = unserialize($this->session->flashdata('mail_message'));
		$missionType = $mailInfo['select_mission_type'];
		$missionClient = $mailInfo['mission_client'];
		$merchId = $mailInfo['input_merch_id'];
		$orderPrice = $mailInfo['add_order_price'];
		$merchUnit = $mailInfo['select_merch_unit'];
		$this->data['orderPrice'] = $orderPrice;
		$this->data['merchUnit'] = $merchUnit;
		$this->data['merchId'] = $merchId;
		$this->data['missionClient'] = $missionClient;
		//对线路信息直接弄到前台去处理
		$mailLine = $mailInfo['mailLine'];
		$this->data['mailLine'] = $mailLine;
		$this->session->set_flashdata('mailLine', serialize($mailLine));	
		
		if ($this->input->post('mission_spot_submit')) 
		{
			$this->load->model('jad_mission_model');
			$this->jad_mission_model->new_mission_spot();
		}
		//主任务类型的标识
		
		$this->data['m_type'] = $missionType;
		$this->data['m_type_items'] = $this->config->item('m_type');
		$this->load->view('jadiiar/jad_mission/mission_spot_submit_view', $this->data);
	}	
	
	/**
 	 * 新任务预览页面
 	 * 
 	 */
	function mission_submit(){
		
		$mailInfo = unserialize($this->session->flashdata('mail_message'));
		$missionType = $mailInfo['select_mission_type'];
		$seriesnum = $mailInfo['select_mission_seriesnum'];
		$colour = $mailInfo['select_mission_colour'];
		$size = $mailInfo['select_mission_size'];
		$s_desc = $mailInfo['input_mission_s_desc'];
		$buyid = $mailInfo['select_mission_buyer'];
		$desc = $mailInfo['add_mission_desc'];
		$isFromMPage = $mailInfo['isFromMissionPage'];
		$missionClient = $mailInfo['mission_client'];
		$orderPrice = $mailInfo['add_order_price'];
		$merchUnit = $mailInfo['select_merch_unit'];
		
		$this->data['orderPrice'] = $orderPrice;
		$this->data['merchUnit'] = $merchUnit;
		$this->data['missionClient'] = $missionClient;
		//对线路信息直接弄到前台去处理
		$mailLine = $mailInfo['mailLine'];
		$this->data['mailLine'] = $mailLine;
		$this->session->set_flashdata('mailLine', serialize($mailLine));	
		

		if ($this->input->post('mission_submit')) 
		{
			$this->load->model('jad_mission_model');
			$this->jad_mission_model->new_mission();
		}
		//商品详细信息，包括颜色和尺寸
		$this->data['m_merch_id'] = $seriesnum.$colour.$size;
		foreach($this->config->item('merch_colour') as $co){
		 if($co[1]==$colour) $this->data['m_colour'] = $co[0];
		}
		foreach($this->config->item('merch_size') as $ms){
		 if($ms[1]==$size) $this->data['m_size'] = $ms[0];
		}
		//商品一级信息，包括品牌、类别和基本描述
    $this->data['goods_first'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum);
    //所需的商品二级信息，包括详细描述和长编码
    $this->data['goods_code'] = $seriesnum.$colour.$size;
    $this->data['goods_s_desc'] = $s_desc;
		
		//获取买手信息，联系方式
		$this->data['m_buyer'] = $this->flexi_auth->get_user_by_id($buyid)->row_array();
		//标记来源页面
		$this->data['isFromMPage'] = $isFromMPage;
		
		//任务状况
		$this->data['m_buyid'] = $buyid;
		$this->data['m_seriesnum'] = $seriesnum;
		$this->data['m_desc'] = $desc;
		
		//主任务类型的标识
		
		$this->data['m_type'] = $missionType;
		$this->data['m_type_items'] = $this->config->item('m_type');
		$this->load->view('jadiiar/jad_mission/mission_submit_view', $this->data);
	}
 	/**
 	 * 买手查看当前任务
 	 * 
 	 */
	function manage_current_missions(){
		
	   $this->load->model('jad_mission_model');
		 // Check user has privileges to view suppliers info, else display a message to notify the user they do not have valid privileges.
		 /*
		 if (! $this->flexi_auth->is_privileged('View Mission'))
		 {
		    $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view mission info.</p>');
	      redirect('jad_auth');
     }*/
     //获取当前的购买任务
		 $this->jad_mission_model->get_current_mission();
		 
		 $this->data['pm_status'] = $this->config->item('pm_status');
     // Set any returned status/error messages.
     $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	   $this->load->view('jadiiar/jad_mission/current_mission_view', $this->data);		
	}
 	/**
 	 * 当前邮递任务一览
 	 * 
 	 */
	function manage_current_mail_missions(){
		
	   $this->load->model('jad_mission_model');
		 // Check user has privileges to view suppliers info, else display a message to notify the user they do not have valid privileges.
		 /*
		 if (! $this->flexi_auth->is_privileged('View Mission'))
		 {
		    $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view mission info.</p>');
	      redirect('jad_auth');
     }*/
		 //获取当前的邮递任务
		 $this->jad_mission_model->get_current_mail_mission();
		 $this->data['mm_status'] = $this->config->item('mm_status');
     // Set any returned status/error messages.
     $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	   $this->load->view('jadiiar/jad_mission/current_mail_mission_view', $this->data);		
	}
	
 	/**
 	 * 邮寄对象处理邮寄任务，更新状态信息
 	 * 
 	 */
	function execute_mission($id,$status){
		
	  $this->load->model('jad_mission_model');
	  $this->jad_mission_model->execute_mission_by_buyer($id,$status);
		redirect('jad_mission/manage_current_missions');
	}

 	/**
 	 * 买手完成任务，提交任务相关信息
 	 * 
 	 */
	function finish_purchase($id,$m_id,$missionId){
		if ($this->input->post('finish_purchase')) 
		{
			$this->load->model('jad_mission_model');
			$this->jad_mission_model->finish_purchase();
		}		
		//获取供应商列表
	  $this->data['suppliers'] = $this->jad_global_model->get_suppliersinfo();
	  //获取商品标识码
	  $this->data['merch_id'] = urldecode($m_id);
	  $this->data['pur_id'] = $id;
	  $this->data['missionId'] = $missionId;
	  $this->data['monetary_unit'] = $this->config->item('monetary_unit');
		$this->load->view('jadiiar/jad_mission/finish_purchase_view', $this->data);
	}
	
 	/**
 	 * 供货商收到退货商品，提交相关费用信息。
 	 * 
 	 */
	function finish_return($transId,$merchId,$missionId){
		if ($this->input->post('finish_return')) 
		{
			$this->load->model('jad_mission_model');
			$this->jad_mission_model->finish_return();
		}				
	  //获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');			
		$this->data['infoGoods'] = $this->jad_global_model->get_merch_info_by_merch_id($merchId);
	  $this->data['merchId'] = urldecode($merchId);
	  $this->data['transId'] = $transId;
	  $this->data['missionId'] = $missionId;
	  $this->data['monetary_unit'] = $this->config->item('monetary_unit');
	  
		$this->load->view('jadiiar/jad_mission/finish_return_view', $this->data);
	}
	
 	/**
 	 * 邮寄人完成邮寄任务，提交邮寄的相关信息
 	 * 
 	 */
	function finish_mail($transId){
		if ($this->input->post('finish_mail')) 
		{
			$this->load->model('jad_mission_model');
			//提前获取邮费
			$this->load->library('form_validation');
			$this->form_validation->set_rules(array(			
			   array('field' => 'add_mail_tracking', 'label' => '快递单号:', 'rules' => 'required|alpha_dash|max_length[50]'),
			   array('field' => 'add_mail_price', 'label' => '邮寄费用:', 'rules' => 'required|numeric')
		  ));
		  if ($this->form_validation->run()){
			  $mailPrice = $this->input->post('add_mail_price');
			  //如果是集体邮寄，需要循环执行finish_mail()方法，并实现价格的均摊；
			  if ($this->input->post('get_trans_id')=='selected'){
				  //从闪存中取出
				  $mailInfo = unserialize($this->session->flashdata('mail_message'));
				  $priceAllocate = $mailPrice/count($mailInfo['mailLine']);
				  $priceAllocate=floor($priceAllocate*100)/100;
				  foreach($mailInfo['mailLine'] as $mal){
					  $this->jad_mission_model->finish_mail($mal,$priceAllocate);
				  }
		    }else{
		  	  $this->jad_mission_model->finish_mail($transId,$mailPrice);
		    }
	      redirect('jad_mission/history_mail_search');
	    }
		  // Set validation errors.
		  $this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		  //return FALSE;
		}		
		//判断是集体邮寄'2'表示，还是单独邮寄'1'表示
		if($transId=='selected'){
		  $this->data['mailType'] = '2';
		  //获取待邮寄的单号
		  if ($select_trans = $this->input->post('select_trans')){
		  	  $i=0;
				  foreach($select_trans as $st => $mail){
				  	//放入闪存
				  	$mailMessage['mailLine'][$i] = $st;$i++;
				  }
				  $this->session->set_flashdata('mail_message', serialize($mailMessage));
			}
	  }else{
	    $this->data['mailType'] = '1';
		}
	  //获取商品标识码
	  $this->data['monetary_unit'] = $this->config->item('monetary_unit');
	  $this->data['transId'] = $transId;	  
	  $this->data['couriersInfo'] = $this->config->item('couriers_info');	
		$this->load->view('jadiiar/jad_mission/finish_mail_view', $this->data);
	}
 	/**
 	 * 邮寄接收人收到邮寄商品，激活转发或入库的任务。
 	 * 
 	 */
	function received_mail($transId){

	  $this->load->model('jad_mission_model');
		$this->jad_mission_model->received_mail($transId);
		redirect('jad_mission/manage_current_mail_missions');
	}
	
 	/**
 	 * 对于主任务已经终止，但尚未邮寄的商品，采取直接本地入库的处理。
 	 * 
 	 */
	function end_terminated_mission($transId){

	  $this->load->model('jad_mission_model');
		$this->jad_mission_model->end_terminated_mission($transId);
		redirect('jad_mission/manage_current_mail_missions');
	}	
 	/**
 	 * 对于买手无法退货的商品，采取直接本地入库的处理。
 	 * 
 	 */
	function end_return_mission($transId){

	  $this->load->model('jad_mission_model');
		$this->jad_mission_model->end_return_mission($transId);
		redirect('jad_mission/manage_current_mail_missions');
	}	
	
 	/**
 	 * 历史任务查询
 	 * 
 	 */	
	function history_missions_search(){
	   $this->load->model('jad_mission_model');
		 // Check user has privileges to view suppliers info, else display a message to notify the user they do not have valid privileges.
		 /*
		 if (! $this->flexi_auth->is_privileged('View Mission'))
		 {
		    $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view mission info.</p>');
	      redirect('jad_auth');
     }*/
		 $this->jad_mission_model->get_history_missions();
		 
		 $this->data['pm_status'] = $this->config->item('pm_status');
     // Set any returned status/error messages.
     $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	   $this->load->view('jadiiar/jad_mission/history_missions_view', $this->data);	
	}
	
 	/**
 	 * 历史邮寄任务查询
 	 * 
 	 */	
	function history_mail_search(){
	   $this->load->model('jad_mission_model');
		 // Check user has privileges to view suppliers info, else display a message to notify the user they do not have valid privileges.
		 /*
		 if (! $this->flexi_auth->is_privileged('View Mission'))
		 {
		    $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view mission info.</p>');
	      redirect('jad_auth');
     }*/
		 $this->jad_mission_model->get_history_mail();
		 
		 $this->data['mm_status'] = $this->config->item('mm_status');
		 $this->data['couriers_info'] = $this->config->item('couriers_info');
     // Set any returned status/error messages.
     $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	   $this->load->view('jadiiar/jad_mission/history_mail_view', $this->data);	
	}	
	
	
	
 	/**
 	 * 客服搜索所有任务
 	 * 
 	 */
	function missions_search(){
		
	   $this->load->model('jad_mission_model');
		 // Check user has privileges to view suppliers info, else display a message to notify the user they do not have valid privileges.
		 /*
		 if (! $this->flexi_auth->is_privileged('View Mission'))
		 {
		    $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view mission info.</p>');
	      redirect('jad_auth');
     }*/
     if ($this->input->post('select_status')) 
		    {
			    $search_query = str_replace(' ','-',$this->input->post('select_status'));
		      redirect('jad_mission/missions_search/search/'.$search_query.'/page/');
		    } 
		 $this->jad_mission_model->search_missions();
		 $this->data['m_status'] = $this->config->item('m_status');
		 $this->data['m_type'] = $this->config->item('m_type');
		 
     // Set any returned status/error messages.
     $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	   $this->load->view('jadiiar/jad_mission/search_missions_view', $this->data);		
	}
 	/**
 	 * 根据主任务ID,给出相关任务信息一览
 	 * mission_details
 	 */
	function mission_details($missionId){
		
	  $this->load->model('jad_mission_model');
		$this->jad_mission_model->mission_details($missionId);
	  //获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');			
		$this->data['pm_status'] = $this->config->item('pm_status');
		$this->data['mm_status'] = $this->config->item('mm_status');
    // Set any returned status/error messages.
    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	  $this->load->view('jadiiar/jad_mission/mission_details_view', $this->data);		
	}
 	/**
 	 * 根据主任务ID,提前终止未完成的代购或囤货任务。
 	 * terminate_mission
 	 */
	function terminate_mission($missionId){
	  $this->load->model('jad_mission_model');
		$this->jad_mission_model->terminate_mission($missionId);	
	}

 	/**
 	 * 根据主任务ID,取消尚未开始购买的任务
 	 * end_purchase_mission
 	 */
	function end_purchase_mission($missionId){
	  $this->load->model('jad_mission_model');
		$this->jad_mission_model->end_purchase_mission($missionId);
	
	}
	
	
}