<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JaddiarERP</title>
<?php $this->load->view('includes/jad_head'); ?>  
</head>
  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class=""> 
  <!--<![endif]-->
<?php $this->load->view('includes/jad_navbar'); ?>  
<?php $this->load->view('includes/jad_sidebar'); ?>  
    <div class="content">
        <div class="header">
            <h1 class="page-title">编辑分店信息</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_branches">分店信息列表</a> <span class="divider">/</span></li>
            <li class="active">编辑分店信息</li>
        </ul>
        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php echo form_open(current_url());?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn"/><i class="icon-save"></i> 保存 
  </button>
  <input type="hidden" name="update_branch" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
								<label>分店名称:</label>
								<input type="text" id="branch_name" class="span4" name="update_branch_name" value="<?php echo set_value('update_branch_name',$branch['branch_name']);?>"/>
								<label>分店地址:</label>
								<input type="text" id="branch_address" class="span4" name="update_branch_address" value="<?php echo set_value('update_branch_address',$branch['branch_address']);?>"/>
								<label>分店电话:</label>
								<input type="text" id="branch_phone" class="span4" name="update_branch_phone" value="<?php echo set_value('update_branch_phone',$branch['branch_phone']);?>"/>
								<label>邮编:</label>
								<input type="text" id="branch_zipcode" class="span4" name="update_branch_zipcode" value="<?php echo set_value('update_branch_zipcode',$branch['branch_zipcode']);?>"/>
                                <label>负责人:</label>
								<select id="upro_id" name="update_upro_id" class="span4">
								<?php foreach($managers as $manager) { ?>
									<?php $branch_manager = ($manager['upro_id'] == $branch['upro_id']) ? TRUE : FALSE;?>
									<option value="<?php echo $manager['upro_id'];?>" <?php echo set_select('update_manager', $manager['upro_id'], $branch_manager);?>>
										<?php echo $manager['upro_first_name'].' '.$manager['upro_last_name'];?>
									</option>
								<?php } ?>
                                </select>

</div>
	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
  </body>
</html>
