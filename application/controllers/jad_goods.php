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
	// 产品信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	/**
	 * add_product
	 * 新增产品信息
     * 需要获取jadiiar和siiena店的品牌信息
	 */ 
	function add_product(){ 
        $this->load->model('jad_goods_model');
		if ($this->input->post('add_goods_first'))
		{
			$this->jad_goods_model->add_product();
		}
        $this->data['productId'] = $this->jad_goods_model->get_new_series_num();
        $this->data['brandList'] = $this->jad_goods_model->get_brand_list();
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
		$this->load->view('jadiiar/jad_goods/product_add_view',$this->data);
	}	 	  
 	/**
 	 * update_product
 	 * 更新产品信息
     * 若该产品已经发布，考虑更新宝贝图片信息
     * 需要获取jadiiar和siiena店的品牌信息
 	 */
    function update_product($productId)
    {
        $this->load->model('jad_goods_model');
		if ($this->input->post('update_product')) 
		{
			$this->jad_goods_model->update_product($productId);
		}
        $this->data['brandList'] = $this->jad_goods_model->get_brand_list();
		//获取指定产品的信息
	    $this->data['productInfo'] = $this->jad_goods_model->get_product($productId);
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		
        // 对系统报错信息（p标签包裹的数据）进行重新处理；        
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
		$this->load->view('jadiiar/jad_goods/product_update_view', $this->data);
    }

 	/**
 	 * manage_products
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
            redirect('jad_goods/manage_products/search/'.$search_query.'/order_by/num_iid/order_parameter/desc/page/');
        }
        else if ($this->input->post('delete_product')) 
        {
            $this->jad_goods_model->update_products();
        }
        // 获取所有的产品信息
        $this->jad_goods_model->get_products();
        //获取提示信息 
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
        $this->load->view('jadiiar/jad_goods/products_view', $this->data);
    }

	/**
	 * publish_product_items
     * 发布某产品下的商品信息
     * 参数：产品ID
	 */ 
    function publish_product_items($productId){
		$this->load->model('jad_goods_model');
		if ($this->input->post('publish_product_items'))
		{
			$this->jad_goods_model->publish_product_items();
		}
        //根据product_id，获取该产品下的所有item信息，以便建立销售属性sku列表
        $this->jad_goods_model->get_normal_product_item_info_by_product_id($productId);
        //根据product_id，获取该产品的所有信息
        $this->data['productInfo'] = $this->jad_goods_model->get_product($productId);
		$this->load->view('jadiiar/jad_goods/product_items_publish_view',$this->data);
    }

	/**
	 * add_product_item
	 * 增加商品销售属性
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
        $propsArr = array();
        $propertyAliasArr = array();

        $propsImgUrlArr = array();
        $propsImgUrlIndexArr = array();

        //$skuProperiesArr = array();
        $skuPropertiesIndexArr = array();

        if (count($productItemsInfo) != 0) {
		    //遍历销售属性，获取已经存在的sku
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
		$this->load->view('jadiiar/jad_goods/product_item_add_view',$this->data);
    }
 	/**
	 * manage_product_items
 	 * 对产品的商品信息列表进行维护
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
 	/**
	 * items_catalogue
 	 * 商品目录 
 	 */
	function items_catalogue(){
        $this->load->model('jad_goods_model');
        $this->jad_goods_model->get_items_catalogue();

        if ($this->input->post('search_product') && $this->input->post('search_query')) 
        {
            $search_query = str_replace(' ','-',$this->input->post('search_query'));
            redirect('jad_goods/items_catalogue/search/'.$search_query.'/order_by/num_iid/order_parameter/desc/page/');
        }
        //获取提示信息 
        $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
        if (!empty($this->data['message']))
        {
            $this->data['alert_message'] = $this->jad_global_model->get_alert_message($this->data['message']);   
        } 
        $this->load->view('jadiiar/jad_goods/items_catalogue_view', $this->data);
	}

    /* function manage_goods_first() */
    /* { */
		    /* $this->load->model('jad_goods_model'); */
		    /* if (! $this->flexi_auth->is_privileged('View Goods')) */
		        /* { */
		        	  /* $this->session->set_flashdata('message', '<p class="error_msg">您没有权限查看该页面</p>'); */
			          /* redirect('jad_auth'); */
		        /* } */

    /*     if ($this->input->post('search_goods') && $this->input->post('search_query')) */ 
		    /* { */
			    /* $search_query = str_replace(' ','-',$this->input->post('search_query')); */
		      /* redirect('jad_goods/manage_goods_first/search/'.$search_query.'/page/'); */
		    /* } */
		    /* else if ($this->input->post('update_goodes_first')) */ 
		    /* { */
			    /* $this->jad_goods_model->update_goods_first(); */
		    /* } */
		    /* // 获取所有商品的一级信息 */
		    /* $this->jad_goods_model->get_goods_first(); */
		    /* // Set any returned status/error messages. */
		    /* $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message']; */		
		    /* $this->load->view('jadiiar/jad_goods/goods_first_view', $this->data); */		
    /* } */

	/* function update_goods_first($seriesnum) */
	/* { */
	/* 	if ($this->input->post('update_goods_first')) */ 
	/* 	{ */
	/* 		$this->load->model('jad_goods_model'); */
	/* 		$this->jad_goods_model->update_single_goods_first($seriesnum); */
	/* 	} */
	/* 	//获取指定商品的所有一级信息 */
	/*   $this->data['goods_first'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum); */
	/* 	$this->load->view('jadiiar/jad_goods/goods_first_update_view', $this->data); */
	/* } */
 	  
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 商品二级信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * update_item_info
     * 编辑宝贝信息，包括主图、sku信息、其他的一些比较常用的信息
     * AJAX动态生成宝贝的各种信息，便于局部设置与刷新
     * 貌似已经无用了
	 */ 
    /* function update_item_info(){ */
		/* $this->load->model('jad_goods_model'); */
    /*     $this->jad_goods_model->update_item_info(); */
    /* } */
	/**
	 * publish_sku_items
     * 编辑宝贝信息，包括主图、sku信息、其他的一些比较常用的信息
     * AJAX动态生成宝贝的各种信息，便于局部设置与刷新
     * 参数：产品ID
	 */ 
    /* function publish_sku_items($productId){ */
    /*     $this->load->library('TopSdk', $this->config->item('topapi_config') ); */
		/* $this->load->model('jad_goods_model'); */
		/* if ($this->input->post('publish_product_items')) */
		/* { */
			/* $this->jad_goods_model->publish_sku_items(); */
		/* } */
    /*     $this->data['productId'] = $productId; */
    /*     //根据product_id，获取该产品下的所有item信息 */
    /*     $this->jad_goods_model->get_normal_product_item_info_by_product_id($productId); */
    /*     //根据product_id，获取该产品的所有信息 */
    /*     $this->data['productInfo'] = $this->jad_goods_model->get_product($productId); */

    /*     //获取该宝贝的信息，比如描述、价格、数量等等 */
    /*     $this->topsdk->autoload('ItemGetRequest'); */
    /*     $this->topsdk->req->setFields("num_iid,title,seller_cids,desc,num,price,global_stock_type,global_stock_country"); */
    /*     $this->topsdk->req->setNumIid($this->data['productInfo']['num_iid']); */
    /*     $itemInfoArr = $this->topsdk->get_data(); */
    /*     $alertMessage =""; */ 
    /*     if ( count($itemInfoArr) == 1){ */
    /*         //操作成功 */
    /*         $this->data['itemInfo'] = $itemInfoArr['item']; */
    /*     }else{ */
    /*         //操作失败 */
    /*         $alertMessage = $alertMessage.'<p class="error_msg">获取宝贝信息失败:'.$itemInfoArr['msg'].'</p>'; */
    /*         $this->session->set_flashdata('message',$alertMessage); */
    /*         redirect('jad_goods/manage_product_items/'.$productId); */	
    /*     } */


    /*     $this->topsdk->autoload('ItemSkuGetRequest'); */
		/* $this->load->view('jadiiar/jad_goods/sku_items_publish_view',$this->data); */
    /* } */


	/* function add_goods_second($seriesnum) */
	/* { */ 
	/* 	$this->load->model('jad_goods_model'); */
	/* 	if ($this->input->post('add_goods_second')) */
	/* 	{ */
	/* 		$this->jad_goods_model->add_goods_second($this->input->post('input_goods_seriesnum'),$this->input->post('input_goods_shortcode')); */
	/* 	} */
	/* 	//获取该货号下的商品一级信息 */
	/* 	$this->data['goods_first'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum); */
	/*   //获取配置文件中的颜色数组 */
	/*   $this->data['colour'] = $this->config->item('merch_colour'); */
	/*   //获取配置文件中的尺寸数组 */
	/*   $this->data['size'] = $this->config->item('merch_size'); */
	/* 	$this->load->view('jadiiar/jad_goods/goods_second_add_view',$this->data); */
	  
	/* } */	 	  
 	/**
 	 * update_goods_second
 	 * 更新指定商品的二级信息
 	 */
	/* function update_goods_second($goodsCode){ */ 
	/* 	$goodsCode = urldecode($goodsCode); */
	/* 	if ($this->input->post('update_goods_second')) */ 
	/* 	{ */
	/* 		$this->load->model('jad_goods_model'); */
	/* 		$this->jad_goods_model->update_single_goods_second($goodsCode); */
	/* 	} */
	/* 	//获取配置文件中的颜色数组 */
	/*   $this->data['colour'] = $this->config->item('merch_colour'); */
	/*   //获取配置文件中的尺寸数组 */
	/*   $this->data['size'] = $this->config->item('merch_size'); */
	  
	/* 	//获取指定商品的所有二级信息 */
	/*   $this->data['goodsSecondInfo'] = $this->jad_global_model->get_goods_s_info_by_code($goodsCode); */
	/*   $this->load->view('jadiiar/jad_goods/goods_second_update_view', $this->data); */
	/* } */

    /* function manage_goods_second($seriesnum) */
    /* { */
		    /* $this->load->model('jad_goods_model'); */
		    /* if (! $this->flexi_auth->is_privileged('View Merchandises')) */
		        /* { */
		        	  /* $this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view merchandises info.</p>'); */
			          /* redirect('jad_auth'); */
		        /* } */
    /*     if ($this->input->post('search_merchandises') && $this->input->post('search_query')) */ 
		    /* { */
			    /* $search_query = str_replace(' ','-',$this->input->post('search_query')); */
		      /* redirect('jad_goods/manage_merchandises/'.$seriesnum.'/search/'.$search_query.'/page/'); */
		    /* } */
		    /* else if ($this->input->post('update_merchandises')) */ 
		    /* { */
			    /* $this->jad_goods_model->update_merchandises(); */
		    /* } */
		    /* // 获取商品详细信息 */
		    /* $this->jad_goods_model->get_goods_second($seriesnum); */
		    /* // 判断此商品货号是否过期 */
		    /* $seriesnumArray = array(); */   
		    /* foreach($this->jad_goods_model->get_seriesnums_unexpired() as $key => $col) { */
		    	/* $seriesnumArray[] = $col['goods_seriesnum']; */ 
		    /* } */
		    /* $this->data['isExpired'] = !in_array($seriesnum,$seriesnumArray); */ 
		    /* $this->data['goodsFirst'] = $this->jad_global_model->get_goods_info_by_seriesnum($seriesnum); */
		    
		    /* //获取配置文件中的颜色数组 */
	      /* $this->data['colour'] = $this->config->item('merch_colour'); */
	      /* //获取配置文件中的尺寸数组 */
	      /* $this->data['size'] = $this->config->item('merch_size'); */
		    
		    /* // Set any returned status/error messages. */
		    /* $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message']; */		
		    /* $this->load->view('jadiiar/jad_goods/goods_second_view', $this->data); */		
    /* } */

 	/**
 	 * update_goods
 	 * Update the info details of a specific goods.
 	 */
	/* function update_merchandise($seriesnum){ */
	/* 	if ($this->input->post('update_merchandise')) */ 
	/* 	{ */
	/* 		$this->load->model('jad_goods_model'); */
	/* 		$this->jad_goods_model->update_merchandise($seriesnum); */
	/* 	} */
	/* } */ 	 

 	/**
 	 * 根据商品标识码,给出商品信息一览
 	 * merch_details
 	 /* *1/ */
	/* function merch_details($merchId){ */
		
	  /* $this->load->model('jad_goods_model'); */
		/* $this->jad_goods_model->merch_details($merchId); */
    /* //获取配置文件中的颜色数组 */
	  /* $this->data['colour'] = $this->config->item('merch_colour'); */
	  /* //获取配置文件中的尺寸数组 */
	  /* $this->data['size'] = $this->config->item('merch_size'); */	
    /* $this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message']; */	
	  /* $this->load->view('jadiiar/jad_goods/merch_details_view', $this->data); */		
    /* } */
    function get_product_items_by_pId(){
		$pId = $_POST['pId']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_product_items_by_pId($pId);
    }
 	/**
     * ajax调用接口，通过skuID,获取sku信息
     * get_seller_cats_by_nickname
 	 */
    function get_sku_info_by_sId(){
		$sId = $_POST['skuId']; 
		$numIid = $_POST['numIid']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_sku_info_by_sId($sId,$numIid);
    }

 	/**
     * ajax调用接口，通过店铺昵称,调用TOP API获取店铺类目信息
     * get_seller_cats_by_nickname
 	 */
    function get_seller_cats_by_nickname(){
		$nickName = $_POST['nickName']; 
		$this->load->model('jad_goods_model');
        $this->jad_goods_model->get_seller_cats_by_nickname($nickName);
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
	/**
     * 通过AJAX方法，更新ERP产品图片和TOP宝贝图片 
     * update_item_img
 	 */
    /* function update_item_img(){ */
		/* $imgUrl = $_POST['picUrl']; */ 
		/* $pId = $_POST['pId']; */ 
		/* $this->load->model('jad_goods_model'); */
    /*     $this->jad_goods_model->update_item_img($pId,$imgUrl); */
    /* } */
	/**
     * 通过AJAX方法，更新sku信息 
     * update_sku_items
 	 */
    /* function update_sku_items(){ */
		/* $this->load->model('jad_goods_model'); */
    /*     $this->jad_goods_model->update_sku_items(); */
    /* } */
}
