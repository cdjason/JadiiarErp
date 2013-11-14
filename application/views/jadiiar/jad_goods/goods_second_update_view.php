<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>更新商品一级信息条目</title>
<?php $this->load->view('includes/head'); ?> 
</head>

<body id="update_branch">

<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>更新商品二级信息</h2>
				

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品信息</legend>
						<ul>
							<li>
								<label>品牌:</label>
								<label><?php echo $goodsSecondInfo['goods_brand'];?></label>
							</li>
							<li>
								<label>类别:</label>
								<label><?php echo $goodsSecondInfo['categ_name'];?></label>
							</li>
							<li>
								<label>生产年份:</label>
								<label><?php echo substr($goodsSecondInfo['goods_seriesnum'],4,2);?></label>
							</li>
							<li>
								<label>商品一级信息描述:</label>
								<label><?php echo $goodsSecondInfo['goods_desc'];?></label>
							</li>
             <HR/>
							<li>
								<label>颜色:</label>
								<label><?php foreach($colour as $coo){ 
									          if ($coo[1] == substr($goodsSecondInfo['goods_code'],10,3)) echo $coo[0];
									        }
									  ?></label>
								<label>尺码:</label>
								<label><?php foreach($size as $soo){ 
									          if ($soo[1] == substr($goodsSecondInfo['goods_code'],13,3)) echo $soo[0];
									        }
									  ?></label>
							</li>            
							<li>
								<label>条形码:</label>
								<label><?php echo $goodsSecondInfo['goods_barcode'];?></label>
								<label>货号:</label>
								<label><?php echo $goodsSecondInfo['goods_seriesnum'];?></label>
							</li>             
							<li>
								<label for="goods_s_desc">商品二级信息描述:</label>
								<textarea id="goods_s_desc" name="update_goods_s_desc" class="width_400 tooltip_trigger" 
									title="商品二级信息描述"><?php echo set_value('update_goods_s_desc',$goodsSecondInfo['goods_s_desc']);?></textarea>
							</li>
							<li>
								<label for="merch_image_url">图片链接:</label>
								<textarea id="merch_image_url" name="update_merch_image_url" class="width_400 tooltip_trigger" 
									title="商品图片链接"><?php echo set_value('update_merch_image_url',$goodsSecondInfo['goods_image_url']);?></textarea>
							</li>
							<li>
								<label for="submit">更新基础信息:</label>
								<input type="submit" name="update_goods_second" id="submit" value="提  交" class="link_button large"/>
							</li>
						</ul>
					</fieldset>
				<?php echo form_close();?>
				<input type="button" name="cancel" id="cancel" value="返回" class="link_button large" onclick="javascript:window.history.back(-1);" />
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