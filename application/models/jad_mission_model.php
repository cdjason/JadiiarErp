<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jad_mission_model extends CI_Model {
	
	public function &__get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Missions
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * execute_mission_by_buyer
	 * 买手处理购买任务，更新任务状态信息. 
	 * 参数：任务ID,任务状态标示
	 */
	function execute_mission_by_buyer($purchaseId,$purchaseStatus){
		$this->db->trans_start();	
		$profile_data = array('purchase_status' => $purchaseStatus);		
		$this->db->where('purchase_id', $purchaseId);
		$this->db->update('mission_purchase', $profile_data); 
		
		//买手更新购买状态为2：正在购买，意味着买手接受囤货或代购任务；那么需要更新主任务状态为2：正在执行
		if( $purchaseStatus == 2 ){
			//获取该购买任务对应的主任务id
			$query = $this->db->get_where('mission_purchase',array('purchase_id' => $purchaseId))->row_array();
			$profile_mission_data = array('mission_status' => 2);		
		  $this->db->where('mission_id', $query['mission_id']);
		  $this->db->update('info_mission', $profile_mission_data); 			
		}
		$this->db->trans_complete();
	}

	/**
	 * new_mission
	 * 下达新任务
	 * 若是通过任务管理部分直接下达，则会新生成相应商品的二级信息 
	 */
	function new_mission(){
		
		  $shortCode='';
		  $barCode='';
			//获取表单提交的值，若是直接下达任务，则需要新增商品二级信息	
			if ($this->input->post('is_from_mission_page')=='yes'){
				$goodsCode = $this->input->post('submit_mission_merch_id');
			  $desc = $this->input->post('submit_mission_s_desc');
			  //获取商品短编码
			  $shortCode = $this->jad_global_model->get_goods_info_by_seriesnum(substr($goodsCode,0,10));
			  //生成唯一的长条码,并验证是否唯一，否则重新生成
        do{
      	  //随机生成3位字符串。
      	  $randomTemp = $this->generate_ranstring();
      	  $codeTemp = $shortCode['goods_shortcode'].$randomTemp;
        }while( $this->db->get_where('info_goods_second',array('goods_barcode' => $codeTemp))->num_rows() == 1 );
						
			  $profile_data = array(
			    'goods_code' => $goodsCode,
				  'goods_seriesnum' => substr($goodsCode,0,10),
				  'goods_barcode' => $codeTemp,
				  'goods_shortcode' => substr($codeTemp,0,5),
				  'goods_s_desc' => $desc
			  );
			  $this->db->insert('info_goods_second',$profile_data); 
			  $barCode = $codeTemp;
			}else{
				$randomTemp = $this->jad_global_model->get_goods_s_info_by_code($this->input->post('submit_mission_merch_id'));
				$barCode = $randomTemp['goods_barcode'];
			}
			
			$buyerid = $this->input->post('submit_mission_buyer_id');
			$mdesc = $this->input->post('submit_mission_desc');
			
			//生成唯一的标识码,从二级信息中的条形码过渡而来;在任务总表中验证是否唯一；逻辑上的设计是在任务总表生成的时候，商品的唯一码就确定了；
      do{
      	//随机生成3位字符串。
      	$randomTemp = $this->generate_ranstring();
      	$codeTemp = $barCode.$randomTemp;
      }while( $this->db->get_where('info_mission',array('merch_pre_id' => $codeTemp))->num_rows() == 1 );
      $missionType = $this->input->post('mission_type');
			//设置数据库中总任务信息
			$mission_data = array(
				'mission_time' => date('Y-m-d H:i:s',time()),
				'mission_status' => 1,
				'mission_type' => $missionType,
				'merch_pre_id' => $codeTemp
			);
			
			//保证此处完全满足任务总表和购买子表同时添加任务信息。
      $this->db->trans_start();	
			$this->db->insert('info_mission',$mission_data);
			$m_id = $this->db->insert_id();
			//设置购买子任务信息
			$purchase_data = array(
				'mission_id' => $m_id,
				'upro_id' => $buyerid,
				'purchase_desc' => $mdesc,
				'purchase_status' => 1
			);			
			$this->db->insert('mission_purchase',$purchase_data);
			
			//在代购任务时，设置订购客户信息
			$c_id = '';
			if ( $missionType == 1 ){
				//设置客户联系信息
			  $client_data = array(
				  'client_message' => $this->input->post('set_mission_client'),
			  );			
			  $this->db->insert('info_client',$client_data); 
			  $c_id = $this->db->insert_id();
			  //设置客户订购信息
			  $order_data = array(
				  'merch_id' => $codeTemp,
				  'client_id' => $c_id,
				  'order_price' => $this->input->post('set_order_price'),
				  'monetary_unit' => $this->input->post('set_merch_unit')
			  );			
			  $this->db->insert('info_order',$order_data); 			  
		  } 			
			//设置邮寄子任务表
			$mailMission = unserialize($this->session->flashdata('mailLine'));
			
			foreach($mailMission as $mmis){
				if ( $mmis['1'] == 888 ) $mmis['1'] = $c_id;
			  $mail_data = array(
				  'senduser_id' => $mmis['0'],
				  'recuser_id' => $mmis['1'],
				  'mission_id' => $m_id,
				  'trans_status' => 1
			  );
		  $this->db->insert('mission_transfer',$mail_data);
			}
			$this->db->trans_complete();
	    redirect('jad_mission/missions_search');	
	}
	/**
	 * new_mission_spot
	 * 下达发货任务
	 */
	function new_mission_spot(){
			//设置数据库中总任务信息
			$missionType = $this->input->post('mission_type');
			$mission_data = array(
				'mission_time' => date('Y-m-d H:i:s',time()),
				'mission_status' => 1,
				'mission_type' => $missionType,
				'merch_pre_id' => $this->input->post('submit_mission_merch_id')
			);
			
			//保证此处完全满足任务总表和购买子表同时添加任务信息。
      $this->db->trans_start();	
			$this->db->insert('info_mission',$mission_data);
			$m_id = $this->db->insert_id();
			
			//在现货任务时，设置订购客户信息
			$c_id = '';
			if ( $missionType == 3 ){
			  $client_data = array(
				  'client_message' => $this->input->post('set_mission_client'),
			  );			
			  $this->db->insert('info_client',$client_data); 
			  $c_id = $this->db->insert_id();
			  //设置客户订购信息
			  $order_data = array(
				  'merch_id' => $this->input->post('submit_mission_merch_id'),
				  'client_id' => $c_id,
				  'order_price' => $this->input->post('set_order_price'),
				  'monetary_unit' => $this->input->post('set_merch_unit')
			  );			
			  $this->db->insert('info_order',$order_data);
			}
			//设置邮寄子任务表
			$mailMission = unserialize($this->session->flashdata('mailLine'));
			//$tStatus保证本次主任务下所有的邮寄任务中的第一项任务状态为2，其余为1；保证了发货者可以通过当前邮寄任务发货。
			$tStatus = 2;
			foreach($mailMission as $mmi){
				if ( $mmi['1'] == 888 ) $mmi['1'] = $c_id;
			  $mail_data = array(
				  'senduser_id' => $mmi['0'],
				  'recuser_id' => $mmi['1'],
				  'mission_id' => $m_id,
				  'trans_status' => $tStatus
			  );
			  //设置具体线路信息
		    $this->db->insert('mission_transfer',$mail_data);
		    if( $tStatus == 2) $tStatus = 1;
			}
			$this->db->trans_complete();
	    redirect('jad_mission/missions_search');	
	}
		/**
	 * new_mission_return
	 * 下达退货任务
	 */
	function new_mission_return(){

			//设置数据库中总任务信息
			$mission_data = array(
				'mission_time' => date('Y-m-d H:i:s',time()),
				'mission_status' => 1,
				'mission_type' => 4,
				'merch_pre_id' => $this->input->post('submit_mission_merch_id')
			);
			
      $this->db->trans_start();	
			$this->db->insert('info_mission',$mission_data);
			$m_id = $this->db->insert_id();
			 			
			//设置邮寄子任务表
			$mailMission = unserialize($this->session->flashdata('mailLine'));
			//$tStatus保证本次主任务下所有的邮寄任务中的第一项任务状态为2，其余为1；保证了发货者可以通过当前邮寄任务发货。
			$tStatus = 2;
			//foreach($mailMission as $key => $ml){
			foreach($mailMission as $mmis){
				//if ( $mmis[1] == 888 ) $mmis[1] = $c_id;
			  $mail_data = array(
				  'senduser_id' => $mmis[0],
				  'recuser_id' => $mmis[1],
				  'mission_id' => $m_id,
				  'trans_status' => $tStatus
			  );
			  //设置具体线路信息
		    $this->db->insert('mission_transfer',$mail_data);
		    if( $tStatus == 2) $tStatus = 1;
			}
			$this->db->trans_complete();
	    redirect('jad_mission/missions_search');	
	}
	/**
	 * 
	 * 获取买手当前的购买任务. 
	 */
  function get_current_mission(){
			$this->db->select('*');
      $this->db->from('mission_purchase');
	    $this->db->join('info_mission','info_mission.mission_id = mission_purchase.mission_id');
	    $this->db->where('upro_id', $this->flexi_auth->get_user_id());
	    $this->db->where('purchase_status !=',4 );		
	    $this->db->where('purchase_status !=',3 );
      $this->data['c_mission'] = $this->db->get()->result_array();  	
  }
  
	/**
	 * 
	 * 获取用户当前的邮寄任务. 
	 */
  function get_current_mail_mission(){
  	
			$this->db->select('*');
      $this->db->from('info_mission');
	    $this->db->join('mission_purchase','info_mission.mission_id = mission_purchase.mission_id','left');
	    $this->db->join('mission_transfer','info_mission.mission_id = mission_transfer.mission_id');
	    $where = "(senduser_id='".$this->flexi_auth->get_user_id()."' AND trans_status=2) 
	    OR (senduser_id>20000 AND trans_status=2 AND recuser_id='".$this->flexi_auth->get_user_id()."')
	    OR (senduser_id>20000 AND trans_status=2 AND recuser_id!='".$this->flexi_auth->get_user_id()."' AND upro_id = '".$this->flexi_auth->get_user_id()."')
	    OR (recuser_id='".$this->flexi_auth->get_user_id()."' AND trans_status=3) 
	    OR (senduser_id='".$this->flexi_auth->get_user_id()."' AND trans_status=3 AND recuser_id>10000)";
      //只有当当前用户为管理员级别的用户时，才可以对客户的退货邮寄信息进行操作。
      if($this->flexi_auth->in_group('Master Admin'))
          $where = $where.' OR (senduser_id>10000 AND senduser_id<20000 AND trans_status=2)';
      $this->db->where($where);      
      $this->data['cm_mission'] = $this->db->get()->result_array();
         	
  }
	/**
	 * 
	 * 获取买手历史购买任务. 
	 */
  function get_history_missions(){
  	
			$this->db->select('*');
      $this->db->from('mission_purchase');
	    $this->db->join('info_mission','info_mission.mission_id = mission_purchase.mission_id');
	    $this->db->join('info_merchandise','info_merchandise.merch_id = info_mission.merch_pre_id');
	    $this->db->join('info_supplier','info_supplier.suppl_id = info_merchandise.suppl_id');
	    $this->db->where('mission_purchase.upro_id', $this->flexi_auth->get_user_id());
	    $this->db->where('purchase_status !=',1 );		
	    $this->db->where('purchase_status !=',2 );
      $this->data['h_mission'] = $this->db->get()->result_array();
  }
	/**
	 * 
	 * 获取邮递历史任务. 
	 */
  function get_history_mail(){
  	
			$this->db->select('*');
      $this->db->from('mission_transfer');
	    $this->db->join('info_mission','info_mission.mission_id = mission_transfer.mission_id');
	    $this->db->join('info_merchandise','info_merchandise.merch_id = info_mission.merch_pre_id');
	    $this->db->join('info_mail','info_mail.trans_id = mission_transfer.trans_id');
	    
	    $where = "(senduser_id='".$this->flexi_auth->get_user_id()."' AND trans_status != 1 AND trans_status != 2)
       OR (recuser_id='".$this->flexi_auth->get_user_id()."' AND trans_status = 4) ";
      
      //考虑到店小二查询为客户添加邮寄信息的情况，当店小二查询时，需要增加sql语句     
      if($this->flexi_auth->in_group('Master Admin'))
          $where = $where.' OR (senduser_id>10000 AND senduser_id<20000 AND ( trans_status=4 OR trans_status=3 ))';      
      
      $this->db->where($where);
      $this->data['h_mail'] = $this->db->get()->result_array();
  }
	/**
	 * 提交购买商品信息
	 */
	function finish_purchase(){
		$finishTime = date('Y-m-d H:i:s',time());
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'add_merch_price', 'label' => '价格:', 'rules' => 'required|numeric')
		);
		$this->form_validation->set_rules($validation_rules);
		if ($this->form_validation->run())
		{	
			$this->db->trans_start();	
			//需要判断买手是否需要邮寄取货，如果需要邮寄取货，要增加一条邮寄线路信息
			if($this->input->post('select_get_type')==2){
				if ($this->input->post('select_mail_type')==1){
			    //当邮寄对象为当前买手时：增加邮寄线路
		      $new_mail_data = array(
				    'senduser_id' => $this->input->post('select_suppl_id'),
				    'recuser_id' => $this->flexi_auth->get_user_id(),
				    'mission_id' => $this->input->post('get_mission_id'),
				    'trans_status' => 1
		      );	
		      $this->db->insert('mission_transfer',$new_mail_data);		
		    }else{
		    	//当邮寄对象为分店接收时：修改原有邮寄线路
		    	//获取需要修改的邮寄线路信息的trans_id
		    	$transUpdateInfo = $this->db->get_where('mission_transfer',array(
		    	   'mission_id' => $this->input->post('get_mission_id'),
		    	   'senduser_id' => $this->flexi_auth->get_user_id()
		    	))->row_array();
		      $updata_mail_data = array(
				     'senduser_id' => $this->input->post('select_suppl_id')
		      );		
		      $this->db->where('trans_id',$transUpdateInfo['trans_id']);
		      $this->db->update('mission_transfer', $updata_mail_data);		    	
		    }	
			}
			//操作一：在数据库中新增商品详细信息
			$merch_data = array(
				'merch_id' => $this->input->post('add_merch_id'),
				'goods_barcode' => substr($this->input->post('add_merch_id'),0,8),
				'merch_price' => $this->input->post('add_merch_price'),
				'monetary_unit' => $this->input->post('select_merch_unit'),
				'suppl_id' => $this->input->post('select_suppl_id')
			);
	    if ($this->db->insert('info_merchandise', $merch_data)){
	    	
	      //操作二：更新purchase表中的状态
		    $profile_data = array(
		       'purchase_time' => $finishTime,
				   'purchase_status' => 3
		    );		
		    $this->db->where('purchase_id', $this->input->post('add_purchase_id'));
		    $this->db->update('mission_purchase', $profile_data);
	      //设置当前主任务的下的第一条邮寄子任务的状态为2：“尚未邮寄”
        if($this->db->query("SELECT * FROM mission_transfer WHERE mission_id = '".$this->input->post('get_mission_id')."' AND senduser_id > 20000")->num_rows()>0){
		      $this->db->query("UPDATE mission_transfer SET trans_status = 2 WHERE mission_id = '".$this->input->post('get_mission_id')."' AND senduser_id > 20000");
        }else{
        	$this->db->query("UPDATE mission_transfer SET trans_status = 2 WHERE mission_id = '".$this->input->post('get_mission_id')."' AND 
             trans_status = 1 AND senduser_id = '".$this->flexi_auth->get_user_id()."'");
        }
	    }
	    $this->db->trans_complete();
	    redirect('jad_mission/history_missions_search');	
		}
	}
	/**
	 * 提交商品退货信息
	 */
	function finish_return(){
		$returnTime = date('Y-m-d H:i:s',time());
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'add_merch_price', 'label' => '价格:', 'rules' => 'required|numeric')
		);
		$this->form_validation->set_rules($validation_rules);
		// Run the validation.
		if ($this->form_validation->run())
		{	
			//保存退货信息
			$return_data = array(
				'merch_id' => $this->input->post('get_merch_id'),
				'mission_id' => $this->input->post('get_mission_id'),
				'return_price' => $this->input->post('add_merch_price'),
				'monetary_unit' => $this->input->post('select_merch_unit'),
				'return_time' => $returnTime
			);
			$this->db->trans_start();	
			//完成邮寄接收的操作
			if ($this->db->insert('info_return', $return_data)){
				$this->received_mail($this->input->post('get_trans_id'));
			}
			$this->db->trans_complete();	
	    redirect('jad_mission/history_mail_search');	
		}
		$this->data['message'] = validation_errors('<p class="error_msg">', '</p>');
		return FALSE;
	}
	/**
	 * finish_mail
	 * 提交邮寄商品信息，更新任务状态信息.
	 * 需要判断是否是一个现货任务的开始
	 * 判断是否是一个出库的操作
	 */
	function finish_mail($transId,$mailPrice){
		$this->load->library('form_validation');
		$validation_rules = array(
			array('field' => 'select_courier', 'label' => '物流公司:', 'rules' => 'required'),
			array('field' => 'add_mail_tracking', 'label' => '快递单号:', 'rules' => 'required')
			//array('field' => 'add_mail_price', 'label' => '邮寄费用:', 'rules' => 'required')
		);
		$this->form_validation->set_rules($validation_rules);
		if ($this->form_validation->run())
		{	
			//操作一：在数据库中新增商品邮寄详细信息***********************
			$mail_data = array(
				'trans_id' => $transId,
				'mail_time' => date('Y-m-d H:i:s',time()),
				'mail_courier' => $this->input->post('select_courier'),
				'mail_tracking' => $this->input->post('add_mail_tracking'),
				'monetary_unit' => $this->input->post('select_merch_unit'),
				'mail_fee' => $mailPrice
			);
			$this->db->trans_start();	
	    if ($this->db->insert('info_mail', $mail_data)){
	      //操作二：设置当前邮寄任务状态为：正在寄出***********
	      
		    $profile_data = array(
				   'trans_status' => 3,
				   'trans_sendtime' => date('Y-m-d H:i:s',time())
		    );		
		    $this->db->where('trans_id', $transId);
		    $this->db->update('mission_transfer', $profile_data);
        
		    //获取当前邮寄任务的详细信息
		    $transInfo = $this->db->get_where('mission_transfer',array('trans_id' => $transId))->row_array();
		    //获取当前邮寄任务对应的主任务信息
		    $missionId = $transInfo['mission_id'];
		    
		    //判断当前邮寄任务是否是调、现货、退货任务中的第一条邮寄信息，即ID号最小的邮寄任务*************************************
		    //触发非购买任务的主任务状态
		    $this->db->select('*');
		    $this->db->from('info_mission');
		    $this->db->join('mission_transfer','mission_transfer.mission_id = info_mission.mission_id');
		    $where = "(mission_type = 3 or mission_type = 4 or mission_type = 5) and mission_transfer.mission_id = '".$missionId."'";
		    $this->db->where($where);
		    $this->db->order_by("trans_id");//默认为升序，降序必须加上desc
		    $this->db->limit(1);
		    $queryTransInfo = $this->db->get()->row_array();
		    $queryTransId = $queryTransInfo['trans_id'];
		    if( $queryTransId == $transId){
		    	
		    	//操作三：更新当前邮寄任务所对应主任务的任务状态为：正在进行中
		      $profile_mission_data = array('mission_status' => 2);		
		      $this->db->where('mission_id', $missionId);
		      $this->db->update('info_mission', $profile_mission_data);
		      
		      //操作四：出库操作
		      $queryInven = $this->db->query("SELECT * FROM info_inventory WHERE inven_expired = 0 and inven_out_time is null and merch_id ='".$queryTransInfo['merch_pre_id']."'")->row_array();
		      $profile_inven_data = array('inven_out_time' => date('Y-m-d H:i:s',time()));	
		      $this->db->where('inven_id', $queryInven['inven_id']);
		      $this->db->update('info_inventory', $profile_inven_data);		      
		    }  
	    }
	    $this->db->trans_complete();	
		}
	}
	
	/**
	 * end_terminated_mission
	 * 对于主任务已经终止，但尚未邮寄的商品，采取直接本地入库的处理。
	 * 邮寄对象处理邮寄任务，更新任务状态信息. 
	 * 参数：邮寄任务ID
	 * 涉及到入库的操作，当任务类型为“囤货”时，表示入库
	 */
	function end_terminated_mission($transId){

		$this->db->trans_start();	
		//操作一：设置当前邮寄任务的任务状态为'终止'，并且使该主任务下其他状态为1的邮寄任务为终止
		$profile_data = array(
		       'trans_status' => 5
		);		
		$this->db->where('trans_id', $transId);
		$this->db->update('mission_transfer', $profile_data); 
		
		$transInfo =  $this->db->get_where('mission_transfer',array('trans_id' => $transId))->row_array();
		$trans_data = array(
		       'trans_status' => 5
		);		
		$this->db->where('mission_id', $transInfo['mission_id']);
		$this->db->where('trans_status', 1);
		$this->db->update('mission_transfer', $trans_data); 		

	  //分店主才需要一下入库操作
	  
	  if ($this->jad_global_model->is_in_group($this->flexi_auth->get_user_id(),2)){
		  //获取该邮寄任务ID下的主任务号和接收对象ID
	    //$mail_data = $this->jad_global_model->get_transfer_info_by_id($transId);
	    $mail_data = $this->db->get_where('mission_transfer',array('trans_id' => $tId))->row_array();
 	    //获取当前邮寄任务对应的主任务的任务信息
	    $mission_data = $this->db->get_where('info_mission',array('mission_id' => $mail_data['mission_id']))->row_array();
	    //操作三：只要库存表中存在有该货物的信息，需要将该库存信息置过期*************************
		  $profile_inven_data = array('inven_expired' => 1);
		  $where = "inven_out_time is not null and merch_id = '".$mission_data['merch_pre_id']."'";	
		  $this->db->where($where);		
		  $this->db->update('info_inventory', $profile_inven_data);	  		
	    //获取员工的分店信息
	    $branch_data = $this->db->get_where('info_branch',array('upro_id' => $this->flexi_auth->get_user_id()))->row_array();
	    //操作四:入库操作***********************************
      $data = array(
           'merch_id' => $mission_data['merch_pre_id'] ,
           'branch_id' => $branch_data['branch_id'] ,
           'inven_in_time' => date('Y-m-d H:i:s',time()),
           'inven_expired' => 0
      );
      $this->db->insert('info_inventory', $data); 	
    }
		$this->db->trans_complete();
	}	
	
	/**
	 * end_return_mission
	 * 对于买手无法退货的退货任务，采取直接本地入库的处理。
	 * 参数：邮寄任务ID
	 * 
	 */
	function end_return_mission($transId){

		$this->db->trans_start();	
		//操作一：设置当前邮寄任务的任务状态为'终止'，并且使该主任务状态为终止
		$profile_data = array(
		       'trans_status' => 5
		);		
		$this->db->where('trans_id', $transId);
		$this->db->update('mission_transfer', $profile_data); 
		$transInfo =  $this->db->get_where('mission_transfer',array('trans_id' => $transId))->row_array();
		
		$mission_data = array(
		       'mission_status' => 4,
		       'mission_end_time' => date('Y-m-d H:i:s',time()),
		);		
		$this->db->where('mission_id', $transInfo['mission_id']);
		$this->db->update('info_mission', $mission_data); 		
	
		$this->db->trans_complete();
	}		
	
	/**
	 * received_mail
	 * 邮寄对象处理邮寄任务，更新任务状态信息. 
	 * 参数：邮寄任务ID
	 * 涉及到入库的操作，当任务类型为“囤货”时，表示入库
	 */
	function received_mail($transId){
    $currentTime = date('Y-m-d H:i:s',time());
		$this->db->trans_start();	
		
		//操作一：设置当前邮寄任务的任务状态为'已经签收'
		$profile_data = array(
		       'trans_status' => 4,
				   'trans_rectime' => $currentTime
		);		
		$this->db->where('trans_id', $transId);
		$this->db->update('mission_transfer', $profile_data); 
		
		//获取该邮寄任务ID下的主任务号和接收对象ID
	  $mail_data = $this->db->get_where('mission_transfer',array('trans_id' => $transId))->row_array();
	  $profile_mail_data = array('trans_status' => 1,'senduser_id' => $mail_data['recuser_id'],'mission_id' => $mail_data['mission_id']);		 
	  if ($this->db->get_where('mission_transfer',$profile_mail_data)->num_rows()==0){
	  	//最后一条邮寄任务的相关操作**************************************************
	  	
	  	//获取当前邮寄任务对应的主任务的任务信息
	  	$mission_data = $this->db->get_where('info_mission',array('mission_id' => $mail_data['mission_id']))->row_array();
      
      //操作二：设置主任务为完成状态*************************************************      
      $profile_mission_data = array(
        'mission_status' => 3,
        'mission_end_time' => $currentTime
      );
      $this->db->where('mission_id', $mission_data['mission_id']);
      //若没有邮寄人，则是买手直接退货的场景，则可以不管主任务的状态
      if (!empty($mail_data['trans_sendtime']))
         $this->db->where('mission_status', 2);
		  $this->db->update('info_mission', $profile_mission_data);	 	  	
	  	
	  	//入库操作判断：囤货、调货、邮寄给分店的退货或者“主任务被终止且寄往分店”需要入库操作
	  	if ( ($mission_data['mission_status']==4 && $this->jad_global_model->is_in_group($mail_data['recuser_id'],2))||($mission_data['mission_status']!=4 && 
	  	       ($mission_data['mission_type']==2 || $mission_data['mission_type']==5 || ( $mission_data['mission_type']==4 && $mail_data['recuser_id'] < 10000 )))){
	 
	  		//操作三：只要库存表中存在有该货物的信息，需要将该库存信息置过期*************************
		    $profile_inven_data = array('inven_expired' => 1);
		    $where = "inven_out_time is not null and merch_id = '".$mission_data['merch_pre_id']."'";	
		    $this->db->where($where);		
		    $this->db->update('info_inventory', $profile_inven_data);	  		
	  		
	  		//获取员工的分店信息
	  		$branch_data = $this->db->get_where('info_branch',array('upro_id' => $this->flexi_auth->get_user_id()))->row_array();
	  		
	  		//操作四:入库操作***********************************
        $data = array(
               'merch_id' => $mission_data['merch_pre_id'] ,
               'branch_id' => $branch_data['branch_id'] ,
               'inven_in_time' => $currentTime,
               'inven_expired' => 0
        );
        $this->db->insert('info_inventory', $data); 	
	  	}
	  }else{
	  	//操作五：激活最近的下一个邮寄任务的状态,防止在激活那些需中转多次的相同邮递。
	  	$this->db->select('trans_id');
	  	$this->db->from('mission_transfer');
	  	$this->db->where('senduser_id', $mail_data['recuser_id']);
	  	$this->db->where('mission_id', $mail_data['mission_id']);	  	
	  	$this->db->where('trans_status', 1);	
	  	$this->db->order_by('trans_id');
	  	$this->db->limit(1);
	  	$queryInvenInfo = $this->db->get()->row_array();
	  	
      $profile_mail_new_data = array('trans_status' => 2);			  	
	  	$this->db->where('trans_id', $queryInvenInfo['trans_id']);
	  	$this->db->update('mission_transfer', $profile_mail_new_data); 	
	  }	
		$this->db->trans_complete();
	}
	/**
	 * 
	 * 搜索任务. 
	 */
  function search_missions(){
  	
		// Get url for any search query or pagination position.
		$uri = $this->uri->uri_to_assoc(3);     
		// Set pagination limit, get current position and get total users.
		$limit = $this->config->item('pag_limit');
		$offset = (isset($uri['page'])) ? $uri['page'] : FALSE;	
		

		if (array_key_exists('search', $uri)){
			$pagination_url = 'jad_mission/missions_search/search/'.$uri['search'].'/';
			$config['uri_segment'] = 6; // Changing to 6 will select the 6th segment, example 'controller/function/search/query/page/10'.
	    $search_query = str_replace('-',' ',urldecode($uri['search']));
		  	  
			$this->db->select('*');
      $this->db->from('info_mission');
	    $this->db->where('mission_status', $search_query);
	    $this->db->order_by("mission_time", "desc");   
      $this->db->limit($limit, $offset);
      $query = $this->db->get();			
			
			$this->db->select('*');
      $this->db->from('info_mission');
	    $this->db->where('mission_status', $search_query);
		  $total_missions = $this->db->get()->num_rows();
	    $this->data['s_mission'] = $query->result_array();
	  }else{
			$pagination_url = 'jad_mission/missions_search/';
			$search_query = 0;
			$config['uri_segment'] = 4;
			
			$this->db->select('*');
      $this->db->from('info_mission');
	    $this->db->order_by("mission_time", "desc");  
      $this->db->limit($limit, $offset);    
	    $this->data['s_mission'] = $this->db->get()->result_array();	 

			$this->db->select('*');
      $this->db->from('info_mission');
	    $total_missions = $this->db->get()->num_rows();			  	
		}
		// Create user record pagination.
		$this->load->library('pagination');	
		$config['base_url'] = base_url().'index.php/'.$pagination_url.'page/';
		$config['total_rows'] = $total_missions;
		$config['per_page'] = $limit; 
		$this->pagination->initialize($config); 
		
		// Make search query and pagination data available to view.
		$this->data['search_query'] = $search_query; // Populates search input field in view.
		$this->data['pagination']['links'] = $this->pagination->create_links();
		$this->data['pagination']['total_missions'] = $total_missions;
  }
	/**
	 * 
	 * 根据主任务ID，给出相关任务信息。 
	 */
  function mission_details($missionId){
  	//获取该任务号下的商品预编码ID以及任务类型
  	$query = $this->db->get_where('info_mission',array('mission_id' => $missionId))->row_array();
  	$mType = $query['mission_type'];//任务类型
  	$this->data['m_type'] = $mType;
  	$goodsBarcode = substr($query['merch_pre_id'],0,8);
  	//获取商品信息
  	$this->data['infoGoods'] = $this->db->get_where('goodssecondinfoview',array( 'goods_barcode' => $goodsBarcode ))->row_array();
    
    //如果任务类型为代购或者囤货，则存在买手信息**********************************************************************
    if ( $mType==1 || $mType == 2 ){
    	
  	  //获取购买信息,包括买手信息
  	  $this->data['infoPurchase'] = $this->db->get_where('merchpurchaseinfoview',array( 'mission_id' => $missionId ))->row_array();
  	  if (count($this->data['infoPurchase']) == 0){
  	    $this->db->select('*'); 
  	    $this->db->from('mission_purchase');
	      $this->db->join('jadiiar_user_profiles','jadiiar_user_profiles.upro_id = mission_purchase.upro_id');
		    $this->db->where('mission_id', $missionId);
  	    $this->data['infoPurchase'] = $this->db->get()->row_array();
  	  }
    }
    //任何任务都存在邮寄信息
  	$this->db->select('*'); 
  	$this->db->from('mission_transfer');
	  $this->db->join('info_mail','info_mail.trans_id = mission_transfer.trans_id','left');
		$this->db->where('mission_id', $missionId);  	
  	$this->data['infoMail'] = $this->db->get()->result_array();

  	//如果任务类型为代购或者现货，则存在客户信息********************************************************************** 
    if ( $mType == 1 || $mType == 3 ){
    	//可以直接从订购表中取出客户信息
    	$this->db->select('client_message'); 
    	$this->db->from('info_order');
	    $this->db->join('info_client','info_client.client_id = info_order.client_id'); 
    	$this->db->where('merch_id', $query['merch_pre_id']);
    	//考虑到去重
    	$this->db->order_by('order_id','desc');
    	$this->db->limit(1);
  	  $this->data['infoClient'] = $this->db->get()->row_array();
  	}
  }
  
	/**
	 * 
	 * 根据主任务ID，终止该任务。 
	 */
  function terminate_mission($missionId){ 
  	//获取主任务的执行状态
  	$missionInfo = $this->db->get_where('info_mission',array('mission_id' => $missionId))->row_array();
  	//若主任务尚未开始执行，则删除该主任务信息，相关的购买和邮寄信息会在数据库中自动删除
  	//注意：此处没有采用更新状态的方式，因为有可能是客户下达了错误信息造成。
  	if ( $missionInfo['mission_status'] == 1){
  		$this->db->delete('info_mission', array('mission_id' => $missionId)); 
  	}
  	//若主任务已经开始执行，代表已经开始购买了，则更新主任务的终止状态,trigger更新transfer表中状态为1的非第一条任务
   	if ( $missionInfo['mission_status'] == 2){
      $data = array(
        'mission_status' => 4,
        'mission_end_time' => date('Y-m-d H:i:s',time())
      );
      $this->db->where('mission_id', $missionId);
      $this->db->update('info_mission', $data); 
      $qNum = $this->db->get_where('mission_transfer',array('mission_id'=> $missionId ,'trans_status' => 1))->num_rows();
      if($qNum > 2||$qNum == 2){
      	$qNum--;
        $data_new = array(
         'trans_status' => 5
        );
        $this->db->where('mission_id', $missionId);
        $this->db->where('trans_status', 1);
        $this->db->order_by('trans_id','DESC');
        $this->db->limit($qNum);
        $this->db->update('mission_transfer', $data_new);      	
      }
  	} 	
	  redirect('jad_mission/missions_search');	
  }
	/**
	 * 
	 * 根据主任务ID，删除该主任务
	 */  
  function end_purchase_mission($missionId){ 
  	$this->db->delete('info_mission', array('mission_id' => $missionId));  
	  redirect('jad_mission/manage_current_missions');	
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