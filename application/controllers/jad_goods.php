<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_goods extends CI_Controller {
   
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
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 商品一级信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	/**
	 * add_product
	 * 新增产品信息
	 */ 
	function add_product(){ 
		if ($this->input->post('add_goods_first'))
		{
			$this->load->model('jad_goods_model');
			$this->jad_goods_model->add_product();
		}

        $this->load->model('jad_goods_model');
        $this->data['productId'] = $this->jad_goods_model->get_new_series_num();

		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
		$this->load->view('jadiiar/jad_goods/product_add_view',$this->data);
	}	 	  
 	/**
 	 * manage_goods_first
 	 * 对商品一级信息列表进行维护
 	 * 并允许对商品一级信息进行编辑、删除和过期操作
 	 * 商品过期意味着在新建涉及购买子任务的任务时，在选择货号的时候，不会出现过期的货号。
 	 */
    function manage_products()
    {
        $this->load->model('jad_goods_model');
        if (! $this->flexi_auth->is_privileged('View Goods'))
        {
            $this->session->set_flashdata('message', '<p class="error_msg">您没有权限查看该页面</p>');
            redirect('jad_auth');
        } 
        if ($this->input->post('search_product') && $this->input->post('search_query')) 
        {
            $search_query = str_replace(' ','-',$this->input->post('search_query'));
            redirect('jad_goods/manage_products/search/'.$search_query.'/page/');
        }
        else if ($this->input->post('delete_product')) 
        {
            $this->jad_goods_model->update_products();
        }
        // 获取所有的产品信息
        $this->jad_goods_model->get_products();
        // Set any returned status/error messages.
        //获取提示信息 
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
        $this->load->view('jadiiar/jad_goods/products_view', $this->data);
    }
    function manage_goods_first()
    {
		    $this->load->model('jad_goods_model');
		    if (! $this->flexi_auth->is_privileged('View Goods'))
		        {
		        	  $this->session->set_flashdata('message', '<p class="error_msg">您没有权限查看该页面</p>');
			          redirect('jad_auth');
		        }

        if ($this->input->post('search_goods') && $this->input->post('search_query')) 
		    {
			    $search_query = str_replace(' ','-',$this->input->post('search_query'));
		      redirect('jad_goods/manage_goods_first/search/'.$search_query.'/page/');
		    }
		    else if ($this->input->post('update_goodes_first')) 
		    {
			    $this->jad_goods_model->update_goods_first();
		    }
		    // 获取所有商品的一级信息
		    $this->jad_goods_model->get_goods_first();
		    // Set any returned status/error messages.
		    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		    $this->load->view('jadiiar/jad_goods/goods_first_view', $this->data);		
    }

 	/**
 	 * update_product
 	 * 更新产品信息
     * 若该产品已经发布，还需要更新宝贝信息
 	 */
    function update_product($productId)
    {
        $this->load->model('jad_goods_model');
		if ($this->input->post('update_product')) 
		{
			$this->jad_goods_model->update_product($productId);
		}
		//获取指定产品的信息
	    $this->data['productInfo'] = $this->jad_goods_model->get_product($productId);
		$this->load->view('jadiiar/jad_goods/product_update_view', $this->data);
    }

	function update_goods_first($seriesnum)
	{
		if ($this->input->post('update_goods_first')) 
		{
			$this->load->model('jad_goods_model');
			$this->jad_goods_model->update_single_goods_first($seriesnum);
		}
		//获取指定商品的所有一级信息
	  $this->data['goods_first'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum);
		$this->load->view('jadiiar/jad_goods/goods_first_update_view', $this->data);
	}
 	  
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 商品二级信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	/**
	 * publish_product_items
     * 发布某产品下的商品信息，若该产品已发布，则为增加num_iid下的sku信息
     * 否则，则为发布新的产品信息 
     * 参数：产品ID
	 */ 
    function publish_product_items($productId){
		$this->load->model('jad_goods_model');
		if ($this->input->post('publish_product_items'))
		{
			$this->jad_goods_model->publish_product_items();
		}
        //根据product_id，获取该产品下的所有item信息
        $this->jad_goods_model->get_product_item_info_by_product_id($productId);
        //根据product_id，获取该产品的所有信息
        $this->data['productInfo'] = $this->jad_goods_model->get_product($productId);
		$this->load->view('jadiiar/jad_goods/product_items_publish_view',$this->data);
    }
	/**
	 * publish_sku_items
     * 修改宝贝基本信息以及sku信息
     * 参数：产品ID
	 */ 
    function publish_sku_items($productId){
		$this->load->model('jad_goods_model');
		if ($this->input->post('publish_product_items'))
		{
			$this->jad_goods_model->publish_sku_items();
		}
        //根据product_id，获取该产品下的所有item信息
        $this->jad_goods_model->get_normal_product_item_info_by_product_id($productId);
        //根据product_id，获取该产品的所有信息
        $this->data['productInfo'] = $this->jad_goods_model->get_product($productId);
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        /*/获取该宝贝的信息，比如描述、价格、数量等等
        $this->topsdk->autoload('ItemGetRequest');
        $this->topsdk->req->setFields("props,property_alias");
        $this->topsdk->req->setNumIid($this->data['productInfo']['num_iid']);
        $propsArr = $this->topsdk->get_data();
        $props_current = $propsArr['item']['props'];
        *///

        $this->topsdk->autoload('ItemSkuGetRequest');
		$this->load->view('jadiiar/jad_goods/sku_items_publish_view',$this->data);
    }
    function publish_product_item($itemId){
        //从product_item_view视图中根据item_id查找信息
		$this->load->model('jad_goods_model');
		if ($this->input->post('publish_product_item'))
		{
			$this->jad_goods_model->publish_product_item();
		}
		$this->jad_goods_model->get_product_item_info_by_item_id($itemId);
		$this->load->view('jadiiar/jad_goods/product_item_publish_view',$this->data);
    }
	/**
	 * add_goods_second
	 * 增加商品二级信息
	 */ 
    function add_product_item($productId)
    {
		$this->load->model('jad_goods_model');
		if ($this->input->post('add_product_item'))
		{
			$this->jad_goods_model->add_product_item($this->input->post('product_id_hidden'),$this->input->post('t_colour_img'),$this->input->post('t_sku'));
		}
		//获取该product_id下的产品信息
		$this->data['product'] = $this->jad_goods_model->get_product($productId);

        //获取该product_id下未过期的item信息，提取出已存在的property_alias与props
		$this->jad_goods_model->get_normal_product_item_info_by_product_id($productId);
        $productItemsInfo = $this->data['productItemsInfo'];
        //遍历销售属性，获取已经存在的sku
        $propsArr = array();
        $propertyAliasArr = array();

        $propsImgUrlArr = array();
        $propsImgUrlIndexArr = array();

        //$skuProperiesArr = array();
        $skuPropertiesIndexArr = array();

        if (count($productItemsInfo) != 0) {
            for( $i = 0; $i < count($productItemsInfo); $i++){
                //获取props值，有些props值需要分解
                $propertyAliasTempArr = explode(";",$productItemsInfo[$i]['property_alias']);
                //获取已有的信息
                array_push($propsImgUrlArr,$productItemsInfo[$i]['item_colour_props'].';'.$productItemsInfo[$i]['item_desc'].';'.$productItemsInfo[$i]['item_img_link'].';'.$productItemsInfo[$i]['item_colour']);
                array_push($propsImgUrlIndexArr,$productItemsInfo[$i]['item_colour_props']);
                array_push($skuPropertiesIndexArr,$productItemsInfo[$i]['iprops']);

                if (count($propertyAliasTempArr) != 0) {
                    for( $j = 0; $j < count($propertyAliasTempArr); $j++){
                        //$propsTempStr = $propsTempArr[$j];
                        //    var_dump($propsTempStr);
                        if (!in_array($propertyAliasTempArr[$j],$propertyAliasArr)){
                            array_push( $propertyAliasArr , $propertyAliasTempArr[$j]);
                            //var_dump($propsTempStr);
                            $propsTempArr = explode(":",$propertyAliasTempArr[$j]);
                            array_push( $propsArr , $propsTempArr[0].':'.$propsTempArr[1] );
                        }
                    }
                }
            }
        }
        //可以思考一下，以前四种数据可以融合在一起


        //获取已有的商品销售属性的props值和别名值
        $this->data['propertyAliasArrStr'] = implode(";",$propertyAliasArr );
        $this->data['propsArrStr'] = implode(";",$propsArr );

        //获取已有的商品销售属性的色卡、图片链接值
        $this->data['propsImgUrlArrStr'] = implode(",",$propsImgUrlArr);
        $this->data['propsImgUrlIndexArrStr'] = implode(",",$propsImgUrlIndexArr);

        $this->data['skuPropertiesIndexArrStr'] = implode(",",$skuPropertiesIndexArr);

        //var_dump($this->data['propertyAliasArrStr']);
        //var_dump($this->data['propsArrStr']);
        //var_dump($this->data['propsImgUrlArrStr']);
        //var_dump($this->data['propsImgUrlIndexArrStr']);
        //var_dump($this->data['skuPropertiesIndexArrStr']);

		$this->load->view('jadiiar/jad_goods/product_item_add_view',$this->data);
    }
	function add_goods_second($seriesnum)
	{ 
		$this->load->model('jad_goods_model');
		if ($this->input->post('add_goods_second'))
		{
			$this->jad_goods_model->add_goods_second($this->input->post('input_goods_seriesnum'),$this->input->post('input_goods_shortcode'));
		}
		//获取该货号下的商品一级信息
		$this->data['goods_first'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum);
	  //获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');
		$this->load->view('jadiiar/jad_goods/goods_second_add_view',$this->data);
	  
	}	 	  
 	/**
 	 * update_goods_second
 	 * 更新指定商品的二级信息
 	 */
	function update_goods_second($goodsCode){ 
		$goodsCode = urldecode($goodsCode);
		if ($this->input->post('update_goods_second')) 
		{
			$this->load->model('jad_goods_model');
			$this->jad_goods_model->update_single_goods_second($goodsCode);
		}
		//获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');
	  
		//获取指定商品的所有二级信息
	  $this->data['goodsSecondInfo'] = $this->jad_global_model->get_goods_s_info_by_code($goodsCode);
	  $this->load->view('jadiiar/jad_goods/goods_second_update_view', $this->data);
	}
 	/**
 	 * manage_goods_second
 	 * 对商品二级信息列表进行维护
 	 * 
 	 */
    function manage_product_items($productId)
    {
        $this->load->model('jad_goods_model');
        if (! $this->flexi_auth->is_privileged('View Merchandises'))
        {
              $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view merchandises info.</p>');
              redirect('jad_auth');
        }
        if ($this->input->post('search_item') && $this->input->post('search_query')) 
        {
            $search_query = str_replace(' ','-',$this->input->post('search_query'));
            redirect('jad_goods/manage_product_items/'.$productId.'/search_item/'.$search_query.'/page/');
        }
        else if ($this->input->post('delete_item')) 
        {
            $this->jad_goods_model->update_item();
        }
        // 获取商品详细信息
        $this->data['productId'] = $productId;
        //根据product_id，获取该产品的所有信息
        $this->data['productInfo'] = $this->jad_goods_model->get_product($productId);
        // 判断此商品货号是否过期
        //$seriesnumArray = array();   
        //foreach($this->jad_goods_model->get_seriesnums_unexpired() as $key => $col) {
        //    $seriesnumArray[] = $col['goods_seriesnum']; 
        //}
        //$this->data['isExpired'] = !in_array($seriesnum,$seriesnumArray); 
        //$this->data['goodsFirst'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum);
        // 获取所有的产品信息
        $this->jad_goods_model->get_product_items($productId);

        //获取提示信息 
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
          if (!empty($this->data['message']))
          {
              $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
          } 
        $this->load->view('jadiiar/jad_goods/product_items_view', $this->data);
    }
    function manage_goods_second($seriesnum)
    {
		    $this->load->model('jad_goods_model');
		    if (! $this->flexi_auth->is_privileged('View Merchandises'))
		        {
		        	  $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view merchandises info.</p>');
			          redirect('jad_auth');
		        }
        if ($this->input->post('search_merchandises') && $this->input->post('search_query')) 
		    {
			    $search_query = str_replace(' ','-',$this->input->post('search_query'));
		      redirect('jad_goods/manage_merchandises/'.$seriesnum.'/search/'.$search_query.'/page/');
		    }
		    else if ($this->input->post('update_merchandises')) 
		    {
			    $this->jad_goods_model->update_merchandises();
		    }
		    // 获取商品详细信息
		    $this->jad_goods_model->get_goods_second($seriesnum);
		    // 判断此商品货号是否过期
		    $seriesnumArray = array();   
		    foreach($this->jad_goods_model->get_seriesnums_unexpired() as $key => $col) {
		    	$seriesnumArray[] = $col['goods_seriesnum']; 
		    }
		    $this->data['isExpired'] = !in_array($seriesnum,$seriesnumArray); 
		    $this->data['goodsFirst'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum);
		    
		    //获取配置文件中的颜色数组
	      $this->data['colour'] = $this->config->item('merch_colour');
	      //获取配置文件中的尺寸数组
	      $this->data['size'] = $this->config->item('merch_size');
		    
		    // Set any returned status/error messages.
		    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
		    $this->load->view('jadiiar/jad_goods/goods_second_view', $this->data);		
    }

 	/**
 	 * update_goods
 	 * Update the info details of a specific goods.
 	 */
	function update_merchandise($seriesnum){
		if ($this->input->post('update_merchandise')) 
		{
			$this->load->model('jad_goods_model');
			$this->jad_goods_model->update_merchandise($seriesnum);
		}
	} 	 

 	/**
 	 * 根据商品标识码,给出商品信息一览
 	 * merch_details
 	 */
	function merch_details($merchId){
		
	  $this->load->model('jad_goods_model');
		$this->jad_goods_model->merch_details($merchId);
    //获取配置文件中的颜色数组
	  $this->data['colour'] = $this->config->item('merch_colour');
	  //获取配置文件中的尺寸数组
	  $this->data['size'] = $this->config->item('merch_size');	
    $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
	  $this->load->view('jadiiar/jad_goods/merch_details_view', $this->data);		
    }

 	/**
     * ajax调用接口，通过店铺昵称,调用TOP API获取店铺类目信息
     * get_seller_cats_by_nickname
 	 */
    function get_seller_cats_by_nickname(){
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_seller_cats_by_nickname();
    }
 	/**
     * ajax调用接口，通过父节点cid,调用TOP API获取子类信息
     * get_itemcats_by_parent_id
 	 */
    function get_itemcats_by_parent_id()
    {
		$cId = $_POST['parentId']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_itemcats_by_parent_id($cId);
    }  
 	 /**
     * ajax调用接口，通过节点cid,调用该节点下的属性列表
     * get_itemcats_by_parent_id
 	 */
    function get_itemprops_by_cid()
    {
		$cId = $_POST['parentId']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_itemprops_by_cid($cId);
    } 
  	 /**
     * ajax调用接口，通过节点cid,调用该节点下的属性列表
     * get_itemcats_by_parent_id
 	 */
    function get_child_itemprops()
    {
		$cId = $_POST['cId']; 
		$parentPId = $_POST['parentPId']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_child_itemprops($cId,$parentPId);
    } 
	 /**
     * ajax调用接口，通过节点cid,调用该节点下的属性列表
     * get_itemcats_by_parent_id
 	 */
    function get_propvalues()
    {
		$cId = $_POST['cId']; 
		$pId = $_POST['pId']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_propvalues($cId,$pId);
    } 
}
