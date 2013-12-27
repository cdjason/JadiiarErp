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
            <p class="stat" id="p_title"><span class="number">商家编码:</span><?php echo $productItem['item_id']; ?></p>
            <p class="stat" id="p_cid"><span class="number">CID:</span><?php echo $productItem['cid']; ?></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php echo $productItem['product_id']; ?></p>
            <p class="stat" id="product_title"><span class="number">产品标题:</span><?php echo $productItem['product_title']; ?></p>
        </div>

        <h1 class="page-title">发布商品信息</h1>
    </div>
        
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">商品列表</a> <span class="divider">/</span></li>
        <li class="active">发布商品信息</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
        <?php $this->load->view('includes/jad_message'); ?>  
        <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
        <div class="row-fluid">
            <div class="span2">
                <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  发布</button>
                <input type="hidden" name="publish_product_item" value="1" />
            </div>
            <div class="span1" id = "ajaxPic">
            </div>
        </div></br>

        <div class="well">
            <div class="row-fluid">
                <div class="span2" ><b>宝贝图片</b></div>
                <div class="span10" >
                    <img class="img-rounded" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($productItem['item_img_link']);?>"  >
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2" ><b>宝贝类型</b></div>
                <div class="span10" >
                     <input type="radio" name="stuffStatus" id="optionsRadios1" value="new" checked>全新&nbsp;&nbsp;&nbsp;&nbsp;
                     <input type="radio" name="stuffStatus" id="optionsRadios2" value="second">二手
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>价格</b></div>
                <div class="span10" ><input type="text" name="price" id="price" /></div></pre>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>数量</b></div>
                <div class="span10" ><input type="text" name="num" id="num" /></div>
            </div>              
            <div class="row-fluid" id = 'location_checkbox' name = 'location_checkbox'>
                <div class="span2" ><b>采购地</b></div>
                <div class="span10" >
                     <input type="radio" name="location_bought" id="location_bought" value="1" checked>国内&nbsp;&nbsp;&nbsp;&nbsp;
                     <input type="radio" name="location_bought" id="location_bought" value="2">海外及港澳台
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2"><b>必选属性</b></div>
                <div class="span10" id="enum-must-props"></div>
            </div>
            <div class="row-fluid">
                <div class="span2" ><b>商品描述</b></div>
                <div class="span10" id="item_desc"><textarea id="input11" name="input11"><?php echo $productItem['product_desc'].$productItem['item_desc']; ?></textarea></div>
            </div>              
        </div>

        <input type="hidden" name = "cid" id= "cid" value = '<?php echo $productItem['cid']; ?>' />
        <input type="hidden" name = "must_props" id = "must_props" value = '' />
        <input type="hidden" name = "product_item_props" id = "product_item_props" value = '<?php echo $productItem['iprops']; ?>' />
        <input type="hidden" name = "product_props" id = "product_props" value = '<?php echo $productItem['props']; ?>' />
        <input type="hidden" name = "product_item_property_alias" id = "product_item_property_alias" value = '<?php echo $productItem['property_alias']; ?>' />
        <input type="hidden" name = "product_inputs_str" id = "product_inputs_str" value = '<?php echo $productItem['inputs_str']; ?>' />
        <input type="hidden" name = "product_inputs_pids" id = "product_inputs_pids" value = '<?php echo $productItem['inputs_pids']; ?>' />
        <input type="hidden" name = "productTitle" id = "productTitle" value = '<?php echo $productItem['product_title']; ?>' />
        <input type="hidden" name = "img_remote_url" value="<?php echo $productItem['item_img_link']; ?>" />

	    <?php echo form_close();?>
        <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//获取非关键属性中必选属性链pid:vid
function checkAll(form){
    //对表单输入框进行验证

    //遍历非关键属性中必选属性，必须对每一个进行选择
    var mustProps = $("#enum-must-props")[0];
    var mustPropsStr = '';
    for ( var i=0;i<mustProps.childNodes.length;i++){
        if ( mustProps.childNodes[i].childNodes[1].childNodes[0].value == ''){
            alert('必须选择每一个必选属性!');
            return false;
        }
        mustPropsStr = mustPropsStr + mustProps.childNodes[i].childNodes[1].childNodes[0].value + ';';
    }
    mustPropsStr = mustPropsStr.substr(0,mustPropsStr.length-1);
    $("#must_props")[0].value = mustPropsStr;
}

String.prototype.Trim = function() { 
   return this.replace(/(^\s*)|(\s*$)/g, ""); 
}
//去除字符串左侧空格
String.prototype.LTrim = function() { 
   return this.replace(/(^\s*)/g, ""); 
}
//去除字符串右侧空格
String.prototype.RTrim = function() { 
   return this.replace(/(\s*$)/g, ""); 
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
            if(props=='') return false;
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

$(document).ready(function(){
    $("#input11").cleditor(); 
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
    
    createPropsForm(<?php echo $productItem['cid']; ?>);
});
</script>  
</body>
</html>
