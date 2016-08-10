<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>左侧菜单</title>
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/style.css" />
<script type="text/javascript" src="/thinkphp/Public/Js/Jquery.js"></script>
</head>
<body>
<script type="text/javascript">
    $(function(){
//导航切换
        $(".menuson li").click(function(){
            $(".menuson li.active").removeClass("active")
            $(this).addClass("active");
            var href = $("a",this).attr("href");
            var frame = window.parent.frames["rightFrame"];
            $(frame).attr('src',href);
            frame.location = href;
            return false;
        });
        $('.title').click(function(){
            var $ul = $(this).next('ul');
            $('dd').find('ul').slideUp();
            if($ul.is(':visible')){
                $(this).next('ul').slideUp();
            }
            else{
                $(this).next('ul').slideDown();
            }
        });
    })
</script>
    <div class="lefttop"><span></span>管理菜单</div>
    <dl class="leftmenu">
		<?php if(is_array($menuList)): foreach($menuList as $key=>$one): ?><dd>
                <div class="title">
                    <span><img src="/thinkphp/Public/images/<?php echo ($one["func_type"]); ?>.png" /></span><?php echo ($one["name"]); ?>
                </div>

                <ul class="menuson">
                    <?php if(is_array($one["child_node"])): foreach($one["child_node"] as $key=>$function): ?><li><cite></cite><a href="/thinkphp/<?php echo ($function["path"]); ?>"   target="rightFrame" ><?php echo ($function["name"]); ?></a><i></i></li><?php endforeach; endif; ?>
                </ul>
            </dd><?php endforeach; endif; ?>
        </dl>
</body>
</html>