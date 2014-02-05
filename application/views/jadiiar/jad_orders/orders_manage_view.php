<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>订单搜索</title>
<?php $this->load->view('includes/jad_head'); ?>  
<link rel="stylesheet" href="<?php echo $includes_dir;?>css/jquery.fancybox-1.3.4.css">
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
        <div class="stats">
        </div>
        <h1 class="page-title">订单搜索</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">信息列表</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">

<?php $this->load->view('includes/jad_message'); ?>  

<?php echo form_open(current_url());?>
<div class="input-append">
<input type="text" class="span4 search-query" id="search" placeholder="色卡or商品描述or商品编号" name="search_query" value="<?php ?>">
<input type="submit" name="search_item" class="btn" value="Search" />
<a class="btn" href="<?php echo $base_url; ?>index.php/jad_goods/manage_product_items/">重设</a>
</div>
<?php echo form_close();?>	

<div class="row-fluid">
	<div class="span6">
		<div class="row-fluid">
			<div class="span8" style="text-align:center"><strong>商品</strong>
			</div>
			<div class="span2"><strong>价格</strong>
			</div>
			<div class="span2"><strong>数量</strong>
			</div>
		</div>
    </div>
	<div class="span2"><strong>客户信息</strong>
    </div>
	<div class="span2"><strong>状态</strong>
    </div>
	<div class="span2"><strong>总价</strong>
    </div>
</div>
<div class="accordion" id="accordion2">
<?php foreach( $ordersInfo as $order){ 
?>

	<div class="accordion-group">
		<div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#div<?php echo $order['order_id']; ?>">订单编号：<?php echo $order['order_id']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;下单时间：<?php echo $order['order_time']; ?></a>
		</div>
		<div id="div<?php echo $order['order_id']; ?>" class="accordion-body collapse in">
			<div class="accordion-inner">

				<div class="row-fluid">
						<table class="table table-condensed table-bordered">
							<tbody>
							<?php $orderItems = $this->jad_orders_model->get_order_items($order['order_id']); //根据订单号，获取订单详情 
		                          $i=0;
								  foreach ($orderItems as $item){
									  $i++;
							?>
								<tr>
									<td class="span1"><img class="img-rounded" title="<?php echo $item['product_title'];?>" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($item['item_img_link'],'sq');?>"  ></td>
									<td class="span3"><?php echo $item['product_title']; ?><br><?php echo $item['item_desc']; ?></td>
									<td class="span1"><?php echo $item['o_item_price']; ?></td>
									<td class="span1"><a href="#" name="<?php echo $item['order_id'].'.'.$item['item_id'].'.'.$item['o_item_num']; ?>" onclick="orderItemNumAdd(this)"><i class="icon-plus-sign"> </i></a>&nbsp;<?php echo $item['o_item_num']; ?>&nbsp;<a href="#" name="<?php echo $item['order_id'].'.'.$item['item_id'].'.'.$item['o_item_num']; ?>" onclick="orderItemNumDel(this)"><i class="icon-minus-sign"> </i></a></td>
                                    <?php if($i==1){ ?>
										<td class="span2" rowspan="<?php echo count($orderItems);?>"><?php echo $order['client_street']; ?><br><?php echo $order['client_name']; ?></td>
										<td class="span2" rowspan="<?php echo count($orderItems);?>"><?php echo $order['order_note']; ?><br><a href="<?php echo $base_url;?>index.php/jad_orders/order_items/<?php echo $order['order_id']; ?>">详情</a></td>
										<td class="span2" rowspan="<?php echo count($orderItems);?>"><?php echo $order['order_total_price']; ?></td>
                                    <?php }?>
								</tr>
							<?php } ?>
							</tbody>
						</table>
				</div>

			</div>
		</div>
	</div>

<?php }; ?>
</div>

<?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script src="<?php echo $includes_dir;?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.fancybox-1.3.4.pack.js"></script> 
<script>
function orderItemNumAdd(o){
	$.ajax({
		type:"post",
		data: "str=" + o.name,
		url: "<?php echo base_url();?>index.php/jad_orders/item_num_add",
		success: function(data){
			//刷新当前页面
			window.location.reload(); 
		},
		error: function(){
		alert("款项信息删除失败，请与网站管理员联系！");
		}
	}); 
}
function orderItemNumDel(o){
	$.ajax({
		type:"post",
		data: "str=" + o.name,
		url: "<?php echo base_url();?>index.php/jad_orders/item_num_del",
		success: function(data){
			//刷新当前页面
			window.location.reload(); 
		},
		error: function(){
		alert("款项信息删除失败，请与网站管理员联系！");
		}
	}); 
}
$(document).ready(function(){
    $("a#fancy_box").fancybox({
        'titlePosition'		: 'outside',
        'overlayColor'		: '#000',
        'overlayOpacity'	: 0.9
    });
    $('#del_btn').confirm({
		'title' : '删除商品',
		'message' : '您确定要删除商品信息吗,请谨慎操作？!',        
		'action' : function() {
			$('#product_items_list_form').submit();
		}
	});
});
</script>  
</body>
</html>
