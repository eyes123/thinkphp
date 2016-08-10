<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/partner/contact.css" />
    <script type="text/javascript" src="/thinkphp/Public/Js/jquery-1.8.2.min.js"></script>
    <title>无标题文档</title>
    <style type="text/css">
        <!--
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            overflow:hidden;
        }
        -->
    </style></head>

<body >
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top"><iframe height="100%" width="100%" border="0" frameborder="0" src="left.html" scrolling="no"></iframe></td>

        <td valign="top"><iframe name="rightFrame" height="100%" width="100%" border="0" frameborder="0" src="/thinkphp/Home/Index/main"></iframe></td>

    </tr>
</table>
<!--右侧在线咨询开始-->
<div class="suspend">
    <dl>
        <dt class="IE6PNG"></dt>
        <dd class="suspendQQ"><a href="tencent://message/?Menu=yes&uin=2740439877" target="_blank"></a></dd>
        <dd class="suspendTel"><a href="javascript:void(0);"></a></dd>
    </dl>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $(".suspend").mouseover(function() {
            $(this).stop();
            $(this).animate({width: 160}, 400);
        })

        $(".suspend").mouseout(function() {
            $(this).stop();
            $(this).animate({width: 40}, 400);
        });

    });
</script>
<!--右侧在线咨询结束-->
</body>
</html>