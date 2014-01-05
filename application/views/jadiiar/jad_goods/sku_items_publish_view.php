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
            <p class="stat" id="p_cid"><span class="number">CID:</span><?php echo $productInfo['cid']; ?></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php echo $productInfo['product_id']; ?></p>
            <p class="stat" id="product_title"><span class="number">产品标题:</span><?php echo $productInfo['product_title']; ?></p>
        </div>

        <h1 class="page-title">宝贝SKU信息设置</h1>
    </div>
        
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_product_items/<?php echo $productInfo['product_id']; ?>">商品列表</a> <span class="divider">/</span></li>
        <li class="active">宝贝SKU信息设置</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
        <?php $this->load->view('includes/jad_message'); ?>  
        <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
        <div class="row-fluid">
            <div class="span2">
                <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  确定</button>
                <input type="hidden" name="publish_product_items" value="1" />
            </div>
            <div class="span1" id = "ajaxPic">
            </div>
        </div></br>

        <div class="well">
            <div class="row-fluid">
                <div class="span2" ><b>宝贝图片</b></div>
                <div class="span10" ><img class="img-rounded" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($productInfo['product_img_url'],'sq');?>"  ></div>
            </div>
            <div class="row-fluid">
                <div class="span2" ><b>价格</b></div>
                <div class="span10" >
                     <input type="text" name="product_price"  id = "product_price" placeholder="注意价格区间位于sku属性之间" />
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>数量</b></div>
                <div class="span10" >
                     <input type="text" name="product_num" id = "product_num" placeholder="若销售属性有值，则为sku之和" />
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>宝贝SKU信息</b></div>
                <div class="span10" >
                    <table id="sku_items_table" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <td>商品编号</td>
                                <td>色卡</td>
                                <td>SKU描述</td>
                                <td>价格</td>
                                <td>数量</td>
                                <td>发布状态</td>
                                <td>选择</td>
                            </tr>
                        </thead>
                        <?php if (!empty($productItemsInfo)) { 
                        ?>
                        <tbody>
                            <?php foreach ($productItemsInfo as $pItemInfo) { 
                                $isNormal = false;
                                //获取相应的价格与数量
                                if ( $pItemInfo['sku_id'] != '' && $pItemInfo['item_expired'] != '2' ){
                                    $isNormal = true;
                                    
                                    //$skuInfo = $this->jad_goods_model->get_sku_info($pItemInfo['sku_id'],$pItemInfo['num_iid']);
                                    //$this->topsdk->autoload('ItemSkuGetRequest');
                                    $this->topsdk->req->setFields("sku_id,iid,properties,quantity,price,outer_id,created,modified,status");
                                    $this->topsdk->req->setSkuId($pItemInfo['sku_id']);
                                    $this->topsdk->req->setNumIid($pItemInfo['num_iid']);
                                    $skuInfo = $this->topsdk->get_data();
                                }
                            ?>
                            <tr>
                                <td id = "<?php echo $pItemInfo['iprops'];?>" ><?php echo $pItemInfo['item_id'];?></td>
                                <td><?php echo $pItemInfo['item_colour'];?></td>
                                <td id = "<?php echo $pItemInfo['property_alias'];?>">
                                    <?php if (!empty($pItemInfo['property_alias'])){
                                                $skuDescStr = ''; 
                                                $skuDesc = explode(";",$pItemInfo['property_alias']);
                                                for($i = 0 ; $i < count($skuDesc) ; $i++){
                                                    $skuItemDesc = explode(":",$skuDesc[$i]);
                                                    $skuDescStr = $skuDescStr.' & '.$skuItemDesc[2]; 
                                                }
                                                $skuDescStr = substr($skuDescStr,3);
                                                echo $skuDescStr;
                                          }
                                    ?>
                                </td>
                                <td><input type = "text" class='span6' name = "sku_item_price" value ="<?php echo ( $isNormal ) ? $skuInfo['sku']['price'] : "" ;?>" /></td>
                                <td><input type = "text" class='span6' name = "sku_item_num" value ="<?php echo ( $isNormal ) ? $skuInfo['sku']['quantity'] : "" ;?>" /></td>
                                <td id = "<?php echo $pItemInfo['sku_id']; ?>" ><?php echo ( $isNormal ) ? "已发布" : "" ;?></td>
                                <td><input type="checkbox" name="publish_item_checkbox" value="1" <?php echo ( $isNormal ) ? "checked" : "";?> /></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>商品描述</b></div>
                <div class="span10"><textarea id="item_desc" name="item_desc"></textarea></div>
            </div>              
        </div>

        <input type="hidden" name = "num_iid" value = '<?php echo $productInfo['num_iid']; ?>' />
        <input type="hidden" name = "product_id" value = '<?php echo $productInfo['product_id']; ?>' />
        <input type="hidden" name = "cid" id= "cid" value = '<?php echo $productInfo['cid']; ?>' />
        <input type="hidden" name = "product_props" value = '<?php echo $productInfo['props']; ?>' />
        <input type="hidden" name = "product_item_props" id = "product_item_props" />
        <input type="hidden" name = "productTitle" id = "productTitle" value = '<?php echo $productInfo['product_title']; ?>' />
        <input type="hidden" name = "sku_properties" id= "sku_properties" />
        <input type="hidden" name = "sku_quantities" id= "sku_quantities" />
        <input type="hidden" name = "sku_prices" id= "sku_prices" />
        <input type="hidden" name = "sku_outer_ids" id= "sku_outer_ids" />
        <input type="hidden" name = "props_property_alias" id = "props_property_alias" />
        <input type="hidden" name = "sku_properties_for_del" id = "sku_properties_for_del" />
        <input type="hidden" name = "sku_properies_for_edit" id = "sku_properies_for_edit" />
        <input type="hidden" name = "product_items_num" id= "product_items_num" />
        <input type="hidden" name = "format_item_desc" id = "format_item_desc" />

	    <?php echo form_close();?>
        <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
Array.prototype.max = function(){ 
    return Math.max.apply({},this) 
} 
Array.prototype.min = function(){ 
    return Math.min.apply({},this) 
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

function HTMLEncode( input ){
    var converter = document.createElement("DIV");
    converter.innerText = input;
    var output = converter.innerHTML;
    converter = null;
    return output;
}
//获取非关键属性中必选属性链pid:vid
function checkAll(form){
    //对表单输入框进行验证


    //遍历sku_item_table，获取sku相关参数
    var sku_properties_for_del = "";
    var sku_properties = "";
    var sku_property_alias = "";
    var item_props = "";
    var sku_quantities = "";   
    var sku_prices = "";
    var sku_outer_ids = "";
    var product_items_num = 0;
    var skuPropValues = $("#sku_items_table")[0];
    var propsArray = new Array();
    var pricesArray = new Array();

    var sku_properies_for_edit_array = new Array();
    var sku_properies_for_del_array = new Array();
    //防止对没有销售属性的产品进行sku表的遍历 
    if( skuPropValues.tBodies.length > 0 ){
        //对sku属性列表进行遍历，获取需要更新的sku的详细值，获取需要删除的sku的id值
        for(var i = 0 ; i < skuPropValues.tBodies[0].rows.length ; i++ ){
            //获取需要更新的sku的详细值，必须要checked以后的才可以发布
            if (skuPropValues.tBodies[0].rows[i].cells[skuPropValues.tBodies[0].rows[i].cells.length - 1].childNodes[0].checked) {
                //对checked的销售属性的输入信息进行验证，目前只设置了判空验证
                if( !isfloat( skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value )  || !checkNum( skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value )){
                    alert("宝贝SKU的价格信息必须是最多两位小数的正实数，数量信息必须是正整数!");
                    return false;
                } 
                //获取outer_id
                sku_outer_ids = sku_outer_ids + "," + skuPropValues.tBodies[0].rows[i].cells[0].innerText;
                //获取sku价格
                sku_prices = sku_prices + "," + skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value;
                pricesArray.push(skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value); 
                //获取sku数量
                sku_quantities = sku_quantities + "," + skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value;
                //设置宝贝总的数量
                product_items_num += parseInt(skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value);
                //props属性值需要重新提取，不能用单条item中的iprop
                var propsArr = skuPropValues.tBodies[0].rows[i].cells[2].id.split(";");
                var sku_property = '';
                for (var j = 0; j < propsArr.length; j++){
                    //取第二个冒号之前的数据
                    var propsAlias = propsArr[j].split(":");
                    sku_property = sku_property + ';' + propsAlias[0]+':'+propsAlias[1];
                    if(propsArray.indexOf(propsAlias[0]+':'+propsAlias[1]) <0 ){
                        propsArray.push(propsAlias[0]+':'+propsAlias[1]);
                        item_props = item_props + ';' + propsAlias[0]+':'+propsAlias[1];
                        sku_property_alias = sku_property_alias + ';' + propsArr[j];
                    }
                }
                sku_property = sku_property.substr(1);
                //sku_properties = sku_properties.substr(1);
                sku_properties = sku_properties + ',' + sku_property; 
                //将完整的sku参数放入该数组,包括sku_id
                var sku_properies_for_edit_item_array = new Array();
                sku_properies_for_edit_item_array.push(skuPropValues.tBodies[0].rows[i].cells[0].id,skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value,skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value,skuPropValues.tBodies[0].rows[i].cells[0].innerText,skuPropValues.tBodies[0].rows[i].cells[5].id);

                sku_properies_for_edit_array.push(sku_properies_for_edit_item_array);
            }else if( skuPropValues.tBodies[0].rows[i].cells[5].id != '') {
                //获取需要删除的sku的sku_properies值
                var sku_properies_for_del_item_array = new Array();
                sku_properies_for_del_item_array.push(skuPropValues.tBodies[0].rows[i].cells[0].id,skuPropValues.tBodies[0].rows[i].cells[0].innerText); 
                sku_properies_for_del_array.push(sku_properies_for_del_item_array);
            }

        }
        item_props = item_props.substr(1); 
        sku_property_alias = sku_property_alias.substr(1); 
        sku_outer_ids = sku_outer_ids.substr(1);
        sku_properties = sku_properties.substr(1);
        sku_prices = sku_prices.substr(1);
        sku_quantities = sku_quantities.substr(1);
    }

    //修改宝贝sku的时候，必须至少选择一个销售属性进行发布
    if(  sku_outer_ids == '' ){
        alert("请选择要发布的宝贝SKU，至少选择一项!");
        return false;
    }

    //1、宝贝描述信息不能为空
    if( $("#item_desc")[0].value == '' ){
        alert('宝贝描述信息不能为空!');
        return false;
    }

    //2、宝贝价格信息不能为空,且必须为正整数！
    if (!checkNum($("#product_price")[0].value)){
        alert('宝贝价格信息不能为空，且必须为正整数!');
        return false;
    }else if($("#product_price")[0].value < pricesArray.min() || $("#product_price")[0].value > pricesArray.max()){
        //3、宝贝价格必须处于sku价格之间
        alert('宝贝的价格必须处于sku价格之间!');
        return false;
    }

    //3、宝贝数量信息不能为空,且必须为正整数！
    if (!checkNum($("#product_num")[0].value)){
        alert('宝贝数量信息不能为空，且必须为正整数!');
        return false;
    }


    $("#product_item_props")[0].value = item_props;
    $("#sku_outer_ids")[0].value = sku_outer_ids;
    $("#sku_properties")[0].value = sku_properties;
    $("#sku_prices")[0].value = sku_prices;
    $("#sku_quantities")[0].value = sku_quantities;
    $("#props_property_alias")[0].value = sku_property_alias;
    $("#sku_properies_for_edit")[0].value = sku_properies_for_edit_array.join('#');
    $("#sku_properties_for_del")[0].value = sku_properies_for_del_array.join('#');
    $("#product_items_num")[0].value = product_items_num;
    $("#format_item_desc")[0].value = HTMLEncode($("#item_desc")[0].value);
 

}
function insertAfter(newElement,targetElement) {
    var parent = targetElement.parentNode;
    if (parent.lastChild == targetElement) {
        // 如果最后的节点是目标元素，则直接添加。因为默认是最后
        parent.appendChild(newElement);
    } else {
        //如果不是，则插入在目标元素的下一个兄弟节点 的前面。也就是目标元素的后面。
        parent.insertBefore(newElement,targetElement.nextSibling);
    }
}
//对于必填属性中的子属性进行展示
function parentPropList(o){
    //一部分属性存在子属性，而另一部分属性不存在子属性
	if ('' == o.value) {
        pid = o.name.substr(4);
        //需要把之前显示该属性的子属性的HTML元素给去掉
        if (document.getElementById('pid_' + pid + '_subDiv') != null){
            document.getElementById('pid_' + pid + '_subDiv').outerHTML = '';
        }
		//document.getElementById(o.id + '_span').innerHTML = '';
    } else {
        var pidVidArr = o.value.split(':');
		if (1 === pidVidArr.length && pidVidArr[0]) {
			pidVidArr = pidVidArr[0].split('_');
			var pid = pidVidArr[0];
			var childName = '';

			var txt = document.createElement('input');
			txt.type = 'text';
			txt.name = 'input_' + pid;
			txt.id = 'input_' + pid;
            txt.setAttribute('class', 'span6');
			txt.value = '';
            //添加作为附加子属性的元素
            var propDiv = document.createElement('div');
            propDiv.id =  o.id + '_subDiv';
            propDiv.setAttribute('class', 'row-fluid');

            var propDivName = document.createElement('div');
            propDivName.id =  o.id + '_subName';
            propDivName.setAttribute('class', 'span2 offset1');

            var propDivValue = document.createElement('div');
            propDivValue.id =  o.id + '_subValue';
            propDivValue.setAttribute('class', 'span9');

            propDivValue.appendChild(txt);
            propDivName.appendChild(document.createTextNode("自定义："));

            propDiv.appendChild(propDivName);
            propDiv.appendChild(propDivValue);
            insertAfter(propDiv,o.parentNode.parentNode);
        }else {
			var pid = pidVidArr[0];
			var vid = pidVidArr[1];
            //需要把之前显示该属性的子属性的HTML元素给去掉
            if (document.getElementById('pid_' + pid + '_subDiv') != null){
                document.getElementById('pid_' + pid + '_subDiv').outerHTML = '';
            }
            //获取在当前叶子cid和pid的下面，以pid为父亲属性的子属性数据，在AJAX获取的过程中首先就需要把提交的按钮给disabled了
            $("#form_btn")[0].disabled = 'disabled';
            var ajaxPic = document.createElement('img');
            ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
            $("#ajaxPic")[0].appendChild(ajaxPic);

            $.ajax({
                type:"post",
                data: "cId=" + $("#cid").val() + "&parentPId=" + pid,
                url: "<?php echo base_url();?>index.php/jad_goods/get_child_itemprops",
                success: function(prop_data){
                    var prop_data = eval('('+prop_data+')');
                    if(prop_data==''){
                        $("#form_btn")[0].disabled = '';
                        $("#ajaxPic")[0].innerHTML = '';
                        return false;
                    }else if ( prop_data.code === 0){
                        //当网络链接失败的时候，也会在这个地方返回数据；一定要注意对格式进行处理
                        alert('网络访问失败，请检查网络设置!');
                        $("#form_btn")[0].disabled = '';
                        $("#ajaxPic")[0].innerHTML = '';
                        return false;
                    }
                    prop_data = prop_data.item_props.item_prop;
                    var i;
                    for (i in prop_data) {
                        if (pid == prop_data[i].parent_pid && vid == prop_data[i].parent_vid) {
                            var sel = document.createElement('SELECT');
                            sel.setAttribute('name', 'pid_' + prop_data[i]['pid']);
                            sel.setAttribute('id', 'pid_' + prop_data[i]['pid']);
                            sel.setAttribute('class', 'span6');
                            var op = document.createElement('OPTION');
                            op.setAttribute('value', '');
                            op.innerHTML = '--请选择--';
                            sel.appendChild(op);
                            //alert(prop_data[i].prop_values.prop_value[0]['name']);
                            var propvalues = prop_data[i].prop_values.prop_value;
                            if(propvalues!=''){
                               //alert(propvalues[0].name_alias); 
                               var j;
                               for(j in propvalues){
                               op = document.createElement('OPTION');
                               op.setAttribute('value', prop_data[i]['pid'] + ':' + propvalues[j]['vid']);
                               op.innerHTML = propvalues[j]['name'];
                               sel.appendChild(op);
                               }
                            }
                            if (prop_data[i]['is_input_prop']) {
                                op = document.createElement('OPTION');
                                op.setAttribute('value', prop_data[i].pid);
                                op.innerHTML = '自定义';
                                sel.appendChild(op);
                            }
                            //添加作为附加子属性的元素
                            var propDiv = document.createElement('div');
                            propDiv.id = 'pid_' + pid + '_subDiv';
                            propDiv.setAttribute('class', 'row-fluid');

                            var propDivName = document.createElement('div');
                            propDivName.id =  pid + '_subName';
                            propDivName.setAttribute('class', 'span2 offset1');

                            var propDivValue = document.createElement('div');
                            propDivValue.id =  pid + '_subValue';
                            propDivValue.setAttribute('class', 'span9');

                            propDivValue.appendChild(sel);
                            propDivName.appendChild(document.createTextNode(prop_data[i]['name']));

                            propDiv.appendChild(propDivName);
                            propDiv.appendChild(propDivValue);

                            insertAfter(propDiv,o.parentNode.parentNode);
                          }
                        }
                        $("#form_btn")[0].disabled = '';
                        $("#ajaxPic")[0].innerHTML = '';
                },          
                error: function(){
                    alert("获取数据失败，请与网站管理员联系！");
                }
            });
		} 
    }
} 
//创建非关键属性中必选属性的选择表单
function createPropsForm(cid) {
    //对提交按钮做出处理
    $("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);
    //获取该cid下的销售属性
    $.ajax({
        type:"post",
        data: "parentId=" + cid,
        url: "<?php echo base_url();?>index.php/jad_goods/get_itemprops_by_cid",
        success: function(data){
            var props = eval('('+data+')');
            if(props==''){
                $("#form_btn")[0].disabled = '';
                $("#ajaxPic")[0].innerHTML = '';
                return false;
            }
            props = props.item_props.item_prop;
            var i;
            for (i in props) {
                //对数据进行处理,获取每一项销售属性
                if (props[i].is_sale_prop == false && props[i].is_key_prop == false && props[i].must == true) {
                    if (props[i].is_enum_prop) {
                        var sel = document.createElement('SELECT');
                        sel.setAttribute('name', 'pid_' + props[i].pid);
                        sel.setAttribute('id', 'pid_' + props[i].pid);
                        sel.setAttribute('class', 'span6');

                        var op = document.createElement('OPTION');
                        op.setAttribute('value', '');
                        op.innerHTML = '--请选择--';
                        sel.appendChild(op);

                        $.ajax({
                            type:"post",
                            data: "cId="+cid+"&pId=" + props[i].pid,
                            url: "<?php echo base_url();?>index.php/jad_goods/get_propvalues",
                            async: false,
                            success: function(prop_data){
                               var propvalues = eval('(' + prop_data + ')');
                               if(propvalues!=''){
                                   propvalues = propvalues.prop_values.prop_value;
                                   var j;
                                   for(j in propvalues){
                                       op = document.createElement('OPTION');
                                       op.setAttribute('value', propvalues[j].pid + ':' + propvalues[j].vid);
                                       op.innerHTML = propvalues[j].name_alias;
                                       sel.appendChild(op);
                                   }
                               } 
                            },          
                            error: function(){
                                alert("获取数据失败，请与网站管理员联系！");
                            }
                        }); 
                        //针对每一项关键属性，都用了一个div来便于多重属性的选择或者设置
                        var propDiv = document.createElement('div');
                        propDiv.id = 'pid_' + props[i]['pid'] + '_div';
                        propDiv.setAttribute("class", "row-fluid"); 

                        var propDivName = document.createElement('div');
                        propDivName.id = 'pid_' + props[i]['pid'] + '_divName';
                        propDivName.setAttribute('class', 'span2');

                        var propDivValue = document.createElement('div');
                        propDivValue.id = 'pid_' + props[i]['pid'] + '_divValue';
                        propDivValue.setAttribute('class', 'span10');

                        propDivName.appendChild(document.createTextNode(props[i].name+"："));
                        propDivValue.appendChild(sel);

                        propDiv.appendChild(propDivName);
                        propDiv.appendChild(propDivValue);
                        $("#enum-must-props")[0].appendChild(propDiv);
                        document.getElementById('pid_' + props[i].pid).onchange = function(){parentPropList(this)};
                    }
                }
            }
            $("#form_btn")[0].disabled = '';
            $("#ajaxPic")[0].innerHTML = '';
        },
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    }); 
}
$(document).ready(function(){
    $("#item_desc").cleditor(); 
    /*
    //为选择采购地绑定响应时间
    $("input[type='radio'][name='location_bought']").change(
        function() {
            var $selectedvalue = $("input[type='radio'][name='location_bought']:checked").val();
            if ($selectedvalue == 1) {
                //清除选择框
                $("#globalStockDiv")[0].outerHTML = '';
            }
            else {
                //在采购地的后面加上选择附加选择框，并调用接口，获取国家、地区值
                var propDiv = document.createElement('div');
                propDiv.id = 'globalStockDiv';
                propDiv.setAttribute('class', 'row-fluid');
                var propDivName = document.createElement('div');
                propDivName.setAttribute('class', 'span2');
                var propDivValue = document.createElement('div');
                propDivValue.setAttribute('class', 'span10');

                var propSubDiv1 = document.createElement('div');
                propSubDiv1.setAttribute('class', 'row-fluid');
                var propSubDivName1 = document.createElement('div');
                propSubDivName1.appendChild(document.createTextNode('国家/地区'));
                propSubDivName1.setAttribute('class', 'span2');
                var propSubDivValue1 = document.createElement('div');
                propSubDivValue1.setAttribute('class', 'span10');
                propSubDiv1.appendChild(propSubDivName1);
                propSubDiv1.appendChild(propSubDivValue1);
                //设置select中的值

                var sel = document.createElement('SELECT');
                sel.setAttribute('name', 'sel_global_stock');
                sel.setAttribute('id', 'sel_global_stock');
                sel.setAttribute('class', 'span6');

                var op = document.createElement('OPTION');
                op.setAttribute('value', '');
                op.innerHTML = '--请选择国家或地区--';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '美国');
                op.innerHTML = '美国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '香港');
                op.innerHTML = '香港';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '日本');
                op.innerHTML = '日本';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '英国');
                op.innerHTML = '英国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '新西兰');
                op.innerHTML = '新西兰';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '德国');
                op.innerHTML = '德国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '韩国');
                op.innerHTML = '韩国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '荷兰');
                op.innerHTML = '荷兰';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '澳洲');
                op.innerHTML = '澳洲';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '法国');
                op.innerHTML = '法国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '意大利');
                op.innerHTML = '意大利';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '台湾');
                op.innerHTML = '台湾';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '澳门');
                op.innerHTML = '澳门';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '其他');
                op.innerHTML = '其他';
                sel.appendChild(op);
                propSubDivValue1.appendChild(sel);

                var propSubDiv2 = document.createElement('div');
                propSubDiv2.setAttribute('class', 'row-fluid');
                var propSubDivName2 = document.createElement('div');
                propSubDivName2.appendChild(document.createTextNode('库存类型'));
                propSubDivName2.setAttribute('class', 'span2');
                var propSubDivValue2 = document.createElement('div');
                propSubDivValue2.setAttribute('class', 'span10');
                propSubDiv2.appendChild(propSubDivName2);
                propSubDiv2.appendChild(propSubDivValue2);
                //增加radiobox
                var radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'global_type';
                radio.id = 'global_type';
                radio.value = '1';
                propSubDivValue2.appendChild(radio);
                propSubDivValue2.appendChild(document.createTextNode('现货'));

                var radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'global_type';
                radio.id = 'global_type';
                radio.value = '2';
                propSubDivValue2.appendChild(radio);
                propSubDivValue2.appendChild(document.createTextNode('代购'));
                
                propDiv.appendChild(propDivName);
                propDiv.appendChild(propDivValue);

                propDivValue.appendChild(propSubDiv1);
                propDivValue.appendChild(propSubDiv2);

                insertAfter(propDiv,$("#location_checkbox")[0]);

            }
    });
    */
    //createPropsForm(<?php echo $productInfo['cid']; ?>);
});
</script>  
</body>

