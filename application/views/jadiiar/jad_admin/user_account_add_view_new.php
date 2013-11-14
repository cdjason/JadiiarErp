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
            <h1 class="page-title">新增用户</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">用户列表</a> <span class="divider">/</span></li>
            <li class="active">新增用户资料</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'test_form');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn" /><i class="icon-plus"></i> 增加
  </button>
  <input type="hidden" name="add_new_user" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
                    <label>First Name</label>
                    <input type="text" class="span4" id="first_name" name="register_first_name" value="<?php echo set_value('register_first_name');?>"/>
                    <label>Last Name</label>
                    <input type="text" class="span4" id="last_name" name="register_last_name" value="<?php echo set_value('register_last_name');?>"/>
                    <label>Phone Number</label>
                    <input type="text" class="span4" id="phone_number" name="register_phone_number" value="<?php echo set_value('register_phone_number');?>"/>
                    <label>Email Address</label>
                    <input type="text" class="span4" id="email_address" name="register_email_address" value="<?php echo set_value('register_email_address');?>" /> 
                    <label>Username</label>
                    <input type="text" class="span4" id="username" name="register_username" value="<?php echo set_value('register_username');?>"/>
                    <label for="password">密码:</label>
                    <input type="password" class="span4" id="password" name="register_password" value="<?php echo set_value('register_password');?>"/><span class="help-block">	密码长度不能少于<?php echo $this->flexi_auth->min_password_length(); ?>位；阿拉伯数字, 破折号, 下划线, 字母和符号字符有效。</span>
                    <label for="confirm_password">密码确认:</label>
                    <input type="password" class="span4" id="confirm_password" name="register_confirm_password" value="<?php echo set_value('register_confirm_password');?>"/>
</div>

	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
$(document).ready(function(){
    $('#form_btn').confirm({
		'title' : '新增用户',
		'message' : '您确定要新增该用户吗？',        
		'action' : function() {
			$('#test_form').submit();
		}
	});
});
</script>  
  </body>
</html>



