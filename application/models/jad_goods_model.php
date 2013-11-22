<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_goods_model extends CI_Model {
	
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 商品基础信息查询
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	/**
	 * 查询未过期的商品货号
	 * 
	 */
	function get_seriesnums_unexpired(){
		$this->db->select('goods_seriesnum');
		return $this->db->get_where('info_goods_first',array('goods_expired' => 0))->result_array();
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 商品基础信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
    /**
	 * add_goods_first
	 * 新增商品一级信息
	 */
    function add_product()
    {
        $cId = $this->input->post('cid');
        $props = $this->input->post('props');
        $inputStr = $this->input->post('input_str');
        $inputPids = $this->input->post('input_pids');

        //生成10位随机货号，并判断该货号在数据库中是否存在，若存在，则重新生成
        $productId = $this->generate_randnum(10);
        while($this->db->get_where('info_product', array('product_id' => $productId))->num_rows() != 0){
            $productId = $this->generate_randnum(10);
        }

        $profile_data = array(
            'product_id' => $this->generate_randnum(10),
            'product_title' => $this->input->post('product_title'),
            'cid' => $cId,
            'props' => $props,
            'inputs_str' => $inputStr,
            'inputs_pids' => $inputPids,
            'product_expired' => 0
        );
		$this->db->insert('info_product',$profile_data);	
        redirect('jad_goods/manage_product');	
    }
	/**
	 * add_goods_first
	 * 新增商品一级信息
	 */
	function add_goods_first()
	{
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'add_goods_desc', 'label' => '细节描述', 'rules' => 'required|max_length[1000]')
		);
		$this->form_validation->set_rules($validation_rules);
		// Run the validation.
		if ($this->form_validation->run())
		{			
			$sbrand = $this->input->post('add_goods_brand');
			$scateg = $this->input->post('add_categ_id');
			$series = substr($sbrand,0,4).$this->input->post('add_goods_year').$scateg;
			//获取goods表内为此序列号的数量
			$this->db->select('*');
			$this->db->from('info_goods_first');  
			$this->db->like('goods_seriesnum', $series); 
			$id = $this->db->count_all_results();
			$id++;
      if ($id < 10){
      	$series = $series.'00'.$id;
      }else if($id < 100){
      	$series = $series.'0'.$id;
      }else{
      	$series = $series.$id;
      }
      //处理该货号，使其唯一
      while($this->db->get_where('info_goods_first', array('goods_seriesnum' => $series))->num_rows() != 0){
      	$id++;
        if ($id < 10){
      	   $id = '00'.$id;
        }else if($id < 100){
      	   $id = '0'.$id;
        }else{
      	   $id = $id;
        }
      	$series = substr($series,0,7).$id;
      }
      //生成唯一的短条码,并验证是否唯一，否则重新生成
      do{
        $shortCode = substr(strtoupper(hash('md5',$series)),0,5);
      }while( $this->db->get_where('info_goods_first',array('goods_shortcode' => $shortCode))->num_rows() == 1 );
      
			$profile_data = array(
				'goods_brand' => substr($sbrand,4,strlen($sbrand)),
				'categ_id' => $scateg,
				'goods_seriesnum' => $series,
				'goods_shortcode' => $shortCode,
				'goods_desc' => $this->input->post('add_goods_desc')
			);
			$this->db->insert('info_goods_first',$profile_data);
			
		  redirect('jad_goods/manage_goods_first');			
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}

 	/**
	 * get_goods_first
	 * 获取所有商品的一级信息
	 */
    function get_product()
    {
        $uri = $this->uri->uri_to_assoc(3);     
        $limit = $this->config->item('pag_limit');
        $offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
        if (array_key_exists('search', $uri))
        {
            $pagination_url = 'jad_goods/manage_product/search/'.$uri['search'].'/';
            $config['uri_segment'] = 6; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
            $search_query = str_replace('-',' ',urldecode($uri['search']));
            
            $this->db->select('*');
            $this->db->from('info_product');
            $this->db->like('product_id', $search_query);
            $this->db->or_like('product_title', $search_query);     
            $this->db->limit($limit, $offset);
            $query = $this->db->get();			
          
            $this->db->select('*');
            $this->db->from('info_product');
            $this->db->like('product_id', $search_query);
            $this->db->or_like('product_title', $search_query);     
            $total_product = $this->db->get()->num_rows();
            $this->data['product'] = $query->result_array();            
        }
        else
        {
            $pagination_url = 'jad_goods/manage_product/';
			$search_query = FALSE;
			$config['uri_segment'] = 4;
	        $query = $this->db->get_where('info_product',array(), $limit, $offset);
			$this->data['product'] = $query->result_array();		 
	        $total_product = $this->db->get('info_product')->num_rows();
        }
        		// Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_product;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_product'] = $total_product;

    }
	function get_goods_first()
	{
		// Get url for any search query or pagination position.
		$uri = $this->uri->uri_to_assoc(3);     
		// Set pagination limit, get current position and get total users.
		$limit = $this->config->item('pag_limit');
		$offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
		if (array_key_exists('search', $uri)){
			$pagination_url = 'jad_goods/manage_goods_first/search/'.$uri['search'].'/';
			$config['uri_segment'] = 6; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
	    $search_query = str_replace('-',' ',urldecode($uri['search']));
		   
			$this->db->select('*');
      $this->db->from('goodsfirstinfoview');
      $this->db->like('goods_brand', $search_query);
      $this->db->or_like('categ_name', $search_query);     
      $this->db->or_like('goods_seriesnum', $search_query);
      $this->db->limit($limit, $offset);
      $query = $this->db->get();			
			
			$this->db->select('*');
      $this->db->from('goodsfirstinfoview');
	    $this->db->like('goods_brand', $search_query);
      $this->db->or_like('categ_name', $search_query);     
      $this->db->or_like('goods_seriesnum', $search_query); 
		  $total_goods_first = $this->db->get()->num_rows();
	    $this->data['goods_first'] = $query->result_array();
	  }else{
			$pagination_url = 'jad_goods/manage_goods_first/';
			$search_query = FALSE;
			$config['uri_segment'] = 4;
	    $query = $this->db->get_where('goodsfirstinfoview',array(), $limit, $offset);
			$this->data['goods_first'] = $query->result_array();		 

	    $total_goods_first = $this->db->get('goodsfirstinfoview')->num_rows();			  	
		}
		
		
		// Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_goods_first;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_goods_first'] = $total_goods_first;

	  
	}

 	/**
	 * update_product
     * 通过checkbox的选择，删除相应的产品信息
     * 以下情况为把产品信息设置过期的条件：
     * 1、存在商品信息，该商品信息已经购买过实际的商品；
     * 2、存在商品信息，该商品信息正在淘宝的店铺上发布；
	 */
    function update_product()
    {
        if ($delete_product = $this->input->post('delete_product'))
        {
            foreach($delete_product as $product_id => $delete)
            {
                $this->db->delete('info_product', array('product_id' => $product_id)); 
            }
        }
        redirect('jad_goods/manage_product');			
    }
	function update_goods_first()
    {
			if ($delete_goods = $this->input->post('delete_goods'))
			{
				foreach($delete_goods as $goods_seriesnum => $delete)
				{
					$this->db->delete('info_goods_first', array('goods_seriesnum' => $goods_seriesnum)); 
				}
			}
		  //更新、过期商品基础信息的状态
		  if ($expire_goods = $this->input->post('expire_goods'))
		  {
			  // Get current statuses to check if submitted status has changed.
			  $current_status = $this->input->post('current_status');
			
			  foreach($expire_goods as $goods_seriesnum => $status)
			  {
				  if ($current_status[$goods_seriesnum] != $status)
				  {
					  if ($status == 1)
					  {
						  $this->db->where('goods_seriesnum', $goods_seriesnum);
              $this->db->update('info_goods_first', array('goods_expired' => 1)); 
					  }
					  else
				  	{
						  $this->db->where('goods_seriesnum', $goods_seriesnum);
              $this->db->update('info_goods_first', array('goods_expired' => 0)); 
					  }
				  }
			  }
		  }
		  redirect('jad_goods/manage_goods_first');			
	  }	
	
 	/**
	 * update_goods
	*/
	function update_single_goods_first($seriesnum)
	{
		$this->load->library('form_validation');
		// Set validation rules.
		$validation_rules = array(
			array('field' => 'update_goods_desc', 'label' => '细节描述', 'rules' => 'required|max_length[1000]')
		);

		$this->form_validation->set_rules($validation_rules);
		
		if ($this->form_validation->run())
		{
			$profile_data = array(
				'goods_desc' => $this->input->post('update_goods_desc'),
			);		
			$this->db->where('goods_seriesnum', $seriesnum);
			$this->db->update('info_goods_first', $profile_data); 
			redirect('jad_goods/manage_goods_first');			
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}	

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 商品二级信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

 	/**
	 * get_goods_second
	 * 获取指定商品二级信息列表
	 * 参数：货号
	 */
	function get_goods_second($goodsBarcode)
	{
		// Get url for any search query or pagination position.
		$uri = $this->uri->uri_to_assoc(4);     
		// Set pagination limit, get current position and get total users.
		$limit = $this->config->item('pag_limit');
		$offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
		if (array_key_exists('search', $uri)){
			$pagination_url = 'jad_goods/manage_goods_second/'.$goodsBarcode.'/search/'.$uri['search'].'/';
			$config['uri_segment'] = 7; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
	    $search_query = str_replace('-',' ',urldecode($uri['search']));  
			//获取数据库中的数据
			
	    $this->db->select('*');
	    $this->db->from('goodssecondinfoview');
	    $this->db->where('goods_seriesnum', $goodsBarcode);  
	    //$this->db->like('info_goods.goods_seriesnum', $search_query);
      //$this->db->or_like('categ_name', $search_query);     
      //$this->db->or_like('goods_seriesnum', $search_query);
      $this->db->limit($limit, $offset);
      $query = $this->db->get();			
			
	    $this->db->select('*');
	    $this->db->from('goodssecondinfoview');
	    $this->db->where('goods_seriesnum', $goodsBarcode);  
	    //$this->db->like('info_goods.goods_seriesnum', $search_query);
		  $total_goods_second = $this->db->get()->num_rows();
	    $this->data['goods_second'] = $query->result_array();
	  }else{
			$pagination_url = 'jad_goods/manage_goods_second/'.$goodsBarcode.'/';
			$search_query = FALSE;
			$config['uri_segment'] = 5;  
	    $query = $this->db->get_where('goodssecondinfoview',array( 'goods_seriesnum' => $goodsBarcode), $limit, $offset);
			$this->data['goods_second'] = $query->result_array();		 
	    $total_goods_second = $this->db->get_where('goodssecondinfoview',array( 'goods_seriesnum' => $goodsBarcode))->num_rows();			  	
		}
		//便于增加商品二级信息的链接获取商品货号
		$this->data['g_seriesnum'] = $goodsBarcode;
		
		// Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_goods_second;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_goods_second'] = $total_goods_second;

	  
	}
 	/**
	 * update_goods_second
	*/
	function update_single_goods_second($goodsCode)
	{
		$this->load->library('form_validation');
		// Set validation rules.
		$validation_rules = array(
			array('field' => 'update_goods_s_desc', 'label' => '商品二级信息描述','rules' => 'required|max_length[1000]' ),
		  array('field' => 'update_merch_image_url', 'label' => '图片链接', 'rules' => 'required|max_length[1000]' )
		);

		$this->form_validation->set_rules($validation_rules);
		if ($this->form_validation->run())
		{
			$profile_data = array(
				'goods_s_desc' => $this->input->post('update_goods_s_desc'),
				'goods_image_url' => $this->input->post('update_merch_image_url')
			);		
			$this->db->where('goods_code', $goodsCode);
			$this->db->update('info_goods_second', $profile_data); 
			redirect('jad_goods/manage_goods_second/'.substr($goodsCode,0,10));			
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}	
 	/**
	 * add_goods_second
	 * 增加指定货号下的商品二级信息
	 * 参数：货号,短条码
	 */
	function add_goods_second($series,$shortCode){
		
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'select_merch_colour', 'label' => '商品颜色', 'rules' => 'required'),
			array('field' => 'add_merch_desc', 'label' => '细节描述', 'rules' => 'required|max_length[1000]'),
			array('field' => 'add_merch_image_url', 'label' => '图片链接', 'rules' => 'required|max_length[1000]'),
			array('field' => 'select_merch_size', 'label' => '商品尺码', 'rules' => 'required')
		);
		$this->form_validation->set_rules($validation_rules);
		// Run the validation.
		if ($this->form_validation->run()){	
	
			$colour = $this->input->post('select_merch_colour');
			$size = $this->input->post('select_merch_size');
			$desc = $this->input->post('add_merch_desc');
			//生成唯一的长条码,并验证是否唯一，否则重新生成
      do{
      	//随机生成3位字符串。
      	$randomTemp = $this->generate_ranstring();
      	$codeTemp = $shortCode.$randomTemp;
      }while( $this->db->get_where('info_goods_second',array('goods_barcode' => $codeTemp))->num_rows() == 1 );
			
			
			$profile_data = array(
			  'goods_code' => $series.$colour.$size,
				'goods_seriesnum' => $series,
				'goods_barcode' => $codeTemp,
				'goods_shortcode' => $shortCode,
				'goods_image_url' => $this->input->post('add_merch_image_url'),
				'goods_s_desc' => $desc
			);
			$this->db->insert('info_goods_second',$profile_data);
		  redirect('jad_goods/manage_goods_second/'.$series);	
	  }
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}
	
	/**
	 * 
	 * 根据指定的商品id返回商品详细信息。 
	 */
  function merch_details($merchId){ 	
  	//商品信息
  	$this->data['infoMerch'] = $this->db->get_where('goodssecondinfoview',array('goods_barcode'=> substr($merchId,0,8)))->row_array();
  }
 	/**
	 * generate_ranstring
	 * 随机生成3位大写字符串，不能出现数字0和字符O
	 * 参数：货号,短条码
	 */
	function generate_ranstring( $length = 3 ){  
    // 字符集，可任意添加你需要的字符  
    $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';  
    $ranstring = '';  
    for ( $i = 0; $i < $length; $i++ ){  
    // 这里提供两种字符获取方式  
    // 第一种是使用 substr 截取$chars中的任意一位字符；  
    // 第二种是取字符数组 $chars 的任意元素  
    // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);  
    $ranstring .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
    }  
    return $ranstring;  
    }
 	/**
	 * generate_ranstring
	 * 随机生成10位数字
	 * 参数：货号,短条码
	 */
  function generate_randnum( $length = 10 ){  
    // 字符集，可任意添加你需要的字符  
    $chars = '0123456789';  
    $ranstring = '';  
    for ( $i = 0; $i < $length; $i++ ){  
    // 这里提供两种字符获取方式  
    // 第一种是使用 substr 截取$chars中的任意一位字符；  
    // 第二种是取字符数组 $chars 的任意元素  
    // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);  
    $ranstring .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
    }  
    return $ranstring;  
  } 
 	/**
	 * get_itemcats_by_parent_id
	 */
    function get_itemcats_by_parent_id($parentCid)
    {
        //从TOP API里面获取类目信息
        $topapi_config = array(
            'app_key'=>'21669164', 
            'secret_key'=>'b4cc7e5f5bbcadaf1ced4dc49265adcc',
        );
        $this->load->library('topsdk', $topapi_config );
        $this->topsdk->autoload('ItemcatsGetRequest');
        $this->topsdk->req->setFields("cid,parent_cid,name,is_parent,status");
        $this->topsdk->req->setParentCid($parentCid);
        
        $result = $this->topsdk->get_data();
        echo json_encode($result);
    }	
 	/**
	 * get_itemprops_by_cid
	 */
    function get_itemprops_by_cid($cId)
    {
        //从TOP API里面获取叶子类目的属性信息
        $topapi_config = array(
            'app_key'=>'21669164', 
            'secret_key'=>'b4cc7e5f5bbcadaf1ced4dc49265adcc',
        );
        $this->load->library('topsdk', $topapi_config );
        $this->topsdk->autoload('ItempropsGetRequest');
        $this->topsdk->req->setFields("pid,name,must,multi,prop_values,is_key_prop,is_enum_prop,parent_pid,is_input_prop,child_template");
        $this->topsdk->req->setCid($cId);
        $result = $this->topsdk->get_data();
        //注意：在该叶子类目下没有属性信息的时候，要对返回值进行判断，否则会引起错误；
        //echo count($result,1);
        echo json_encode($result);
    }
    /**
	 * get_itemprops_by_cid
	 */
    function get_child_itemprops($cId,$parentPId)
    {
        //从TOP API里面获取叶子类目的属性信息
        $topapi_config = array(
            'app_key'=>'21669164', 
            'secret_key'=>'b4cc7e5f5bbcadaf1ced4dc49265adcc',
        );
        $this->load->library('topsdk', $topapi_config );
        $this->topsdk->autoload('ItempropsGetRequest');
        $this->topsdk->req->setFields("pid,name,must,parent_pid,parent_vid,multi,prop_values,is_key_prop,is_enum_prop,parent_pid,is_input_prop,child_template");
        $this->topsdk->req->setCid($cId);
        $this->topsdk->req->setParentPid($parentPId);
        $result = $this->topsdk->get_data();
        //注意：在该叶子类目下没有属性信息的时候，要对返回值进行判断，否则会引起错误；
        //echo count($result,1);
        echo json_encode($result);
    }	
 	/**
	 * get_propvalues
	 */
    function get_propvalues($cId,$pId)
    {
        //从TOP API里面获取指定叶子类目下的指定属性的值
        $topapi_config = array(
            'app_key'=>'21669164', 
            'secret_key'=>'b4cc7e5f5bbcadaf1ced4dc49265adcc',
        );
        $this->load->library('topsdk', $topapi_config );
        $this->topsdk->autoload('ItempropvaluesGetRequest');

        $this->topsdk->req->setFields("cid,pid,prop_name,vid,name,name_alias,status,sort_order");
        $this->topsdk->req->setCid($cId);
        $this->topsdk->req->setPvs($pId);

        $result = $this->topsdk->get_data();
        //注意：在该叶子类目下没有属性信息的时候，要对返回值进行判断，否则会引起错误；
        //echo count($result,1);
        echo json_encode($result);
    }	

}
