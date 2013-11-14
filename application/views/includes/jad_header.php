	<div class="content_wrap nav_bg">
		<div id="sub_nav_wrap" class="content">
			<ul id="sub_nav">

				<li class="css_nav_dropmenu" style="display:<?php //echo $this->flexi_auth->in_group('Master Admin')?"none":"" 
					?> ;">
					<a href="#">任务处理</a>
					<ul>
		         <li><a href="<?php echo $base_url;?>index.php/jad_mission/manage_current_missions">查看当前购买任务</a></li>
		         <li><a href="<?php echo $base_url;?>index.php/jad_mission/history_missions_search">历史购买任务查询</a></li>
		         <li><a href="<?php echo $base_url;?>index.php/jad_mission/manage_current_mail_missions">查看当前邮寄任务</a></li>
		         <li><a href="<?php echo $base_url;?>index.php/jad_mission/history_mail_search">历史邮寄任务查询</a></li>
	      	</ul>	
			  </li>	
				<li class="css_nav_dropmenu" style="display:<?php echo $this->flexi_auth->in_group('Master Admin')?"":"none" ?> ;">
					<a href="#">任务管理</a>
					<ul>
		         <li><a href="<?php echo $base_url;?>index.php/jad_mission/new_mission">下达新任务</a></li>
		         <li><a href="<?php echo $base_url;?>index.php/jad_mission/missions_search">任务查询</a></li>
					</ul>
			  </li>			  
				<li class="css_nav_dropmenu">
					<a href="#">商品信息维护</a>
					<ul>
		         <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_goods_first">商品一级信息维护</a></li>
	      	</ul>	
			  </li>	
				<li class="css_nav_dropmenu" style="display:<?php echo $this->flexi_auth->in_group('Master Admin')?"":"none" ?> ;">
					<a href="#">库存信息维护</a>
					<ul>
		         <li><a href="<?php echo $base_url;?>index.php/jad_inventory/inventory_search">库存综合查询</a></li>
	      	</ul>	
			  </li>							
				
			  <li class="css_nav_dropmenu">
					<a href="#">其他信息维护</a>
					<ul>
		         <li><a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_suppliers">供应商信息维护</a></li>
		         <li><a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_branches">分店信息维护</a></li>
					</ul>	
			  </li>	

				<li class="css_nav_dropmenu">
					<a href="#">用户信息维护</a>
					<ul>
						 <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">用户一览表</a></li>
						 <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_groups">角色维护</a></li>	
						 <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_privileges">权限维护</a></li>							
					</ul>		
				</li>
				<li class="css_nav_dropmenu">
					<a href="#">我的账户</a>
					<ul>
				     <li><a href="<?php echo $base_url;?>index.php/jad_auth_public/update_account">编辑基本信息</a></li>	
		         <li><a href="<?php echo $base_url;?>index.php/jad_auth_public/change_password">更改密码</a></li>	
					</ul>	
			  </li>
			  <?php if ($this->flexi_auth->is_logged_in_via_password()) { ?>
				<li><a href="<?php echo $base_url;?>index.php/jad_auth/logout">退出登录</a></li>
			  <?php }else{ ?>
				<li><a href="<?php echo $base_url;?>index.php/jad_auth/login">登录</a></li>
			  <?php } ?>
			</ul>
		</div>
	</div>