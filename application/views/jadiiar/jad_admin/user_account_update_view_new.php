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
            <h1 class="page-title">编辑用户信息</h1>
        </div>
        
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">用户列表</a> <span class="divider">/</span></li>
            <li class="active">用户资料</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  

		<div class="span1" id = "ajaxPic">
		</div>

    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">基本资料</a></li>
      <li><a href="#profile" data-toggle="tab">密码</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active in" id="home">

		<?php $attributes = array('id' => 'user_update_form','class' => 'form-horizontal','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
		<div class="control-group">
			<label class="control-label" for="full_name">用户姓名</label>
			<div class="controls">
				<input type="text" id="full_name" class="input-xlarge" name="update_full_name" value="<?php echo set_value('update_full_name',$user['upro_full_name']);?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="phone_number">电话</label>
			<div class="controls">
				<input type="text" id="phone_number" class="input-xlarge" name="update_phone_number" value="<?php echo set_value('update_phone_number',$user['upro_phone']);?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="full_name">Email</label>
			<div class="controls">
				<input type="text" id="email_address" class="input-xlarge" name="update_email_address" value="<?php echo set_value('update_email_address',$user[$this->flexi_auth->db_column('user_acc', 'email')]);?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="">所在地区</label>
			<div class="controls" id="location_div"></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="full_name">详细地址</label>
			<div class="controls">
				<textarea id="address_01" name="update_address_01" rows="3" class="input-xlarge"><?php echo set_value('update_address_01',$user['upro_address']);?></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="full_name">邮编</label>
			<div class="controls">
				<input type="text" id="post_code" class="input-xlarge" name="update_post_code" value="<?php echo set_value('update_post_code',$user['upro_post_code']);?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="full_name">用户名</label>
			<div class="controls">
				<input type="text" id="username" name="update_username" value="<?php echo set_value('update_username',$user[$this->flexi_auth->db_column('user_acc', 'username')]);?>" class="tooltip_trigger" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="full_name">用户组</label>
			<div class="controls">
				<select id="group" name="update_group" class="input-xlarge" >
				<?php foreach($groups as $group) { ?>
				  <?php $user_group = ($group[$this->flexi_auth->db_column('user_group', 'id')] == $user[$this->flexi_auth->db_column('user_acc', 'group_id')]) ? TRUE : FALSE;?>
					<option value="<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>" <?php echo set_select('update_group', $group[$this->flexi_auth->db_column('user_group', 'id')], $user_group);?>>
				<?php echo $group[$this->flexi_auth->db_column('user_group', 'name')];?>
					</option>
				<?php } ?>
				</select>
			</div>
		</div>
		<input type="hidden" name="location_name_chain" id="location_name_chain" />
		<input type="hidden" name="location_id_chain" id="location_id_chain" />
		<div class="control-group">
			<label class="control-label" for="full_name"></label>
			<div class="controls">
				<input type="submit" value="保存" name="update_user_account" class="btn btn-primary" />
			</div>
		</div>
	  <?php echo form_close();?>
      </div>
      <div class="tab-pane fade" id="profile">
		<?php $attributes = array('id' => 'pass_update_form','class' => 'form-horizontal');echo form_open(current_url(), $attributes);?>  	
<div class="control-group">
	<label class="control-label" for="new_password">新密码</label>
	<div class="controls">
        <input type="password" class="input-xlarge" id="new_password" name="new_password" value="<?php echo set_value('new_password');?>"/><span class="help-inline">	密码长度不能少于<?php echo $this->flexi_auth->min_password_length(); ?>位；阿拉伯数字, 破折号, 下划线, 字母和符号字符有效。</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="confirm_new_password">确认新密码</label>
	<div class="controls">
        <input type="password" class="input-xlarge" id="confirm_new_password" name="confirm_new_password" value="<?php echo set_value('confirm_new_password');?>"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="change_password"></label>
	<div class="controls">
            <input type="submit" value="更新" name="change_password" class="btn btn-primary" />
	</div>
</div>
	  <?php echo form_close();?>
      </div>
  </div>

<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>  
<script>
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

function checkAll(form){
    //遍历、验证、获取地域信息
    var oJu = $("#location_div")[0];
    var cidName = '';
    var locationId = '';
	for (var i=0;i<oJu.childNodes.length;i++){
		if(oJu.childNodes[i].value == ''){
				alert("地区信息不能不选");
				return false;
		}
        cidName = cidName + ',' + oJu.childNodes[i].options[oJu.childNodes[i].selectedIndex].text; 
        locationId = locationId + ',' + oJu.childNodes[i].value; 
	}
    $("#location_name_chain")[0].value = cidName.substr(1); 
    $("#location_id_chain")[0].value = locationId.substr(1); 
}

$(document).ready(function(){
	var country = '<?php echo $areas_info; ?>';
    window.areasObject = top_error_check(country );
	//从数据库的地址信息中找出串值
	var idStr = '';
	var country = "<?php echo $user['upro_country']; ?>";
	if ( country != ''){
			var tempArr = country.split(',');
			idStr = idStr + ',' + tempArr[1];
	}
	//每一个人的地址一定含有国家的信息
    createAreaSelect("0",tempArr[1]);
	var county = "<?php echo $user['upro_county']; ?>";
	if ( county != ''){
			var tempArr = county.split(',');
			idStr = idStr + ',' + tempArr[1];
	}
	var city = "<?php echo $user['upro_city']; ?>";
	if ( city != ''){
			var tempArr = city.split(',');
			idStr = idStr + ',' + tempArr[1];
	}
	var district = "<?php echo $user['upro_district']; ?>";
	if ( city != ''){
			var tempArr = district.split(',');
			idStr = idStr + ',' + tempArr[1];
	}
	var idTempArr = idStr.substr(1).split(',');
	for(var i=0;i<idTempArr.length-1;i++){
		createAreaSelect(idTempArr[i],idTempArr[i+1]);
	}
    //$("#0_select")[0].text = '中国';
    //为表单绑定验证
    $('#user_update_form').validate({
        rules: {
          update_full_name: {
            required: true
          },
          update_phone_number: {
            required: true
          },
          update_email_address: {
		    email: true,	
            required: true
          },
          update_username: {
            required: true
          } ,
          update_address_01: {
            rangelength: [5,200],  
            required: true
          },
          update_post_code: {
            required: true
          },
          update_group: {
            required: true
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

    $('#pass_update_form').validate({
        rules: {
          new_password: {
            rangelength: [8,20],  
            required: true
          },
          confirm_new_password: {
            rangelength: [8,20],  
			equalTo: "#new_password", 
            required: true
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
//响应类目选择的动作
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

</script>  

</body>
</html>



