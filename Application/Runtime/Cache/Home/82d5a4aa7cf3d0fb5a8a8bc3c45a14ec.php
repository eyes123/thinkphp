<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TP后台系统</title>
<script type="text/javascript" src="/thinkphp/Public/Js/Jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/style.css" />
<script type="text/javascript">
        $(function(){
            //顶部导航切换
            $(".nav li a").click(function(){
                $(".nav li a.selected").removeClass("selected")
                $(this).addClass("selected");
            })
        })
</script>
</head>
<body class="top">
<div class="topleft">
    <li class="topleft_wz">后台管理系统<li>
</div>
<div class="topright">
    <ul>
        <li>
            <a href="/thinkphp/Home/User/editlogin" target="rightFrame">修改密码</a>
        </li>
        <li>
           <a>欢迎您,<?php echo ($partner["name"]); ?></a>
        </li>
        <li><a href="/thinkphp/Home/Index/logout" target="_parent">退出</a></li>
    </ul>
</div>
</div>
</body>
</html>