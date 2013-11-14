<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>任务详情</title>
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
        <h2商品详情</h2>
        
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品详细信息</legend>
						<ul>
             <li>
								<label>商品品牌:</label>
								<label><?php echo $infoMerch['goods_brand'];?></label>
								<label>商品类别:</label>
								<label><?php echo $infoMerch['categ_name'];?></label>
             </li>
             <li>
								<label>货号:</label>
								<label><?php echo $infoMerch['goods_seriesnum'];?></label>
								<label>短条码:</label>
								<label><?php echo $infoMerch['goods_shortcode'];?></label>
             </li>
             <li>
								<label>基本描述:</label>
								<label><?php echo $infoMerch['goods_desc'];?></label>
             </li>
             <hr/>
             <li>
								<label>商品颜色:</label>
								<label><?php foreach($colour as $coo){ 
									          if ($coo[1] == substr($infoMerch['goods_code'],10,3)) echo $coo[0];
									        }
									  ?></label>
	             
             
								<label>商品尺码:</label>
								<label><?php foreach($size as $soo){ 
									          if ($soo[1] == substr($infoMerch['goods_code'],13,3)) echo $soo[0];
									        }
									  ?></label>
             </li>
             <li>
								<label>条形码:</label>
								<label><?php echo $infoMerch['goods_barcode'];?></label>
	           </li>
             <li>
								<label>详细描述:</label>
								<label><?php echo $infoMerch['goods_s_desc'];?></label>
	           </li>
             </ul>
          </fieldset>   
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

</body>
</html>