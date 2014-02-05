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
        <div class="stats">
            <p class="stat" id="p_cid"><span class="number">订单号</span></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span></p>
        </div>
        <h1 class="page-title">订单详情</h1>
    </div>
        
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_product_items/ ">商品列表</a> <span class="divider">/</span></li>
        <li class="active">订单详情</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
        <?php $this->load->view('includes/jad_message'); ?>  
    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse">交易概况</a>
        <div id="page-stats" class="block-body collapse in">
            <div class="stat-widget-container">
                <div class="stat-widget">
                    <div class="stat-button">
					<p class=""><?php echo $orderClientInfo['client_street'].' '.$orderClientInfo['client_name']; ?></p>
                        <p class="detail">客户信息</p>
                    </div>
                </div>

                <div class="stat-widget">
                    <div class="stat-button">
						<p class=""><?php echo $orderClientInfo['order_note']; ?></p>
                        <p class="detail">订单备注</p>
                    </div>
                </div>

                <div class="stat-widget">
                    <div class="stat-button">
                        <p class="title">￥<?php echo $orderClientInfo['order_total_funds']; ?></p>
                        <p class="detail">已付</p>
                    </div>
                </div>

                <div class="stat-widget">
                    <div class="stat-button">
						<p class="title">￥<?php echo $orderClientInfo['order_total_price']; ?></p>
                        <p class="detail">总价</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

<p class="lead">订单商品</p>
       <div class="row-fluid">
              <div class="well">
                    <table id="orders_table" class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="span1">图片</th>
                                <th class="span1">产品名称</th>
                                <th class="span2">商品编号</th>
                                <th class="span2">SKU描述</th>
                                <th class="span1">库存数</th>
                                <th class="span1">购买数</th>
                                <th class="span1">成交价格</th>
                                <th class="span1">淘宝单号</th>
                                <th class="span1">操作</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php   $totalPrice = 0;
									foreach ($orderItemsList as $item) { 
										$totalPrice = $totalPrice + $item['o_item_num']*$item['o_item_price']; 		
                            ?>
                            <tr>
                                <td><img class="img-rounded" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($item['item_img_link'],'sq');?>" /></td>
								<td><?php echo $item['product_title']; ?></td>
								<td><?php echo $item['item_id']; ?></td>
								<td><?php echo $item['item_desc']; ?></td>
								<td></td>
								<td><?php echo $item['o_item_num']; ?></td>
								<td><?php echo $item['o_item_price']; ?></td>
								<td><?php echo $item['o_item_taobao']; ?></td>
								<td>发货</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
				</div>
        </div>


    <p class="lead">已付款项</p>
       <div class="row-fluid">
              <div class="well">
                    <table id="order_funds_table" class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="span2">收款渠道</th>
                                <th class="span2">凭证号</th>
                                <th class="span1">数额</th>
                                <th class="span3">时间</th>
                                <th class="span3">备注</th>
                                <th class="span1">操作</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php   $totalFund = 0;
									foreach ($orderFundsList as $fund) { 
										$totalFund = $totalFund + $fund['fund_price']; 		
                            ?>
                            <tr>
								<td><?php echo $fund['fund_channel']; ?></td>
								<td><?php echo $fund['fund_voucher']; ?></td>
								<td><?php echo $fund['fund_price']; ?></td>
								<td><?php echo $fund['fund_time']; ?></td>
								<td><?php echo $fund['fund_note']; ?></td>
								<td><a href="#" id="<?php echo $fund['fund_id']; ?>" onclick="fundEdit(this)"><i class="icon-wrench"></i></a>&nbsp;&nbsp;&nbsp;<a href="#" id="<?php echo $fund['fund_id']; ?>" onclick="fundDel(this)"><i class="icon-remove"></i></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
				</div>
        </div>

          <!-- sample modal content -->
          <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h3 id="myModalLabel"></h3>
            </div>
            <div class="modal-body">
            <?php $attributes = array('id' => 'fund_add_form','class' => 'form-horizontal');echo form_open(current_url(), $attributes);?>  	

				<div class="control-group">
					<label class="control-label" for="fund_channel">收款渠道</label>
					<div class="controls">
					    <select name = "fund_channel" id = "fund_channel">
							<option value = "">--请选择--</option>
							<option value = "zhifubao">支付宝</option>
							<option value = "huikuan">银行汇款</option>
							<option value = "cash">现金</option>
							<option value = "other">其他</option>
                        </select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fund_id">凭证号</label>
					<div class="controls">
						<input = "text" id = "fund_id" name = "fund_id" class="span10" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fund_price">数额</label>
					<div class="controls">
						<input = "text" id = "fund_price" name = "fund_price" class="span10" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fund_note">备注</label>
					<div class="controls">
						<input = "text" id = "fund_note" name = "fund_note" class="span10" />
					</div>
				</div>
				<input type="hidden" name="fund_add_submit" id="fund_add_submit" />
				<input type="hidden" name="fund_edit_id" id="fund_edit_id" />
				<input type="hidden" name="fund_order_id" value="<?php echo $orderClientInfo['order_id']; ?>" />
            <?php echo form_close();?>
            </div>
            <div class="modal-footer">
              <button class="btn" data-dismiss="modal">关闭</button>
              <button id = "fund_add_submit_id" class="btn btn-primary"></button>
            </div>
          </div>
          <div class="bs-docs-example" style="padding-bottom: 24px;">
            <a href="#" id="newFundAddId" onclick="fundEdit(this)" class="btn btn-primary btn-large">新增款项</a>
          </div>
        <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div> </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//补充待编辑的数据，触发模态框
function fundEdit(o){
	//设置模态框的数据
	if (o.id == "newFundAddId" ){//清空模态框数据
		$("#fund_channel")[0].value = "";
		$("#fund_id")[0].value ="";
		$("#fund_price")[0].value ="";
		$("#fund_note")[0].value ="";
		$("#fund_add_submit")[0].value ="add";
		$("#myModalLabel")[0].innerHTML ="新增款项";
		$("#fund_add_submit_id")[0].innerHTML ="新增款项";


	}else{//填充模态框数据
		var channel = o.parentNode.parentNode.cells[0].innerHTML;
		var voucher = o.parentNode.parentNode.cells[1].innerHTML;
		var price = o.parentNode.parentNode.cells[2].innerHTML;
		var note = o.parentNode.parentNode.cells[4].innerHTML;
		$("#fund_channel")[0].value = channel;
		$("#fund_id")[0].value = voucher;
		$("#fund_price")[0].value = price;
		$("#fund_note")[0].value = note;
		$("#fund_edit_id")[0].value = o.id;
		$("#fund_add_submit")[0].value ="edit";
		$("#myModalLabel")[0].innerHTML ="编辑款项";
		$("#fund_add_submit_id")[0].innerHTML ="编辑款项";
	}
	//显示模态框
	$('#myModal').modal();
}
function fundDel(o){
	$.ajax({
		type:"post",
		data: "fundId=" + o.id ,
		url: "<?php echo base_url();?>index.php/jad_orders/fund_del",
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
	$("#fund_add_submit_id").click(function(){
		$("#fund_add_form").validate({
			rules: {
			  fund_channel: {
				required: true,
			  },
			  fund_id: {
				required: true,
				maxlength: 25
			  },
			  fund_price: {
				required: true,
				number:true  
			  },
			  fund_note: {
				required: true,
				maxlength: 25
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
		if($("#fund_add_form").valid()){
			$("#fund_add_form")[0].submit();
		}
	 });
});
</script>  
</body>
</html>
