<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_dictionary_model extends CI_Model { 
	
	public function __construct(){ 
		//$this->load->database();
	}
	// The following method prevents an error occurring when $this->data is modified.
	// Error Message: 'Indirect modification of overloaded property Demo_cart_admin_model::$data has no effect'.
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 供应商信息
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	/**
	 * add_supplier
	 * Create a new supplier. 
	 */
	function add_supplier()
	{
		$this->load->library('form_validation');
		// Set validation rules.
		// The custom rules 'identity_available' and 'validate_password' can be found in '../libaries/MY_Form_validation.php'.
		$validation_rules = array( 
			array('field' => 'register_suppl_brand', 'label' => 'brand', 'rules' => 'required|max_length[50]'),
			array('field' => 'register_suppl_location', 'label' => '位置', 'rules' => 'required|max_length[100]'),
			array('field' => 'register_suppl_phone', 'label' => '电话', 'rules' => 'required|max_length[25]'),
			array('field' => 'register_suppl_alias', 'label' => '别名', 'rules' => 'required|max_length[100]')	
     );
		$this->form_validation->set_rules($validation_rules);
		// Run the validation.
		if ($this->form_validation->run())
		{			
			$profile_data = array(
				'suppl_brand' => $this->input->post('register_suppl_brand'),
				'suppl_alias' => $this->input->post('register_suppl_alias'),
				'suppl_location' => $this->input->post('register_suppl_location'),
				'suppl_phone' => $this->input->post('register_suppl_phone'),
				'suppl_collaborate' => $this->input->post('register_suppl_collaborate'),
				'suppl_collaphone' => $this->input->post('register_suppl_collaphone'),
				'suppl_taxrebate' => $this->input->post('rebate_status'),
				'suppl_hkmail' => $this->input->post('mailhk_status'),
			  'upro_id' => $this->flexi_auth->get_user_id(),
				'suppl_note' => $this->input->post('register_suppl_note')
			);
			$this->db->insert('info_supplier',$profile_data);
		  redirect('jad_dictionary/manage_suppliers');			
		}
	  // Set validation errors.
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}

 	/**
	 * get_suppliers
	 * 根据搜索条件获取供应商信息 
	 * 过滤条件：供应商品牌OR供应商地址
	 */
	function get_suppliers()
	{
		// Get url for any search query or pagination position.
		$uri = $this->uri->uri_to_assoc(3);     
		// Set pagination limit, get current position and get total users.
		$limit = $this->config->item('pag_limit');
		$offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
		if (array_key_exists('search', $uri)){
			// Set pagination url to include search query.
			$pagination_url = 'jad_dictionary/manage_suppliers/search/'.$uri['search'].'/';
			$config['uri_segment'] = 6; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
	    $search_query = str_replace('-',' ',urldecode($uri['search']));
			if ($this->flexi_auth->in_group('buyer')){
			  $this->db->select('*');
			  $this->db->from('info_supplier');
        $where = "info_supplier.upro_id = ".$this->flexi_auth->get_user_id()." and ( suppl_brand like '%".$search_query."%' or suppl_location like '%".$search_query."%')";
        $this->db->where($where);
        $this->db->join('jadiiar_user_profiles', 'info_supplier.upro_id = jadiiar_user_profiles.upro_id');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
			  //查询的时候，并没有涉及到jadiiar_user_profiles表的信息，所以计算数量只需要info_supplier一个表即可；
        $this->db->select('*');
			  $this->db->from('info_supplier');
        $this->db->where($where);
        //$this->db->join('jadiiar_user_profiles', 'info_supplier.upro_id = jadiiar_user_profiles.upro_id');
        $querynum1 = $this->db->get()->num_rows();        
        $total_suppliers = $querynum1;	  
		  }else{
			  $this->db->select('*');
        $this->db->join('jadiiar_user_profiles', 'info_supplier.upro_id = jadiiar_user_profiles.upro_id');
        $this->db->like('suppl_brand', $search_query);
        $this->db->or_like('suppl_location', $search_query);     
        $query = $this->db->get('info_supplier',$limit, $offset);				  	
			  $this->db->select('*');
        //$this->db->join('jadiiar_user_profiles', 'info_supplier.upro_id = jadiiar_user_profiles.upro_id');
        $this->db->like('suppl_brand', $search_query);
        $this->db->or_like('suppl_location', $search_query); 		  
		    $querynum1 = $this->db->get('info_supplier');
 			  $total_suppliers = $querynum1->num_rows();			
 			}
 			$this->data['suppliers'] = $query->result_array();	 
	  }else{	  	
			// Set some defaults.
			$pagination_url = 'jad_dictionary/manage_suppliers/';
			$search_query = FALSE;
			$config['uri_segment'] = 4; // Changing to 4 will select the 4th segment, example 'controller/function/page/10'.
			if ($this->flexi_auth->in_group('buyer')){
				$query = $this->db->get_where('info_supplier', array('upro_id' => $this->flexi_auth->get_user_id()),$limit, $offset);
			  $querynum = $this->db->get_where('info_supplier', array('upro_id' => $this->flexi_auth->get_user_id()));
 			  $total_suppliers = $querynum->num_rows();		
			}else{	
				$this->db->select('*');
        $this->db->join('jadiiar_user_profiles', 'info_supplier.upro_id = jadiiar_user_profiles.upro_id');
        $query = $this->db->get('info_supplier',$limit, $offset);
			  $querynum = $this->db->get('info_supplier');
 			  $total_suppliers = $querynum->num_rows();				
			}
			$this->data['suppliers'] = $query->result_array();	
	  }
	  
		// Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_suppliers;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_suppliers'] = $total_suppliers;
	}
	
 	/**
	 * update_suppliers
	 * 删除checkbox已选择的供应商条目
	 */
	function update_suppliers()
    {
		// 检查权限
		//if ($this->flexi_auth->is_privileged('Delete Users')) 
		//{
			if ($delete_supplier = $this->input->post('delete_supplier'))
			{
				foreach($delete_supplier as $supplier_id => $delete)
				{
					$this->db->delete('info_supplier', array('suppl_id' => $supplier_id)); 
				}
			}
		//}
		// Save any public or admin status or error messages to CI's flash session data.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		// Redirect user.
		redirect('jad_dictionary/manage_suppliers');			
	}	
	
 	/**
	 * update_supplier
	 * 更新供应商信息
	 */
	function update_supplier($supplier_id)
	{
		$this->load->library('form_validation');
		// Set validation rules.
		$validation_rules = array(
			array('field' => 'update_suppl_brand', 'label' => '品牌', 'rules' => 'required|max_length[50]'),
			array('field' => 'update_suppl_location', 'label' => '位置', 'rules' => 'required|max_length[100]'),
			array('field' => 'update_suppl_phone', 'label' => '电话', 'rules' => 'required|max_length[25]'),
			array('field' => 'update_suppl_alias', 'label' => '别名', 'rules' => 'required|max_length[100]')
		);
		$this->form_validation->set_rules($validation_rules);
		if ($this->form_validation->run())
		{
			$profile_data = array(
				'suppl_brand' => $this->input->post('update_suppl_brand'),
				'suppl_alias' => $this->input->post('update_suppl_alias'),
				'suppl_location' => $this->input->post('update_suppl_location'),
				'suppl_phone' => $this->input->post('update_suppl_phone'),
				'suppl_collaborate' => $this->input->post('update_suppl_collaborate'),
				'suppl_collaphone' => $this->input->post('update_suppl_collaphone'),
				'suppl_taxrebate' => $this->input->post('rebate_status'),
				'suppl_hkmail' => $this->input->post('mailhk_status'),
				'suppl_note' => $this->input->post('update_suppl_note'),
			);			

			// If we were only updating profile data (i.e. no email, username or group included), we could use the 'update_custom_user_data()' function instead.
			$this->db->where('suppl_id', $supplier_id);
      $this->db->update('info_supplier', $profile_data); 
				
			// Save any public or admin status or error messages to CI's flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			redirect('jad_dictionary/manage_suppliers');			
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Branches
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	/**
	 * add_branch
	 * Create a new branch. 
	 */
	function add_branch()
	{
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'register_branch_name', 'label' => '分店名称', 'rules' => 'required|max_length[50]'),
			array('field' => 'register_branch_address', 'label' => '分店地址', 'rules' => 'required|max_length[100]'),
			array('field' => 'register_branch_phone', 'label' => '电话', 'rules' => 'required|max_length[25]'),
			array('field' => 'register_branch_zipcode', 'label' => '邮编', 'rules' => 'required|max_length[25]')
		);
		$this->form_validation->set_rules($validation_rules);
		// Run the validation.
		if ($this->form_validation->run())
		{			
			$profile_data = array(
				'branch_name' => $this->input->post('register_branch_name'),
				'branch_address' => $this->input->post('register_branch_address'),
				'branch_phone' => $this->input->post('register_branch_phone'),
				'upro_id' => $this->input->post('register_upro_id'),
				'branch_zipcode' => $this->input->post('register_branch_zipcode')
			);
			$this->db->insert('info_branch',$profile_data);
		  redirect('jad_dictionary/manage_branches');			
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;		
	}

 	/**
	 * get_branches
	 * 获取分店信息，数量少，不需要查询
	 */
	function get_branches()
	{
	    $this->db->select('branch_id,branch_name,branch_address,branch_phone,branch_zipcode,upro_first_name,upro_last_name');
	    $this->db->from('info_branch');
	    $this->db->join('jadiiar_user_profiles','info_branch.upro_id = jadiiar_user_profiles.upro_id');
	    $query = $this->db->get();
			$this->data['branches'] = $query->result_array();	
	}
	
 	/**
	 * update_branches
	 * 删除checkbox已选择的分店条目
	 */
	function update_branches()
    {
			if ($delete_branch = $this->input->post('delete_branch'))
			{
				foreach($delete_branch as $branch_id => $delete)
				{
					$this->db->delete('info_branch', array('branch_id' => $branch_id)); 
				}
			}
		redirect('jad_dictionary/manage_branches');			
	}	
	
 	/**
	 * update_branch
	 * 更新分店信息
	 */
	function update_branch($branch_id)
	{
		$this->load->library('form_validation');

		// Set validation rules.
		$validation_rules = array(
			array('field' => 'update_branch_name', 'label' => 'Branch Name', 'rules' => 'required'),
			array('field' => 'update_branch_address', 'label' => 'Branch Address', 'rules' => 'required'),
			array('field' => 'update_branch_phone', 'label' => 'Branch Phone', 'rules' => 'required'),
			array('field' => 'update_branch_zipcode', 'label' => 'Branch Zipcode', 'rules' => 'required'),
		);

		$this->form_validation->set_rules($validation_rules);
		
		if ($this->form_validation->run())
		{
			$profile_data = array(
				'branch_name' => $this->input->post('update_branch_name'),
				'branch_address' => $this->input->post('update_branch_address'),
				'branch_phone' => $this->input->post('update_branch_phone'),
				'branch_zipcode' => $this->input->post('update_branch_zipcode'),
				'upro_id' => $this->input->post('update_upro_id'),
			);			

			// If we were only updating profile data (i.e. no email, username or group included), we could use the 'update_custom_user_data()' function instead.
			$this->db->where('branch_id', $branch_id);
      $this->db->update('info_branch', $profile_data); 
				
			// Save any public or admin status or error messages to CI' flash session data.
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
			redirect('jad_dictionary/manage_branches');			
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');		
		return FALSE;
	}		
}


/* End of file jad_dictionary_model.php */
/* Location: ./application/models/jad_dictionary_model.php */
