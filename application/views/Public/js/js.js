function submitForm()
{
	if($('#regModal').form('validate')){
		$('#regModal').submit();
	}   
}
function loginModel(obj)
{
	if ( $(obj).html() == '普通登陆')
	{
		$('#resumeLogin').show();
		$(obj).html('快速登陆');
		$('#gModel').show();
		$('#qModel').hide();
	}
	else
	{
		$('#resumeLogin').hide();
		$(obj).html('普通登陆');
		$('#qModel').show();
		$('#gModel').hide();
	}
}
function resumeLogin(userName,passWord)
{
	$('#resumeUser').val(userName);
	$('#resumePass').val(passWord);
	$('#resumeLogin').click();
}
function mark(id,act='',url='phpCurl')
{
	if ( $('#reason').attr('name') == 'textarea')		
		var reason = $('#reason').val();
	else
		reason = $('#reason').html();
	$.ajax({
		type: "post",
		url: url,
		data: "action=mark&id="+id+"&reason="+reason,
		success: function(html)
		{
			if ( act == '')
			{
				alert(html);
				window.opener.markDone(id,html);
			}
			else
			{
				markDone(id,html,'Auto');
			}
		}
	});
}
function download(id,obj = '')
{
	if ( obj != '')
	{
//		console.log($(obj).parent().prev().prev().prev().children().attr('op'))
		if ( $(obj).parent().prev().prev().prev().children().attr('op') == 'wrong')
		{
			alert('该简历无法打开，不能保存！');
			return;			
		}
		$('#loading').modal();
		$.ajax({
			type: "post",
			url: "../../resume/phpCurl",
			data: "action=save&id="+id,
			success: function(html)
			{
				var re = new RegExp("下载成功！","g"); 
				match = html.match(re); 
				if ( match != '')
				{
					post = "[['action','download'],['id','"+id+"']]";
					post = eval("("+post+")");
					openPostWindow('../../resume/phpCurl',post,'_blank');
					$('#loading').modal('hide');
				}
			}
		});		
	} 
	else
	{
		$.ajax({
			type: "post",
			url: "../../resume/phpCurl",
			data: "action=auto&idArr="+id,
			success: function(html)
			{
				$.ajax({
					type: "post",
					url: "../../resume/phpCurl",
					data: "action=download&id="+id,
					success: function(html)
					{
						post = "[['action','download'],['id','"+id+"']]";
						post = eval("("+post+")");
						openPostWindow('../../resume/phpCurl',post,'_blank');
						$('#loading').modal('hide');
					}
				});				
			}
		});	
	}

}
function markDone(id,html,act='')
{
	var markObj = $('#resumeid_'+id).parent().next().next().next().children();
	var remarkObj = $('#resumeid_'+id).parent().next().next().next().next().children();
	var markSrc = $(markObj).attr('src');		
	var remarkSrc = $(remarkObj).attr('src');
	if ( act == '')
		$('#resumeid_'+id).click();
	var re = new RegExp("加标签&加评语成功！","g"); 
	var match = html.match(re); 
	if ( match != '')
	{		
		$(markObj).attr('src',markSrc.replace('wrong','right'));
		$(remarkObj).attr('src',remarkSrc.replace('wrong','right'));
	}
	else
	{
		var re = new RegExp("加评语失败！","g"); 
		var match = html.match(re); 
		if ( match != '')
		{	
			$(markObj).attr('src',markSrc.replace('wrong','right'));
		}
	}	
}
function checkAll(obj)
{
	if ( $(obj).attr('checked') == 'checked')
	{
		$(obj).attr('checked','checked');
		$('.checkAll').attr('checked','checked');
	}
	else
	{
		$(obj).removeAttr('checked');
		$('.checkAll').removeAttr('checked');
	}
}
/* 使用post打开新窗口 */
function openPostWindow(url, args, name)
{
//	var args = eval("("+args+")");
	var tempForm = document.createElement("form");
	tempForm.id = "tempForm";
	tempForm.method = "post";
	tempForm.action = url;
	tempForm.target = name;
	tempForm.style.display = "none";
    //可传入多个参数
	for ( var i=0; i<args.length; i++)
	{
    	var hideinput=document.createElement("input");
    	hideinput.type="hidden";  
    	hideinput.name=args[i][0]; 
    	hideinput.value=args[i][1];
    	$(tempForm).append(hideinput); 
    }
	if ( name != '_blank')
	{
		$(tempForm).bind("onsubmit",function(){
	    	window.open("about:blank",name,"height=600, width=1000, top=200,left=200,directories=no,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,toolbar=no");
	    });
	}    
    $('body').append(tempForm);
    if ( name != '_blank')
	{
    	$(tempForm).trigger("onsubmit");
	}
    $(tempForm).submit();
    $(tempForm).remove(); 
}
function getAutoInfo()
{	
	var i = 0;
	var idArr = new Array();
	var error = false;
	var reason = $('#reason').html();
	$('input[name=resumeCheck]').each(function(){
		if ( $(this).attr('checked') == 'checked')
		{
			op = $(this).parent().next().next().next().next().next().children().attr('op');
			if ( op == 'wrong')
			{
				alert('请剔除无法打开的简历！');
				error = true;		
			}
			else
			{
				idArr[i] = $(this).attr('resumeid');
				i++;
			}							
		}
	})
	if ( idArr == '')
	{
		error = true;
		alert('没有勾选任何简历！')
	}
	if ( !error)
	{
		$('#loading').modal();
//		console.log(idArr)
		for ( var j=0;j<idArr.length;j++)
		{
			mark(idArr[j],"Auto","../../resume/phpCurl");
		}
		download(idArr);
	}	
}

