<div class="container">
	<div style="margin:60px 0;"></div>


	<button class="btn btn-primary" id="history" data-toggle="modal" href="#regModal">历史</button>
	<br>
	<br>

			
<div style="height:40px"></div>   

<?php 
	echo $html;
?>
<div class="hide fade modal" id="regModal" style="width:800px;padding:10px 0 0 10px"> 
	<div id="date" class="easyui-calendar" style="again:center;width:790px;margin:0 0 10px 0;height:180px;" data-options="months:['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],weeks:['日','一','二','三','四','五','六']"></div>
<?php 
	echo "$htmlHis";
?>
</div>

<div style="height:40px"></div>

<a class="btn btn-primary btn-large" href="<?php echo base_url()?>index.php/checkup"><strong>刷新</strong></a>

<input id="controlTop" style="display:none" value="checkup"/>	

</div>
<script>
jQuery(function($){
	load();
	load('oprNumHis');
 	setInterval("load()",10000);
});

$('#date').calendar({
	onSelect: function(date){
		d = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
		load('oprNumHis',d);
	}
});
function load(cls='oprNum',date = '<?php echo date("Y-m-d")?>')
{
	$('.'+cls).each(function(index,element){
		if ( cls == 'oprNumHis')
			$(element).html("<img style='width:20px' src='public/image/loading.gif'/>");
		$.ajax({
			type: "post",
			url: "<?php echo base_url()?>Statistic/phpCurl",
			data: "action=per&date="+date+"&id="+$(element).attr(cls),
			success:function(html){								
				$(element).html(html);				
			}
		});
	});
}
</script>