// JavaScript Document
/**@CHARSET "UTF-8";**/
//删除数据时提示确认
function deleteData(name,e){
	if(!name)
	{
		name='ckb1';
	}
	var _chbs = $("input[name = '"+name+"']");//获取所有选框
	var del_ids = '';
	
	_chbs.each(function(){if($(this).attr('checked')=="checked")
	{
		if($(this).attr('id'))
		{
			del_ids += ("'"+ $(this).attr('id') + "',");
		}
	}});
	del_ids = del_ids.substr(0,del_ids.length -1);
	
	if(del_ids=='')
	{
		showMessage('请选择要删除的数据','',2000);
		return false;
	}
	else
	{
		var result = confirm("您确定要删除吗");
		if(result)
		{
			var hfInput = $(e).parent().find("input[type = 'hidden']");//[0].value = del_ids;
			//alert(hfInput[0]);
			hfInput[0].value = del_ids;
		}
		return result;
	}
}

//删除数据时提示确认
function deleteAll(url,name){
	//confirmMessage("您确定要删除吗",url);
	if(!name)
	{
		name='ckb';
	}
	var _chbs = $("input[name = '"+name+"']");//获取所有选框
	var del_ids = '';
	
	_chbs.each(function(){if($(this).attr('checked')=="checked"){del_ids += $(this).attr('id') + ',';}});
	del_ids = del_ids.substr(0,del_ids.length -1);
	
	if(del_ids=='')
	{
		showMessage('请选择要删除的数据','',1000);
		return false;
	}
	else
	{
		url = url + "/delIds/" + del_ids;
		confirmMessage("您确定要删除吗",url);
	}
}

function showMessageByJson(json)
{
	if(json!='' && json!=null)
	{
		var arr = JSON.parse(data);
		if(isArray(arr))
		{
			var cont = arr.content;
			var url = arr.url;
			
			var mContent='失败！';
			var mTime=1000;
			if(cont!=null && cont!='')
			{
				mContent=cont;
				mTime = 0;
			}
			if(arr.state==1)
			{
				mTime=1000;
				mContent='成功！';
			}
			else if(arr.state==-1)
			{
				mContent='无权限访问此页！';
			}
			showMessage(mContent,url,mTime);
		}
	}
}
function showMessage(mMmessage,mUrl,mTime)
{
//	alert('showMessage(mMmessage,mUrl,mTime)');
	if(mMmessage)
	{
		dialogAlert('',mMmessage,mUrl,'',mUrl);
		
		if(mTime==null || mTime=='')
		{
			mTime = 2500;
		}
		else if(mTime < 0)
		{
			mTime = 0;
		}
		if(mTime>0)
		{
			setTimeout(function()
			{
				dismiss(null);
				if(mUrl!=null)
				{
					if(mUrl.length > 0)
					{
						window.location = mUrl;
					}
				}
			},mTime);
		}
	}
}

function createMessageBox()
{
	if($("#divMaincontent").attr("id")==null)
	{
		//alert('#divMaincontent');
		var div = "<div class=\"pm_main hidden\" id=\"divMaincontent\">";
		div += "<div class=\"pm_tit1\"><span class=\"left\">提示信息</span><a class=\"my_right\" id=\"btnClose\" href=\"javascript:void(0)\"></a></div>";
		div += "<div class=\"pm_con1\">";
        div += "<div class=\"pm_con2\">";
        div += "<p id=\"alertTitle\" class=\"hidden\">提示</p>";
        div += "<p class=\"fontcolorf1\" id=\"alertContent\"></p>";
        div += "<div class=\"blackf1\"></div>";
        div += "<div class=\"register_linef1\"></div>";
        div += " <p class=\"pm_btn1 hidden\"><a id=\"btnSure\" href=\"javascript:void(0)\">确认</a><a id=\"btnCancel\" href=\"javascript:void(0)\">取消</a></p>";
        div += " </div></div></div>";
		$("body").append(div);
	}
}

function confirmMessage(mMmessage,mUrl)
{
	dialogAlert('',mMmessage,mUrl,'','');
	$(".pm_btn1").removeClass("hidden");
}
function dismiss(btn)
{
	$("#divBackground").addClass("hidden");
	$("#divMaincontent").addClass("hidden");
	if(btn)
	{
		$(btn).unbind("click");
	}
}
//var title = '提示标题';
//var message='提示内容';
//var TrueEvent='';
//var CancelEvent='';
//var CloseEvent='';
function dialogAlert(title, message, TrueEvent, CancelEvent, CloseEvent) {
	createMessageBox();
    $("#divBackground").removeClass("hidden");
    $("#divMaincontent").removeClass("hidden");

    $("#alertTitle").html(title);
    $("#alertContent").html(message);

    $("#btnSure").click(function () {
		dismiss(this);
        if (TrueEvent) { 
        	//确认之后跳转url
        	window.location.href = TrueEvent;
 		}
        
    }); //确定

    $("#btnCancel").click(function () {
       dismiss(this);
        if (CancelEvent) { 
        	//确认之后跳转url
        	window.location.href = CancelEvent;
 		}
    });//取消

    $("#btnClose").click(function () {
		dismiss(this);
        if (CloseEvent) { 
        	//确认之后跳转url
        	window.location.href = CloseEvent;
 		}

    });//X关闭
}