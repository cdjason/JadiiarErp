<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>JaddiarERP Login</title>
<?php $this->load->view('includes/jad_head'); ?>  
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class=""> 
  <!--<![endif]-->
    <div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                </ul>
                <a class="brand" href="index.html"><span class="first">Your</span> <span class="second">Company</span></a>
        </div>
    </div>
    
    <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
      <div class="dialog">
        <div class="block">
            <p class="block-heading">登陆验证</p>
            <div class="block-body">
		    <?php echo form_open(current_url());?>  	
                    <label>用户名</label>
                    <input type="text" class="span12" id="identity" name="login_identity" value="<?php echo set_value('login_identity');?>">
                    <label>密码</label>
                    <input type="password" class="span12" id="password" name="login_password" value="<?php echo set_value('login_password');?>">
                    <?php
                       if(isset($captcha))
                       {
                           echo $captcha;
                       }
                    ?>
                    <input type="submit" class="btn btn-primary pull-right" value="登陆" name="login_user" id="submit"/>
                    <label class="remember-me"><input type="checkbox">&nbsp;记住我</label>
                    <div class="clearfix"></div>
		    <?php echo form_close();?>			
            </div>
        </div>
        <!-- <p class="pull-right" style=""><a href="http://www.portnine.com" target="blank">Theme by Portnine</a></p> -->
        <p><a href="reset-password.html">忘记密码?</a></p>
      </div>
    </div> 
<?php $this->load->view('includes/jad_scripts'); ?>
    <script src="<?php echo $includes_dir;?>lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript">
        $("[rel=tooltip]").tooltip();
        $(function() {
            $('.demo-cancel-click').click(function(){return false;});
        });
    </script>
    </body>
</html>
