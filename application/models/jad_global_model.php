<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_global_model extends CI_Model {
	
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// 获取常用数据
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * 1.根据用户角色ID，获取用户名单.
	 * get_staff_list_by_role_id
	 * 调用：分店的增加、编辑；下达任务获取买手、分店负责人；
	 * 下达退货任务、
	 * 
	 */
  function get_staff_list_by_role_id($groupId){
	  return $this->db->get_where('jadstafflistview',array( 'ugrp_id' => $groupId ))->result_array();
  } 	
	/**
	 * 2.根据货号获取商品一级信息.
	 * get_goods_info_by_seriesnum
	 * 调用：商品一级信息的编辑、商品二级信息列表、增加商品二级信息、代购/囤货任务预览；
	 * jad_mission_model.new_mission();
	 */	
	 function get_goods_info_by_seriesnum($sNum){
     return $this->db->get_where('goodsfirstinfoview',array( 'goods_seriesnum' => $sNum ))->row_array();
	 }
	/**
	 * 3.根据商品长编码获取商品一、二级信息.
	 * get_goods_s_info_by_code
	 * 调用：商品一级信息的编辑；
	 * jad_mission_model.new_mission();
	 */	 	 
	 function get_goods_s_info_by_code($gCode){
     return $this->db->get_where('goodssecondinfoview',array( 'goods_code' => $gCode ))->row_array();
	 }	
	  
  /**
	 * get_goods_s_info_by_barcode
	 * 根据商品货号获取商品的二级信息
	 * 调用：下达退货任务;提交商品购买信息
	 */
  function get_goods_s_info_by_barcode($barCode){		
  return $this->db->get_where('goodssecondinfoview',array( 'goods_barcode' => $barCode ))->row_array();
  }
	 
   /**
	 * 4.根据商品唯一标示获取商品所有信息.
	 * get_merch_info_by_merchid
	 * 调用：下达现/调货任务、下达退货任务、提交退货信息
	 *
	 */
  function get_merch_info_by_merch_id($merchId){		
    return $this->db->get_where('merchinfoview',array('merch_id' => $merchId ))->row_array();
  }  	 

  /**
	 * get_branch_info_by_mId()
	 * 根据商品标识码获取该商品所在的仓库id
	 * 调用：下达退货任务
	 */
  function get_branch_info_by_mId($mId){
  	$queryInven = $this->db->query("SELECT branch_id FROM info_inventory WHERE 
  	  inven_out_time is null and 
  	  inven_expired = 0 and 
  	  merch_id ='".$mId."'")->row_array();		
  	return $queryInven['branch_id'];
  } 
  /**
	 * get_client_by_mId()
	 * 根据商品标识码获取购买该商品的顾客信息
	 * 调用：下达退货任务	 
	 */
  function get_client_info_by_mId($mId){	
  	$this->db->select('*');
  	$this->db->from('info_order');
		$this->db->join('info_client','info_client.client_id = info_order.client_id');
  	$this->db->where('merch_id' , $mId);	
  	return $this->db->get()->row_array();
  }    
  /**
	 * get_buyer_info_by_mId()
	 * 根据商品标识码获取购买该商品的买手id
	 * 调用：下达退货任务
	 */
  function get_buyer_info_by_mId($mId){	
  	$this->db->select('upro_id');
  	$this->db->from('info_mission');
		$this->db->join('mission_purchase','mission_purchase.mission_id = info_mission.mission_id');
  	$this->db->where('merch_pre_id' , $mId);	
  	$query =  $this->db->get()->row_array();
  	return $query['upro_id'];
  }  
  /**
	 * get_buyerinfo_by_id()
	 * 根据商品标识码获取购买该商品的买手信息
	 * 调用：下达退货任务
	 */
  function get_buyerinfo_by_id($mId){	
  	return $this->db->get_where('merchpurchaseinfoview',array( 'merch_pre_id' => $mId ))->row_array();
  }  
  /**
	 * get_suppliersinfo
	 * 获取所有的供货商信息
	 * 调用：下达退货任务;提交商品购买信息
	 */
  function get_suppliersinfo(){		
  return $this->db->get('info_supplier')->result_array();
  }  
  
  /**
	 * is_in_group()
	 * 根据用户ID来判断该用户是否属于某个角色
	 * 调用：jad_mission_model.received_mail();
	 */
  function is_in_group($userId,$groupId){
  	$checkNum = $this->db->get_where('user_accounts',array('uacc_id' => $userId,'uacc_group_fk' => $groupId))->num_rows();		
    if ( $checkNum == 1 ) return true;
    return false;
  }  
  /**
	 * get_branchesinfo
	 * 获取所有的分店信息
	 * 调用：库存搜索
	 */
  function get_branchesinfo(){		
  return $this->db->get('info_branch')->result_array();
  }  
  
  /**
	 * get_merch_exist_status_info_by_id
	 * 根据商品标识判断该商品状态；1、库存商品；2、任务中的商品；3、已售商品；4、厂商退货商品；5、买手暂存
	 * 最早判断该商品是否属于任务中、、、、
	 * 调用：库存搜索页面
	 */
  function get_merch_exist_status_info_by_id($mId){
  	$status = 3;
  	/* 判断在退货表中是否存在
  	** 该表记录了成功退货时的费用信息，只有在退货任务成功完成时，才会有该信息
  	** 所以保证了该表中如果有商品记录，则该商品一定属于4：厂商退货***********************************
   	*/
   	if ($this->db->get_where('info_return',array('merch_id' => $mId))->num_rows() > 0 ){
  	  $status = 4;
  	  return $status;
  	} 
  	/* 判断是否任务中的商品，
    ** 正常情况：在mission表中该merchid下的任务状态<3的条目是否存在
    ** 特俗情况：被终止的任务的邮寄子任务全部完成
  	*/
  	if ($this->db->get_where('info_mission',array('merch_pre_id' => $mId,'mission_status <' => 3))->num_rows() > 0 ){
  	  $status = 2;
  	  return $status;
  	}  	
    $this->db->select('*');
		$this->db->from('info_mission');
    $this->db->join('mission_transfer','mission_transfer.mission_id = info_mission.mission_id');
    $this->db->where('mission_status',4);
    $this->db->where('trans_status <',4);
    $this->db->where('merch_pre_id',$mId);
    if ($this->db->get()->num_rows()>0){
    	$status = 2;
  	  return $status;
    }
    
  	/* 判断是否库存商品
  	** 在任务完成的情况下，所以排在上一个判断之后；如果要单独存在，需要加上对任务是否完成的判断，包括任务终止的判断
  	** 在inventory表中该merchid下invenid最大的那条记录中，是否存在出库时间，存在即无货
  	*/
  	$queryInven = $this->db->query("SELECT * FROM info_inventory WHERE inven_out_time is null and merch_id ='".$mId."' order by inven_id desc limit 1")->num_rows();
    if ($queryInven != 0){
    	$status = 1;
    	return $status;
    }    
    
    
    /* 判断是否是买手暂存********************************************************************
  	** 在任务完成的情况下，所以排在第二个判断之后；如果要单独存在，需要加上对任务是否完成的判断，包括任务终止的判断
    */
    //首先必须满足已经购买
    $this->db->select('*');
    $this->db->from('info_mission');
    $this->db->join('mission_purchase','info_mission.mission_id = mission_purchase.mission_id');
    $this->db->where('merch_pre_id',$mId);
    $this->db->where('purchase_status',3);
    $missionInfo = $this->db->get()->row_array();
    //当还没有进入库存时
    $cNum = $this->db->query("SELECT * FROM info_merchandise 
    left join info_inventory on info_inventory.merch_id = info_merchandise.merch_id
    join info_mission on info_merchandise.merch_id = info_mission.merch_pre_id
    left join mission_transfer on mission_transfer.mission_id = info_mission.mission_id
    where inven_id is null and mission_status=4 and merch_pre_id = '".$mId."'")->num_rows();
    //进入库存以后
    $bQuery = $this->db->query("SELECT * FROM info_merchandise 
    left join info_inventory on info_inventory.merch_id = info_merchandise.merch_id
    join info_mission on info_merchandise.merch_id = info_mission.merch_pre_id
    left join mission_transfer on mission_transfer.mission_id = info_mission.mission_id
    where inven_out_time is not null and merch_pre_id = '".$mId."' 
    and (mission_status=4 or mission_status=3)and trans_status=4 
    order by info_mission.mission_id desc limit 1
    ")->row_array();

    if ( $cNum > 0 ||( count($bQuery)!=0 && $bQuery['recuser_id']==$missionInfo['upro_id'] )){
       $status = 5;
  	   return $status; 
    }
    //判断是否已售商品，不是以上项，就是已售
    return $status;
  }  
 
	/**
	 * get_merch_location_by_id
	 * 返回拥有该商品的买手的id ,买手暂存时，获取该商品的购买者ID，该购买者一定为该商品的暂存者
	 * 调用：库存搜索页面
	 * 约束：退货一定退给购买该商品的买手
	 */
   function get_merch_location_by_id($merchId){
 	 $this->db->select('*');
 	 $this->db->from('info_mission');
 	 $this->db->join('mission_purchase','mission_purchase.mission_id = info_mission.mission_id');
 	 $this->db->where('merch_pre_id',$merchId);
 	 $query = $this->db->get()->row_array();
 	 return $query['upro_id'];
   }
  /**
	 * get_branch_info_by_id
	 * 根据分店ID获取负责人信息
	 * 调用：库存搜索页面
	 */
  function get_branch_info_by_id($bId){		
  return $this->db->get_where('info_branch',array('branch_id' => $bId))->row_array();
  }   
  
	/**
	 * get_clientinfo_by_clientid
	 * 获取给定客户ID的所有信息. 
	 */
  function get_clientinfo_by_clientid($clientId){
	  return $this->db->get_where('info_client', array('client_id' => $clientId))->row_array();
  }   
  /**
	 * get_supplier_info_by_sId()
	 * 根据SID获取供货商信息
	 */
  function get_supplier_info_by_sId($sId){		
  return $this->db->get_where('info_supplier',array('suppl_id' => $sId))->row_array();
  }

  /**
	 * get_mission_info_by_id
	 * 根据商品标识查找该商品的任务类型；mission中不可能存在相同商品标识且未完成的主任务信息
	 */
  function get_mission_info_by_id($mId){		
    return $this->db->get_where('info_mission',array('merch_pre_id' => $mId,'mission_status <' => 3))->row_array();
  }
  /**
	 * is_inventory()
	 * 根据商品标识码判断是否存在该条库存商品,返回该库存的仓库ID
	 */
  function is_inventory($mId){		
  	$queryInven = $this->db->query("SELECT * FROM info_inventory WHERE inven_out_time is null and inven_expired = 0 and merch_id ='".$mId."'")->row_array();
    if (empty($queryInven))
      return "empty";
    else
      return $queryInven['branch_id'];
  }

  /**
	 * is_sold_by_merch_id()
	 * 根据商品标识码判断该商品是否已售
	 * 
	 */
  function is_sold_by_merch_id($mId){		
  	$queryInven = $this->db->query("SELECT * FROM info_inventory WHERE inven_out_time is null and inven_expired = 0 and merch_id ='".$mId."'")->row_array();
    if (empty($queryInven))
      return "empty";
    else
      return $queryInven['branch_id'];
  }  
  /**
	 * is_single_line()
	 * 根据当前代购任务的邮寄信息是否直接由买手邮寄给客户，即判断是否只有一条邮寄线路
	 * 囤货任务一定可以直接邮寄给分店主
	 */
  function is_single_line($missionId){
  	//若是囤货任务，直接返回true；
  	if ($this->db->get_where('info_mission',array( 'mission_type' => 2,'mission_id' => $missionId ))->num_rows()>0)
  	  return false;
  	//若是代购任务，需进行判断；
  	$this->db->select('*');
  	$this->db->from('info_mission');
  	$this->db->join('mission_transfer','mission_transfer.mission_id = info_mission.mission_id');
  	$this->db->where( 'info_mission.mission_id',$missionId );
  	$this->db->where( 'info_mission.mission_type',1 );
  	$checkNum = $this->db->get()->num_rows();		
    if ( $checkNum > 1 ) return false;
    return true;
  }    
  /**
	 * get_mail_end_user_id_by_missionId()
	 * 根据主任务ID获取最初发货或接收人ID
	 * 供货商和买手之间的邮寄作为买手的邮寄
	 * 参数：主任务ID；类型：1、最初发货人，2、最初接收人
	 */  
	 function get_mail_end_user_id_by_missionId($mId,$eType){
	 	 if( $eType == 1 ){
	 	   $this->db->select('*');
	 	   $this->db->from('mission_transfer');
	 	   $this->db->where('mission_id',$mId);
	 	   $this->db->order_by('trans_id');
	 	   $this->db->limit(1);
	 	   $query = $this->db->get()->row_array();
	 	   return $query['senduser_id'];
	 	 }else{
	 	   $this->db->select('*');
	 	   $this->db->from('mission_transfer');
	 	   $this->db->where('mission_id',$mId);
	 	   $this->db->order_by('trans_id','desc');
	 	   $this->db->limit(1);
	 	   $query = $this->db->get()->row_array();
	 	   return $query['recuser_id'];	 	 	
	 	 }
	 }
  /**
	 * get_person_name_by_id()
	 * 根据不同的ID获取不同类型人员的信息
	 * 系统员工：买手、分店；
	 * 客户：20000>ID>10000;供货商ID>20000
	 * 参数：用户ID 
	 */    
   function get_person_name_by_id($uId){
   	 if( $uId > 20000||$uId == 20000 ){
   	 	//供应商ID
   	 	$this->db->distinct();
   	 	$this->db->select('suppl_alias');
   	 	$this->db->from('info_mission');
   	 	$this->db->join('info_merchandise','info_merchandise.merch_id = info_mission.merch_pre_id');
   	 	$this->db->join('info_supplier','info_merchandise.suppl_id = info_supplier.suppl_id');
   	 	$this->db->where('info_supplier.suppl_id',$uId);
   	 	$query = $this->db->get()->row_array();
   	 	return $query['suppl_alias'];
   	 }else if( $uId < 10000 ){
   	 	//员工ID，需要判断是买手还是分店主
   	 	$queryTemp = '';
   	 	if ( $this->is_in_group($uId,2) ){
   	 		$query = $this->db->get_where('info_branch',array( 'upro_id' => $uId ))->row_array();
   	 		$queryTemp = $query['branch_name'];
   	 	}
   	 	if ( $this->is_in_group($uId,4) ){
   	 		$query = $this->db->get_where('jadiiar_user_profiles',array( 'upro_id' => $uId ))->row_array();
        $queryTemp = $query['upro_first_name'].' '.$query['upro_last_name'];
   	 	}
   	 	 return $queryTemp;
   	 }else{
   	 	//客户ID
   	 	$query = $this->db->get_where('info_client',array( 'client_id' => $uId ))->row_array();
   	 	return substr($query['client_message'],0,strpos($query['client_message'],'@'));
   	 }
   }
  /**
	 * get_url_sub_image_by_formal()
	 * 根据piwigo原图的地址转换为缩略图的地址
	 * 参数：用户ID 
	 */ 
	 function get_url_sub_image_by_formal($fUrl){
	 	$subUrl =  substr($fUrl,0,strpos($fUrl,'-me.')).'-sq.jpg';
	 	return $subUrl;
	 }
  /**
     * 对系统提示信息（p标签包裹的数据）进行重新处理        
	 * 参数：系统生成的提示信息 
	 */ 
  function get_alert_message($message)
  {
              $message="<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>".$message;
              $arr = array();  
              $doc = new DOMDocument();
              $doc->loadHTML($message);
              $params = $doc->getElementsByTagName('p');
              foreach ($params as $param) 
              {
                  $arr[] = array( $param->getAttribute('class'),$param->nodeValue);
              }
              return $arr;   
	 }
 
}
