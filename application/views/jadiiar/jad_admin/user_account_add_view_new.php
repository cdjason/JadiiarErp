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
<?php $attributes = array('id' => 'user_add_form','class' => 'form-horizontal');echo form_open(current_url(), $attributes);?>  	
<div class="control-group">
	<label class="control-label" for="full_name">用户姓名</label>
	<div class="controls">
		<input type="text" class="span4" id="full_name" name="register_full_name" value="<?php echo set_value('register_full_name');?>"/>
		</div>
</div>
<div class="control-group">
	<label class="control-label" for="phone_number">联系方式</label>
	<div class="controls">
		<input type="text" class="span4" id="phone_number" name="register_phone_number" value="<?php echo set_value('register_phone_number');?>"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="email_address">邮箱地址</label>
	<div class="controls">
		<input type="text" class="span4" id="email_address" name="register_email_address" value="<?php echo set_value('register_email_address');?>" /> 
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="username">用户名</label>
	<div class="controls">
		<input type="text" class="span4" id="username" name="register_username" value="<?php echo set_value('register_username');?>"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password">密码</label>
	<div class="controls">
		<input type="password" class="span4" id="password" name="register_password" value="<?php echo set_value('register_password');?>"/><span class="help-inline">	密码长度不能少于<?php echo $this->flexi_auth->min_password_length(); ?>位；阿拉伯数字, 破折号, 下划线, 字母和符号字符有效。</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="confirm_password">密码确认</label>
	<div class="controls">
		<input type="password" class="span4" id="confirm_password" name="register_confirm_password" value="<?php echo set_value('register_confirm_password');?>"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password"></label>
	<div class="controls">
		<button type="submit" class="btn btn-primary" id="form_btn" /><i class="icon-plus"></i> 增加
		</button>
		<input type="hidden" name="add_new_user" value="1" />
	</div>
</div>
<?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
    //为表单绑定验证
    $('#user_add_form').validate({
        rules: {
          register_full_name: {
            required: true
          },
          register_phone_number: {
            required: true
          },
          register_email_address: {
		    email: true,	
            required: true
          },
          register_username: {
            required: true
          } ,
          register_password: {
            rangelength: [8,20],  
            required: true
          },
          register_confirm_password: {
            rangelength: [8,20],  
			equalTo: "#password", 
            required: true
          }
        },
        highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function(element) {
            element
            .text('OK!').addClass('valid')
            .closest('.control-group').removeClass('error').addClass('success');
        }
    });
</script>  
  </body>
</html>



