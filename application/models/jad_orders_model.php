<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_orders_model extends CI_Model {
	
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
	/** add_item
	 * 将商品信息加入订单池
	 * $data中id相同的商品信息，已经自动被合并了，在数量上能够看出区别
	 */
	function add_item(){
		$this->load->library('cart');
        $itemId = $this->input->post('itemId');
        $itemUrl = $this->input->post('itemUrl');
		$itemUrl = urldecode($itemUrl);
        $itemUrl = $this->jad_global_model->get_url_sub_image_by_formal($itemUrl,'sq');
        $itemName = $this->input->post('itemName');
        $itemBrand = $this->input->post('itemBrand');
        $itemSku = $this->input->post('itemSku');

		//前4个属性里面最好不要放东西，容易引起错误而导致不能正常添加
		$data = array(
		   'id'      => $itemId,
		   'qty'     => 1,
		   'price'   => 1,
		   'name'    => 'order',
		   'options' => array('name' => $itemName,'brand' => $itemBrand,'sku' => $itemSku,'url' => $itemUrl)
		);
		$rId = $this->cart->insert($data);
		echo $rId;
	}
	/** del_item
	 * 将商品信息从订单池中删除
	 * 直接将相同rowid的那条商品信息的数量置0，即为删除操作 
	 */
	function del_item(){
		$this->load->library('cart');
        $itemId = $this->input->post('rowId');
		$data = array(
           'rowid' => $itemId,
           'qty'   => 0 
        );
		$rId = $this->cart->update($data); 
		echo $rId;
	}
	/** get_items
	 * 获取cart中的订单信息
	 * 直接将多维数组的结果转换为json格式到前台进行处理
	 */
	function get_items(){
		$this->load->library('cart');
		/* echo json_encode($this->cart->contents()); */
		//遍历cart集合，返回order的结果
		$result = array(); 
		foreach ($this->cart->contents() as $items){
			if($items['name'] == 'order'){
				array_push($result,$items);
			}
		}
		echo json_encode($result);
	}
	/** order_add 
	 * 新增订单
	 * 需要同时操作客户表、订单表、订单详情表 
	 */
	function order_add(){
        $clientChannel = $this->input->post('distribution_channel');
        $clientName = $this->input->post('client_name');
        $clientAddress = $this->input->post('address');
        $clientPhone = $this->input->post('client_phone');
        $clientPostCode = $this->input->post('post_code');
        $clientOrderNote= $this->input->post('order_note');
        $orderItems = $this->input->post('order_items');
        $location= $this->input->post('location_json');
        $alertMessage = '';
		$this->db->trans_start();
		//新增客户信息，返回客户id号
		$profile_data = array(
                'client_name' => $clientName,
                'client_location' => $location,
                'client_street' => $clientAddress,
                'client_phone' => $clientPhone,
                'client_zip_code' => $clientPostCode,
        );
        $this->db->insert('info_client',$profile_data);
		$clientId = $this->db->insert_id();
        $alertMessage = $alertMessage.'<p class="">新增客户信息成功  姓名：'.$clientName.'</p>';
		//新增订单信息
		$profile_data = array(
                'order_client' => $clientId,
                'order_channel' => $clientChannel,
				'order_time' => date('Y-m-d H:i:s',time()),
                'order_note' => $clientOrderNote,
        );
        $this->db->insert('info_order',$profile_data);
		$orderId = $this->db->insert_id();
        $alertMessage = $alertMessage.'<p class="">新增订单信息成功  订单号：'.$orderId.'</p>';
		//新增订单详情
		//遍历订单详情数据
		$orderItemsArray = json_decode($orderItems,true);
		for( $i = 0 ; $i < count($orderItemsArray); $i++){
			$profile_data = array(
				'item_id' => $orderItemsArray[$i][0],
				'order_id' => $orderId,
				'o_item_price' => $orderItemsArray[$i][1],
				'o_item_num' => $orderItemsArray[$i][2],
				'o_item_taobao' => $orderItemsArray[$i][3]
			);
			$this->db->insert('info_order_items',$profile_data);
            $alertMessage = $alertMessage.'<p class="">新增订单商品信息成功  商品编号：'.$orderItemsArray[$i][0].'</p>';
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
            $alertMessage = '<p class="">新增订单信息失败，请重试!</p>';
		}else{
			//清空订单车
			$this->load->library('cart');
			$result = array(); 
			foreach ($this->cart->contents() as $items){
				if($items['name'] == 'order'){
					$data = array(
					   'rowid' => $items['rowid'],
					   'qty'   => 0 
					);
					$this->cart->update($data); 
				}
			}
		}
        $this->session->set_flashdata('message',$alertMessage);
        redirect('jad_goods/manage_products/order_by/num_iid/order_parameter/desc');	
	}
	/**  get_orders
	 * 获取所有订单信息 
	 * 
	 */
	function get_orders(){
        $this->db->select("*"); 
        $this->db->from('order_client_view');
        $this->data['ordersInfo'] = $this->db->get()->result_array();
	}
	/** get_order_items
	 * 获取订单下的商品信息 
	 * 参数：订单号
	 */
	function get_order_items($orderId){
        $this->db->select("*"); 
        $this->db->from('order_item_view');
        $this->db->where('order_id' , $orderId );
        return $this->db->get()->result_array();
	}
	function get_order_funds($orderId){
        $this->db->select("*"); 
        $this->db->from('info_order_funds');
        $this->db->where('order_id' , $orderId );
        return $this->db->get()->result_array();
	}
	/** get_order_client
	 * 获取订单信息包括客户信息 
	 * 参数：订单号
	 */
	function get_order_client($orderId){
        $this->db->select("*"); 
        $this->db->from('order_client_view');
        $this->db->where('order_id' , $orderId );
        return $this->db->get()->row_array();
	}
	/** fund_add
	 * 新增款项 
	 * 参数：订单号
	 */
	function fund_add(){
		$fundChannel = $this->input->post('fund_channel');
		$fundId = $this->input->post('fund_id');
		$fundPrice = $this->input->post('fund_price');
		$fundNote = $this->input->post('fund_note');
		$fundOrder = $this->input->post('fund_order_id');
		$profile_data = array(
			'fund_channel' => $fundChannel, 
			'fund_voucher' => $fundId, 
			'fund_price' => $fundPrice, 
			'order_id' => $fundOrder, 
			'fund_time' => date('Y-m-d H:i:s',time()),
			'fund_note' => $fundNote 
		);
		$this->db->insert('info_order_funds',$profile_data);
        redirect('jad_orders/order_items/'.$fundOrder);	
	}
	/** fund_edit
	 * 新增款项 
	 * 参数：订单号
	 */
	function fund_edit(){
		$fundChannel = $this->input->post('fund_channel');
		$fundVoucher = $this->input->post('fund_id');
		$fundPrice = $this->input->post('fund_price');
		$fundNote = $this->input->post('fund_note');
		$fundId = $this->input->post('fund_edit_id');
		$fundOrder = $this->input->post('fund_order_id');
		$data = array(
			'fund_channel' => $fundChannel, 
			'fund_voucher' => $fundVoucher, 
			'fund_price' => $fundPrice, 
			'fund_time' => date('Y-m-d H:i:s',time()),
			'fund_note' => $fundNote 
		);

		$this->db->where('fund_id', $fundId);
		$this->db->update('info_order_funds', $data);
        redirect('jad_orders/order_items/'.$fundOrder);	

	}
	/** fund_del
	 * 删除款项 
	 * 参数：订单号
	 */
	function fund_del(){
		$fundId = $this->input->post('fundId');
		$this->db->where('fund_id', $fundId);
		$this->db->delete('info_order_funds'); 
	}
	function item_num_add(){
		$str = $this->input->post('str');
		$strArray = explode('.',$str);
		$curNum = $strArray[2];
		$curNum++;
		$data = array(
			'o_item_num' => $curNum, 
		);

		$this->db->where('item_id', $strArray[1]);
		$this->db->where('order_id', $strArray[0]);
		$this->db->update('info_order_items', $data);
	}
	function item_num_del(){
		$str = $this->input->post('str');
		$strArray = explode('.',$str);
		$curNum = $strArray[2];
		$curNum--;
		$data = array(
			'o_item_num' => $curNum, 
		);

		$this->db->where('item_id', $strArray[1]);
		$this->db->where('order_id', $strArray[0]);
		$this->db->update('info_order_items', $data);
	}

}
