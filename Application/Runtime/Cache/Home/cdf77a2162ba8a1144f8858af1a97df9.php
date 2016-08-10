<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>完善账户信息</title>
    
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/common.css" />

<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/tab.css" />
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/addAdvertisement.css" />

<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Js/diashow/skins/css/diashow.css" />
<script type="text/javascript" src="/thinkphp/Public/Js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/thinkphp/Public/Js/diashow/diashow.js"></script>
<script type="text/javascript">

	var module = "/thinkphp/Home";
	$(document).ready(function()
	{
		//alert(1);
		var message = '<?php echo ($message); ?>';
		if(message!='')
		{
			showMessage('<?php echo ($message); ?>','<?php echo ($url); ?>','<?php echo ($time); ?>');
		}
	});
</script>

    <script type="text/javascript" src="/thinkphp/Public/Js/Jquery.js"></script>
    <script type="text/javascript" src="/thinkphp/Public/Js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/style.css" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/category.css" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Js/diashow/skins/css/diashow.css" />
    <script type="text/javascript" src="/thinkphp/Public/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/thinkphp/Public/Js/diashow/diashow.js"></script>
    <script type="text/javascript">
        var APP = "/thinkphp";
        var MODULE = "/thinkphp/Home";
        var CONTROLLER = "/thinkphp/Home/User";
        var ACTION = "/thinkphp/Home/User/editlogin";
        $(document).ready(function()
        {
            var message = '<?php echo ($message); ?>';
            if(message!='')
            {
                showMessage('<?php echo ($message); ?>','<?php echo ($url); ?>','<?php echo ($time); ?>');
            }
        });
    </script>
</head>
<body>
<div class="form_div">
    <div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">修改密码</a></li>
    </ul>
</div>
    <form action="/thinkphp/Home/User/editlogin" enctype="multipart/form-data" method="post">
        <input type="hidden" name="id" value="<?php echo ($one["id"]); ?>"/>
        <table>
            <tr>
                <td class="label">密码：</td>
                <td class="value"><input type="password" name="passwd" /></td>
            </tr>
            <tr>
                <td class="label">确认密码：</td>
                <td class="value"><input type="password" name="confirmpwd" /></td>
            </tr>
            <tr>
                <td class="label">地址：</td>
                <td class="value" id="cat">
                    <input type="text" name="address" value="<?php echo ($one["address"]); ?>"/> </td>
            </tr>
            <tr>
                <td class="label"></td>
                <td style="float: left">
                    <input class="button" type="submit" name="submit" value="添加">
                    <input class="button" type="reset" value="重置"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>