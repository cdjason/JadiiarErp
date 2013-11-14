<?php if (! empty($alert_message)) { foreach ($alert_message as $mess){?>
<div class="alert <?php echo ($mess[0]=='error_msg')?'alert-error':'alert-info';?>">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong><?php echo $mess[1]; ?></strong> 
</div>
<?php }} ?>
