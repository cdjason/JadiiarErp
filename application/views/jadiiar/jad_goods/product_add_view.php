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
                <p class="stat"><span class="number">input_pids</span></p>
                <p class="stat"><span class="number">input_str</span></p>
                <p class="stat"><span class="number">props</span></p>
                <p class="stat" id="p_cid"><span class="number">cid</span></p>
</div>

            <h1 class="page-title">新增产品信息</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">产品列表</a> <span class="divider">/</span></li>
            <li class="active">新增产品信息</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn" /><i class="icon-plus"></i> 增加
  </button>
  <input type="hidden" name="add_goods_first" value="1" />
  <div class="btn-group">
  </div>
</div>

<div class="well">
  <div class="row-fluid">
     <div class="span2" id="parentDiv"><b>产品类目</b></div>
     <div class="span10" id="parentCidDiv"></div>
  </div><br><br><br>
  <div class="row-fluid">
     <div class="span2"><b>关键属性</b></div>
     <div class="span10" id="enum-one-props"></div>
  </div>
</div>

<div class="well">
  <div class="row-fluid">
     <div class="span2" ><b>产品标题</b></div>
     <div class="span10" ><input = "text" id = "product_title" name = "product_title" class="input-large" /></div>
  </div>              
</div>

<input type="hidden" name="cid" id="cid"/>
<input type="hidden" name="props" id="props" />
<input type="hidden" name="input_str" id="input_str"/>
<input type="hidden" name="input_pids" id="input_pids"/>
	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//及时响应文本输入框的内容
function submitDate(o){
    alert(34);
    //IE
    /*
    if(window.attachEvent){
      $("#sendMessageText").get(0).attachEvent("onpropertychange",function (o){
         if(o.srcElement.value) $( "#send_button" )[0].disabled=false;
         else $( "#send_button" )[0].disabled=true;
      });
      //非IE
    }else{
      $("#sendMessageText").get(0).addEventListener("input",function(o){
        if(o.srcElement.value) $( "#send_button" )[0].disabled=false;
        else $( "#send_button" )[0].disabled=true;
      },false);
}*/
}
//递归
function getInputStr(inputId) {
//alert(inputId);
/*
	for (var i in props) {
		if ('0' != props[i]['parent_vid']) {
			if (inputId == props[i]['pid']) {
				inputStr = props[i]['name'] + ';' + inputStr;
				pushArr.push('pid_' + inputId);

				for (var j in propvalues) {
					if (props[i]['parent_vid'] == propvalues[j]['vid']) {
						inputStr = propvalues[j]['name_alias'] + ';' + inputStr;
					}
				}
				getInputStr(props[i]['parent_pid']);
			}
		} else {
			if (inputId == props[i]['pid']) {
				pushArr.push('pid_' + inputId);
				pushPidsArr.push(inputId);
			}
		}
    }
*/
}

//提交获取属性链pid:vid
function checkAll(form){
    var formElements = form.elements;
    var propStr = '';
    var inputStr = ''; 
    var inputPidsStr = ''; 
     /*
	for (var i=0;i<formElements.length;i++) {
		var e = formElements[i];
		if (e.type == 'text') {
            var inputArr = e.id.split('_');
			if (2 == inputArr.length) {
				var inputId = inputArr[1];
				inputStr = e.value + inputStr;
				getInputStr(inputId);
				inputStr = ',' + inputStr;
			} else {
				inputStr += ';' + e.id + ';' + e.value + ';';
            }
		}
    }
    inputStr = inputStr.substr(1);
    alert(inputStr); 
    //alert($("#enum-one-props")[0].childNodes[0].id.substr(4,$("#enum-one-props")[0].childNodes[0].id.length-8));
    //alert($("#enum-one-props")[0].childNodes[1].id.substr(4,$("#enum-one-props")[0].childNodes[1].id.length-8));
   
	for (var j=0;j<$("#enum-one-props")[0].childNodes.length;j++) {
		inputPidsStr = ',' + $("#enum-one-props")[0].childNodes[j].id.substr(4,$("#enum-one-props")[0].childNodes[j].id.length-8) + inputPidsStr;
	}
	inputPidsStr = inputPidsStr.substr(1);
    alert(inputPidsStr); 
      */
    //遍历关键属性DIV下的各个属性div
    var prop_divs = $("#enum-one-props")[0];
	for (var j=0;j<prop_divs.childNodes.length;j++) {
        //alert($("#enum-one-props")[0].childNodes[j].outerHTML);
        //alert($("#enum-one-props")[0].childNodes.length);
        //若存在LAEL = input,那么就把pid与vid放入input pid与str中
        var prop_div = prop_divs.childNodes[j];
        //alert(prop_div.childNodes.length);
        for (var k=0;k<prop_div.childNodes.length;k++) {
            //alert(prop_div.childNodes[k].nodeName);
            if(prop_div.childNodes[k].nodeName == 'INPUT'){
                //说明为输入性的属性
                inputStr += ',' + prop_div.childNodes[k].value;
                inputPidsStr += ',' + prop_divs.childNodes[j].id.substr(4,prop_divs.childNodes[j].id.length-8);
            }
            if(prop_div.childNodes[k].nodeName == 'SELECT'){
                //如果选择的是自定义，则存在input
                if(prop_div.childNodes[k].value == '20000'){
                    inputStr += ',' + prop_divs.childNodes[j].childNodes[2].childNodes[0].value;
                    inputPidsStr += ',' + prop_div.childNodes[k].value;
                }else{
                    propStr +=  prop_div.childNodes[k].value + ';';
                }

            }
        }
	}
    inputStr = inputStr.substr(1);
    inputPidsStr = inputPidsStr.substr(1);
    //alert(inputStr);
    //alert(inputPidsStr);
    //alert(propStr);
    $("#props")[0].value = propStr; 
    $("#input_str")[0].value = inputStr; 
    $("#input_pids")[0].value = inputPidsStr; 
}
//对于关键属性中的子属性进行展示
function parentPropList(o){
	if ('' == o.value) {
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
			txt.value = '';
			document.getElementById('pid_' + pid + '_span').innerHTML = '';
			document.getElementById('pid_' + pid + '_span').appendChild(txt);
            //貌似在修改颜色或者尺码时用的
			if (2 == pidVidArr.length) {
				childName = pidVidArr[1];
                //失去焦点时触发事件
				txt.onblur = function(){childTemplate(childName, pid);};
			}
        }else {
			var pid = pidVidArr[0];
			var vid = pidVidArr[1];
            document.getElementById('pid_' + pid + '_span').innerHTML = '';
            //获取在当前叶子cid和pid的下面，以pid为父亲属性的子属性数据
            $.ajax({
                type:"post",
                data: "cId=" + $("#cid").val() + "&parentPId=" + pid,
                url: "<?php echo base_url();?>index.php/jad_goods/get_child_itemprops",
                success: function(prop_data){
                    var prop_data = eval('('+prop_data+')');
                    if(prop_data=='') return false;
                    prop_data = prop_data.item_props.item_prop;
                    var i;
                    for (i in prop_data) {
                        if (pid == prop_data[i].parent_pid && vid == prop_data[i].parent_vid) {
                            var sel = document.createElement('SELECT');
                            sel.setAttribute('name', 'pid_' + prop_data[i]['pid']);
                            sel.setAttribute('id', 'pid_' + prop_data[i]['pid']);
                            var op = document.createElement('OPTION');
                            op.setAttribute('value', '');
                            op.innerHTML = '--请选择--';
                            sel.appendChild(op);
                            $.ajax({
                                type:"post",
                                data: "cId="+$("#cid").val()+"&pId=" + prop_data[i].pid,
                                url: "<?php echo base_url();?>index.php/jad_goods/get_propvalues",
                                async: false,
                                success: function(propvalue_data){
                                   var propvalues = eval('(' + propvalue_data + ')');
                                   if(propvalues!=''){
                                       propvalues = propvalues.prop_values.prop_value;
                                       //alert(propvalues[0].name_alias); 
                                       var j;
                                       for(j in propvalues){
                                       op = document.createElement('OPTION');
                                       op.setAttribute('value', propvalues[j]['pid'] + ':' + propvalues[j]['vid']);
                                       op.innerHTML = propvalues[j]['name_alias'];
                                       sel.appendChild(op);
                                       }
                                   } 
                                },          
                                error: function(){
                                    alert("获取数据失败，请与网站管理员联系！");
                                }
                            }); 
                            if (prop_data[i]['is_input_prop']) {
                                op = document.createElement('OPTION');
                                op.setAttribute('value', prop_data[i].pid);
                                op.innerHTML = '自定义';
                                sel.appendChild(op);
                            }
                                document.getElementById('pid_' + prop_data[i].parent_pid + '_span').appendChild(sel);
                          }
                        }
                },          
                error: function(){
                    alert("获取数据失败，请与网站管理员联系！");
                }
            });
		} 
        
    }
    
}
function childTemplate (childName, pid) {

    /*
	if ('' == document.getElementById('input_' + pid).value) {
		var txt = document.createElement('input');
		txt.type = 'text';
		txt.name = 'input_' + pid;
		txt.id = 'input_' + pid;
		txt.value = '';
		document.getElementById('pid_' + pid + '_span').innerHTML = '';
		document.getElementById('pid_' + pid + '_span').appendChild(txt);
		txt.onblur = function(){childTemplate(childName, pid);};
	} else if (!document.getElementById(childName)) {
		var txt = document.createElement('input');
		txt.type = 'text';
		txt.name = childName;
		txt.id = childName;
		txt.value = '';

		var childTempSpan = document.createElement('span');
		childTempSpan.id = 'childTemp_' + pid;
		childTempSpan.innerHTML = '　' + childName + '：';
		document.getElementById('pid_' + pid + '_span').appendChild(childTempSpan);
		document.getElementById('pid_' + pid + '_span').appendChild(txt);
}*/
}

//创建关键属性的选择表单
function createPropsForm(cid) {
        //获取该cid下的关键属性
        //调用api读取相应的数据
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
                    //对数据进行处理,获取关键属性
                    if (props[i].is_key_prop == 'true' || props[i].is_key_prop === true) {
                        //获取枚举型属性或者父亲属性id为0的属性
                        if (props[i].is_enum_prop && '0' == props[i].parent_pid) {
                            //构建关键属性的HTML元素
                            var sel = document.createElement('SELECT');
                            sel.setAttribute('name', 'pid_' + props[i].pid);
                            sel.setAttribute('id', 'pid_' + props[i].pid);
                            sel.setAttribute('class', 'input-xlarge');

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
                                       //alert(propvalues[0].name_alias); 
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
 
                            if (props[i].is_input_prop) {
                                op = document.createElement('OPTION');
                                if (props[i].child_template) {
                                    op.setAttribute('value', props[i].pid + '_' + props[i].child_template);
                                } else {
                                    op.setAttribute('value', props[i].pid);
                                }
                                op.innerHTML = '自定义';
                                sel.appendChild(op);
                            }
                            //针对每一项关键属性，都用了一个div来便于多重属性的选择或者设置
                            var propDiv = document.createElement('div');
                            propDiv.id = 'pid_' + props[i]['pid'] + '_div';
                            propDiv.class = "row-fluid";
                            propDiv.appendChild(document.createTextNode(props[i].name+"："));
                            propDiv.appendChild(sel);

                            var propSpan = document.createElement('div');
                            propSpan.id = 'pid_' + props[i].pid + '_span';
                            propSpan.class = "row-fluid";
                            propDiv.appendChild(propSpan);
                            $("#enum-one-props")[0].appendChild(propDiv);
                            document.getElementById('pid_' + props[i].pid).onchange = function(){parentPropList(this)};
                        }else if ('0' == props[i].parent_pid){
                            //获取非枚举型的关键属性
                            var txt = document.createElement('input');
                            txt.type = 'text';
                            txt.name = 'input_' + props[i].pid;
                            txt.id = 'input_' + props[i].pid;
                            txt.value = '';
				            txt.onblur = 'javascript:submitDate(this);';
                            //针对每一项关键属性，都用了一个div来便于多重属性的选择或者设置
                            var propDiv = document.createElement('div');
                            propDiv.id = 'pid_' + props[i].pid + '_div';
                            propDiv.class = "row-fluid";
                            propDiv.appendChild(document.createTextNode(props[i].name+"："));
                            propDiv.appendChild(txt);

                            var propSpan = document.createElement('div');
                            propSpan.id = 'pid_' + props[i].pid + '_span';
                            propSpan.class = "row-fluid";
                            propDiv.appendChild(propSpan);
                            $("#enum-one-props")[0].appendChild(propDiv);
                        }
                    }
                }
                
            },
            error: function(){
                alert("获取数据失败，请与网站管理员联系！");
            }
        }); 
}

//响应类目选择的动作
function childCidList(o, p_id)
{
    //当选择非叶子类目或是叶子类目之后，关键属性中的内容都需要消除掉;当前元素节点之后的兄弟节点也需要删除
    $("#enum-one-props")[0].innerHTML='';
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
    var is_parent=o.options[o.selectedIndex ].is_parent;
    if(is_parent === true || is_parent == 'true')
    {
        //非叶子类目，则继续以该cid为父亲id添加子类目下拉菜单
        createCidSelect(cid)
    }
    else
    {
        //叶子类目，则显示该cid
        //$("#parentDiv")[0].appendChild(document.createElement("br"));
        //$("#parentDiv")[0].appendChild(document.createTextNode("cid"+cid));
        //$('#product_cid')[0].getElementsByTagName('label')[0].innerHTML = "商品类目   cid:" + cid;
        $("#cid")[0].value=cid;
        var i=1;
        while($("#p_cid")[0].childNodes[i]!=undefined){
            $("#p_cid")[0].childNodes[i].nodeValue='';
            i++;
        }
        $("#p_cid")[0].appendChild(document.createTextNode(cid));
        createPropsForm(cid);
}
}

//创建类目选择的下拉菜单，参数为parent_id
function createCidSelect(parent_id) 
{
  //调用api读取相应的数据
  $.ajax({
     type:"post",
     data: "parentId=" + parent_id,
     url: "<?php echo base_url();?>index.php/jad_goods/get_itemcats_by_parent_id",
     success: function(data){
         var jsObject = eval('('+data+')');
         jsObject = jsObject.item_cats.item_cat;

	     var sel = document.createElement('SELECT');
	     sel.setAttribute('name', 'pid_' + parent_id);
         sel.setAttribute('id', 'pid_' + parent_id);
         sel.setAttribute('class', 'input-xlarge');
	     var op = document.createElement('OPTION');
	     op.setAttribute('value', '');
	     op.innerHTML = '--请选择--';
	     sel.appendChild(op);
         var i;
		 for (i in jsObject) {
			if (jsObject[i].status == 'normal') {
				var option = document.createElement('OPTION');
				option.innerHTML = jsObject[i].name;
				option.setAttribute('value', jsObject[i].cid);
				option.is_parent=jsObject[i].is_parent;//判断是否是子类目
				sel.appendChild(option);
			}
		 }
         //在第二个label之前创建下拉菜单，即"关键属性"的label之前
         //$('#product_cid')[0].insertBefore(sel,$('#product_cid')[0].getElementsByTagName('label')[1]);
         $("#parentCidDiv")[0].appendChild(sel);
         sel.onchange = function(){childCidList(this, parent_id);};
     },
     error: function(){
         alert("获取数据失败，请与网站管理员联系！");
         //setTimeout($.unblockUI, 20);
     }
  }); 
}
$(document).ready(function(){
    //页面启动的时候，就去调用增加类目选择下拉菜单的方法，只不过传入参数为0，即读取无父子节点的类节点
    createCidSelect(0);
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



