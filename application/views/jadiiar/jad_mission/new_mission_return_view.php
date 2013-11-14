<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>下达退货任务</title>
<?php $this->load->view('includes/head'); ?> 
</head>
<body>
<div id="body_wrap">
<!-- Header -->  
<?php $this->load->view('includes/header'); ?> 
<!-- Demo Navigation -->
<?php $this->load->view('includes/jad_header'); ?> 
<!-- Main Content -->              
<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
        <h2>下达退货任务</h2>
        
				<?php echo form_open(current_url(), array('onsubmit' => 'return submitTest()'));?> 
					<fieldset>

						<legend>商品信息</legend>
						<ul>
             <li>
								<label>长编码:</label>
								<label><?php echo $info_merch['goods_code'];?></label>
								

								<label>唯一码:</label>
								<label><?php echo $info_merch['merch_id'];?></label>
								<input type="hidden" name="input_merch_id" value="<?php echo $info_merch['merch_id'];?>"> 
	           </li>
	           <label>价格:</label>
								<label><?php echo $info_merch['merch_price'];?></label>
	                    <hr/>
             <li>
								<label>商品品牌:</label>
								<label><?php echo $info_merch['goods_brand'];?></label>
								<label>商品类别:</label>
								<label><?php echo $info_merch['categ_name'];?></label>
             </li>
             <li>
								<label>货号:</label>
								<label><?php echo $info_merch['goods_seriesnum'];?></label>
								<label>短条码:</label>
								<label><?php echo $info_merch['goods_shortcode'];?></label>
             </li>
             <li>
								<label>基本描述:</label>
								<label><?php echo $info_merch['goods_desc'];?></label>
             </li>
             <hr/>

             <li>
								<label>条形码:</label>
								<label><?php echo $info_merch['goods_barcode'];?></label>
	           </li>
             <li>
								<label>详细描述:</label>
								<label><?php echo $info_merch['goods_s_desc'];?></label>
	           </li>
             </ul>
					</fieldset>
					<fieldset>						
						<legend>购买信息</legend>
						  <ul>
						  <li><label>买手姓名:</label>
								<label><?php echo $info_buyer['upro_first_name'].' '.$info_buyer['upro_last_name'];?></label>
							</li>						  
							<li><label>买手地址:</label>
								<label><?php echo $info_buyer['upro_address'];?></label>
							</li>
						  </ul>
					</fieldset>

					
          <?php if ( $merchExistLocation == 3 ){ ?>
					<fieldset id="client_info">						
						<legend>客户信息</legend>
						  <ul>
						<li><?php echo $clientInfo['client_message']; ?>
							  <input type="hidden" id="add_mission_client" name="add_mission_client" value ="<?php echo $clientInfo['client_message']; ?>" />
						    <input type="hidden" id="mission_client" name="set_mission_client" value ="<?php echo $clientInfo['client_id']; ?>" />
						</li>
						  </ul>
					</fieldset>
					<?php } ?>
					
					<fieldset>						
						<legend>物流信息</legend>
						  <ul>
						  	<li id="add_link">
								  <label>新增物流条目</label>
								  发货人:
								  <select id="mission_sender_type" name="select_mission_sender_type" onchange="sendChange(this);"  disabled="disabled" >
								  	  <option value="1" <?php if ( $merchExistLocation == 3 ) echo 'selected'; ?> >当前客户</option>
								  	  <option value="2" <?php if ( $merchExistLocation == 1 ) echo 'selected'; ?> >分店负责人</option>
								  	  <option value="3" <?php if ( $merchExistLocation == 5 ) echo 'selected'; ?> >买手</option>
								  </select>
								  <select id="mission_sender_buy" name="select_mission_sender_buy" <?php if(( $merchExistLocation == 3 )||( $merchExistLocation == 1 )) echo "style='display:none'";  ?>  disabled="disabled"  >
								  	  <?php foreach($buyers as $buyer) { ?>
									        <option value="<?php echo $buyer['upro_id'];?>" <?php if ( $buyerId == $buyer['upro_id'] ) echo 'selected';?> >
										         <?php echo $buyer['upro_first_name'].$buyer['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  <select id="mission_sender_b" name="select_mission_sender_b" <?php if(( $merchExistLocation == 3 )||( $merchExistLocation == 5 )) echo "style='display:none'";  ?> disabled="disabled" >
								  	  <?php foreach($mail_branch_users as $b_user){?>
									        <option value="<?php echo $b_user['upro_id'];?>" <?php if ( $branchId == $b_user['branch_id'] ) echo 'selected';?>>
										         <?php echo $b_user['branch_name'].' '.$b_user['upro_first_name'].$b_user['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  
								  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 接收对象:
								  <select id="mission_receiver_type" name="select_mission_receiver" onchange="receiverChange(this);" >
							      <option value="2" <?php if ( $merchExistLocation == 1 || $merchExistLocation == 5 ) echo "style='display:none'"; ?> >分店负责人</option>
							      
								  	<option value="3" <?php if ( $merchExistLocation == 3 || $merchExistLocation == 5 ) echo "style='display:none'"; ?> <?php if ( $merchExistLocation == 1  ) echo "selected"; ?> >买手</option>
								  	
								  	<option value="4" <?php if ( $merchExistLocation == 3 ) echo "style='display:none'";  ?> <?php if ( $merchExistLocation == 5  ) echo "selected"; ?> >供货商</option>
								  </select>
								  <select id="mission_receiver_b" name="select_mission_receiver_b" <?php if ( $merchExistLocation == 1 || $merchExistLocation == 5 ) echo "style='display:none'"; ?>  >
								  	  <?php foreach($mail_branch_users as $b_user) { ?>
									        <option value="<?php echo $b_user['upro_id'];?>">
										         <?php echo $b_user['branch_name'].' '.$b_user['upro_first_name'].$b_user['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  <select id="mission_receiver_buy" name="select_mission_receiver_buy" <?php if ( $merchExistLocation == 3 || $merchExistLocation == 5 ) echo "style='display:none'"; ?> >
								  	  <?php foreach($buyers as $buyer) { ?>
									        <option value="<?php echo $buyer['upro_id'];?>" <?php if ( $info_buyer['upro_id'] == $buyer['upro_id'] ) echo 'selected';?>>
										         <?php echo $buyer['upro_first_name'].$buyer['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  <select id="mission_receiver_sup" name="select_mission_receiver_sup" <?php if ( $merchExistLocation != 5 ) echo "style='display:none'"; ?> >
								  	  <?php foreach($mail_supplier as $msu) { ?>
									        <option value="<?php echo $msu['suppl_id'];?>" <?php if ($msu['suppl_id']==$supplId) echo 'selected'; ?> >
										         <?php echo $msu['suppl_alias'];?>
									        </option>
								      <?php } ?>
								  </select>
								  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
								  <a href = "#"  onclick = "javascript:addMail();">增加</a>
						  	</li>
						  	
						  </ul>
						  
						  <ul id= "mail_info" style="background:#b1b1b1; padding:0px;">
						  </ul>
					</fieldset>
								<li>
								  <hr/>
								  <label for="submit">任务预览:</label>
								  <input type="submit" name="new_mission_return" id="submit_return" value="预览" class="link_button large"/>
							  </li>					
				<?php echo form_close();?>
			</div>
		</div>
	</div>	
	
<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
  <script>
  function receiverChange(obj){
  	
  	var mission_receiver_b = document.getElementById("mission_receiver_b");  
  	var mission_receiver_buy = document.getElementById("mission_receiver_buy");
  	var mission_receiver_sup = document.getElementById("mission_receiver_sup");
  	
  	if(obj.options[obj.selectedIndex].value == 1){
  		mission_receiver_b.style.display='none';
  		mission_receiver_buy.style.display='none';
  		mission_receiver_sup.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 2){
  		mission_receiver_b.style.display='';
  		mission_receiver_buy.style.display='none';
  		mission_receiver_sup.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 3){
  		mission_receiver_b.style.display='none';
  		mission_receiver_buy.style.display='';
  		mission_receiver_sup.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 4){
  		mission_receiver_b.style.display='none';
  		mission_receiver_buy.style.display='none';
  		mission_receiver_sup.style.display='';
  	}
  }  
  //响应select事件
  function sendChange(obj){
  	
  	var mission_sender_b=document.getElementById("mission_sender_b");  
  	var mission_sender_buy=document.getElementById("mission_sender_buy"); 
  	

  	if(obj.options[obj.selectedIndex].value == 2){
  		mission_sender_b.style.display='';
  		mission_sender_buy.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 3){
  		mission_sender_b.style.display='none';
  		mission_sender_buy.style.display='';
  	}
  }    
  function addMail(){

  	//若是调货任务，则不用指定客户联系方式
  	//if (document.getElementById("mission_client").value=='' && document.getElementById("mission_type").value != 5 ){
  	//	alert("请先填写客户联系方式！");
  	//	return false;
  	//}
    var send_gr = '';
  	var send_user = '';
  	var send_id = '';
  	
  	var receiver_gr = '';
  	var receiver_user = '';
  	var receiver_id = '';

    send_user = document.getElementById("mission_sender_b").options[document.getElementById("mission_sender_b").selectedIndex].text;
  	send_id = document.getElementById("mission_sender_b").value;
  		
  	var ms_type = document.getElementById("mission_sender_type");

  	send_gr = ms_type.options[ms_type.selectedIndex].text;
  	
  	if (ms_type.value==1){
  		 send_user = '当前客户';
  		 send_id = document.getElementById("mission_client").value;
  	}
  	if (ms_type.value==2){
  		 send_user = document.getElementById("mission_sender_b").options[document.getElementById("mission_sender_b").selectedIndex].text;
  		 send_id = document.getElementById("mission_sender_b").value;
  	}
  	if (ms_type.value==3){
  		 send_user = document.getElementById("mission_sender_buy").options[document.getElementById("mission_sender_buy").selectedIndex].text;
  		 send_id = document.getElementById("mission_sender_buy").value;
  	}
     		
  	var mr_type = document.getElementById("mission_receiver_type");
    receiver_gr = mr_type.options[mr_type.selectedIndex].text;
  	if (mr_type.value==2){
  		 receiver_user = document.getElementById("mission_receiver_b").options[document.getElementById("mission_receiver_b").selectedIndex].text;
  		 receiver_id = document.getElementById("mission_receiver_b").value;
  	}
  	if (mr_type.value==3){
  		receiver_user = document.getElementById("mission_receiver_buy").options[document.getElementById("mission_receiver_buy").selectedIndex].text;
  		receiver_id = document.getElementById("mission_receiver_buy").value;
  	}
  	if (mr_type.value==4){
  		receiver_user = document.getElementById("mission_receiver_sup").options[document.getElementById("mission_receiver_sup").selectedIndex].text;
  		receiver_id = document.getElementById("mission_receiver_sup").value;
  	}
    
    //如果自己给自己邮寄，报错
    if ( receiver_id == send_id && send_gr == receiver_gr ){
    	alert("发送人和接收人不能相同");
    	return false;
    }
    //如果供应商与该商品的供应商不同时，会提醒。
    if ( receiver_id != <?php echo $supplId; ?> && mr_type.value==4){
    	alert("请注意：该供货商并不是此商品的供货商！");
    }   
    //如果退货买手与该商品的买手不同时，会提醒。
    if ( receiver_id != <?php echo $info_buyer['upro_id']; ?> && mr_type.value==3){
    	alert("请注意：该买手并不是此商品的购买者，请重选！");
    	return false;
    }    
  	var str = '<label>物流线路</label>邮寄人：'+' '+send_gr+' '+send_user+'<input type="hidden" name="mail_sid[]" value="'+send_id+'" /> &nbsp&nbsp&nbsp&nbsp&nbsp 接收对象：'+' '+receiver_gr+' '+receiver_user+'<input type="hidden" name="mail_rid[]" value="'+receiver_id+'" />'+'  '+'<a href = "#" onclick = "javascript:delMail(this);">删除</a>';
    var s=document.getElementById("mail_info");

    var li= document.createElement("li");
    li.id = send_id;
    li.style = "background:#e0e0e0;";
    li.innerHTML=str;
    s.appendChild(li);
    
    //如果增加的接收对象为供应商，说明物流线路设置完毕，隐藏增加链接
  	if(document.getElementById("mission_receiver_type").value==4){
  		document.getElementById("add_link").style.display='none';
  		return;
  	}
  	//级联锁定，确保接收人一定为下一次的发货人
  	document.getElementById("mission_sender_buy").value = document.getElementById("mission_receiver_buy").value;
  	document.getElementById("mission_sender_b").value = document.getElementById("mission_receiver_b").value;
  	document.getElementById("mission_sender_type").value = document.getElementById("mission_receiver_type").value;
  	
  	
  	
  	//如果当前任务为客户退货，则只让增加一次，隐藏增加链接
  	<?php if ( $merchExistLocation == 3 ){ ?>
  	if(document.getElementById("mail_info").childNodes.length==2){
  	  document.getElementById("add_link").style.display='none';
  	}
    <?php } ?>
  	
  	//根据选择的接收对象的类型，进行display判断
  	sendChange(document.getElementById("mission_receiver_type"));
  } 

    function delMail(obj){
  	var yy = obj.parentNode.id;	
  	var dd = document.getElementById("mail_info");
  	var t = dd.childNodes.length;
  	//由于没有指定商品拥有者为第一个发货人，固可解开选择分店发货人的锁定
  	//if(t==2){
  	  //document.getElementById("mission_sender_b").disabled = true;
  	//}
  	for (var i=0;i<t;i++){
  		if ( yy == dd.childNodes[i].id){
  			//若删除的条目包含供应商id，则显示add_link
  			//alert(dd.childNodes[i].innerHTML);
  			//alert(dd.childNodes[i].innerHTML.indexOf('供应商'));
  			if(dd.childNodes[i].innerHTML.indexOf('供货商') != -1)
  			   document.getElementById("add_link").style.display='';
  			dd.removeChild(dd.childNodes[i]);
  			//如果没有物流信息了，就显示物流信息设置
  			if(t==2){
  			  document.getElementById("add_link").style.display='';
  			  location.reload();
  			}
  			return;
  		}
  	}
  }
  
  function submitTest(){ 
  	
  	//如果当前任务为厂商退货，则检查是否最终的退货人是供货商
  	<?php if ( $merchExistLocation == 4 ){ ?>
  		
  		if (document.getElementById("mail_info").innerHTML.indexOf('供货商') == -1)
  		{
  		  alert("请指定供货商为最终接收人！");
  		  return false;
  		}
  		
    <?php } ?>
    
    
    
  	//根据”增加“链接的display属性来判断是否需要输入客户信息
  	if (document.getElementById("add_link").style.display == 'none' && document.getElementById("mission_client").value == ''){
  		alert("请填写客户信息！");
  	  return false;
  	}
  	 	 
  	//对尚未输入物流线路信息进行判断
  	var s=document.getElementById("mail_info");
    lis = s.getElementsByTagName('li');
    if(lis.length==0){
    	alert("请设置物流信息！");
    	return false;
    }	
    
  }

  </script>
</body>
</html>