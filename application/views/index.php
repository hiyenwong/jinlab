<div class="container">
	<div style="padding: 60px 0">
		<div id="browserAlert" class='alert alert-error' style="display:none">
			<button type="button" class="close alert-button" data-dismiss="alert">&times;</button>
			<span id="browserType"></span>
		</div>
		<?php 
		if($rs == 1)
		{
		?>
		<?php // var_dump($this->session->all_userdata());?>
		<table class="table table-bordered">
		<tr>
			<th>姓名</th>
			<th>工号</th>
			<th>职务</th>
			<th>部门</th>
			<th>分机</th>
		</tr>
		<tr class="success">
			<td><?php echo $login['userName'] ?></td>
			<td><?php echo $login['userNum'] ?></td>
			<td><?php echo $login['userPost'] ?></td>
			<td><?php echo $login['userDepName'] ?></td>
			<td><?php echo $login['userTel'] ?></td>
		</tr>
		</table>
	
<?php 
		}
		else
		{
			if ( isset($_GET['login']))
			{
				$_GET['login'] = $_GET['login'];
			}
			else
			{
				$_GET['login'] = "1";
			}
			
			if ( $_GET['login'] == 0)
			{
				echo "<div class='alert alert-error'>";
				echo '<button type="button" class="close  alert-button" data-dismiss="alert">&times;</button>';
				echo "<strong>";
				echo "必须登陆！";
				echo "</strong>";
				echo "</div>";
					
			}
			if ( isset($login['error']))
			{				
				echo "<div class='alert alert-error'>";
				echo '<button type="button" class="close  alert-button" data-dismiss="alert">&times;</button>';
				echo "<strong>";
				echo $login['error'];
				echo "</strong>";
				echo "</div>";
				
			}
		}
// 			var_dump($login);
			?>
<!-- 			<h1>欢迎！</h1> -->

<?php  //var_dump($this->session->all_userdata());?>
			
			<?php 
				$attributes = array('class' => 'form-signin form-horizontal hide fade modal loginWindow','id' => 'regModal','style' => 'display:none');
				echo form_open('../',$attributes);
			?>	
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="form-signin-heading">请登录！</h2>
				<div class="control-group">
					<label class="boolean optional control-label"><strong>用户名：</strong></label>
					<div class="controls">
						<input type="text" class="userName input-block-level easyui-validatebox" data-options="required:true,validType:'minLength[4]',missingMessage:'用户名不能为空！'" name="userName" autocomplete="off" placeholder="电脑用户名" value="<?php echo $Name?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><strong>密码：</strong></label>
					<div class="controls">
						<input type="password" class="input-block-level easyui-validatebox" data-options="required:true,missingMessage:'密码不能为空！'" name="userPass" placeholder="运营平台密码" value="<?php echo $Pass?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label checkbox">
						<input name="remember" type="checkbox" checked="checked" value="remember-me"><strong>保存用户名密码</strong>
					</label>
					<div class="controls">						
						<label class="control-label forget">
						<a href="http://10.100.1.191/woims/index.php/user/forget" target="_blank"><strong>忘记密码</strong></a>
						</label>
					</div>
				</div>
				<div class="control-group">
						<a id="loginButton" class="btn btn-large btn-primary btn-block" onclick="submitForm()"><strong>登陆</strong></a>
				</div>				
			</form>	
	<input id="controlTop" style="display:none" value="main"/>
	</div>
</div>
<script>
$.extend($.fn.validatebox.defaults.rules, {
    minLength: {
    validator: function(value, param){
    return value.length >= param[0];
    },
    message: '请至少输入{0}个字符！'
	}
});
</script>