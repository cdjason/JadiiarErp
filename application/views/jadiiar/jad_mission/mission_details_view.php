<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>任务情况</title>
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
        <h2>任务详情</h2>
        
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品详细信息</legend>
						<ul>
             <li>
								<label>商品品牌:</label>
								<label><?php echo $infoGoods['goods_brand'];?></label>
								<label>商品类别:</label>
								<label><?php echo $infoGoods['categ_name'];?></label>
             </li>
             <li>
								<label>商品短条码:</label>
								<label><?php echo $infoGoods['goods_shortcode'];?></label>
								<label>商品货号:</label>
								<label><?php echo $infoGoods['goods_seriesnum'];?></label>
             </li>
             <li>
								<label>基本描述:</label>
								<label><?php echo $infoGoods['goods_desc'];?></label>
             </li>
             <hr/>
             <li>
								<label>商品颜色:</label>
								<label><?php foreach($colour as $coo){ 
									          if ($coo[1] == substr($infoGoods['goods_code'],10,3)) echo $coo[0];
									        }
									  ?></label>
								<label>商品尺码:</label>
								<label><?php foreach($size as $soo){ 
									          if ($soo[1] == substr($infoGoods['goods_code'],13,3)) echo $soo[0];
									        }
									  ?></label>
             </li>
             <li>
								<label>商品条形码:</label>
								<label><?php echo $infoGoods['goods_barcode'];?></label>
								<label>商品长编码:</label>
								<label><?php echo $infoGoods['goods_code'];?></label>
             </li>
             <li>
								<label>详细描述:</label>
								<label><?php echo $infoGoods['goods_s_desc'];?></label>
	              <label>图片链接:</label>
								<label><a id="example6" href = "<?php echo $infoGoods['goods_image_url'];?>"><img title="<?php echo $infoGoods['goods_desc'];?>" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($infoGoods['goods_image_url']);?>" height="40px" width="60px" ></a></label>
						</li>
             <hr/>

             </ul>
          </fieldset> 
          <?php if ( $m_type==1 || $m_type == 2 ){ ?>
          <fieldset>
						<legend>购买任务信息</legend>
             <ul>
             <li>
								<label>买手姓名:</label>
								<label><?php echo $infoPurchase['upro_first_name'].' '.$infoPurchase['upro_last_name']; ?></label>
             </li>
             <li>
								<label>电话:</label>
								<label><?php echo $infoPurchase['upro_phone'];?></label>
             
								<label>地址:</label>
								<label><?php echo $infoPurchase['upro_address'];?></label>
             </li>
             <li>
								<label>购买状态:</label>
								<label>
								<?php foreach($pm_status as $pm) { 
									      if ( $pm[1] == $infoPurchase['purchase_status'] ) echo $pm[0];
								}?>
							  </label>
							  
								<label>购买时间:</label>
								<label><?php echo $infoPurchase['purchase_time'];?></label>
             </li>
             
             <li>
								<label>购买价格:</label>
								<label><?php if ($infoPurchase['purchase_status']==3 ) echo $infoPurchase['merch_price'].' '.$infoPurchase['monetary_unit'];?></label>
             
             </li>
             
             
						</ul>
					</fieldset>
					<?php } ?>
					
					<fieldset>
					<legend>物流信息</legend>
					<ul><?php $clientInfo='';
						        foreach($infoMail as $im){
						          if ($im['senduser_id'] > 20000 ){
						          		$senduser = $this->jad_global_model->get_supplier_info_by_sId($im['senduser_id']);
						          		echo "<li><label>邮寄人：".$senduser['suppl_alias'];
						          }
						        	if ($im['senduser_id'] > 10000 && $im['senduser_id'] < 20000){
						        		$senduser = $this->jad_global_model->get_clientinfo_by_clientid($im['senduser_id']);
						        		echo "<li><label>邮寄人：".$senduser['client_message'];
						        	}
						        	if ($im['senduser_id'] < 10000 ){
						        		$senduser = $this->flexi_auth->get_user_by_id($im['senduser_id'])->row_array();
						            echo "<li><label>邮寄人：".$senduser['upro_first_name'].$senduser['upro_last_name'];
						        	}
						          echo "</label><label>接收人：";
						          if ($im['recuser_id'] > 20000 ){
						          		$recuser = $this->jad_global_model->get_supplier_info_by_sId($im['recuser_id']);
						          		echo $recuser['suppl_alias'].'</br>'.$recuser['suppl_location'];
						          }
						          if ($im['recuser_id'] > 10000 && $im['recuser_id'] < 20000){
						          		$recuser = $this->jad_global_model->get_clientinfo_by_clientid($im['recuser_id']);
						          		//$clientInfo = $recuser['client_message'];
						              echo '当前客户';			
						          }
						          if ($im['recuser_id'] < 10000 ){
						              $recuser = $this->flexi_auth->get_user_by_id($im['recuser_id'])->row_array();
						              echo $recuser['upro_first_name'].$recuser['upro_last_name'].' '.$recuser['upro_phone'].' '.$recuser['upro_post_code'].'<br />'.$recuser['upro_country'].' '.$recuser['upro_county'].' '.$recuser['upro_city'].' '.$recuser['upro_address'];	
						          }				
						          foreach($mm_status as $mm) { 
									      if ( $mm[1] == $im['trans_status'] ){
									      	 echo "</label><label>任务状态&nbsp：".$mm[0].'</label>';
									      	 echo '<label>快递公司&nbsp'.$im['mail_courier'];
									      	 echo '</br>单号&nbsp'.$im['mail_tracking'];
									      	 echo '</br>费用&nbsp'.$im['mail_fee'].'&nbsp'.$im['monetary_unit'].'</label></li>';
									      }
									    }				
						          //if ($im['trans_status']!=4) break;
						        
						        }
						 ?>
					</ul>
					</fieldset>
					<?php if ( $m_type==1 || $m_type == 3 ){ ?>
					<fieldset><legend>客户信息</legend>
					<ul>
						<li><?php echo $infoClient['client_message']; ?>
						</li>
					</ul>
					</fieldset>
					<?php } ?>
					
          <input type="button" name="cancel" id="cancel" value="返回" class="link_button large" onclick="javascript:window.history.back(-1);" />
	
						
					
				<?php echo form_close();?>
			</div>
		</div>
	</div>	

<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
<script src="<?php echo $includes_dir;?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.fancybox-1.3.4.pack.js"></script> 
<script type="text/javascript">
		$(document).ready(function() {
			$("a#example6").fancybox({
				'titlePosition'		: 'outside',
				'overlayColor'		: '#000',
				'overlayOpacity'	: 0.9
			});
		});
</script>
</body>
</html>
