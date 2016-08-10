<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户列表</title>
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/style.css" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/common.css" />
    <script type="text/javascript" src="/thinkphp/Public/Js/Jquery.js"></script>
    <script type="text/javascript" src="/thinkphp/Public/Js/DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="/thinkphp/Public/Js/diashow/diashow.js"></script>

<script type="text/javascript">
         
	//全选，全不选
	var allSelect = function () 
	{			
        $("input[name='checkname[]']").each(function()
		{
			
			if($(this).attr("checked") != "checked")
			{
                $(this).attr("checked", "checked");
			}
			else
			{
                $(this).removeAttr("checked");
			}
		});

	}

	//反选
	function otherSelect() {
		alert("hhha");
		$(":checkbox").each(function () {
			if ($(this).attr("checked") == "checked") {
				$(this).removeAttr("checked");
			}
			else {
				$(this).attr("checked", "checked");
			}
		});
	}
	function delConfirm(url)
	{		
		msg = "确定要删除？"
		if(confirm(msg))
		{
			location.href=url;
		}
	}
</script>
</head>
<body>
<div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">用户列表</a></li>
    </ul>
</div>

<form action="/thinkphp/Home/user/delete">
<input name="delIds" type="hidden"/>
<div class="data-list" >
     <table cellspacing="1" cellpadding="3">
         <tbody>
             <tr>
			  <th><input type="checkbox" id="checkall" name="checkbox" onClick="allSelect()" />编号</th>
		      <th>用户名</th>      
		      <th>创建时间</th>
			  <th>操作</th>	  
		    </tr>
		    <?php if(empty($users)): ?><tr>
                <td class="no-records" colspan="4" style="background-color: rgb(244, 250, 251);">没有找到任何记录</td>
            </tr> 
             <?php else: ?>
            <?php if(is_array($users)): foreach($users as $k=>$user): ?><tr class="tr<?php echo ($k%2); ?>">
		      <td><input type="checkbox" id="Checkbox<?php echo ($k); ?>" name="checkname[]" value="<?php echo ($user["id"]); ?>" /></td>
		      <td><?php echo ($user["name"]); ?></td>    
		      <td><?php echo ($user["create_time"]); ?></td>
			  <td>
			      <a onclick="delConfirm('/thinkphp/Home/user/delete?user_id=<?php echo ($user["id"]); ?>')"><img src="/thinkphp/Public/images/icon_del.gif" /></a>
			      <a href="/thinkphp/Home/user/edit?user_id=<?php echo ($user["id"]); ?>"><img src="/thinkphp/Public/images/icon_edit.gif" /></a>
			  </td>
		    </tr><?php endforeach; endif; ?>
		    <tr><td><input class="button" name="submit" type="submit" value="删除" onclick="return deleteData('checkname[]',this)"/></td><td colspan="3"><?php echo ($page); ?></td> </tr><?php endif; ?> 
         </tbody>
     </table>
 </div>
 </form>  
</body>
</html>