<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>Admin Dashboard Jadiiar</title>
<?php $this->load->view('includes/head'); ?>
</head>
<body id="login">
<div id="body_wrap">
<!-- Header -->  
<?php $this->load->view('includes/header'); ?> 
<!-- Demo Navigation -->
<?php $this->load->view('includes/jad_header'); ?>
<?php if (! empty($message)) { ?>
		<div id="message">
			<?php echo $message; ?>
		</div>
<?php } ?>
<!-- Scripts   -->
<?php $this->load->view('includes/scripts'); ?> 
<!-- Footer  --> 
<?php $this->load->view('includes/footer'); ?>    
</div>	


</body>
</html>