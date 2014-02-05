<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_orders extends CI_Controller {
   
    function __construct() 
    {
        parent::__construct();
		 
		    // Load required CI libraries and helpers.
		    $this->load->database();
		    $this->load->library('session');
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
	 * order_add
	 * 新增商品信息
	 */ 
	function order_add(){
		//获取orders_pool中的商品信息
		$this->load->library('cart');
		$result = array(); 
		foreach ($this->cart->contents() as $items){
			if($items['name'] == 'order'){
				array_push($result,$items);
			}
		}
		//获取地域信息
		$this->data['areas_info'] = $this->jad_global_model->get_areas_info();
		$this->data['ordersList'] = $result;

        $this->load->model('jad_orders_model');
		if ($this->input->post('order_add_submit'))
		{
			$this->jad_orders_model->order_add();
		}
        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
		$this->load->view('jadiiar/jad_orders/order_add_view',$this->data);
	}
	/**
	 * order_items
	 * 查看订单详情 
	 */ 
	function order_items($orderId){
        $this->load->model('jad_orders_model');
		//获取订单信息及客户信息
		$this->data['orderClientInfo'] = $this->jad_orders_model->get_order_client($orderId);
		//获取订单下的所有商品详情
		$this->data['orderItemsList'] = $this->jad_orders_model->get_order_items($orderId);
		//获取订单下的所有付款记录
		$this->data['orderFundsList'] = $this->jad_orders_model->get_order_funds($orderId);

		if ($this->input->post('fund_add_submit')=="add")
		{
			$this->jad_orders_model->fund_add();
		}else if ($this->input->post('fund_add_submit')=="edit"){
			$this->jad_orders_model->fund_edit();
		}
        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
		$this->load->view('jadiiar/jad_orders/order_items_view',$this->data);
	}
	/**
	 * manage_orders
	 * 订单查询
	 */ 
	function manage_orders(){
        $this->load->model('jad_orders_model');
        // 获取所有的订单信息
        $this->jad_orders_model->get_orders();
        //获取提示信息 
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
        $this->load->view('jadiiar/jad_orders/orders_manage_view', $this->data);
	}
	/**
	 * add_item
	 * 新增订单信息
	 * AJAX方法
	 */ 
	function add_item(){ 
        $this->load->model('jad_orders_model');
        $this->jad_orders_model->add_item();
	}	 	  
	/**
	 * del_item
	 * 删除订单信息
	 * AJAX方法
	 */ 
	function del_item(){ 
        $this->load->model('jad_orders_model');
        $this->jad_orders_model->del_item();
	}	 	  
	/**
	 * get_items
	 * 获取订单信息
	 * AJAX方法
	 */ 
	function get_items(){ 
        $this->load->model('jad_orders_model');
        $this->jad_orders_model->get_items();
	}	 	  
	/**
	 * fund_del
	 * 删除款项
	 * AJAX方法
	 */ 
	function fund_del(){ 
        $this->load->model('jad_orders_model');
        $this->jad_orders_model->fund_del();
	}	 	  
	function item_num_add(){
        $this->load->model('jad_orders_model');
        $this->jad_orders_model->item_num_add();
	}
	function item_num_del(){
        $this->load->model('jad_orders_model');
        $this->jad_orders_model->item_num_del();
	}

}
