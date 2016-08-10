// JavaScript Document
/**@CHARSET "UTF-8";**/
//删除数据时提示确认
var myTsTimer;

 function trim(s,t)
 { //删除左右两端的空格
	if(s.length>0)
	{
		if(s.substr(0,1)==t)
		{
			s=s.substr(1);//取到最后
//			alert(s);
		}
		if(s.charAt(s.length-1)==t)
		{
			s=s.substring(0,s.length-1);	
		}
//		alert(s.charAt(0)==" "||s.charAt(s.length-1)==" ");
		if(s.charAt(0)==t || s.charAt(s.length-1)==t)
		{
			return trim(s);
		}
	}
	return s;
} 

function getCheckedIds(name)
{
	//return '1111111111';
	var delIds = ' ';
	var _chbs = $("input[name = '"+name+"']");//获取所有选框
	//return '1111111111';
	_chbs.each(function(){
		if($(this).attr('checked')=="checked")
		{
			if($(this).val())
			{
				delIds += ("'"+ $(this).val() + "',");
			}
		}
	});
	delIds = delIds.substr(0,delIds.length -1);
	return delIds;
}

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
function clearTsTimer()
{
	if(myTsTimer)
	{
		window.clearInterval(myTsTimer);
	}
}
function showMessage(mMmessage,mUrl,mTime)
{
//	alert('showMessage(mMmessage,mUrl,mTime)');
	if(mMmessage)
	{
		window.clearInterval(myTsTimer);
		dialogAlert(mMmessage,'','','');
		
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
			myTsTimer = setTimeout(function()
			{
				dismiss();
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
	var div = '<div id="mess_mask" style="filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity: 0.5;opacity: 0.5;height: 100%; z-index: 14000; position: absolute; text-align: center; top: 0px; left: 0px; right: 0px; bottom: 0px; visibility: visible; width: 100%;"></div>'
		+ '<div style="z-index: 15000;" class="tishi1" id="divMaincontent"><div class="wenxin tishi_title">温馨提示<span class="ts_shanchu" id="btnClose"></span></div>'
        +'<div class="wenxin1" id="alertContent"></div>'
		+'</div>';
	delMessageDiv();
	$("body").append(div);
}

function app_login(login)
{		
	var flag = true;
	if(!login)
	{
		if(myTsTimer)
		{
			window.clearInterval(myTsTimer);
		}		
		var url = "function://login";
		dialogAlert('您还没有登录哦~赶快先登录吧~',url,'','');
		//var url = "function://login";
		//document.location = url;
		flag = false;
	}
	return flag;
}



function createMessageBox2()
{
	var div = '<div id="mess_mask"></div>'
		+ '	<div class="tishi" id="divMaincontent">'
       	+'<div class="wenxin2 tishi_title">温馨提示<span class="ts_shanchu"  id="btnClose"></span></div>'
        +'<div class="wenxin3" id="alertContent"></div>'
        +'<div class="wenxin4"><span class="haode" id="btnSure">好的</span>'
        +'<span class="guangguang" id="btnCancel">先逛逛</span>'
    	+'</div>';
		
	delMessageDiv();
	$("body").append(div);
}


function dismiss()
{
	$("#mess_mask").addClass("hidden");
	$("#divMaincontent").addClass("hidden");
}

function showMessageDiv()
{
	$("#mess_mask").removeClass("hidden");
	$("#divMaincontent").removeClass("hidden");
	$("#mess_mask").css({height: function () { return $(document).height();},width:"100%"});
}

function delMessageDiv()
{
	if($("#mess_mask").attr("id")!=null)
	{
		$("#mess_mask").remove();
	}
	if($("#divMaincontent").attr("id")!=null)
	{
		$("#divMaincontent").remove();
	}
}

function confirmMessage(message,myEvent,CancelEvent, CloseEvent)
{
	createMessageBox2();
	
    $("#mess_mask").removeClass("hidden");

	//alert(data);
	//TrueEvent(data);
    $("#alertContent").html(message);

    $("#btnSure").click(function () {
		dismiss();
		if (myEvent) { 
			if (typeof (myEvent) == 'function') {
				myEvent();
		  	}
		}
		//alert(1);
    }); //确定
	
    $("#btnCancel").click(function () {
		dismiss();
		if (CancelEvent) { 
			if (typeof (CancelEvent) == 'function') {
				CancelEvent();
		  	}
		}
    });//取消

    $("#btnClose").click(function () {
		dismiss();
		if (CloseEvent) { 
			if (typeof (CloseEvent) == 'function') {
				CloseEvent();
		  	}
		}
    });//X关闭
	if(qxmsg)
	{
		$("#btnCancel").html(qxmsg);
	}
	showMessageDiv();
}

//var title = '提示标题';
//var message='提示内容';
//var TrueEvent='';
//var CancelEvent='';
//var CloseEvent='';
function dialogAlert(message, TrueEvent, CancelEvent, CloseEvent,qxmsg) {
	var div;
	if (TrueEvent) 
	{ 
		div = createMessageBox2();
	}
	else
	{
		div = createMessageBox();
	}
	showMessageDiv();
	

    $("#alertContent").html(message);

	var h1 = document.getElementById("alertContent").innerHTML.length;
	
	var h2 = 30;
	
	if(h1>h2)
	{
		$(".tishi_title").css('padding-top',"5px");
		$("#alertContent").css('padding-top',"5px");
	}
	
    $("#btnSure").click(function () {
		dismiss();
        if (TrueEvent) { 
			if (typeof (TrueEvent) == 'function') {
			TrueEvent();
		  }
		  else{
        	//确认之后跳转url
        	document.location = TrueEvent;
		  }
 		}
        
    }); //确定
	
    $("#btnCancel").click(function () {
       dismiss();
        if (CancelEvent) { 
        	//确认之后跳转url
        	document.location = CancelEvent;
 		}
    });//取消

    $("#btnClose").click(function () {
		dismiss();
        if (CloseEvent) { 
        	//确认之后跳转url
        	document.location = CloseEvent;
 		}

    });//X关闭
	if(qxmsg)
	{
		$("#btnCancel").html(qxmsg);
	}
}