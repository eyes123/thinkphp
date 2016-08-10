<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>添加用户信息</title>

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
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/common.css" />
<link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/style.css" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Css/category.css" />
    <link rel="stylesheet" type="text/css" href="/thinkphp/Public/Js/diashow/skins/css/diashow.css" />
    <script type="text/javascript" src="/thinkphp/Public/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/thinkphp/Public/Js/diashow/diashow.js"></script>
</head>
<body>
<div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">添加用户</a></li>
    </ul>
</div>

<div class="form_div">
<form action="/thinkphp/Home/user/add"  method="post">
<input type="hidden" value="addUser" name="act" />
<table>
    <tr>
        <td class="label">用户权限：</td>
        <td class="value">
        <select name="pow_id" id="pow_id">               
                <option value="0">请选择</option>
                <?php if(is_array($pow)): foreach($pow as $key=>$pw): ?><option value="<?php echo ($pw["id"]); ?>" <?php echo ($pw['id']===$actionId?'selected="selected"':''); ?>><?php echo ($pw["pow_name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="label">用户名：</td>
        <td class="value" id="cat">
        <input type="text" name="user_name" value="<?php echo ($one["user_name"]); ?>"/>
        </td>
    </tr>
    <tr>
        <td class="label">密码：</td>
        <td class="value"><input type="password" name="user_pwd" value="<?php echo ($one["user_pwd"]); ?>" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')"
        /></td>
    </tr>
    <tr>
        <td class="label">确认密码：</td>
        <td class="value"><input type="password" name="confirm_pwd" value="<?php echo ($one["confirm_pwd"]); ?>" /></td>
    </tr>
    <tr>
        <td class="label">银行卡号：</td>
        <td class="value">
        <input type="text" name="card_num" value="<?php echo ($one["card_num"]); ?>"/>
        <span class="input_remark">商家用户必须填写</span>
        </td>
    </tr>
    <tr>
        <td class="label">开户名：</td>
        <td class="value">
        <input type="text" name="card_owner" value="<?php echo ($one["card_owner"]); ?>"/>
        <span class="input_remark">商家用户必须填写</span>
        </td>
    </tr>
    <tr>
        <td class="label">银行：</td>
        <td class="value">
        <select name="bank_name">
        <option value="-1" >请选择开户行</option>
        <option value="" >中国建设银行</option>
        <option value="102100099996" <?php echo ($one['bank_name']== "102100099996" ?'selected="selected"':''); ?>>中国工商银行</option>
        <option value="103100000026" <?php echo ($one['bank_name']== "103100000026" ?'selected="selected"':''); ?>>中国农业银行</option>
        <option value="104100000004" <?php echo ($one['bank_name']== "104100000004" ?'selected="selected"':''); ?>>中国银行</option>
        <option value="301290000007" <?php echo ($one['bank_name']== "301290000007" ?'selected="selected"':''); ?>>交通银行</option>
        <option value="308584000013" <?php echo ($one['bank_name']== "308584000013" ?'selected="selected"':''); ?>>招商银行</option>
        <option value="403100000004" <?php echo ($one['bank_name']== "403100000004" ?'selected="selected"':''); ?>>中国邮政储蓄银行</option>
        </select>
        <span class="input_remark">商家用户必须填写</span>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:left;">
            <input class="button" type="submit" name="submit" value="添加">
            <input class="button" type="reset" value="重置"/>
        </td>
    </tr>
</table>
</form>
</div>
</body>
</html>