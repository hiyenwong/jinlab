<div class="container">
	<div style="margin:60px 0;">
		
<?php 
if(isset($message)){
	echo "<div class='alert alert-error'>";
	echo '<a type="a" style="margin-top:1px" class="close  alert-a" data-dismiss="alert">&times;</a>';
	echo "<strong>";
	echo $message;
	echo "</strong>";
	echo "</div>";
}
?>
<?php 
if ( $form == '1')	
{
?>
<form id="resumeForm" action="" method="post">
<h4>使用简历系统帐号密码登陆</h4>
<div id="gModel" style="display:none">	
	<div>
		用户名：<input id='resumeUser' type="text" name="username" value="" />
	</div>
	<div>
		密　码：<input id='resumePass' name="password" type="password" value=""/>
	</div>	
</div>
<div id="qModel" style="width:100%;" align="center">
	<div style="width:30%;margin:0 0 10px 0" align="left">
	<?php echo $quickLogin;?>
	</div>

</div>
<div>
<input id="resumeLogin" type="submit" name="login" style="height: 30px;display:none" class="btn btn-primary" value="登陆"/>
<a class="btn btn-primary" href="http://10.100.1.191/resume/unlock.php" target="_blank">解锁帐号</a>
<a class="btn btn-primary" href="javascript:void(0)" onclick="loginModel(this)">普通登陆</a>
</div>
</form>


<?php 
}
if ( $form == '0')	
{
?>
<form action="" method="post">
<h4>粘贴屏蔽简历邮件内容</h4>
<div style="float:left;margin-left:100px">
<textarea name="mail" style="resize:none;width:600px;height:350px"></textarea>
<div >
<input type="reset" style="height: 30px；" class="btn btn-primary" value="清空内容"/>
<input type="submit" name="lock" style="height: 30px；" class="btn btn-primary" value="邮件内容识别" onclick="$('#loading').modal()"/>
</div>
</div>
<div style="float:right;margin-right:100px">
<h5>如果同时包含用户ID和简历ID</h5>
<h5>请确保用户ID在简历ID之上（如下）：</h5>
<h5>屏蔽原因XXXXXXXXXXXXXXX</h5>
<h5>用户号：XXXXXX</h5>
<h5>用户ID：XXXXXX</h5>
<h5>　　　　XXXXXX</h5>
<h5>简历ID：XXXXXX</h5>
<h5>　　　　XXXXXX</h5>
<h5>　　　　XXXXXX</h5>
<h5>　　　　XXXXXX</h5>
<h5>　　　　XXXXXX</h5>
</div>

</form>
<?php 
}
if ( $form == '2')	
{
?>
<div>
<h4>邮件内容识别</h4>
<div>
<?php 
echo $table;
?>
</div>
<input type="button" style="height: 30px；" class="btn btn-primary" value="返回重新输入" onclick="history.back()"/>
<input type="button" style="height: 30px；" class="btn btn-primary" value="开始自动屏蔽" onclick="getAutoInfo()"/>
<form style="display:none" action="" method="post" target="">
<input name="action" value=""/>
<input name="id" value=""/>
<input name="param" value=""/>
</form>

</div>
<?php 
}
?>
	<div id="loading" class="modal hide fade" data-backdrop="static">
		<img src="<?php echo $base?>public/image/loading.gif" onclick="$('#loading').modal('hide')" style="cursor:pointer"/>
	</div>
	<input id="controlTop" style="display:none" value="resume"/>	
	</div>
</div>