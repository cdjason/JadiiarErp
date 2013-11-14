<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JaddiarERP</title>
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir;?>lib/bootstrap/css/bootstrap.css">

<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir;?>stylesheets/theme.css">
<link rel="stylesheet" href="<?php echo $includes_dir;?>lib/font-awesome/css/font-awesome.css">


<!-- Demo page code -->

<style type="text/css">
    #line-chart {
        height:300px;
        width:800px;
        margin: 0px auto;
        margin-top: 1em;
    }
    .brand { font-family: georgia, serif; }
    .brand .first {
        color: #ccc;
        font-style: italic;
    }
    .brand .second {
        color: #fff;
        font-weight: bold;
    }
</style>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Le fav and touch icons 作用是什么？没有弄明白。 -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
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
            <h1 class="page-title">编辑个人账户信息</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li class="active">个人资料</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php echo form_open(current_url());?>  	
<div class="btn-toolbar">
    <input type="submit" value="保存" name="update_account" id="submit" class="btn btn-primary" /><i class="icon-save"></i>
  <div class="btn-group">
  </div>
</div>
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">基本资料</a></li>
      <li><a href="#profile" data-toggle="tab">密码</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">
        <label>First Name</label>
        <input type="text" id="first_name" class="input-xlarge" name="update_first_name" value="<?php echo set_value('update_first_name',$user['upro_first_name']);?>"/>
        <label>Last Name</label>
        <input type="text" id="last_name" class="input-xlarge" name="update_last_name" value="<?php echo set_value('update_last_name',$user['upro_last_name']);?>"/>
        <label>国家</label>
        <input type="text" id="country" class="input-xlarge" name="update_country" value="<?php echo set_value('update_country',$user['upro_country']);?>"/>
        <label>省/州</label>
        <input type="text" id="county" class="input-xlarge" name="update_county" value="<?php echo set_value('update_county',$user['upro_county']);?>"/>
        <label>城市</label>
        <input type="text" id="city" class="input-xlarge" name="update_city" value="<?php echo set_value('update_city',$user['upro_city']);?>"/>
        <label>电话</label>
        <input type="text" id="phone_number" class="input-xlarge" name="update_phone_number" value="<?php echo set_value('update_phone_number',$user['upro_phone']);?>"/>
        <label>邮编</label>
        <input type="text" id="post_code" class="input-xlarge" name="update_post_code" value="<?php echo set_value('update_post_code',$user['upro_post_code']);?>"/>
        <label>地址</label>
        <textarea id="address_01" name="update_address_01" rows="3" class="input-xlarge"><?php echo set_value('update_address_01',$user['upro_address']);?></textarea>
		<label>Email Address</label>
		<input type="text" id="email_address" class="input-xlarge" name="update_email" value="<?php echo set_value('update_email_address',$user[$this->flexi_auth->db_column('user_acc', 'email')]);?>" />
        <label for="username">Username</label>
        <input type="text" id="username" name="update_username" value="<?php echo set_value('update_username',$user[$this->flexi_auth->db_column('user_acc', 'username')]);?>" class="input-xlarge" />
      </div>
      <div class="tab-pane fade" id="profile">
        <label>旧密码:</label>
        <input type="password" class="input-xlarge" id="current_password" name="current_password" value="<?php echo set_value('current_password');?>"/>
        <label>新密码</label>
        <input type="password" class="input-xlarge" id="new_password" name="new_password" value="<?php echo set_value('new_password');?>"/><span class="help-block">	密码长度不能少于<?php echo $this->flexi_auth->min_password_length(); ?>位；阿拉伯数字, 破折号, 下划线, 字母和符号字符有效。</span>
        <label>确认新密码</label>
        <input type="password" class="input-xlarge" id="confirm_new_password" name="confirm_new_password" value="<?php echo set_value('confirm_new_password');?>"/>
<div>
            <input type="submit" value="更新" name="change_password" class="btn btn-primary" />
        </div>
      </div>
  </div>

</div>
	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>  
  </body>
</html>



