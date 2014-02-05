<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JaddiarERP</title>
<?php $this->load->view('includes/jad_head'); ?>  
<style>
label.valid {
    width: 24px;
    height: 24px;
    background: url(<?php echo $includes_dir; ?>images/valid.png) center center no-repeat;
    display: inline-block;
    text-indent: -9999px;
}
label.error {
    font-weight: bold;
    color: red;
    padding: 2px 8px;
    margin-top: 2px;
}
</style>
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

        <h1 class="page-title">填写销售记录</h1>
    </div>
        
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_product_items/ ">商品列表</a> <span class="divider">/</span></li>
        <li class="active">填写销售记录</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
        <?php $this->load->view('includes/jad_message'); ?>  

       <div class="row-fluid">
                    <table id="orders_table" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="span1">图片</th>
                                <th class="span2">产品名称</th>
                                <th class="span2">商品编号</th>
                                <th class="span2">SKU描述</th>
                                <th class="span1">库存</th>
                                <th class="span1">成交价</th>
                                <th class="span1">数量</th>
                                <th >订单号</th>
                            </tr>
                        </thead>
                        <?php if (!empty($ordersList)) { ?>
                        <tbody>
                            <?php foreach ($ordersList as $orderInfo) { ?>
                            <tr>
                                <td><img class="img-rounded" src="<?php echo $orderInfo['options']['url'];?>" /></td>
                                <td><?php echo $orderInfo['options']['name'];?></td>
                                <td><?php echo $orderInfo['id'];?></td>
                                <td><?php echo $orderInfo['options']['sku'];?></td>
                                <td></td>
                                <td><input class="span12" type = "text" name = "sku_item_price" /></td>
                                <td><input class="span12" type = "text" name = "sku_item_num" /></td>
                                <td><input placeholder="淘宝订单号,其他渠道不填" class="span12" type = "text" name = "sku_item_num" /></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <?php } ?>
                    </table>
        </div>
        <?php $attributes = array('id' => 'order_add_form','class' => 'form-horizontal','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
	<div class="control-group">
	    <label class="control-label" for="distribution_channel">销售渠道</label>
	    <div class="controls">
			<select id="distribution_channel" name="distribution_channel">
                 <option value = "taobao">淘宝</option>
                 <option value = "weibo">微博</option>
                 <option value = "weixin">微信</option>
                 <option value = "other">其他</option>
            </select>
	    </div>
	</div>
		<div class="control-group">
			<label class="control-label" for="client_name">客户姓名</label>
			<div class="controls">
				<input type="text" id="client_name" name="client_name"  />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="client_phone">电话</label>
			<div class="controls">
				<input type="text" id="client_phone" class="input-xlarge" name="client_phone" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="">所在地区</label>
			<div class="controls" id="location_div"></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="address">详细地址</label>
			<div class="controls">
				<textarea id="address" name="address" rows="3" class="input-xlarge"></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="post_code">邮编</label>
			<div class="controls">
				<input type="text" id="post_code" class="input-xlarge" name="post_code" />
			</div>
		</div>

	<div class="control-group">
	    <label class="control-label" for="order_note">备注</label>
	    <div class="controls">
			 <input type="text" name="order_note" id = "order_note" />
	    </div>
	</div>
	<input type="hidden" name="order_items" id="order_items" />
	<input type="hidden" name="location_json" id="location_json" />
	<div class="control-group">
	    <label class="control-label"></label>
	    <div class="controls">
                <button type="submit" class="btn btn-primary span2" id="form_btn" /><i class="icon-plus"></i>  提交</button>
                <input type="hidden" name="order_add_submit" value="1" />
	    </div>
	</div>
	    <?php echo form_close();?>
        <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//根据不同参数创建各级地域的下拉数据
function createAreaSelect(parentId,value){
	     var sel = document.createElement('SELECT');
		 sel.id = parentId + "_select";
         sel.setAttribute('class', 'span3');
	     var op = document.createElement('OPTION');
	     op.setAttribute('value', '');
	     op.innerHTML = '--请选择--';
	     sel.appendChild(op);
         var i;
		 var j=0;
		 var tempAreas = window.areasObject; 
		 for (i in tempAreas) {
			if ( tempAreas[i].parent_id == parentId) {
				j++;
				var option = document.createElement('OPTION');
				option.innerHTML = tempAreas[i].name;
				option.setAttribute('value',  tempAreas[i].id);
				sel.appendChild(option);
			}
         }
         sel.onchange = function(){ createSubAreaSelect(this, parentId);};
		 if (value != ''){
			sel.value = value;
		 }
	     //sel.value = selValue;
		 if (j!= 0)
			$("#location_div")[0].appendChild(sel);
}

//从订单表中获取每笔订单的详细数据，并验证数据的可靠性
function checkAll(form){
	var orderArray = new Array();
	ordersTable = $("#orders_table")[0];
    //遍历订单中的items 
    for(var i = 0 ; i < ordersTable.tBodies[0].rows.length ; i++ ){
		var itemArray = new Array();
        if( (!isfloat(ordersTable.tBodies[0].rows[i].cells[5].childNodes[0].value ))  || (!checkNum(ordersTable.tBodies[0].rows[i].cells[6].childNodes[0].value ))){
            alert("宝贝SKU的价格信息必须是最多两位小数的正实数，数量信息必须是正整数!");
            return false;
        } 
        itemArray.push(ordersTable.tBodies[0].rows[i].cells[2].innerHTML);//商品编号 
        itemArray.push(ordersTable.tBodies[0].rows[i].cells[5].childNodes[0].value); //价格
        itemArray.push(ordersTable.tBodies[0].rows[i].cells[6].childNodes[0].value); //数量
        itemArray.push(ordersTable.tBodies[0].rows[i].cells[7].childNodes[0].value); //淘宝订单号
		orderArray.push(itemArray); 
    }
	$("#order_items")[0].value = JSON.stringify(orderArray);

	//遍历、验证、获取地域信息
    var oJu = $("#location_div")[0];
    var locationArray = new Array();
	for (var i=0;i<oJu.childNodes.length;i++){
		if(oJu.childNodes[i].value == ''){
			alert("地区信息不能不选");
			return false;
		}
		locationArray.push(oJu.childNodes[i].options[oJu.childNodes[i].selectedIndex].text + '&' + oJu.childNodes[i].value); 
	}
    $("#location_json")[0].value = JSON.stringify(locationArray); 
}

function checkNum(obj)
{
     var re = /^[1-9]\d*$/;
     //alert(re.test(obj));
     return re.test(obj);
} 

function isfloat(oNum){
    if(!oNum) return false;
    var strP=/^[0-9]+(.[0-9]{2})?$/;
    if(!strP.test(oNum)) return false;
    try{
        if(parseFloat(oNum)!=oNum) return false;
    }catch(ex){
        return false;
    }
    return true;
}

//对ajax访问TOP平台的返回错误代码进行检查，及时反馈错误信息
/*访问成功的返回数据一般为一个长度为1的二维数组,json表示如下：
* 例：{"sku":{"iid":234556,"num_iid":123456}}
*访问错误的返回数据一般为一个长度为3或者4的一维数组，code、msg、sub_code、sub_msg
*有些有sub_msg，有些没有；具体原因目前不详
*/
function top_error_check(msg){
     var jsObject = eval('('+msg+')');
     //存在msg即为错误返回
     if ( jsObject.code != undefined ){
         if ( jsObject.sub_msg != undefined ){
             //若存在子错误码，就显示子错误码，否则显示错误码
             alert(jsObject.sub_msg);
         }else{
             alert(jsObject.msg);
         }
         return false;
     }
     //否则就正常执行程序
     return jsObject;
}
function  createSubAreaSelect(o, p_id)
{
    //当选择非叶子类目或是叶子类目之后，关键属性中的内容都需要消除掉;当前元素节点之后的兄弟节点也需要删除
    var oJu = $(o).next();
    while(oJu[0]!=undefined){
        var temp = oJu[0];
        oJu = oJu.next();
        temp.outerHTML='';
    }
    var cid = o.value;
    //当选择"请选择"的时候，考虑一下怎么进行处理一下；
    if (cid==''){
        return false;
	} 
	createAreaSelect(cid,'');
}

$(document).ready(function(){
	var country = '<?php echo $areas_info; ?>';
    window.areasObject = top_error_check(country );
    createAreaSelect("0","");
    // 判断价格信息是否大于0
    jQuery.validator.addMethod("product_price_check", function(value, element) { 
         value=parseFloat(value);      
         return this.optional(element) || value>0;       
    }, "价格必须大于0"); 

    // 判断数量是否为正整数
    jQuery.validator.addMethod("product_num_check", function(value, element) { 
         value=parseInt(value);      
         return this.optional(element) || value>0;       
    }, "产品数量必须为正整数"); 

    //对表单的输入内容进行初步校验，二次校验在checkAll函数中进行
    $('#order_add_form').validate({
        rules: {
          /* distribution_channel: { */
          /*   required: true, */
          /* }, */
          client_name: {
            required: true,
			rangelength:[2,10] 
          },
          address: {
            required: true,
			maxlength:200    
          },
          client_phone: {
            required: true,
			maxlength:15    
          },
          post_code: {
            required: true,
			maxlength:15    
          },
          order_note: {
            required: true,
			maxlength:100    
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
});
</script>  
</body>
</html>
