<script type="text/javascript">	
	$('.userName').typeahead({
			source:function(query,process){
				$.ajax({
				type: 'POST',
				url: "Public/userautoCurl.php",
				data: "q="+query,
				success: function(data){
					if(data != "none"){						  
						var obj =eval("("+data+")");
						process(obj);
						}
					}
				})
			}
			,updater: function (item) {
				  var result = /(.*?)\[(.*?)\]/;
				  var result = item.match(result);	　　
			      return result[1]
		    }
	    ,items:10
	    ,minLength:2	    
	});
</script>
