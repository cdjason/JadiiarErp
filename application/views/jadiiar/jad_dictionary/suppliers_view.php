<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>供应商信息一览</title>
<?php $this->load->view('includes/head'); ?>  
</head>
<body id="manage_privileges">
<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>供应商基本信息维护</h2>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
			
				<?php echo form_open(current_url());	?>
					<fieldset>
						<legend>搜  索</legend>
						
						<label for="search">关键字:</label>
						<input type="text" id="search" name="search_query" value="<?php echo set_value('search_suppliers',$search_query);?>" 
							title="支持供货商品牌或地址的模糊查询"
						/>
						<input type="submit" name="search_suppliers" value="Search" class="link_button"/>
						<a href="<?php echo $base_url; ?>index.php/jad_dictionary/manage_suppliers" class="link_button grey">重设</a>
					</fieldset>
				<?php echo form_close();?>	
			
			
			
							<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th>品牌</th>
								<th>别名</th>
								<th>位置</th>
								<th title="店铺联系方式">电话</th>
								<th>合作店员</th>
								<th  title="合作店员联系方式">联系方式</th>
								<th>
									邮寄
								</th>
								<th>
									退税
								</th>
								<th>
									备注
								</th>
							<?php if(!$IsBuyer){ ?>
								<th>
									相应买手
								</th>
							<?php } ?>								
							  <th>
									编辑
								</th>							
								<th>
									删除
								</th>

							</tr>
						</thead>
						<?php if (!empty($suppliers)) { ?>
						<tbody>
							<?php foreach ($suppliers as $supplier) { ?>
							<tr>
								<td>
									<?php echo $supplier['suppl_brand'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_alias'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_location'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_phone'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_collaborate'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_collaphone'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_hkmail'] ? "Y":"N";?>
								</td>
								<td>
									<?php echo $supplier['suppl_taxrebate'] ? "Y":"N";?>
								</td>

								<td>
									<?php echo $supplier['suppl_note'];?>
								</td>							
                
							 <td style="display:<?php echo ($IsBuyer) ? "none" : "";  ?> ">
									<?php echo $supplier['upro_first_name'].' '.$supplier['upro_last_name'];?>
									
								</td>	               
                							
								<td>
									<a href="<?php echo $base_url.'index.php/jad_dictionary/update_supplier/'.$supplier['suppl_id'];?>">
										编辑
									</a>
								</td>
								<td>
									<input type="checkbox" name="delete_supplier[<?php echo $supplier['suppl_id'];?>]" value="1"/>
								</td>

							
							</tr>
						<?php } ?>
						</tbody>
						
						<tfoot>
							<tr>
								<td colspan="12">
									<input type="submit" id="submit" name="update_suppliers" value="删除供应商信息" class="link_button large" />
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="12" class="highlight_red">
									No suppliers are available.
								</td>
							</tr>
						</tbody>
					<?php } ?>
					</table>
				
				<?php if (! empty($pagination['links'])) { ?>
					<div id="pagination" class="w100 frame">
						<p>分页: 共 <?php echo $pagination['total_suppliers'];?> 条查询结果</p>
						<p>链接: <?php echo $pagination['links'];?></p>
					</div>
				<?php } ?>					
						
				<?php echo form_close();?>
        <a href="<?php echo $base_url; ?>index.php/jad_dictionary/add_supplier" style="display:<?php echo ($IsBuyer) ? "" : "none";  ?> ">增加供应商基本信息</a>
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
