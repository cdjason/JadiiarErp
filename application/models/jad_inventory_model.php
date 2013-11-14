<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_inventory_model extends CI_Model {
	
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
  function search_inventory(){
  	$search_code = '';
  	$search_b_id = '';
		// Get url for any search query or pagination position.
		$uri = $this->uri->uri_to_assoc(3);  
		// Set pagination limit, get current position and get total users.
		$limit = $this->config->item('pag_limit');
		$offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	

		if (array_key_exists('search_code', $uri)){
			$search_code = urldecode($uri['search_code']);
			$search_b_id = urldecode($uri['search_b_id']);
			
			$pagination_url = 'jad_inventory/inventory_search/search_b_id/'.$search_b_id.'/search_code/'.$search_code.'/';
			if ($search_code=='noinputvalue') $search_code = ''; 
			// Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.	  	  
			$config['uri_segment'] = 8;
			
      $this->db->from('inventorylistview');
	    
	    if ($search_b_id=='noselected')
	      $where = "(inven_expired = 0 and (goods_seriesnum like '%".$search_code."%' or goods_barcode like '%".$search_code."%' or goods_code like '%".$search_code."%' or merch_id like '%".$search_code."%'))
	      or (inven_expired is null and (goods_seriesnum like '%".$search_code."%' or goods_barcode like '%".$search_code."%' or goods_code like '%".$search_code."%' or merch_id like '%".$search_code."%'))";
	    else
	      $where = "(inven_expired = 0 and branch_id ='".$search_b_id."' and (goods_seriesnum like '%".$search_code."%' or goods_barcode like '%".$search_code."%' or goods_code like '%".$search_code."%' or merch_id like '%".$search_code."%'))
	      or (inven_expired is null and branch_id ='".$search_b_id."' and (goods_seriesnum like '%".$search_code."%' or goods_barcode like '%".$search_code."%' or goods_code like '%".$search_code."%' or merch_id like '%".$search_code."%'))";
	    
	    $this->db->where($where); 
      $this->db->limit($limit, $offset);
      $query = $this->db->get();	
      
			//$this->db->select('*');
			$this->db->from('inventorylistview');
	    $this->db->where($where);      
		  $total_missions = $this->db->get()->num_rows();
	    $this->data['details_inven'] = $query->result_array();      
          
	  }else{
			$pagination_url = 'jad_inventory/inventory_search/';
			$search_query = '';
			$config['uri_segment'] = 4;
			$this->db->from('inventorylistview');
	    $where = "inven_expired = 0 or inven_expired is null";
	    $this->db->where($where); 
      $this->db->limit($limit, $offset);    
	    $this->data['details_inven'] = $this->db->get()->result_array();	 
			$this->db->from('inventorylistview');
	    $this->db->where($where);
	    $total_missions = $this->db->get()->num_rows();			  	
		}
		// Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_missions;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_code'] = $search_code; // Populates search input field in view.
		$this->data['search_b_id'] = $search_b_id;
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_missions'] = $total_missions;
  }
  function inventory_merch_details($merchId){
  	//获取商品信息
  	$this->db->select('*');      
  	$this->db->from('AllMerchDetailsView');
	  $this->db->where('merch_id', $merchId);
    $this->data['merchInfo'] = $this->db->get()->row_array();
    //获取购买信息
  	$this->db->select('*');      
  	$this->db->from('MerchPurchaseInfoView');
	  $this->db->where('merch_id', $merchId);
    $this->data['merchPurchaseInfo'] = $this->db->get()->row_array();    
  }
}