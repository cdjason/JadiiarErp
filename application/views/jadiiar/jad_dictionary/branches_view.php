<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>分店信息一览</title>
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
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
							<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th>分店名</th>
								<th>地址</th>
								<th>电话</th>
								<th>邮编</th>
								<th>负责人</th>
								<th>编辑</th>
								<th title="If checked, the row will be deleted upon the form being updated.">
									删除
								</th>
							</tr>
						</thead>
						<?php if (!empty($branches)) { ?>
						<tbody>
							<?php foreach ($branches as $branch) { ?>
							<tr>
								<td>
									<?php echo $branch['branch_name'];?>
								</td>
								<td>
									<?php echo $branch['branch_address'];?>
								</td>
								<td>
									<?php echo $branch['branch_phone'];?>
								</td>
								<td>
									<?php echo $branch['branch_zipcode'];?>
								</td>
								<td>
									<?php echo $branch['upro_first_name'].' '.$branch['upro_last_name'];?>
								</td>					
								<td>
									<a href="<?php echo $base_url.'index.php/jad_dictionary/update_branch/'.$branch['branch_id'];?>">
										Edit
									</a>
								</td>
								<td>
									<input type="checkbox" name="delete_branch[<?php echo $branch['branch_id'];?>]" value="1"/>
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7">
									<input type="submit" id="submit" name="update_branches" value="更改/删除分店" class="link_button large" />
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="7" class="highlight_red">
									No branches are available.
								</td>
							</tr>
						</tbody>
					<?php } ?>
					</table>					
				<?php echo form_close();?>	
       <a href="<?php echo $base_url; ?>index.php/jad_dictionary/add_branch" >添加分店</a>
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
