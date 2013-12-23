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
            <p class="stat" id="p_title"><span class="number">Title:</span><?php echo $product['product_title']; ?></p>
            <p class="stat" id="p_cid"><span class="number">CID:</span><?php echo $product['cid']; ?></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php echo $product['product_id']; ?></p>
        </div>

        <h1 class="page-title">新增商品信息</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_product_items/<?php echo $product['product_id']; ?>">商品列表</a> <span class="divider">/</span></li>
        <li class="active">新增商品信息</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php $this->load->view('includes/jad_message'); ?>  
            <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	

        <div class="row-fluid">
            <div class="span2">
                <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  增加</button>
                <input type="hidden" name="add_product_item" value="1" />
            </div>
            <div class="span1" id = "ajaxPic">
            </div>
        </div></br>

            <div class="well">
                <div class="row-fluid">
                    <div class="span2"><b>销售属性</b></div>
                    <div class="span10">
                        <div class="row-fluid" id="enum-sale-props"></div>
                        <div class="row-fluid" id="enum-sale-additional"></div>
                    </div>
                </div>
            </div>

            <input type="hidden" id ="t_colour_img" name ="t_colour_img"/>
            <input type="hidden" id ="t_sku" name ="t_sku"/>
            <input type="hidden" id ="product_id_hidden" name ="product_id_hidden" value="<?php echo $product['product_id'];?>" />

            <?php echo form_close();?>
            <?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//提交获取属性链pid:vid，把sku表和颜色色卡图片表做成json字符串放入t_colour_img和t_sku中
function checkAll(form){
    //遍历颜色色卡图片表，如果色卡信息和图片链接没有值，则不允许提交
    var temp_tr = $("#table_color_img_url")[0].childNodes[1];
    var temp_str = '';
    for(var i=0;i<temp_tr.childNodes.length;i++){
        temp_str = temp_str + temp_tr.childNodes[i].childNodes[0].id + ',';
        temp_str = temp_str + temp_tr.childNodes[i].childNodes[0].innerHTML + ',';
        temp_str = temp_str + temp_tr.childNodes[i].childNodes[1].childNodes[0].value+ ',';
        temp_str = temp_str + temp_tr.childNodes[i].childNodes[2].childNodes[0].value+ ';';
        if (temp_tr.childNodes[i].childNodes[1].childNodes[0].value.indexOf(',') >= 0 || temp_tr.childNodes[i].childNodes[1].childNodes[0].value.indexOf(';') >= 0 || temp_tr.childNodes[i].childNodes[2].childNodes[0].value.indexOf(',') >= 0 || temp_tr.childNodes[i].childNodes[2].childNodes[0].value.indexOf(';') >= 0 ){
            alert('色卡或图片信息中不能出现逗号或分号');
            return false;
        }
    }
    temp_str = temp_str.substr(0,temp_str.length-1);
    $("#t_colour_img")[0].value = temp_str;

    //遍历sku表,需要引入一个二维数组来辅助
    var temp_th = $("#table_sku")[0].childNodes[1];
    var temp_th_str = '';
    var propSkuArr = new Array();
    for(var i=0;i<temp_th.childNodes.length;i++){
        var propSkuTempArr = new Array();
        //若当前行的列数比第一列的列数少的话,就把前一行的相应列的值加入作为当前列的值
        for(var k=0;k<temp_th.childNodes[0].childNodes.length-temp_th.childNodes[i].childNodes.length;k++){
            propSkuTempArr.push(propSkuArr[i-1][k]);
            temp_th_str = temp_th_str + propSkuArr[i-1][k] + ',';
        }
        //alert(temp_th.childNodes[i].childNodes.length);
        for(var j=0;j<temp_th.childNodes[i].childNodes.length;j++){
            if( j == temp_th.childNodes[i].childNodes.length - 1 ){
                propSkuTempArr.push(temp_th.childNodes[i].childNodes[j].childNodes[0].value);
                if (temp_th.childNodes[i].childNodes[j].childNodes[0].value.indexOf(',') >= 0 || temp_th.childNodes[i].childNodes[j].childNodes[0].value.indexOf(';') >= 0){
                    alert('商品描述信息中不能出现逗号或分号');
                    return false;
                }
                //alert(temp_th.childNodes[i].childNodes[j].childNodes[0].value);
                temp_th_str = temp_th_str + temp_th.childNodes[i].childNodes[j].childNodes[0].value + ',';
            }else{
                var temp_value = temp_th.childNodes[i].childNodes[j].id + ':' +temp_th.childNodes[i].childNodes[j].innerHTML;
                propSkuTempArr.push(temp_value);
                //alert(temp_th.childNodes[i].childNodes[j].innerHTML);
                temp_th_str = temp_th_str + temp_value + ',';
            }
        }
        temp_th_str = temp_th_str.substr(0,temp_th_str.length-1);  
        temp_th_str = temp_th_str + ';';
        propSkuArr.push(propSkuTempArr);
    }
    temp_th_str = temp_th_str.substr(0,temp_th_str.length-1);
    $("#t_sku")[0].value = temp_th_str;
}
//对更改别名框的触发事件，用于更改附加属性信息中的相关值；注意，有些类目下销售属性的值是不允许更改的，比如手机。
function setSkuAlias(o){
    //更改颜色表
    if ( $("#table_color_img_url")[0] != undefined ){
        var temp_tr = $("#table_color_img_url")[0].childNodes[1];
        for(var i=0;i<temp_tr.childNodes.length;i++){
            if(temp_tr.childNodes[i].childNodes[0].id == o.name){
                temp_tr.childNodes[i].childNodes[0].innerHTML='';
                temp_tr.childNodes[i].childNodes[0].innerHTML=o.value;
            }
        }
    }
    //更改sku属性表
    if ( $("#table_sku")[0] != undefined ){
        var temp_th = $("#table_sku")[0].childNodes[1];
        for(var i=0;i<temp_th.childNodes.length;i++){
            for(var j=0;j<temp_th.childNodes[i].childNodes.length-1;j++){
                //alert(temp_th.childNodes[i].childNodes[j].outerHTML);
                //alert(o.name);
                if(temp_th.childNodes[i].childNodes[j].id == o.name){
                    temp_th.childNodes[i].childNodes[j].innerHTML='';
                    temp_th.childNodes[i].childNodes[j].innerHTML=o.value;
                } 
            }
        }
    }
}
//定义属性名称别名所用的输入框
function nameAlias(o){
    var pidVidArr = o.value.split(':');
    //alert(o.nodeName);
    //先删除多余的别名框
    var oJu = $(o).next();
        while(oJu[0]!=undefined){
            var temp = oJu[0];
            oJu = oJu.next();
            temp.outerHTML='';
        }
    var txt = document.createElement('input');
    txt.type = 'text';
    txt.name = 'input_alias_' + o.value;
    txt.id = 'input_alias_' + o.value;
    txt.value = '';
    txt.placeholder="别名，没有就不填";
    o.parentNode.appendChild(txt);
}

//为每一个属性的名称设定一个别名
function setAlias(o){
    var oldAlias = o.childNodes[1].childNodes[0].nodeValue;
    //alert(o.childNodes[0].childNodes[0].value);
    var inputAlias = window.document.createElement("input");
    inputAlias.type = "text";
    inputAlias.name = o.childNodes[0].childNodes[0].value;
    inputAlias.value = oldAlias;
    inputAlias.onblur = function(){setSkuAlias(this);};
    inputAlias.setAttribute("class", "span12"); 
    //当checked而且没有input的时候，显示输入框
    if(o.childNodes[0].childNodes[0].checked==true && o.childNodes[1].childNodes[0].nodeType==3){
        o.childNodes[1].innerHTML= '';
        o.childNodes[1].appendChild(inputAlias);
    }
    //当没有checked但是有input的时候，隐藏输入框，从hidd中存放的原属性值来还原
    if(o.childNodes[0].childNodes[0].checked==false && o.childNodes[1].childNodes[0].nodeType==1){
        o.childNodes[1].innerHTML= '';
        o.childNodes[1].innerHTML= o.childNodes[2].value;
    }
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

function createSkuForm(pValues,tbody,layer,curString){
    if( layer < pValues.length - 1 ){
        if( pValues[layer].length == 0 ){}
            //当当前的维度没有数据的时候，但是在sku模型中是不存在的
            //createSkuForm(pValues, tbody, layer + 1, curString);
        else {
            for (var i = 1; i < pValues[layer].length; i++) {
                //alert(pValues[layer][i][0]);
                //获取当前td的rowspan的数量
                var rowSpan=1;
                var layer_temp = layer + 1;
                while ( layer_temp < pValues.length ){
                    rowSpan = rowSpan * ( pValues[layer_temp].length - 1 );
                    layer_temp++;
                }
                //alert('layer:'+layer+'i:'+i+'rowspan:'+rowSpan);
                if (layer == 0){
                    var tr = document.createElement('tr');
                    var td = document.createElement('td');
                    td.setAttribute("rowspan", rowSpan); 
                    td.appendChild(document.createTextNode(pValues[layer][i][1]));
                    td.id = pValues[layer][i][0];
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                }else{
                    if( i != 1 ){
                        var tr = document.createElement('tr');
                        var td = document.createElement('td');
                        td.setAttribute("rowspan", rowSpan); 
                        td.appendChild(document.createTextNode(pValues[layer][i][1]));
                        td.id = pValues[layer][i][0];
                        tr.appendChild(td);
                        tbody.appendChild(tr);
                    }else{
                        //取当前tbody中最后一个tr，然后在里面加
                        var td = document.createElement('td');
                        td.setAttribute("rowspan", rowSpan); 
                        td.appendChild(document.createTextNode(pValues[layer][i][1]));
                        td.id = pValues[layer][i][0];
                        tbody.childNodes[tbody.childNodes.length-1].appendChild(td);
                    }
                }
                createSkuForm( pValues, tbody, layer + 1, curString);
            }
        }
    }else if (layer == pValues.length - 1) {
        if ( pValues[layer].length == 0 ){} 
            //当当前的维度没有数据的时候，但是在sku模型中是不存在的
            //result.Add(curstring);
        else {
            for (var i = 1; i < pValues[layer].length; i++) {
                //alert("bb:"+pValues[layer][i]+"layer:"+layer);
                //result.Add(curstring + pValues[layer][i]);
                if ( i==1 && pValues.length > 1 ){
                    var td = document.createElement('td');
                    td.appendChild(document.createTextNode(pValues[layer][i][1]));
                    td.id = pValues[layer][i][0];
                    var input_desc = document.createElement('input');
                    input_desc.type = "text";
                    input_desc.setAttribute("class", "span12"); 
                    var td2 = document.createElement('td');
                    td2.appendChild(input_desc);
                    tbody.childNodes[tbody.childNodes.length-1].appendChild(td);
                    tbody.childNodes[tbody.childNodes.length-1].appendChild(td2);
                }else{
                    var input_desc = document.createElement('input');
                    input_desc.type = "text";
                    input_desc.setAttribute("class", "span12"); 
                    var td2 = document.createElement('td');
                    td2.appendChild(input_desc);
                    var tr = document.createElement('tr');
                    var td = document.createElement('td');
                    td.appendChild(document.createTextNode(pValues[layer][i][1]));
                    td.id = pValues[layer][i][0];
                    tr.appendChild(td);
                    tr.appendChild(td2);
                    tbody.appendChild(tr);
                }
            }
        }
    }
}

function setSku(){ 
    //propArr为一个二维数组，放置每一个销售属性下checked的value和名称
    var propArr = new Array();
    var m = 0;
    var prop_divs = $("#enum-sale-props")[0];
    //销售属性下的每一类属性不能一个都不选，必须每一类都至少选择一个值才会生成sku的设置框
    for (var j=0;j<prop_divs.childNodes.length;j++) {//遍历每一个有效的销售属性
        var propValueArr = new Array();
        //每一个属性值数组的一个位置存放该销售属性的名字
        propValueArr.push(prop_divs.childNodes[j].childNodes[0].innerHTML);
        for (var k=1;k<prop_divs.childNodes[j].childNodes[1].childNodes.length;k++) {
            if(prop_divs.childNodes[j].childNodes[1].childNodes[k].childNodes[0].childNodes[0].childNodes[0].checked){
                //把该类销售属性下checked的值都加入到这个数组当中去
                var propValueArray = new Array();
                propValueArray.push(prop_divs.childNodes[j].childNodes[1].childNodes[k].childNodes[0].childNodes[0].childNodes[0].value);
                //alert(prop_divs.childNodes[j].childNodes[1].childNodes[k].childNodes[0].childNodes[2].value);
                propValueArray.push(prop_divs.childNodes[j].childNodes[1].childNodes[k].childNodes[0].childNodes[2].value);
                //propVauleArray.push(prop_divs.childNodes[j].childNodes[1].childNodes[k].childNodes[0].childNodes[0].childNodes[0].value);
                propValueArr.push(propValueArray);
            }
        }
        propArr.push(propValueArr);
    }
    //必须每一类都至少选择一个值才会生成sku的设置框，用isRight来判断
    var isRight=1;
    for(var i=0;i<propArr.length;i++){
        if(propArr[i].length==1){
            isRight = 0;
        }
    }
    //对sku属性进行组合排序,是直接限定，还是采用笛卡儿积的办法来进行通用的算法设计,销售属性以多维数组的形式存放在propArr数组中
    //第一维是销售属性item，第二维是销售属性下的属性item，第三维放置具体的值，value and name
    if(isRight==1){
        //alert(propArr.length);
        //目前假定销售属性下必有颜色属性，构造color_img_url的标题
        for(var c=0;c<propArr.length;c++){
            var colorValue = propArr[c][1][0].split(':');
            if(colorValue[0]=='1627207'){
                //构造分颜色的图片上传区
                var th = document.createElement('th');
                th.setAttribute("class", "span2"); 
                th.appendChild(document.createTextNode('颜色分类'));
                var th1 = document.createElement('th');
                th1.setAttribute("class", "span2"); 
                th1.appendChild(document.createTextNode('色卡'));
                var th2 = document.createElement('th');
                th2.setAttribute("class", "span8"); 
                th2.appendChild(document.createTextNode('图片URL'));
                var tr = document.createElement('tr');
                tr.appendChild(th);
                tr.appendChild(th1);
                tr.appendChild(th2);
                var thead = document.createElement('thead');
                thead.appendChild(tr);

                var table = document.createElement('table');
                table.id = 'table_color_img_url';
                table.setAttribute("class", "table table-bordered table-condensed"); 
                table.appendChild(thead);


                var tbody = document.createElement('tbody');
                //直接在这个下面加就行了
                for(var i=1;i<propArr[c].length;i++){
                    var td = document.createElement('td');
                    td.id = propArr[c][i][0];
                    td.appendChild(document.createTextNode(propArr[c][i][1]));
                    var input = document.createElement('input');
                    input.type = "text";
                    input.setAttribute("class", "span12"); 
                    var input1 = document.createElement('input');
                    input1.type = "text";
                    input1.setAttribute("class", "span12"); 
                    var td1 = document.createElement('td');
                    td1.appendChild(input);
                    var td2 = document.createElement('td');
                    td2.appendChild(input1);
                    var tr1 = document.createElement('tr');
                    tr1.appendChild(td);
                    tr1.appendChild(td1);
                    tr1.appendChild(td2);
                    tbody.appendChild(tr1);
                }
                table.appendChild(tbody);

                $("#enum-sale-additional")[0].innerHTML='';
                $("#enum-sale-additional")[0].appendChild(table);
                //alert($("#enum-sale-additional")[0].innerHTML);
            }
        }
        //构造sku的补充属性
        var tr = document.createElement('tr');
        for(var p=0;p<propArr.length;p++){
            //获取属性id
            //alert(propArr[p][1][0].split(':')[0]);
            var th = document.createElement('th');
            th.setAttribute("class", "span2"); 
            th.id = propArr[p][1][0].split(':')[0];
            th.appendChild(document.createTextNode(propArr[p][0]));
            tr.appendChild(th);
        }
        var th = document.createElement('th');
        th.appendChild(document.createTextNode('商品描述'));
        tr.appendChild(th);

        var thead = document.createElement('thead');
        thead.appendChild(tr);
        var table = document.createElement('table');
        table.id = 'table_sku';
        table.setAttribute("class", "table table-bordered table-condensed"); 
        table.appendChild(thead);
        var tbody = document.createElement('tbody');
        //调用递归函数处理笛卡儿积
        createSkuForm(propArr,tbody,0,'');
        table.appendChild(tbody);
        $("#enum-sale-additional")[0].appendChild(table);
    }else
    {
        $("#enum-sale-additional")[0].innerHTML='';
    }
}

//创建销售属性的选择表单
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
            if(props=='') {
                $("#form_btn")[0].disabled = '';
                $("#ajaxPic")[0].innerHTML = '';
                return false;
            }
            props = props.item_props.item_prop;
            var i;
            for (i in props) {
                //对数据进行处理,获取每一项销售属性
                if (props[i].is_sale_prop == 'true' || props[i].is_sale_prop === true) {
                    //创建每一个销售属性的DIV
                    var propDiv = document.createElement('div');
                    propDiv.id = 'pid_' + props[i].pid + '_div';
                    propDiv.setAttribute("class", "row-fluid"); 
                    //propDiv.appendChild(document.createElement('br'));
                    //创建两个row-fluid型的子DIV，一个放置销售属性的名称，另一个放置属性值
                    var propDivName = document.createElement('div');
                    propDivName.setAttribute("class", "row-fluid"); 
                    propDivName.appendChild(document.createTextNode(props[i].name+"："));
                    var propDivValue = document.createElement('div');
                    propDivValue.setAttribute("class", "row-fluid"); 
                    //$("#enum-sale-props")[0].appendChild(document.createTextNode(props[i].name+"："));
                    propDivValue.appendChild(document.createElement('br'));
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
                               //对某一项销售属性下的选项进行处理
                               for(j in propvalues){
                                   
                                   var span_prop = document.createElement('div');
                                   span_prop.id = 'pid_' + props[i].pid + '_sub_div';
                                   span_prop.setAttribute("class", "span2"); 
                                   
                                   
                                   var span_prop_main = document.createElement('div');
                                   //span_prop.id = 'pid_' + props[i].pid + '_sub_div';
                                   span_prop_main.setAttribute("class", "row-fluid"); 

                                   var span_prop_main_check = document.createElement('div');
                                   //span_prop.id = 'pid_' + props[i].pid + '_sub_div';
                                   span_prop_main_check.setAttribute("class", "span2"); 

                                   var span_prop_main_alias = document.createElement('div');
                                   //span_prop.id = 'pid_' + props[i].pid + '_sub_div';
                                   span_prop_main_alias.setAttribute("class", "span10"); 


                                   var check = window.document.createElement("input");
                                   check.type = "checkbox";
                                   check.name = 'pid_' + propvalues[j].pid;
                                   check.id = 'pid_' + propvalues[j].pid;
                                   check.value = propvalues[j].pid + ':' + propvalues[j].vid;
                                   
                                   check.onclick = function(){setSku();
                                   };
                                   
                                   span_prop_main_check.appendChild(check);
                                   //var span_vname = window.document.createElement("span");
                                   //span_vname.innerHTML = propvalues[j].name_alias;
                                   span_prop_main_alias.appendChild(document.createTextNode(propvalues[j].name_alias));
                                   span_prop_main.appendChild(span_prop_main_check);
                                   span_prop_main.appendChild(span_prop_main_alias);
                                   //hidden一个值，用于存放未被修改过的原属性值

                                   var hidd = window.document.createElement("input");
                                   hidd.type = "hidden";
                                   hidd.value = propvalues[j].name_alias;
                                   span_prop_main.appendChild(hidd);
                                   //为每一个销售属性的DIV绑定一个设置别名的方法来设置别名
                                   span_prop_main.onclick = function(){setAlias(this);
                                   };
                                   span_prop.appendChild(span_prop_main);

                                   propDivValue.appendChild(span_prop);
                               }

                           } 
                        },          
                        error: function(){
                            alert("获取数据失败，请与网站管理员联系！");
                        }
                    }); 
                    propDiv.appendChild(propDivName);
                    propDiv.appendChild(propDivValue);
                    $("#enum-sale-props")[0].appendChild(propDiv);
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
    createPropsForm(<?php echo $product['cid']; ?>);
    /*
$.getJSON("http://127.0.0.1/JadiiarErp/top_cats/11", function(json){
  alert(json.childCategoryList.categoryPropList);
});
     */
/*
    $('#form_btn').confirm({
		'title' : '新增产品信息',
		'message' : '您确定要新增该产品信息吗？',        
        'action' : function() {
			$('#test_form').submit();
		}
});
 */

});
</script>  
  </body>
</html>



