<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>商品退货信息</title>
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
        <h2>提交商品退货信息</h2>
        
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
								<label>详细描述:</label>
								<label><?php echo $infoGoods['goods_s_desc'];?></label>
	           </li>
             <hr/>

             </ul>
          </fieldset> 
					<fieldset>
						<legend>商品退货信息</legend>
						<ul>
						<input type="hidden" id="merch_id" name="get_merch_id" value="<?php echo $merchId;?>"/>	
						<input type="hidden" id="trans_id" name="get_trans_id" value="<?php echo $transId;?>"/>
						<input type="hidden" id="mission_id" name="get_mission_id" value="<?php echo $missionId;?>"/>
							<li>
								<label for="merch_price">退货价格:</label>
								<input type="text" id="merch_price" name="add_merch_price" value="<?php echo set_value('add_merch_price');?>"/>
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
				
							<li>
								<hr/>
								<label for="submit">提交:</label>
								<input type="submit" name="finish_return" id="submit" value="Submit" class="link_button large"/>
							</li>
						</ul>
					</fieldset>
				<?php echo form_close();?>
			</div>
		</div>
	</div>	
	
<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 

</body>
</html>