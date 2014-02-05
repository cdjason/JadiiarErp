<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>商品目录</title>
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
            <p class="stat" id="product_publish"><span class="number"></span><?php  ?></p>
            <p class="stat" id="product_id"><span class="number"></span><?php  ?></p>
        </div>
        <h1 class="page-title">商品目录</h1>
    </div>
            <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">ERP商品目录</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">

<?php $this->load->view('includes/jad_message'); ?>  

<?php echo form_open(current_url());?>
        <div class="row-fluid">
            <div class="span6">
                <div class="input-prepend">
					<input type="text" class="span8 search-query" id="search" placeholder="色卡or产品编号or商品编号or商品描述" name="search_query" value="<?php echo $search_query;?>">
                    <input type="submit" name="search_product" class="btn" value="Search" />
                    <a class="btn" href="<?php echo $base_url; ?>index.php/jad_goods/items_catalogue/order_by/num_iid/order_parameter/desc">重设</a>
                </div>
            </div>
            <div class="span6">
                <div class="input-prepend pull-right ">
                    <a title="加入任务pool" class="btn" href="#" onclick="missionAdd()">设置进货任务</a>
                    <a title="按品牌排序" class="btn <?php echo ($orderBy=='product_brand')?'btn-info':'';?>" href="<?php echo $base_url; ?>index.php/jad_goods/items_catalogue/order_by/product_brand/order_parameter/<?php echo ($orderPara=='desc')?'asc':'desc';?>">品牌<i class="<?php echo ($orderPara=='desc')?'icon-arrow-up':'icon-arrow-down';?>"></i></a>
                    <a title="按状态排序" class="btn <?php echo ($orderBy=='num_iid')?'btn-info':'';?>" href="<?php echo $base_url; ?>index.php/jad_goods/items_catalogue/order_by/num_iid/order_parameter/<?php echo ($orderPara=='desc')?'asc':'desc';?>">状态<i class="<?php echo ($orderPara=='desc')?'icon-arrow-up':'icon-arrow-down';?>"></i></a>
                    <a title="按产品创建时间排序" class="btn <?php echo ($orderBy=='product_create_time')?'btn-info':'';?>" href="<?php echo $base_url; ?>index.php/jad_goods/items_catalogue/order_by/product_create_time/order_parameter/<?php echo ($orderPara=='desc')?'asc':'desc';?>">新品<i class="<?php echo ($orderPara=='desc')?'icon-arrow-up':'icon-arrow-down';?>"></i></a>
                </div>
            </div>
        </div>

<?php echo form_close();?>	

<div class="well">
    <?php $attributes = array('id' => 'product_items_list_form');echo form_open(current_url(), $attributes);?>  	
            <table id="product_items_list_table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class = "span1"></th>
                        <th class = "span1">图片</th>
                        <th>商品编号</th>
                        <th class = "span1">品牌</th>
                        <th class="span1">色卡</th>
                        <th>销售属性</th>
                        <th>描述</th>
                        <th class="span2">商品状态</th>
                        <th class="span2">操作</th>
                    </tr>
                </thead>
                <?php if (!empty($items_catalogue)) { ?>
                <tbody>
						<?php foreach ($items_catalogue as $itemCata) { ?>
                    <tr>
                        <td><input type="checkbox" name="mission_add[<?php echo $itemCata['item_id'];?>]" value="1"/></td>
                        <td><a id="fancy_box" href = "<?php echo $itemCata['item_img_link'];?>"><img class="img-rounded" title="<?php echo $itemCata['product_title'];?>" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($itemCata['item_img_link'],'sq');?>"  ></a></td>
                        <td><?php echo $itemCata['item_id'];?></td>
                        <td><?php echo $itemCata['product_brand'];?></td>
                        <td><?php echo $itemCata['item_colour'];?></td>
                        <td><?php if (!empty($itemCata['property_alias'])){
                                  $skuDescStr = ''; 
                                  $skuDesc = explode(";",$itemCata['property_alias']);
                                  for($i = 0 ; $i < count($skuDesc) ; $i++){
                                      $skuItemDesc = explode(":",$skuDesc[$i]);
                                      $skuDescStr = $skuDescStr.' & '.$skuItemDesc[2]; 
                                  }
                                  $skuDescStr = substr($skuDescStr,3);
                                  echo $skuDescStr;
                              }?></td>
                        <td><?php echo $itemCata['item_desc'];?></td>
                        <td>
                        <?php echo ($itemCata['item_expired']=='1') ? "正常 &" : "过期 &" ;?>
                        <?php echo ($itemCata['sku_id']=='') ? "未发布" : "已发布" ;?>
                        </td>
                        <td><a id = "<?php echo $itemCata['item_id'];?>" href = "#" onclick = "javascript:OrderAdd(this)"  >加入订单</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <TFOOT>
      <TR>
             <TD colSpan=7>
<?php if (! empty($pagination['links'])) { ?>
               总数: 共 <?php echo $pagination['total_items_catalogue'];?> 条查询结果
               链接: <?php echo $pagination['links'];?>
               <?php } ?>
         </td>
      </TR>
    </TFOOT>
            <?php } else { ?>
                <tbody>
                    <tr>
                        <td colspan="7">
                            No privileges are available.
                        </td>
                    </tr>
                </tbody>
            <?php } ?>
            </table>
            <?php echo form_close();?>

</div>


          <!-- sample modal content -->
          <div id="missionModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="missionModalLabel" aria-hidden="true">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h2 id="missionModalLabel">加入进货任务pool</h2>
            </div>
            <div id="mission_pool_div" class="modal-body">
            <div class="well">
                <table id="mission_table" class="table table-hover table-condensed">
                    <thead>
                            <tr>
                                <th class="span1">图片</th>
                                <th class="span2">商品编号</th>
                                <th class="span2">销售属性</th>
                                <th class="span1">购买数</th>
                            </tr>
                    </thead>
					<tbody>
                    </tbody>
				</table>
            </div>
            </div>

<form class="form-inline">
  <label>买手</label>
  <select name="" class="span2"><option value="">jack</option><option value="">hunt</option></select>
  <label>send</label>
  <select name="" class="span2"><option value="">jack</option><option value="">hunt</option></select>
  <label>rec</label>
  <select name="" class="span2"><option value="">jack</option><option value="">hunt</option></select>
  <button type="submit" class="btn">Sign in</button>
</form>
            <div class="modal-footer">
              <button class="btn" data-dismiss="modal">关闭</button>
              <a href="http://127.0.0.1/JadiiarErp/index.php/jad_goods/items_catalogue/order_by/num_iid/order_parameter/asc" class="btn btn-primary">设置进货信息</a>
            </div>
          </div>
<?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script src="<?php echo $includes_dir;?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.fancybox-1.3.4.pack.js"></script> 
<script>
//创建订单池信息
function createOrdersList(){
	//每一次创建显示订单pool之前，都要提前清空显示区域
	$("#orders_pool_ul")[0].innerHTML = "";
	//AJAX获取订单list
    $.ajax({
        type:"post",
        url: "<?php echo base_url();?>index.php/jad_orders/get_items",
        success: function(data){
            var props = eval('('+data+')');
			for (i in props) {
			   	 var li = document.createElement('li');
				 var div = document.createElement('div');
				 div.setAttribute('class', 'row-fluid');
				 var div_img = document.createElement('div_img');
				 div_img.setAttribute('class', 'span2');
				 var img = document.createElement('img');
				 img.setAttribute('class', 'img-rounded');
				 img.setAttribute('src', props[i].options['url']);
				 div_img.appendChild(img);
				 div.appendChild(div_img);

				 var div_desc = document.createElement('div_desc');
				 div_desc.setAttribute('class', 'span7');
				 div_desc.appendChild(document.createTextNode(props[i].options['sku']));
				 div_desc.appendChild(document.createTextNode(props[i].options['brand']));
				 div_desc.appendChild(document.createTextNode(props[i].options['name']));
				 div.appendChild(div_desc);

				 var div_oper = document.createElement('div_oper');
				 div_oper.setAttribute('class', 'span1');
				 var ahref = document.createElement('a');
				 ahref.setAttribute('href', '#');
				 ahref.setAttribute('id', props[i].rowid);
				 ahref.setAttribute('onclick', 'javascript:OrderDel(this)');
				 ahref.appendChild(document.createTextNode("删除"));
				 div_oper.appendChild(ahref);
				 div.appendChild(div_oper);

				 li.appendChild(div);
				 $("#orders_pool_ul")[0].appendChild(li);

			   	 var li = document.createElement('li');
				 li.setAttribute('class', 'divider');
				 $("#orders_pool_ul")[0].appendChild(li);
			 }
			if(Object.keys(props).length == 0){
				$("#orders_pool_ul")[0].appendChild(document.createTextNode("订单pool目前为空"));
			}else{
				//需要在最后加上填写销售记录的链接
			   	 var li = document.createElement('li');
				 var ahref = document.createElement('a');
				 ahref.setAttribute('href', '<?php echo $base_url; ?>index.php/jad_orders/order_add');
				 ahref.setAttribute('tabindex', '-1');
				 ahref.appendChild(document.createTextNode("填写销售记录"));
				 li.appendChild(ahref);
				 $("#orders_pool_ul")[0].appendChild(li);
			}
        },
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    }); 
}
//AJAX方法，加入订单池
function OrderAdd(o){
	//从TABLE表中获取需要加入订单池的商品数据
    var item_title = o.parentNode.parentNode.cells[0].childNodes[0].childNodes[0].title;
	//要将那些url中的符号处理一下
    var item_img_link = o.parentNode.parentNode.cells[0].childNodes[0].childNodes[0].src;
	var item_img_link_temp = item_img_link; 
	item_img_link = encodeURIComponent(item_img_link );
    var item_brand = o.parentNode.parentNode.cells[2].innerHTML;
    var item_sku = o.parentNode.parentNode.cells[4].innerHTML;
    var item_id = o.id;

		$("#order_item_add_ul")[0].innerHTML = "";
    $.ajax({
        type:"post",
        data: "itemId=" + o.id + "&itemUrl=" + item_img_link + "&itemName=" + item_title + "&itemBrand=" + item_brand + "&itemSku=" + item_sku,
        url: "<?php echo base_url();?>index.php/jad_orders/add_item",
        success: function(data){
				var li = document.createElement('li');
				 var div = document.createElement('div');
				 div.setAttribute('class', 'row-fluid');
				 var div_img = document.createElement('div_img');
				 div_img.setAttribute('class', 'span3');
				 var img = document.createElement('img');
				 img.setAttribute('class', 'img-rounded');
				 img.setAttribute('src', item_img_link_temp );
				 div_img.appendChild(img);
				 div.appendChild(div_img);

				 var div_desc = document.createElement('div_desc');
				 div_desc.setAttribute('class', 'span9');
				 div_desc.appendChild(document.createTextNode(item_sku));
				 div_desc.appendChild(document.createTextNode(item_brand));
				 div_desc.appendChild(document.createTextNode(item_title));
				 div.appendChild(div_desc);

				 li.appendChild(div);
				 $("#order_item_add_ul")[0].appendChild(li);
			createOrdersList();

				//添加成功之后，需要显示添加的商品
			$("#order_item_add_ul").dropdown('toggle');
        },
        error: function(){
            alert("加入订单失败，请与网站管理员联系！");
        }
    }); 
}
//AJAX方法，从订单池中删除
function OrderDel(o){
    $.ajax({
        type:"post",
        data: "rowId=" + o.id,
        url: "<?php echo base_url();?>index.php/jad_orders/del_item",
        success: function(data){
			alert("删除成功!");
			createOrdersList();
        },
        error: function(){
            alert("删除订单item失败，请与网站管理员联系！");
        }
    }); 
}
//为选择的items设置买手与线路信息
function missionAdd(){
	//遍历被check的item数据
    var itemTable = $("#product_items_list_table")[0];
	var mission_tbody = $("#mission_table")[0].tBodies[0];
	mission_tbody.innerHTML = ""; 
	var j=0;
	for(i = 0 ; i < itemTable.tBodies[0].rows.length ; i++ ){
		//checked的itemValues
		if (itemTable.tBodies[0].rows[i].cells[0].childNodes[0].checked) {
			//获取item数据并填充到模态框里去
			var tr = document.createElement('tr');
			var td = document.createElement('td');
            td.innerHTML =  itemTable.tBodies[0].rows[i].cells[1].childNodes[0].innerHTML;
			tr.appendChild(td);
			var td = document.createElement('td');
            td.innerHTML = itemTable.tBodies[0].rows[i].cells[2].innerHTML;
			tr.appendChild(td);
			var td = document.createElement('td');
            td.innerHTML = itemTable.tBodies[0].rows[i].cells[5].innerHTML;
			tr.appendChild(td);
			mission_tbody.appendChild(tr); 
			j++;
        }
	}
	if( j > 0 ){
		$('#missionModal').modal();
	}else{
		alert("请选择需要进货的商品");
	}
}

$(document).ready(function(){
	createOrdersList();
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

