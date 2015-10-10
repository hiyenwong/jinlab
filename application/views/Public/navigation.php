<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		 <div class="container">
			<div class="nav-collapse collapse">
<!-- 				<a id="main" class="brand active" href="../../"><strong>Webmaster Labs</strong></a> -->
				<ul class="nav nav-pills" id="navWrap">
					<li id="main" class="active"><a href="../../" ><strong>首页</strong></a></li>
				<?php if ( !$ipCheck){?>
					<li id="checkup"><a href="<?php echo base_url()?>statistic" ><strong>任务完成统计</strong></a></li>
				<?php }?>
					<li id="resume"><a href="<?php echo base_url()?>resume" ><strong>客服屏蔽简历</strong></a></li>
				</ul>
				<?php if($rs == 1)
				{?>
					<div class="navbar-form pull-right">		
						<input class="info-" readonly="readonly" value='<?php echo "操作员：".$userName."　";?>'/>
						<a class="btn btn-primary" name="logout" onclick="window.location.href='<?php echo $logout;?>'"><strong>注销</strong></a>
					</div>				
				<?php 
				}
				else
				{
				?>
				<button id="loginBtn" class="btn btn-primary login" data-toggle="modal" href="#regModal "><strong>登陆</strong></button>
				<?php 
				}
				?>				
			</div>
		</div>
	</div>
</div>