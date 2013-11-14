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
        
				<?php echo form_open(current_url());?> 
				
				
				
					<fieldset>
						<legend>商品详细信息</legend>
						<ul>
             <li>
								<label>商品品牌:</label>
								<label><?php echo $merchInfo['goods_brand'];?></label>
								<label>商品类别:</label>
								<label><?php echo $merchInfo['categ_name'];?></label>
             </li>
             <li>
								<label>短编码:</label>
								<label><?php echo $merchInfo['goods_shortcode'];?></label>
								<label>货号:</label>
								<label><?php echo $merchInfo['goods_seriesnum'];?></label>
             </li>
             <li>
								<label>基本描述:</label>
								<label><?php echo $merchInfo['goods_desc'];?></label>
             </li>
             <hr/>
             <li>
								<label>商品颜色:</label>
								<label><?php foreach($colour as $coo){ 
									          if ($coo[1] == substr($merchInfo['goods_code'],10,3)) echo $coo[0];
									        }
									  ?></label>
	             
             
								<label>商品尺码:</label>
								<label><?php foreach($size as $soo){ 
									          if ($soo[1] == substr($merchInfo['goods_code'],13,3)) echo $soo[0];
									        }
									  ?></label>
             </li>
             <li>
								<label>长编码:</label>
								<label><?php echo $merchInfo['goods_code'];?></label>
								<label>条形码:</label>
								<label><?php echo $merchInfo['goods_barcode'];?></label>
             </li>
             <li>
								<label>详细描述:</label>
								<label><?php echo $merchInfo['goods_s_desc'];?></label>
	           </li>
	           <li>
								<label>唯一码:</label>
								<label><?php echo $merchInfo['merch_id'];?></label>
								<label>图片链接:</label>
								<label><a id="example6" href = "<?php echo $merchInfo['goods_image_url'];?>"><img title="<?php echo $merchInfo['goods_desc'];?>" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($merchInfo['goods_image_url']);?>" height="40px" width="60px" ></a></label>
						</li>
						
             <hr/>
             <li>
								<label>供应商:</label>
								<label><?php echo $merchPurchaseInfo['suppl_alias'];?></label>
								<label>买手:</label>
								<label><?php echo $merchPurchaseInfo['upro_first_name'].' '.$merchPurchaseInfo['upro_last_name'];?></label>
             </li>
             <li>
								<label>购买成本:</label>
								<label><?php echo $merchPurchaseInfo['merch_price'].' '.$merchPurchaseInfo['monetary_unit'];?></label>
								<label>购买时间:</label>
								<label><?php echo $merchPurchaseInfo['purchase_time'];?></label>
             </li>
             <hr/>

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
