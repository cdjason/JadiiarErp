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
	// 产品信息维护
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
    /**
	 * add_product
	 * 新增产品信息
	 */
    function add_product()
    {
        //对输入提交的产品信息表单进行验证
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'product_title', 'label' => '产品标题', 'rules' => 'required|max_length[30]'),
			array('field' => 'product_desc', 'label' => '产品描述', 'rules' => 'required|min_length[5]|max_length[100]')
		);
		$this->form_validation->set_rules($validation_rules);
		if ($this->form_validation->run())
		{			
            $cId = $this->input->post('cid');
            $cNameChain = $this->input->post('cid_name_chain');
            
            $brand = $this->input->post('product_brand');

            $props = $this->input->post('props');
            $inputStr = $this->input->post('input_str');
            $inputPids = $this->input->post('input_pids');
            $productId = $this->input->post('product_hidd_id');
            $productDesc = $this->input->post('product_desc');
            $productImgUrl = $this->input->post('product_img_url');
            //若在props为''的情况下去执行这个方法，会返回false,直接在数据库中体现为0，会影响以后的判断
            if ( $props != '' ){
                $props = substr($props,0,strlen($props)-1);
            }
            $profile_data = array(
                'product_id' => $productId,
                'product_title' => $this->input->post('product_title'),
                'product_desc' => $productDesc,
                'product_img_url' => $productImgUrl,
                //'product_seller_cats' => $sellerCats,
                'product_brand' => $brand,
                'cid' => $cId,
                'cid_name_chain' => $cNameChain,
                'props' => $props,
                'inputs_str' => $inputStr,
                'inputs_pids' => $inputPids,
				'product_create_time' => date('Y-m-d H:i:s',time()),
                'product_expired' => 0
            );
            $this->db->insert('info_product',$profile_data);	
            $alertMessage = '<p class="">新增产品信息成功,ID'.$productId.'</p>';
            $this->session->set_flashdata('message',$alertMessage);
            //默认为状态降序的排序
            redirect('jad_goods/manage_products/order_by/num_iid/order_parameter/desc');	
        }
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
    }
 	/**
	 * update_product
     * 更新产品信息 
     * 注意：未发布的时候，可以更改品牌信息，但是发布之后，品牌信息还能不能改
     * 考虑：如果该产品已发布，是否更新宝贝的主图信息。
	 */
    function update_product($pId)
    {
        //需要更改相应inputs_str的信息
        $inputsPidsArr = explode(',',$this->input->post('inputs_pids'));
        $inputsStrArr = explode(',',$this->input->post('inputs_str'));
        //当inputs_pids中含有品牌的id：20000时，则进行替换
        if (in_array('20000',$inputsPidsArr)){
            $inputsStrArr[array_search('20000', $inputsPidsArr)] = $this->input->post('product_brand');
        }

        $profile_data = array(
            'product_title' => $this->input->post('product_title'),
            'inputs_str' => implode(',',$inputsStrArr),
            'product_brand' => $this->input->post('product_brand'),
            'product_desc' => $this->input->post('product_desc'),
            'product_img_url' => $this->input->post('product_img_url')
        );		
        $this->db->where('product_id', $pId);
        $this->db->update('info_product', $profile_data); 
        //注意，若没有改动数据的提交，会被数据库认为是没有修改，从而$this->db->affected_rows()返回0

        /***判断该产品是否已经提交，若提交，则要修改宝贝的相应信息**************************************************
         * 新修改，不允许在这个地方去修改宝贝信息

        if ($this->input->post('num_iid') != '' ){
            $this->load->library('TopSdk', $this->config->item('topapi_config') );
            $this->topsdk->autoload('ItemUpdateRequest');
            $this->topsdk->req->setNumIid($this->input->post('num_iid'));
            $this->topsdk->req->setTitle($this->input->post('product_title')); 
            //var_dump($this->input->post('product_img_url'));
            //$localPath = $this->jad_global_model->get_local_image_path($this->input->post('product_img_url'));
            $localPath = 'C:\Users\ChenJ\Desktop\20130522045702-f37e7322-me.jpg';

            if(file_exists($localPath))
            {
                $this->topsdk->req->setImage('@'.$localPath);
            }
                    //var_dump($this->input->post('num_iid'));
            $result = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
            $alertMessage = '';
            if ( count($result) == 1){
                $alertMessage = $alertMessage.'<p class="">宝贝信息更新成功,ID:'.$result['item']['num_iid'].'</p>';
            }else{
                //发布失败
                if($result['msg']=='Remote service error'){
                    $alertMessage = $alertMessage.'<p class="error_msg">宝贝信息更新失败:'.$result['sub_msg'].'</p>';
                }else{
                    $alertMessage = $alertMessage.'<p class="error_msg">宝贝信息更新失败:'.$result['msg'].'</p>';
                }
            }
        }
        $this->session->set_flashdata('message',$alertMessage);
        **************************************************************************************************************/
        redirect('jad_goods/manage_products/order_by/num_iid/order_parameter/desc');	
    }
 	/**
	 * update_products
     * 通过checkbox的选择，删除相应的产品信息
     * 目前只要发布的产品，就不允许删除
     * 以下情况为把产品信息设置过期的条件：
     * 1、存在商品信息，该商品信息已经购买过实际的商品；
     * 2、存在商品信息，该商品信息正在淘宝的店铺上发布；
	 */
    function update_products()
    {
        if ($delete_product = $this->input->post('delete_product'))
        {
            foreach($delete_product as $product_id => $delete)
            {
                $this->db->delete('info_product', array('product_id' => $product_id)); 
            }
        }
        redirect('jad_goods/manage_products/order_by/num_iid/order_parameter/desc');	
    }

    /**
	 * get_products
	 * 获取ERP中的产品信息
     * 目前并没有加入过期产品的判断
	 */
    function get_products()
    {
        $uri = $this->uri->uri_to_assoc(3);     
        $limit = $this->config->item('pag_limit');
        $offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
        if (array_key_exists('search', $uri))
        {
            $pagination_url = 'jad_goods/manage_products/search/'.$uri['search'].'/order_by/num_iid/order_parameter/desc/';
            $config['uri_segment'] = 10; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
            $search_query = str_replace('-',' ',urldecode($uri['search']));
            
            $this->db->select('*');
            $this->db->from('info_product');
            $this->db->like('product_id', $search_query);
            $this->db->or_like('product_title', $search_query);     
            $this->db->order_by($uri['order_by'], $uri['order_parameter']); 
            $this->db->limit($limit, $offset);
            $query = $this->db->get();			
          
            $this->db->select('*');
            $this->db->from('info_product');
            $this->db->like('product_id', $search_query);
            $this->db->or_like('product_title', $search_query);     
            $total_product = $this->db->get()->num_rows();
            $this->data['product'] = $query->result_array();            
			$this->data['orderBy'] = $uri['order_by'];		 
			$this->data['orderPara'] = $uri['order_parameter'];		 
        }
        else
        {
            $pagination_url = 'jad_goods/manage_products/order_by/'.$uri['order_by'].'/order_parameter/'.$uri['order_parameter'].'/';
			$search_query = FALSE;
			$config['uri_segment'] = 8;
            $this->db->select("*"); 
            $this->db->from('info_product');
            $this->db->order_by($uri['order_by'], $uri['order_parameter']); 
            $this->db->limit($limit, $offset);
	        $query = $this->db->get();
			$this->data['product'] = $query->result_array();		 
			$this->data['orderBy'] = $uri['order_by'];		 
			$this->data['orderPara'] = $uri['order_parameter'];		 
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

    /**
	 * get_new_series_num
	 * 生成新的货号
     * 生成10位随机货号，并判断该货号在数据库中是否存在，若存在，则重新生成
	 */
    function get_new_series_num()
    {
        $productId = $this->generate_randnum(10);
        while($this->db->get_where('info_product', array('product_id' => $productId))->num_rows() != 0){
            $productId = $this->generate_randnum(10);
        }
        return $productId;

    }
    /**
	 * get_new_product_item_num
	 * 生成新的商品编码
     * 生成3位随机号，并判断该号在数据库中是否存在，若存在，则重新生成
	 */
    function get_new_product_item_num($productId)
    {
        $productItemId = $this->generate_randnum(3);
        $pItemId = (string)$productId . (string)$productItemId;
        while($this->db->get_where('info_product_item', array('item_id' => $pItemId))->num_rows() != 0){
            $productItemId = $this->generate_randnum(3);
            $pItemId = (string)$productId . (string)$productItemId;
        }
        return $pItemId;

    }
    /**
	 * get_brand_list
	 * 从现有店铺中获取品牌信息，目前从jadiiar和siiena中获取 
	 */
    function get_brand_list(){
        //从不同店铺中获取品牌list，去重
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('SellercatsListGetRequest');
        $this->topsdk->req->setNick('jadiiar');
        $result = $this->topsdk->get_data();
        $brandCid = '';
        $brandArray = array();
        if ( count($result) == 1){//当网络无法连接时，数组result的个数大于1;
            $jadSellCatsListArr = $result['seller_cats']['seller_cat'];
            for ( $i = 0 ; $i < count( $jadSellCatsListArr ) - 1 ; $i++ ){//品牌属性必然有子项目，所以品牌属性一定不是数组的最后一个元素
                if ( $jadSellCatsListArr[$i]['name'] == '品牌' ){
                    //获取品牌类目的类目ID
                    $brandCid = $jadSellCatsListArr[$i]['cid'];
                    break;
                }
            }
            for ( $i = 0 ; $i < count( $jadSellCatsListArr ); $i++ ){
                //遍历店铺类目的数组，如果类目的父类目ID为品牌类目的ID，那么就将该类目存入数组
                if ( $jadSellCatsListArr[$i]['parent_cid'] == $brandCid ){
                    array_push($brandArray,$jadSellCatsListArr[$i]['name']);
                }
            }
            $this->topsdk->req->setNick('siiena');
            $result = $this->topsdk->get_data();
            //echo json_encode($result);
            $brandCid = '';
            $siiSellCatsListArr = $result['seller_cats']['seller_cat'];
            for ( $i = 0 ; $i < count( $siiSellCatsListArr ) - 1 ; $i++ ){
                if ( $siiSellCatsListArr[$i]['name'] == '品牌' ){
                    $brandCid = $siiSellCatsListArr[$i]['cid'];
                    break;
                }
            }
            for ( $i = 0 ; $i < count( $siiSellCatsListArr ) ; $i++ ){
                if ( $siiSellCatsListArr[$i]['parent_cid'] == $brandCid ){
                    if ( !in_array($siiSellCatsListArr[$i]['name'],$brandArray) ){
                        array_push($brandArray,$siiSellCatsListArr[$i]['name']);
                    }
                }
            }
            sort($brandArray);//对数组内容进行排序
        }
        return $brandArray;
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
    function get_product($productId)
    {
        return $this->db->get_where('info_product',array( 'product_id' => $productId ))->row_array();
    } 

 	/**
	 * publish_product_items
	 * 发布商品信息到指定的店铺 
     * JADIIAR二期的主要方法
	 */
    function publish_product_items(){
        //从发布商品页面获取该商品发布必须的参数
        $itemShop = $this->input->post('publish_shop');//店铺的淘宝名称，如：肥肥9089、jadiiar、siiena
        $sellerCats = $this->input->post('seller_cats_str');//店铺类别的类别id串，如：123456,12311223,234567,

        $cId = $this->input->post('cid');//宝贝所属叶子类目id
        $itemTitle = $this->input->post('product_title');//宝贝标题
        $productId = $this->input->post('product_id');//获取产品ID
        $itemNum = $this->input->post('product_items_num');//宝贝数量
        $itemPrice = $this->input->post('product_price');//宝贝一口价
        $itemStuffStatus = $this->input->post('stuffStatus');//宝贝类型，全新还是二手

        $itemDesc = $this->input->post('format_item_desc');//宝贝描述，在参数传递的时候通过了html编码，避免post方法的自动转换
        $itemDesc = html_entity_decode($itemDesc);

        $itemType = 'fixed'; //发布类型，fixed:一口价；auction:拍卖
        $itemLoState = '海外'; //宝贝所在地
        $itemLoCity = '美国'; //宝贝所在城市
        $itemFreightPayer = 'seller'; //邮费承担方式，seller：卖家承担；buyer：买家承担

        $locationCheckbox = $this->input->post('location_bought');//采购地
        //图片在远程piwigo上的地址
        $item_remote_url = $this->input->post('img_remote_url');

        //props需要拼接判断处理，可能有些属性下面没有inputs_str或者inputs_pids，先假设都有的情况
        $props = '';
        if( $this->input->post('product_props') != '' ){
            $props = $props . $this->input->post('product_props') . ';';
        }
        if( $this->input->post('product_item_props') != '' ){
            $props = $props . $this->input->post('product_item_props') . ';';
        }
        if( $this->input->post('must_props') != '' ){
            $props = $props . $this->input->post('must_props');
        }
        $propertyAlias = $this->input->post('props_property_alias');//属性别名
        $inputStr = $this->input->post('product_inputs_str');
        $inputPids = $this->input->post('product_inputs_pids');

        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItemAddRequest');
        $this->topsdk->req->setNum($itemNum);
        $this->topsdk->req->setPrice($itemPrice);
        $this->topsdk->req->setType($itemType);
        $this->topsdk->req->setStuffStatus($itemStuffStatus);
        $this->topsdk->req->setTitle($itemTitle);
        $this->topsdk->req->setDesc($itemDesc);
        $this->topsdk->req->setLocationState($itemLoState);
        $this->topsdk->req->setLocationCity($itemLoCity);
        $this->topsdk->req->setCid($cId);
        $this->topsdk->req->setProps($props);
        $this->topsdk->req->setPropertyAlias($propertyAlias);
        $this->topsdk->req->setInputPids($inputPids);
        $this->topsdk->req->setInputStr($inputStr);
        $this->topsdk->req->setFreightPayer($itemFreightPayer);
        $this->topsdk->req->setSellerCids($sellerCats); 
        $this->topsdk->req->setOuterId($productId);

        //若已经选择了sku，则必须提交以下属性
        $this->topsdk->req->setSkuProperties($this->input->post('sku_properties'));
        $this->topsdk->req->setSkuQuantities($this->input->post('sku_quantities'));
        $this->topsdk->req->setSkuOuterIds($this->input->post('sku_outer_ids'));
        $this->topsdk->req->setSkuPrices($this->input->post('sku_prices'));

        //远程地址访问失败：$this->topsdk->req->setImage('@http://service.jadiiar.com/piwigo/i.php?/upload/2013/05/12/20130512213849-1e08cb99-me.jpg');
        //本地绝对地址访问成功：$this->topsdk->req->setImage('@C:\Users\ChenJ\Desktop\20130522045702-f37e732882-me.jpg');

        //获取图片的名称，然后通过piwigo的本地绝对地址来确定文件名称
        //$localPath = $this->jad_global_model->get_local_image_path($item_remote_url);
        $localPath = 'C:\Users\ChenJ\Desktop\20130522045702-f37e7322-me.jpg';

        if(file_exists($localPath))
        {
            $this->topsdk->req->setImage('@'.$localPath);
        }
        //采购地为海外或港澳台的时候，才有global_stock的设置，很有可能和消费者保障权益有关，现货必须在规定时间发货
        if($locationCheckbox == 2){
            $this->topsdk->req->setGlobalStockType($this->input->post('global_type'));
            $this->topsdk->req->setGlobalStockCountry($this->input->post('sel_global_stock'));
        }
        //用于保存各个SKU操作的回馈信息
        $alertMessage = '';
        //参数为sessionkey,在配置文件中读取;
        //如果是多店铺的情况，则要根据参数$itemShop的不同来获取不同的sessionkey，从而访问不同的店铺。
        $result = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
        if ( count($result) == 1){
            //操作成功
            $alertMessage = $alertMessage.'<p class="">新增宝贝信息成功,ID:'.$result['item']['num_iid'].'</p>';
            //发布成功，需要对数据继续处理，获取生成的num_iid,并存入ERP的数据库，与已发布数据保持一致。
            //注意，若该产品类目的非关键属性与非销售属性中存在必选属性，则要加入到产品信息表中，以备修改宝贝信息时，直接读取
            $pd_props_update = ''; 
            if ( $this->input->post('product_props') != '' ){
                $pd_props_update = $pd_props_update.$this->input->post('product_props'); 
            }
            if ( $this->input->post('must_props') != '' ){
                if ($pd_props_update == ''){
                    $pd_props_update = $this->input->post('must_props'); 
                }else{
                    $pd_props_update = $pd_props_update.';'.$this->input->post('must_props'); 
                }
            }
			$profile_data_update = array(
                'props' => $pd_props_update,
                'inputs_str' => $inputStr,
                'inputs_pids' => $inputPids,
                'shop_published_on' => $itemShop,
                'num_iid' => $result['item']['iid']
			);		
            $this->db->trans_start();
            $this->db->where('product_id', $productId);
            $this->db->update('info_product', $profile_data_update); 

            //若添加了SKU信息,则要根据num_iid获取商品信息中的sku信息来为每个item指定sku_id
            if ($this->input->post('sku_outer_ids') != ''){//通过sku_outer_ids的值来判断是否存在sku数据
                $this->topsdk->autoload('ItemSkusGetRequest');
                $this->topsdk->req->setFields("sku_id,num_iid,properties,quantity,price,outer_id,created,modified,status");
                $this->topsdk->req->setNumIids($result['item']['iid']);
                //只有带sessionkey的方法，才能获取到outer_id
                //这个地方同样涉及到选择sessionkey的问题
                $skusList = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
                $skusArr = $skusList['skus']['sku'];

                for ( $k = 0 ; $k < count($skusArr); $k++ ){
                    $this->db->where('item_id', $skusArr[$k]['outer_id']);
                    $this->db->update('info_product_item', array('sku_id' => $skusArr[$k]['sku_id'] )); 
                    $alertMessage = $alertMessage.'<p class="">新增宝贝SKU信息,ID:'.$skusArr[$k]['sku_id'].'</p>';
                }
            } 
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                $alertMessage = $alertMessage.'<p class="error_msg">新增宝贝信息导入ERP失败!</p>';
            }
        }else{
            //发布失败
            if($result['msg']=='Remote service error'){
                $alertMessage = $alertMessage.'<p class="error_msg">新增宝贝信息失败:'.$result['sub_msg'].'</p>';
            }else{
                $alertMessage = $alertMessage.'<p class="error_msg">新增宝贝信息失败:'.$result['msg'].'</p>';
            }
        }
        $this->session->set_flashdata('message',$alertMessage);
        redirect('jad_goods/manage_product_items/'.$productId);	 // disable redirect if debug get_local_image_path()
    }

    //批量更新sku商品
    //注意：不能通过item.update接口直接覆盖更新sku的信息
    //先实现更新sku的信息
    function publish_sku_items(){
        $itemTitle = $this->input->post('product_title');
        $locationCheckbox = $this->input->post('location_bought');
        $sellerCats = $this->input->post('seller_cats_str');
        $itemDesc = $this->input->post('format_item_desc');
        $itemDesc = html_entity_decode($itemDesc);

        $itemNum = $this->input->post('product_items_num');
        $productPrice = $this->input->post('product_price');

        $numIid = $this->input->post('num_iid');
        $pItemProps = $this->input->post('product_item_props');
        $sPropertyAlias = $this->input->post('props_property_alias');
        $sProperty = $this->input->post('sku_properties');
        $sQuantities = $this->input->post('sku_quantities');
        $sOuterIds = $this->input->post('sku_outer_ids');
        $sPrices = $this->input->post('sku_prices');
        $productId = $this->input->post('product_id');
        $skuPropertiesDel = $this->input->post('sku_properties_for_del');
        $skuPropertiesEdit = $this->input->post('sku_properies_for_edit');
        $productProps = $this->input->post('product_props');

        //用于更改别名
        $tempProps = ''; 
        if ($productProps != ''){
            $tempProps = $tempProps.$productProps;
        }
        if ($pItemProps != ''){
            if ($tempProps == ''){
                $tempProps = $pItemProps;
            }else{
                $tempProps = $tempProps.';'.$pItemProps;
            }
        }
        
        /*
        var_dump($locationCheckbox);
        var_dump($tempProps);
        var_dump($sPropertyAlias);
        var_dump($itemDesc);
        var_dump($productPrice);
        var_dump($itemNum);
        var_dump($sellerCats);
        */

        //获取需要删除的sku的sku_properties参数数组
        $skuPropertiesDelArr = explode("#",$skuPropertiesDel);
        $skuPropertiesEditArr = explode("#",$skuPropertiesEdit);
        
        $productInfo = $this->get_product($productId);
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        //遍历提交的sku属性，如果没有选择，就是删除sku，如果选择，就是更新sku
        
        //新增或更改sku，一定存在一个更新或者增加的操作，因为不允许将商品的sku全部删除

        //用于保存各个SKU操作的回馈信息
        $alertMessage = '';
        //更新
        $this->topsdk->autoload('ItemSkuPriceUpdateRequest');
        for($k = 0; $k < count($skuPropertiesEditArr); $k++){
            $skuPropertiesEditItemArr = explode(",",$skuPropertiesEditArr[$k]);
            if ( $skuPropertiesEditItemArr[4] != '' ){
                //var_dump($skuPropertiesEditItemArr[4]);
                $this->topsdk->req->setNumIid($numIid);
                $this->topsdk->req->setProperties($skuPropertiesEditItemArr[0]);
                $this->topsdk->req->setPrice($skuPropertiesEditItemArr[1]);
                $this->topsdk->req->setQuantity($skuPropertiesEditItemArr[2]);
                $result = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
                //var_dump($result);
                if ( count($result) == 1){
                    //操作成功
                    $alertMessage = $alertMessage.'<p class="">SKU信息更新成功,ID:'.$result['sku']['sku_id'].'</p>';
                }else{
                    //操作失败
                    $alertMessage = $alertMessage.'<p class="error_msg">SKU信息更新失败:'.$result['sub_msg'].'</p>';
                }
            }
        }

        //新增
        $this->topsdk->autoload('ItemSkuAddRequest');
        for($k = 0; $k < count($skuPropertiesEditArr); $k++){
            $skuPropertiesEditItemArr = explode(",",$skuPropertiesEditArr[$k]);
            if ( $skuPropertiesEditItemArr[4] == '' ){
                $this->topsdk->req->setNumIid($numIid);
                $this->topsdk->req->setProperties($skuPropertiesEditItemArr[0]);
                $this->topsdk->req->setPrice($skuPropertiesEditItemArr[1]);
                $this->topsdk->req->setQuantity($skuPropertiesEditItemArr[2]);
                $this->topsdk->req->setOuterId($skuPropertiesEditItemArr[3]);
                $result = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
                //需要入库绑定
                $this->db->where('item_id', $skuPropertiesEditItemArr[3]);
                $this->db->update('info_product_item', array( 'sku_id' => $result['sku']['sku_id'] , 'item_expired'  => '1' )); 
                if ( count($result) == 1){
                    //操作成功
                    $alertMessage = $alertMessage.'<p class="">新增SKU信息成功,ID:'.$result['sku']['sku_id'].'</p>';
                }else{
                    //操作失败
                    $alertMessage = $alertMessage.'<p class="error_msg">新增SKU信息失败:'.$result['msg'].'</p>';
                }
            }
        }
        //删除sku，根据item_id来删除
        if($skuPropertiesDel != ''){
            $this->topsdk->autoload('ItemSkuDeleteRequest');
            for($s = 0; $s < count($skuPropertiesDelArr); $s++){
                $skuPropertiesDelItemArr = explode(",",$skuPropertiesDelArr[$s]);
                $this->topsdk->req->setNumIid($numIid);
                $this->topsdk->req->setProperties($skuPropertiesDelItemArr[0]);
                $result = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
                $this->db->where('item_id', $skuPropertiesDelItemArr[1]);
                $this->db->update('info_product_item', array( 'item_expired'  => '2' )); 
                if ( count($result) == 1){
                    //操作成功
                    $alertMessage = $alertMessage.'<p class="">SKU信息删除成功,NUM_ID:'.$result['sku']['num_iid'].'</p>';
                }else{
                    //操作失败
                    $alertMessage = $alertMessage.'<p class="error_msg">SKU信息删除失败:'.$result['msg'].'</p>';
                }
            }
        }
        //直接去更新描述信息、产品价格、产品数量
        $this->topsdk->autoload('ItemUpdateRequest');
        $this->topsdk->req->setNumIid($numIid);
        $this->topsdk->req->setNum($itemNum);
        $this->topsdk->req->setPrice($productPrice);
        $this->topsdk->req->setDesc($itemDesc);
        $this->topsdk->req->setSellerCids($sellerCats);
        $this->topsdk->req->setTitle($itemTitle);
        if($locationCheckbox == 2){
            $this->topsdk->req->setGlobalStockType($this->input->post('global_type'));
            $this->topsdk->req->setGlobalStockCountry($this->input->post('sel_global_stock'));
        }
        
        $this->topsdk->req->setProps($tempProps);
        $this->topsdk->req->setPropertyAlias($sPropertyAlias);
        
        $result = $this->topsdk->get_auth_data($this->config->item('topapi_session_key'));
        if ( count($result) == 1){
            //操作成功
            $alertMessage = $alertMessage.'<p class="">宝贝信息更新成功,ID:'.$result['item']['num_iid'].'</p>';
        }else{
            //操作失败
            $alertMessage = $alertMessage.'<p class="error_msg">宝贝信息更新失败:'.$result['msg'].'</p>';
        }
        $this->session->set_flashdata('message',$alertMessage);
        redirect('jad_goods/manage_product_items/'.$productId);	
    }

    /**
	 * get_items_catalogue
	 * 获取商品目录
	 */
	function get_items_catalogue(){
        $uri = $this->uri->uri_to_assoc(3);     
        $limit = $this->config->item('pag_limit');
        $offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
        if (array_key_exists('search', $uri))
        {
            $pagination_url = 'jad_goods/items_catalogue/search/'.$uri['search'].'/order_by/num_iid/order_parameter/desc/';
            $config['uri_segment'] = 10; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
            $search_query = str_replace('-',' ',urldecode($uri['search']));
            
            $this->db->select('*');
            $this->db->from('product_item_view');
            $where ="product_id like '%".$search_query."%' or item_id like '%".$search_query."%' or item_colour like '%".$search_query."%' or item_desc like '%".$search_query."%' ";
            $this->db->where($where);
            //$this->db->where('product_id', $productId);
            //$this->db->like('product_id', $search_query);
            //$this->db->or_like('product_title', $search_query);     
            $this->db->limit($limit, $offset);
            $query = $this->db->get();			
          
            $this->db->select('*');
            $this->db->from('product_item_view');
            $this->db->where($where);
            //$this->db->where('product_id', $productId);
            //$this->db->like('product_id', $search_query);
            //$this->db->or_like('product_title', $search_query);     
            $total_items_catalogue = $this->db->get()->num_rows();
            $this->data['items_catalogue'] = $query->result_array();            
			$this->data['orderBy'] = $uri['order_by'];		 
			$this->data['orderPara'] = $uri['order_parameter'];		 
        }
        else
        {
            $pagination_url = 'jad_goods/items_catalogue/order_by/'.$uri['order_by'].'/order_parameter/'.$uri['order_parameter'].'/';
			$search_query = FALSE;
			$config['uri_segment'] = 8;
            $this->db->select("*"); 
            $this->db->from('product_item_view');
            $this->db->order_by($uri['order_by'], $uri['order_parameter']); 
            $this->db->limit($limit, $offset);
	        $query = $this->db->get();
			$this->data['items_catalogue'] = $query->result_array();		 
			$this->data['orderBy'] = $uri['order_by'];		 
			$this->data['orderPara'] = $uri['order_parameter'];		 
	        $total_items_catalogue = $this->db->get('product_item_view')->num_rows();
        }
        // Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_items_catalogue;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_items_catalogue'] = $total_items_catalogue;
	}

    function get_product_items($productId)
    {
        $uri = $this->uri->uri_to_assoc(4);     
        $limit = $this->config->item('pag_limit');
        $offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
        if (array_key_exists('search_item', $uri))
        {
            $pagination_url = 'jad_goods/manage_product_items/'.$productId.'/search_item/'.$uri['search_item'].'/';
            $config['uri_segment'] = 7; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
            $search_query = str_replace('-',' ',urldecode($uri['search_item']));
            
            $this->db->select('*');
            $this->db->from('info_product_item');
            $where ="(product_id='".$productId."') and (item_id like '%".$search_query."%' or item_colour like '%".$search_query."%' or item_desc like '%".$search_query."%' )";
            $this->db->where($where);
            //$this->db->where('product_id', $productId);
            //$this->db->like('product_id', $search_query);
            //$this->db->or_like('product_title', $search_query);     
            $this->db->limit($limit, $offset);
            $query = $this->db->get();			
          
            $this->db->select('*');
            $this->db->from('info_product_item');
            $this->db->where($where);
            //$this->db->where('product_id', $productId);
            //$this->db->like('product_id', $search_query);
            //$this->db->or_like('product_title', $search_query);     
            $total_product_items = $this->db->get()->num_rows();
            $this->data['product_items'] = $query->result_array();            
        }
        else
        {
            $pagination_url = 'jad_goods/manage_product_items/'.$productId.'/';
			$search_query = FALSE;
			$config['uri_segment'] = 5;
	        $query = $this->db->get_where('info_product_item',array('product_id' => $productId), $limit, $offset);
			$this->data['product_items'] = $query->result_array();		 
	        $total_product_items = $this->db->get_where('info_product_item',array('product_id' => $productId))->num_rows();
        }
        // Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_product_items;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_product_items'] = $total_product_items;
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
	 * update_item
     * 通过checkbox的选择，删除相应的产品信息
     * 以下情况为把商品信息设置过期的条件：
     * 存在商品信息，该商品信息正在淘宝的店铺上发布；
	 */
    function update_item()
    {
        if ($delete_item = $this->input->post('delete_item'))
        {
            foreach($delete_item as $item_id => $delete)
            {
                $this->db->delete('info_product_item', array('item_id' => $item_id)); 
            }
        }
        redirect('jad_goods/manage_product_items/'.$this->input->post('productId'));			
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
	 * add_product_item
	 * 增加指定货号下的商品sku信息,可一次加多条商品sku信息，但首先需要从提交的数据格式中把数据格式化
	 * 参数：货号，商品颜色信息、商品sku信息
     * 若不存在颜色数组怎么办
	 */
    function add_product_item($productId,$colourInfo,$skuInfo){
        $product_item_desc = '';
        $product_item_colour = '';
        $product_item_colour_props = '';
        $product_item_img = '';
        //存放颜色色卡图片URL的数组
        $colourArr = explode(';',$colourInfo);
        //存放SKU信息的数组
        $skuArr = explode(';',$skuInfo);

        //对sku信息进行遍历,每一条信息都需要插入数据库,并为每一条记录生成一个id号
        //数据库中已经存在的数据直接更新，若不存在则插入新的数据行
        $alertMessage = ''; 
        for( $i = 0; $i < count($skuArr); $i++){
            
            $product_item_property_alias = '';
            $product_item_prop = '';
            //随机的3位序列号+10位货号=商品编码
            $productItemId = $this->get_new_product_item_num($productId);
            $skuItemArr = explode(',',$skuArr[$i]);
            for( $j = 0; $j < count($colourArr); $j++){
                $colourItemArr = explode(',',$colourArr[$j]);
                if( stripos($skuItemArr[0],$colourItemArr[0]) === 0 ){
                    //获取色卡值、图片链接
                    $product_item_colour = $colourItemArr[2];
                    $product_item_img = $colourItemArr[3];
                    $product_item_colour_props = $colourItemArr[0];
                }
            }
            for( $k = 0; $k < count($skuItemArr)-1; $k++){
                $propArr = explode(':',$skuItemArr[$k]);
                $product_item_prop = $product_item_prop . $propArr[0] . ':' . $propArr[1] . ';';
                $product_item_property_alias = $product_item_property_alias . $skuItemArr[$k] . ';';
            }
            $product_item_property_alias = substr($product_item_property_alias,0,strlen($product_item_property_alias) - 1 ); 
            $product_item_prop = substr($product_item_prop,0,strlen($product_item_prop) - 1 ); 
            $product_item_desc = $skuItemArr[count($skuItemArr)-1];


            //如果未过期且相同的product_item_prop已经存在了，则对该条信息进行更新，否则新增信息
            $itemPropNum = $this->db->get_where('info_product_item', array( 'product_id' => $productId , 'props' => $product_item_prop , 'item_expired' => '1' ))->num_rows();
            if ( $itemPropNum > 0 ){
                $profile_data_update = array(
                    'item_desc' => $product_item_desc,
                    'item_colour' => $product_item_colour,
                    'item_img_link' => $product_item_img,
                    'property_alias' => $product_item_property_alias
                );
                $this->db->where('props', $product_item_prop);
                $this->db->update('info_product_item', $profile_data_update); 
                if ( $this->db->affected_rows() > 0 ){
                    $alertMessage = $alertMessage.'<p class="">ERP更新商品信息成功</p>';
                }
            }else{
                //当更新操作影响记录的条数为0的时候，则往ERP中新增商品销售属性
                $profile_data = array(
                    'item_id' => $productItemId,
                    'product_id' => $productId,
                    'item_desc' => $product_item_desc,
                    'item_colour' => $product_item_colour,
                    'item_colour_props' => $product_item_colour_props,
                    'item_img_link' => $product_item_img,
                    'property_alias' => $product_item_property_alias,
                    'props' => $product_item_prop 
                );
                $this->db->insert('info_product_item',$profile_data);
                if ( $this->db->affected_rows() > 0 ){
                    $alertMessage = $alertMessage.'<p class="">ERP新增商品信息成功,ID:'.$productItemId.'</p>';
                }else{
                    $alertMessage = $alertMessage.'<p class="error_msg">ERP新增商品信息失败,ID:'.$productItemId.'</p>';
                }
            }
        }
        $this->session->set_flashdata('message',$alertMessage);
        redirect('jad_goods/manage_product_items/'.$productId);	
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
    function get_sku_info($skuId,$numIid){
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItemSkuGetRequest');
        $this->topsdk->req->setFields("sku_id,iid,properties,quantity,price,outer_id,created,modified,status");
        $this->topsdk->req->setSkuId($skuId);
        $this->topsdk->req->setNumIid($numIid);
        return $this->topsdk->get_data();
    }  
  function get_product_item_info_by_item_id($itemId){
  	$this->data['productItem'] = $this->db->get_where('product_item_view',array('item_id'=> $itemId ))->row_array();
  }

    function get_sku_info_by_sId($sId,$numIid){
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItemSkuGetRequest');
        $this->topsdk->req->setFields("sku_id,iid,properties,quantity,price,outer_id,created,modified,status");
        $this->topsdk->req->setSkuId($sId);
        $this->topsdk->req->setNumIid($numIid);
        $skuInfo = $this->topsdk->get_data();
        echo json_encode($skuInfo['sku']);
    }
    /**
    * get_product_items_by_pId
    * AJAX方法通过产品ID获取未过期的商品信息 
    * 编辑宝贝页面调用，便于页面局部刷新
    */
    function get_product_items_by_pId($pId){
        $result = $this->db->get_where('product_item_view',array('product_id'=> $pId , 'item_expired' => 1 ))->result_array();
        echo json_encode($result); 
    }

  function get_product_item_info_by_product_id($pId){
  	$this->data['productItemsInfo'] = $this->db->get_where('product_item_view',array('product_id'=> $pId ))->result_array();
  }
  function get_normal_product_item_info_by_product_id($pId){
  	$this->data['productItemsInfo'] = $this->db->get_where('product_item_view',array('product_id'=> $pId , 'item_expired' => 1 ))->result_array();
  }
    /**
    * get_item_info_by_num_id
    * 通过宝贝ID获取宝贝信息 
    */
    function get_item_info_by_num_id($numIid){
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItemGetRequest');
        $this->topsdk->req->setNumIid($numIid);
        $this->topsdk->req->setFields("detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual");
        $result = $this->topsdk->get_data();
        return $result['item'];
    }
    /**
     * get_seller_cats_by_nickname
     * AJAX方法
     * 通过用户昵称获取店铺类别 
     * 参数：通过配置文件获取店铺昵称
     */
    function get_seller_cats_by_nickname($nickName){
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('SellercatsListGetRequest');
        //从配置文件中获取店铺昵称
        //$this->topsdk->req->setNick($this->config->item('nick_name'));
        //从参数中获取店铺昵称
        $this->topsdk->req->setNick($nickName);

        $result = $this->topsdk->get_data();
        echo json_encode($result);
    }
    /**
     * get_itemcats_by_parent_id
     * AJAX方法
     * 通过父类目的CID获取以该CID为父类目CID的所有类目信息 
     * 参数：父类目CID
     */
    function get_itemcats_by_parent_id($parentCid)
    {
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItemcatsGetRequest');
        $this->topsdk->req->setFields("cid,parent_cid,name,is_parent,status");
        $this->topsdk->req->setParentCid($parentCid);
        
        $result = $this->topsdk->get_data();
        echo json_encode($result);
    }	
    /**
     * get_itemcats_by_cid
     * AJAX方法
     * 通过叶子类目的CID获取该叶子类目的属性 
     * 参数：叶子类目CID
     */
    function get_itemprops_by_cid($cId)
    {
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItempropsGetRequest');
        $this->topsdk->req->setFields("pid,name,must,multi,prop_values,is_color_prop,is_key_prop,is_sale_prop,is_enum_prop,parent_pid,is_input_prop,child_template");
        $this->topsdk->req->setCid($cId);
        $result = $this->topsdk->get_data();
        //注意：在该叶子类目下没有属性信息的时候，要对返回值进行判断，否则会引起错误；
        //echo count($result,1);
        echo json_encode($result);
    }
    /**
     * get_child_itemprops
     * AJAX方法
     * 通过叶子类目CID与父属性ID获取属性信息 
     * 参数：叶子类目CID,父属性ID
     */
    function get_child_itemprops($cId,$parentPId)
    {
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItempropsGetRequest');
        $this->topsdk->req->setFields("pid,name,must,parent_pid,parent_vid,multi,prop_values,is_key_prop,is_enum_prop,parent_pid,is_input_prop,child_template");
        $this->topsdk->req->setCid($cId);
        $this->topsdk->req->setParentPid($parentPId);
        $result = $this->topsdk->get_data();
        //注意：在该叶子类目下没有属性信息的时候，要对返回值进行判断，否则会引起错误；
        echo json_encode($result);
    }	
    /**
     * get_propvalues
     * AJAX方法
     * 通过叶子类目CID与属性ID获取属性信息 
     * 参数：叶子类目CID,属性ID
     */
    function get_propvalues($cId,$pId)
    {
        $this->load->library('TopSdk', $this->config->item('topapi_config') );
        $this->topsdk->autoload('ItempropvaluesGetRequest');

        $this->topsdk->req->setFields("cid,pid,prop_name,vid,name,name_alias,status,sort_order");
        $this->topsdk->req->setCid($cId);
        $this->topsdk->req->setPvs($pId);

        $result = $this->topsdk->get_data();
        //注意：在该叶子类目下没有属性信息的时候，要对返回值进行判断，否则会引起错误；
        echo json_encode($result);
    }
    /**
     * update_item_info
     * AJAX方法
     */
    function update_item_info(){
        $price = $this->input->post('price');
        $num = $this->input->post('num');
        $title = $this->input->post('title');
        $desc =  $_POST["desc"];
        $sellerCats = $this->input->post('sellerCats');
        $locationCheckbox = $this->input->post('locationBought');

        if($locationCheckbox == 2){
            $globalType =$this->input->post('globalType');
            $globalStock =$this->input->post('globalStock');
            echo $globalType;
            echo $globalStock;
        }

        echo html_entity_decode($desc);
    }
}
