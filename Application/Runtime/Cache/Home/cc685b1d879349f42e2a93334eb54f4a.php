<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>广告系统后台管理</title>
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/common.css" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/style.css" />
<script type="text/javascript" src="/thinkphp/Public/Js/Jquery.js"></script>
<script type="text/javascript" src="/thinkphp/Public/Js/cat.js"></script>
<script type="text/javascript" src="/thinkphp/Public/Js/DatePicker/WdatePicker.js"></script>
<script>
var module = "/thinkphp/Home";
</script>  
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
<!-- 顶部页面导航 -->
<div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">产品列表</a></li>
    </ul>
</div>
<!-- 搜索模块 -->
<div class="search_div">
    <form action="/thinkphp/Home/advertisement/index">
        标题关键字：
        <input type="text" name="title" value="<?php echo ($titleWhere); ?>" />
        上线时间：
        <input type="text" size="15" name="start_time" value="<?php echo ($startWhere); ?>" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" /><bold>~</bold>
        <input type="text" size="15" name="end_time" value="<?php echo ($endWhere); ?>" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
         上架状态:
        <select name="issell">
        <option value="-1">全部</option>
        <option value="0" <?php echo ($issell=='0'?'selected="selected"':''); ?>>否</option>
        <option value="1" <?php echo ($issell=='1'?'selected="selected"':''); ?>>是</option>
        </select>
        <input style="margin-left:35px;" class="button" type="submit" name="submit" value="&nbsp;" />
    </form>
</div>

<!-- 列表样式 -->
<form action="/thinkphp/Home/advertisement/delete" method="get">
    <div class="data-list" >
        <table cellspacing="1" cellpadding="3">
            <tbody>
                <tr>
                    <th><input type="checkbox" id="checkall" name="checkbox" onClick="allSelect()" value="全选" />编号</th>
                    <th>广告标题</th>
                    <th>价格</th>
	                <th>                          浏览量
	                <a class="sort" href="/thinkphp/Home/advertisement/index?sort=view_count desc&where=<?php echo ($where); ?>">降序</a> 
	                <a class="sort" href="/thinkphp/Home/advertisement/index?sort=view_count asc&where=<?php echo ($where); ?>">升序</a>
	                </th>
                    <th>                            发布时间
                    <a class="sort" href="/thinkphp/Home/advertisement/index?sort=create_time desc&where=<?php echo ($where); ?>">降序</a> 
                    <a class="sort" href="/thinkphp/Home/advertisement/index?sort=create_time asc&where=<?php echo ($where); ?>">升序</a>
                    </th>
                    <th>是否上架</th>
                    <th>操作</th>
                </tr> 
                <?php if(empty($ads)): ?><tr>
                    <td class="no-records" colspan="7" style="background-color: rgb(244, 250, 251);">没有找到任何记录</td>
                </tr> 
	             <?php else: ?>
                <?php if(is_array($ads)): foreach($ads as $k=>$ad): ?><tr class="tr<?php echo ($k%2); ?>">
				      <td><input type="checkbox" id="Checkbox<?php echo ($k); ?>" name="checkname[]" value="<?php echo ($ad["id"]); ?>" /><?php echo ($pageCount*($currentPage-1)+$k+1); ?></td>
				      <td style="text-align: left;"><a href="/thinkphp/Mobile/index/index?ad_id=<?php echo ($ad["id"]); ?>&from=trash"><?php echo ($ad["short_title"]); ?></a></td>   
				      <td><?php echo ($ad["product_price"]); ?></td> 
				      <td>
				      	<?php if($ad['view_count'] == ''): ?>0<?php else: echo ($ad["view_count"]); endif; ?>
				      </td>
				      <td><?php echo ($ad["create_time"]); ?></td>
				      <td><?php if($ad['is_sell'] == '0'): ?>否<?php else: ?>是<?php endif; ?></td>
					  <td><a onclick="delConfirm('/thinkphp/Home/advertisement/delete?ad_id=<?php echo ($ad["id"]); ?>')"><img src="/thinkphp/Public/images/de.png" /></a>
					      <?php if($ad['is_sell'] == '0'): else: ?> 
					      <a id="b" href="/thinkphp/Home/advertisement/jiangjia1?ad_id=<?php echo ($ad["id"]); ?>">降价</a>
					      <a id="b" href="/thinkphp/Home/advertisement/tiyan1?ad_id=<?php echo ($ad["id"]); ?>">体验</a>
					      <a id="b" href="/thinkphp/Home/advertisement/news1?ad_id=<?php echo ($ad["id"]); ?>">新品</a>
					      <a id="b" href="/thinkphp/Home/advertisement/jc1?ad_id=<?php echo ($ad["id"]); ?>">九橙推荐</a>
                          <a id="b" href="/thinkphp/Home/advertisement/match?ad_id=<?php echo ($ad["id"]); ?>">搭配产品</a><?php endif; ?>
					      <a href="/thinkphp/Home/advertisement/edit?ad_id=<?php echo ($ad["id"]); ?>" ><img src="/thinkphp/Public/images/bj.png" /></a>
					  </td>
			    </tr><?php endforeach; endif; ?>
                 <tr>
	                <td><input class="button" type="submit" value="删除"/></td>
	                <td colspan="7"><?php echo ($page); ?></td>
                </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</form>
</body>
</html>