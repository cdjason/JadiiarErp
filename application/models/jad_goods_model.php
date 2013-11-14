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
	 * update_goods_first
	 * 通过checkbox的选择，删除或更新相应的商品一级信息
	 */
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
	
}