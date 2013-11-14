<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>下达代购或囤货任务</title>
<?php $this->load->view('includes/head'); ?> 
</head>

<body style="text-align:left;">
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
        <h2>下达新任务</h2>
        
				<?php echo form_open(current_url(), array('onsubmit' => 'return submitTest()'));?> 
					<fieldset>
						<legend>购买任务信息</legend>
						<ul>
             <li>
								<label for="mission_type">请选择任务类型:</label>
								<select id="mission_type" name="select_mission_type"  onchange="typeChange(this);" >
									<option value="1" selected >代购任务</option>
							    <option value="2">囤货任务</option>
								</select>
							<input type="hidden" id="input_m_type" name="input_mission_type" value = "1" />
             </li>
						
							
             <li>
								<label for="mission_buyer">请选择买手:</label>
								<select id="mission_buyer" name="select_mission_buyer" >
									<option value="">请选择买手</option>
									
								<?php foreach($buyers as $buyer) { ?>
									<option value="<?php echo $buyer['upro_id'];?>">
										<?php echo $buyer['upro_first_name'].$buyer['upro_last_name'];?>
									</option>
								<?php } ?>
								</select>
             </li>
             <li>
								<?php if (!isset($goodCode)) {?>
								<label for="mission_seriesnum">请选择商品货号:</label>
								<select id="mission_seriesnum" name="select_mission_seriesnum">
								<?php foreach($goods as $good) { ?>
									<option value="<?php echo $good['goods_seriesnum'];?>">
										<?php echo $good['goods_seriesnum'];?>
									</option>
								<?php } ?>
								</select>
								<?php }else{?>
								<label for="mission_seriesnum">商品货号:</label>
								<label for="mission_seriesnum"><?php echo substr($goodCode,0,10); ?></label>
								<input type="hidden" id="mission_seriesnum" name="select_mission_seriesnum" value="<?php echo substr($goodCode,0,10); ?>"  />
								<?php }?>
             </li>
             <li>
             	<?php if (!isset($goodCode)) {?>
								<div class="ui-widget" >
                <label for="mission_colour" >商品颜色: </label>
                    <input id="mission_colour" name="select_mission_colour" value="<?php echo set_value('select_mission_colour');?>" />
                </div>
              <?php }else{?>
								<label for="mission_colour">商品颜色:</label>
								<label for="mission_colour"><?php foreach($colour as $coo){ 
									          if ($coo[1] == substr($goodCode,10,3)) echo $coo[0];
									        }
									  ?></label>
								<input type="hidden" id="mission_colour" name="select_mission_colour" value="<?php echo substr($goodCode,10,3); ?>"  />
              <?php }?>
             </li>
             <li>
             	 <?php if (!isset($goodCode)) {?>
								<div class="ui-widget">
                <label for="mission_size" >商品尺码: </label>
                    <input id="mission_size" name="select_mission_size"/>
                </div>
               <?php }else{?>
								<label for="mission_size">商品尺码:</label>
								<label for="mission_size"><?php foreach($size as $soo){ 
									          if ($soo[1] == substr($goodCode,13,3)) echo $soo[0];
									        }
									  ?></label>
								<input type="hidden" id="mission_size" name="select_mission_size" value="<?php echo substr($goodCode,13,3); ?>"  />
               <?php }?>
             </li>
             
             <li>
             	 <?php if (!isset($goodCode)) {?>
								<div class="ui-widget">
                <label for="mission_s_desc" >商品详细描述: </label>
                		<textarea id="mission_s_desc" name="input_mission_s_desc" class="width_400 tooltip_trigger"
									  title="商品详细描述"><?php echo set_value('input_mission_s_desc');?></textarea>
                 
                </div>
               <?php }else{?>
								<label for="mission_s_desc">商品详细描述:</label>   
								<label for="mission_s_desc"><?php echo $goods_s_desc; ?></label>
								<input type="hidden" id="mission_s_desc" name="input_mission_s_desc" value="<?php echo $goods_s_desc; ?>"  />
               <?php }?>
             </li> 
						</ul>
					</fieldset>
					
					<fieldset>						
						<legend>任务信息</legend>
						  <ul>
							<li>
								<div class="ui-widget">
								<label for="mission_desc">任务要求:</label>
								<textarea id="mission_desc" name="add_mission_desc" class="width_400 tooltip_trigger"
									title="备注信息"><?php echo set_value('add_mission_desc');?></textarea>
							  </div>
							</li>
						  </ul>
					</fieldset>
					<fieldset id="client_info">						
						<legend>订购客户信息</legend>
						  <ul>
						  	<li><div class="ui-widget">
						  		 <label for="client_name">客户名称:</label>
						  		 <input type="text" id="client_name" name="add_client_name" value="<?php echo set_value('add_client_name');?>"/>
						  	</div>
						  	</li>
							  <li>
								  <div class="ui-widget">
								  <label for="mission_client">联系方式:</label>
								  <textarea id="mission_client" name="add_mission_client" class="width_400 tooltip_trigger"
									  title="客户联系方式"><?php echo set_value('add_mission_client');?></textarea>
							    </div>
							  </li>
							<li>
								<label for="order_price">价格:</label>
								<input type="text" id="order_price" name="add_order_price" value="<?php echo set_value('add_order_price');?>"/>
								<label for="merch_unit">货币单位:
								<select id="merch_unit" name="select_merch_unit" >
								<?php foreach($monetary_unit as $mu) { ?>
									<option value="<?php echo $mu[1];?>">
										<?php echo $mu[0];?>
									</option>
								<?php } ?>
								</select>
							  </label>
							</li>
						  </ul>
					</fieldset>
					
					<fieldset>						
						<legend>物流信息</legend>
						  <ul>
						  	<li id="add_link">
								  <label>新增物流条目</label>
								  发货人:
								  <select id="mission_sender_type" name="select_mission_sender_type" onchange="sendChange(this);" disabled="disabled">
								  	  <option value="1">当前买手</option>
								  	  <option value="2">分店负责人</option>
								  	  <option value="3">其它买手</option>
								  </select>
								  <select id="mission_sender_b" name="select_mission_sender_b" style="display:none" >
								  	  <?php foreach($mail_branch_users as $b_user) { ?>
									        <option value="<?php echo $b_user['upro_id'];?>">
										         <?php echo $b_user['branch_name'].' '.$b_user['upro_first_name'].$b_user['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  <select id="mission_sender_buy" name="select_mission_sender_buy" style="display:none" >
								  	  <?php foreach($buyers as $buyer) { ?>
									        <option value="<?php echo $buyer['upro_id'];?>">
										         <?php echo $buyer['upro_first_name'].$buyer['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  
								  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 接收对象:
								  <select id="mission_receiver_type" name="select_mission_receiver" onchange="receiverChange(this);">
								  	<option value="1">当前客户</option>
							      <option value="2">分店负责人</option>
								  	<option value="3">其它买手</option>
								  </select>
								  <select id="mission_receiver_b" name="select_mission_receiver_b" style="display:none" >
								  	  <?php foreach($mail_branch_users as $b_user) { ?>
									        <option value="<?php echo $b_user['upro_id'];?>" >
										         <?php echo $b_user['branch_name'].' '.$b_user['upro_first_name'].$b_user['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  <select id="mission_receiver_buy" name="select_mission_receiver_buy" style="display:none" >
								  	  <?php foreach($buyers as $buyer) { ?>
									        <option value="<?php echo $buyer['upro_id'];?>">
										         <?php echo $buyer['upro_first_name'].$buyer['upro_last_name'];?>
									        </option>
								      <?php } ?>
								  </select>
								  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
								  <a href = "#" onclick = "javascript:addMail();">增加</a>
						  	</li>
						  	
						  </ul>
						  
						  <ul id= "mail_info" style="background:#b1b1b1; padding:0px;">
						  </ul>
					</fieldset>
								<li>
								  <hr/>
								  <label for="submit">任务预览:</label>
								  <input type="submit" name="new_mission" id="new_mission_submit" value="预览" class="link_button large"/>
							  </li>
					<input type="hidden" name="isFromMissionPage" value="<?php echo isset($goodCode)? "no":"yes" ?>" />
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
  function jsSelectIsExitItem(objSelect, objItemValue) {        
    var isExit = false;        
    for (var i = 0; i < objSelect.options.length; i++) {        
        if (objSelect.options[i].value == objItemValue) {        
            isExit = true;        
            break;        
        }        
    }        
    return isExit;        
  }     
    function jsRemoveItemFromSelect(objSelect, objItemValue) {        
    //判断是否存在        
    if (jsSelectIsExitItem(objSelect, objItemValue)) {        
        for (var i = 0; i < objSelect.options.length; i++) {        
            if (objSelect.options[i].value == objItemValue) {        
                objSelect.options.remove(i);        
                break;        
            }        
        }        
        //alert("成功删除");        
    } else {        
        //alert("该select中 不存在该项");        
    }        
  } 
   function jsAddItemToSelect(objSelect, objItemText, objItemValue) {        
    //判断是否存在        
    if (jsSelectIsExitItem(objSelect, objItemValue)) {        
        //alert("该Item的Value值已经存在");        
    } else {        
        var varItem = new Option(objItemText, objItemValue);      
        objSelect.options.add(varItem);     
        //成功加入,还需要设置选中
        document.getElementById("mission_receiver_type").value = objItemValue;
             
    }        
   } 
//响应任务类型的SELECT事件，当类型为囤货时，删掉“当前客户”的选项；否则增加
  function typeChange(obj){
  	var selected_mission_type=document.getElementById("mission_type").value; 
  	if (selected_mission_type==2){
  		document.getElementById("input_m_type").value = 2;
  		//囤货任务，去掉选项中当前客户那项；
  		jsRemoveItemFromSelect(document.getElementById("mission_receiver_type"),"1");
  		document.getElementById("client_info").style.display='none';
  		//document.getElementById("client_info").value='无';
  		//显示分店主的接收选择
  		document.getElementById("mission_receiver_b").style.display='';  
  	}else{
  		document.getElementById("input_m_type").value = 1;
  		jsAddItemToSelect(document.getElementById("mission_receiver_type"), "当前客户", "1");
  		document.getElementById("client_info").style.display='';
  		//隐藏分店选择接收
  		document.getElementById("mission_receiver_b").style.display='none'; 		
  	}
  }
  
  	
  //响应select事件，并立即刷新页面，返回查询结果
  function sendChange(obj){
  	
  	var mission_sender_b=document.getElementById("mission_sender_b");  
  	var mission_sender_buy=document.getElementById("mission_sender_buy"); 
  	
  	if(obj.options[obj.selectedIndex].value == 1){
  		mission_sender_b.style.display='none';
  		mission_sender_buy.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 2){
  		mission_sender_b.style.display='';
  		mission_sender_buy.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 3){
  		mission_sender_b.style.display='none';
  		mission_sender_buy.style.display='';
  	}
  }  	
  //响应select事件，并立即刷新页面，返回查询结果
  function receiverChange(obj){
  	
  	var mission_receiver_b=document.getElementById("mission_receiver_b");  
  	var mission_receiver_buy=document.getElementById("mission_receiver_buy"); 
  	
  	if(obj.options[obj.selectedIndex].value == 1){
  		mission_receiver_b.style.display='none';
  		mission_receiver_buy.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 2){
  		mission_receiver_b.style.display='';
  		mission_receiver_buy.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 3){
  		mission_receiver_b.style.display='none';
  		mission_receiver_buy.style.display='';
  	}
  }  	
  function delMail(obj){
  	var yy = obj.parentNode.id;	
  	var dd = document.getElementById("mail_info");
  	var t=dd.childNodes.length;
  	//当t==2，表示所有新增的li标签已经删除完毕；则可以打开任务类型选择的select
  	if(t==2){
  	  document.getElementById("mission_type").disabled = false;
  	}  	
  	for (var i=0;i<t;i++){
  		if ( yy == dd.childNodes[i].id){
  			if(dd.childNodes[i].innerHTML.indexOf('888') != -1)
  			   document.getElementById("add_link").style.display='';
  			dd.removeChild(dd.childNodes[i]);
  			return;
  		}
  	}
  	
  }
  
  function addMail(){
  	
  	if (document.getElementById("mission_buyer").value==''){
  		alert("请先指定任务买手！");
  		return false;
  	}
  	var send_gr = '';
  	var send_user = '';
  	var send_id = '';
  	
  	var receiver_gr = '';
  	var receiver_user = '';
  	var receiver_id = '';
  	
  	var ms_type = document.getElementById("mission_sender_type");
  	if(ms_type.selectedIndex==0){
  		send_gr = '当前买手';
  		send_user = document.getElementById("mission_buyer").options[document.getElementById("mission_buyer").selectedIndex].text;
  		send_id = document.getElementById("mission_buyer").value;
  	}else{
  		send_gr = ms_type.options[ms_type.selectedIndex].text;
  		if (ms_type.selectedIndex==1){
  			send_user = document.getElementById("mission_sender_b").options[document.getElementById("mission_sender_b").selectedIndex].text;
  		  send_id = document.getElementById("mission_sender_b").value;
  		}else{
  			send_user = document.getElementById("mission_sender_buy").options[document.getElementById("mission_sender_buy").selectedIndex].text;
  		  send_id = document.getElementById("mission_sender_buy").value;
  		}
    }
  	var mr_type = document.getElementById("mission_receiver_type");
  	if(mr_type.value==1){
  		receiver_gr = '当前客户';
  		receiver_user = '待定';
  		receiver_id = 888;
  	}else{
  		receiver_gr = mr_type.options[mr_type.selectedIndex].text;
  		if (mr_type.value==2){
  			receiver_user = document.getElementById("mission_receiver_b").options[document.getElementById("mission_receiver_b").selectedIndex].text;
  		  receiver_id = document.getElementById("mission_receiver_b").value;
  		}else{
  			receiver_user = document.getElementById("mission_receiver_buy").options[document.getElementById("mission_receiver_buy").selectedIndex].text;
  		  receiver_id = document.getElementById("mission_receiver_buy").value;
  		}
    }
    //如果自己给自己邮寄，报错
    if ( receiver_id == send_id && send_gr == receiver_gr ){
    	alert("发送人和接收人不能相同");
    	return false;
    }
   var str = '<label>物流线路</label>邮寄人：'+' '+send_gr+' '+send_user+'<input type="hidden" name="mail_sid[]" value="'+send_id+'" /> &nbsp&nbsp&nbsp&nbsp&nbsp 接收对象：'+' '+receiver_gr+' '+receiver_user+'<input type="hidden" name="mail_rid[]" value="'+receiver_id+'" />'+'  '+'<a href = "#" onclick = "javascript:delMail(this);">删除</a>';

   var s=document.getElementById("mail_info");

   var li= document.createElement("li");
   li.id = send_id;
   li.style = "background:#e0e0e0;";
   li.innerHTML=str;
   s.appendChild(li);
   //当增加物流信息时，锁定任务类型信息
   document.getElementById("mission_type").disabled = true;
  	//级联锁定，确保接收人一定为下一次的发货人
  	document.getElementById("mission_sender_type").value = document.getElementById("mission_receiver_type").value;
  	document.getElementById("mission_sender_b").value = document.getElementById("mission_receiver_b").value;  
  	document.getElementById("mission_sender_buy").value = document.getElementById("mission_receiver_buy").value;  
  	//如果增加的接收对象为当前客户，说明物流线路设置完毕，隐藏增加链接
  	if(document.getElementById("mission_receiver_type").value==1){
  		document.getElementById("add_link").style.display='none';
  	}

  }  	
  function submitTest(){
  	//如果当前为代购任务，则检查是否最终的退货人是客户
  	if (( document.getElementById("mail_info").innerHTML.indexOf('当前客户') == -1 )&&document.getElementById("mission_type").value == 1 ){
  		 alert("请指定当前客户为最终接收人！");
  		 return false;
  	} 	
  	//对尚未输入物流线路信息进行判断
  	var s=document.getElementById("mail_info");
    lis = s.getElementsByTagName('li');
    if(lis.length==0){
    	alert("请设置物流信息！");
    	return false;
    }
    <?php if (!isset($goodCode)) {?>  
  	//在提交时，对输入错误颜色信息和尺码信息的判断
  	var arrcolour = new Array();
  	var arrsize = new Array();
  	<?php foreach($colour as $coo){ ?>
  	arrcolour.push(<?php echo '"'.$coo[1].'"'; ?>);
    <?php } ?>	
  	<?php foreach($size as $soo){ ?>
  	arrsize.push(<?php echo '"'.$soo[1].'"'; ?>);
    <?php } ?>	  		
  	if (jQuery.inArray(document.getElementById("mission_colour").value, arrcolour) == -1 || jQuery.inArray(document.getElementById("mission_size").value, arrsize) == -1){
  		alert("请重新选择正确的颜色和尺码信息！");
  	  return false;
  	}else 
  		return true;
  	<?php }?>  	
  }	 
   	
  <?php if (!isset($goodCode)) {?>  	
  $(function() {
    var availableColours = [
		<?php foreach($colour as $key => $col) { 
			    if ($key == (count($colour)-1) ) 
			       echo '{ label: "'.$col[0].'", value: "'.$col[1].'" }'; 
		      else 
		         echo '{ label: "'.$col[0].'", value: "'.$col[1].'" },'; 	    
			    
		} ?>    
    ];
    var availableSizes = [
		<?php foreach($size as $key => $si) { 
          if ($key == (count($size)-1) ) 
              echo '{ label: "'.$si[0].'", value: "'.$si[1].'" }'; 							
		      else
		          echo '{ label: "'.$si[0].'", value: "'.$si[1].'" },'; 	
		} ?>    
    ];
    $( "#mission_colour" ).autocomplete({
      source: availableColours
    });
    $( "#mission_size" ).autocomplete({
      source: availableSizes
    });
  });
  
  <?php } ?>  
</script>
</body>
</html>