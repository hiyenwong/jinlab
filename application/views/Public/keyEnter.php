<script type="text/javascript">
$(function(){	
	$(document).keydown(function(event){
		if(event.keyCode==13){
			if ( $('#regModal').css('display') == 'none')
			{
				$("#loginBtn").click();
			}
			else
			{
				$("#loginButton").click();
			}
		}
	});
})
</script>