<?php 
use Org\Util\Page;
use Think\Model;


/**
 * 提示消息,并跳转
 * @param unknown $message
 * @param unknown $url
 * @param number $time
 */
function myMessage($message,$url,$time=2)
{
	$public = __ROOT__.'/Public';
	echo "<meta http-equiv='refresh' content='$time;url=$url'/>";
	$str = <<<EOD
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="$public/Css/mobile/index.css"/>

		<script type="text/javascript" src="$public/Js/Jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				if($("#message_div").attr("id")==null)
				{
					$("body").append('<div id="message_div"><div class="notice_title">提示</div><div class="notice_body"></div></div>');
				}
				$(".notice_body").text('$message');
	            $("#message_div").css("display","block");
			});
		</script>
EOD;
	echo $str;
}

/**
 * 提示消息,并跳转
 * @param unknown $message
 * @param unknown $url
 * @param number $time
 */
function myMessage2($message,$url1,$url2,$result)
{
	if($result) 
	{
		$message.='成功！';
		$time = 1000;
		$url = $url1;
	}
	else 
	{
		$message.='失败！';
		$time = 2500;
		$url = $url2;
	}
	$public = __ROOT__.'/Public';
	//echo "<meta http-equiv='refresh' content='$time;url=$url'/>";
	$str = <<<EOD
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link type="text/css" rel="stylesheet" href="$public/Js/diashow/skins/css/diashow.css"/>
		<script src="$public/Js/jquery-1.8.2.min.js" type="text/javascript"></script>
		<script src="$public/Js/diashow/diashow.js" type="text/javascript"></script>
		<script type="text/javascript" src="$public/Js/Jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				showMessage('$message','$url',$time);
			});
		</script>
EOD;
	echo $str;exit;
}

function json($data, $type='eval',$error) {
	$type = strtolower($type);
	$allow = array('eval','alert','updater','dialog','mix', 'refresh');
	if (false==in_array($type, $allow))
		return false;
	Output::Json(array( 'data' => $data, 'type' => $type,),$error);
}

function myjson($error, $message,$data=array()) {
	if($data==array())
	{
		die( json_encode(array( 'error' => $error, 'message' => $message,)) );
	}
	else 
	{
		die( json_encode(array( 'error' => $error, 'message' => $message, 'data' => $data,)) );
	}
}

/**
 * 格式化金额
 *
 * @param int $money
 * @param int $len
 * @param string $sign
 * @return string
 */
function doFormatMoney($money, $len=2, $sign=''){
	$negative = $money > 0 ? '' : '-';
	$int_money = intval(abs($money));
	$len = intval(abs($len));
	$decimal = '';//小数
	if ($len > 0) {
		$decimal = '.'.substr(sprintf('%01.'.$len.'f', $money),-$len);
	}
	$tmp_money = strrev($int_money);
	$strlen = strlen($tmp_money);
	for ($i = 3; $i < $strlen; $i += 3) {
		$format_money .= substr($tmp_money,0,3).',';
		$tmp_money = substr($tmp_money,3);
	}
	$format_money .= $tmp_money;
	$format_money = strrev($format_money);
	$string = $sign.$negative.$format_money.$decimal;
	if($string=='-0.00')
	{
		$string = '0.00';
	}
	return $string;
}

/**
 * 格式化金额
 *
 * @param string $money
 * @return double $money
 */
function GetMoney($moneyStr,$default=0){
	$money = $default;
	if($moneyStr!='')
	{
		$moneyStr = str_replace(',', '', $moneyStr);
		//echo $moneyStr."<br/>";
		$money = sprintf("%0.2f", $moneyStr);
		//echo $productPrice."<br/>";
	}
	return $money;
}

function objectToArray($obj)
{
	$arr = is_object($obj)? get_object_vars($obj) :$obj;
	foreach ($arr as $key => $val)
	{
		$val=(is_array($val)) || is_object($val) ? objectToArray($val):$val;
		$arr[$key] = $val;
	}
	return $arr;  
}

/**
 * 分页函数
 * @param 总记录数 $count
 */
function sepePage($myPage,$count,$param,$pageSize=5)
{
	import('Org.Util.Page');
	$page      = new Page((int)$count,$pageSize,$param);
	$nowPage   = isset($_GET['p'])?$_GET['p']:1;
	$limit     = (($nowPage-1)*$pageSize).','.$pageSize;
	$myPage->pageCount = $pageSize;
	$myPage->currentPage = $nowPage;
	$myPage->page = $page->show();

	return $limit;
}

/**
 * 获取客户端ip地址
 */
function getIP()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else if(!empty($_SERVER["REMOTE_ADDR"]))
	{
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else
	{
		$cip = '';
	}

	return $cip;
}

/**
 * 发送普通短信通知
 * @param unknown $phone
 * @param unknown $message
 * @return string
 */
function sendMessage($phone,$message)
{
	$data 	= array("code"=>"ytweheart","action"=>"sendKd","phone"=>$phone,"message"=>$message);
	$count	= file_get_contents_post(C("sms_url"),$data);
	return $count;
}

/**
 * 发送发货短信通知
 * @param unknown $phone
 * @param unknown $message
 * @return string
 */
function sendKdMessage($phone,$title,$name,$kdNo)
{
	$count = 0;
	if(!empty($phone) && !empty($name) && !empty($kdNo))
	{
		$message = "您的订单".$title."已发货，承运快递".$name."，快递单号".$kdNo."，请注意查收";

		$count	 = sendMessage($phone,$message);

	}
	return $count;
}

function file_get_contents_post($url, $post=array()) {
	$options = array(
		'http' => array(
				'method' => 'POST',
				'content' => http_build_query($post),
				'header' => "Content-type: application/x-www-form-urlencoded",
		),
	);
	$result = file_get_contents($url, false, stream_context_create($options));
	return $result;
}

function is_mobile(){
	
	$isMobile = false;
	if(!isset($_SERVER['HTTP_USER_AGENT']))
	{
		$isMobile = true;
	}
	else
	{
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', $agent))
		{
			$isMobile = true;
		}
		else
		{
			if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0))
			{
				$isMobile = true;
			}
			else
			{
				$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
				$mobile_agents = array(
						'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
						'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
						'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
						'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
						'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
						'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
						'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
						'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
						'wapr','webc','winw','xda','xda-','Googlebot-Mobile');
				if(in_array($mobile_ua,$mobile_agents))
				{
					$isMobile = true;
				}
				else
				{
					if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) 
					{
						$isMobile = true;
					}
					else 
					{
						$regExp  = "/nokia|iphone|android|samsung|htc|motorola|blackberry|ericsson|huawei|dopod|amoi|gionee|^sie\-|^bird|^zte\-|haier|";
							
						$regExp .= "blazer|netfront|helio|hosin|novarra|techfaith|palmsource|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
							
						$regExp .= "symbian|smartphone|midp|wap|phone|windows ce|windows mobile|CoolPad|webos|iemobile|^spice|longcos|pantech|portalmmm|";
							
						$regExp .= "alcatel|ktouch|nexian|^sam\-|s[cg]h|^lge|philips|sagem|wellcom|bunjalloo|maui|";
							
						$regExp .= "jig\s browser|hiptop|ucweb|ucmobile|opera\s*mobi|opera\*mini|mqqbrowser|^benq|^lct|";
							
						$regExp .= "480×640|640x480|320x320|240x320|320x240|176x220|220x176/i";
							
						if(!isset($_SERVER['HTTP_USER_AGENT']))
						{
							$isMobile = true;
						}
						else
						{
							$isMobile =  isset($_GET['mobile']) || isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($regExp, $agent) || !strpos($agent, 'windows nt');
						}
					}
				}
			}
		}
	}
	
	return $isMobile;	
}
function is_iphone()
{
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$iphone = (strpos($agent, 'iphone')) ? true : false;
	return $iphone;
}

function is_weixin()
{
	$isWeixin = false;
	//检测是否在微信打开
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'MicroMessenger') !== false) {
		$isWeixin = true;
	}
	return $isWeixin;
}

function headgetArrayByJsonerByIsMobile($contol='Mobile')
{
	$test = C('TestState');
	if($test==1)
	{
		return;
	}
	if(empty($_POST))
	{
		//echo 'headerByIsMobile';
		if(isset($_GET["app_type"]))
		{
			if($_GET["app_type"]=='9c')
			{
				return;
			}
		}
		$replace = 'Mobile';
		if(is_mobile())
		{
			$replace = 'Mobile';
		}
		else
		{
			$replace = 'Normal';
		}
		if($replace!=$contol)
		{
			$self = $_SERVER['PHP_SELF'];
			$replace = 'Normal';
			if($contol != 'Mobile')
			{
				$replace = 'Mobile';
			}

			if(strstr($_SERVER['PHP_SELF'], $contol))
			{
				$self = str_replace($contol, $replace, $self);
			}
			else
			{
				$contol = strtolower($contol);
			}
			$self = str_replace($contol, $replace, $self);
			$url = 'http://'.$_SERVER['HTTP_HOST'].$self.'?'.$_SERVER['QUERY_STRING'];
// 			echo $url;exit;
			header('Location: '.$url);
			exit;
		}
	}
}

/**
 * 获取上传的手机图片
 * @param $data 数据
 */
function getShoujiPic($control,$data,$key='shouji_url')
{
	if(session('?shoujiPicArr'))
	{
		$shoujiPics = session('shoujiPicArr');
		$count = count($shoujiPics);
		if($count >= 1)
		{
			if($count > 1)
			{
				$public = getPublicImgPath();
				for ($i=0;$i<$count-1;$i++)
				{
					$path = $public.$shoujiPics[$i];
					unlink($path);
					unset($shoujiPics[$i]);
				}
			}
			$data[$key] = $shoujiPics[$count-1];
		}
		$control->pic_dir = C('web_root').C('pic_dir');
		$control->shouji_pics = $shoujiPics;
	}
	return $data;
}

/**
 * 获取上传的图片
 * @param $data 数据
 */
function getPic($key,$one = 'n',$isDelOther = false)
{
	$value = '';
	if(!empty($_POST[$key]))
	{
		$value = $_POST[$key];
		if($one == 'y')
		{
			$array = explode(";", $_POST[$key]);
			$count = count($array);
			$value = $array[$count-1];
			if($isDelOther)
			{
				if($count > 1)
				{
					$public = getPublicImgPath();
					for ($i=0;$i<$count-1;$i++)
					{
						$path = $public.$array[$i];
						unlink($path);
					}
				}
			}
		}
	}
	return $value;
}
/**
 * 获取保存图片的路径
 */
function getPublicImgPath()
{
	$public = $_SERVER["DOCUMENT_ROOT"].__ROOT__.'/Public/Upload/Img/';
	return $public;
}


/**
 * 设置页面id
 * @param $data 数据
 */
function setPageId($control)
{
	if(!isset($_POST['page_id']))
	{
		$pageId = Model::create_guid();
	}
	else 
	{
		$pageId = $_POST['page_id'];
	}
	$control->page_id = $pageId;
	return $pageId;
}

/**
 * 快递查询 http://m.kuaidi100.com/query?id=1&type=&postid=
 */
function getKuaiId($name,$danhao)
{
	
	$url ="http://m.kuaidi100.com/query?id=1&type={$name}&postid={$danhao}";
	
	$body = file_get_contents($url);
	
	$body = json_decode($body,true);

	return $body;
	
}

/* share link */
 
 //分享到QQ空间
function share_qqkongjian($url,$title,$pic){
	$query = array(
			 
			    'url' => $url,
				//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			    'title' => $title,
			    'pic' => $pic,
		);
	

	$query = http_build_query($query); 
	return 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?'.$query;
}

//分享到人人网
function share_renren($url,$title,$pic='') {
	//http://widget.renren.com/dialog/share?
	//resourceUrl=http%3A%2F%2Fwww.bshare.cn%2Fhelp%2FinstallImage%3Futm_source%3Dbshare%26utm_campaign%3Dbshare%26utm_medium%3Drenren%26bsh_bid%3D574633550%26bsh_vuid%3D4879866
	//&srcUrl=http%3A%2F%2Fwww.bshare.cn%2Fhelp%2FinstallImage%3Futm_source%3Dbshare%26utm_campaign%3Dbshare%26utm_medium%3Drenren%26bsh_bid%3D574633550%26bsh_vuid%3D4879866
	//&title=bShare%E5%88%86%E4%BA%AB%E5%BF%AB%E4%B9%90&images=http%3A%2F%2Fstatic.bshare.cn%2Fimages%2Fshare-image.jpg
	//&description=%E4%B8%AD%E5%9B%BD%E6%9C%80%E5%BC%BA%E5%A4%A7%E7%9A%84%E7%A4%BE%E4%BC%9A%E5%8C%96%E5%88%86%E4%BA%AB%E5%88%86%E4%BA%AB%E5%B7%A5%E5%85%B7%EF%BC%8C%E9%82%80%E8%AF%B7%E6%82%A8%E6%9D%A5%E4%BD%93%E9%AA%8C%EF%BC%81
	$query = array(
			'resourceUrl'=> $url,
			'srcUrl' => $url,
			'url' => $url,
			//'resourceUrl'=>"http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			//'srcUrl' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'title' => $title,
			'pic' => $pic,
	);


	$query = http_build_query($query);
	return 'http://widget.renren.com/dialog/share?'.$query;
}

//分享到开心网
function share_kaixin($url,$title,$pic='') {
	$query = array(
			'url' => $url,
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'style'=>11,
			'title' => $title,
			'&content'=> $title,
			'pic' => $pic,
	);


	$query = http_build_query($query);
	return 'http://www.kaixin001.com/rest/records.php?'.$query;
	//return 'http://b.bshare.cn/bshare_redirect?'.$query;
}

//分享到豆瓣网
function share_douban($url,$title,$pic='') {
	$query = array(
			'url' => $url,
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'title' => $title,
			'pic' => $pic,
	);


	$query = http_build_query($query);
	return 'http://www.douban.com/recommend/?'.$query;
}

//分享到新浪网
function share_sina($url,$title,$pic='') {
	//http://service.weibo.com/share/share.php?
	//appkey=583395093
	//&title=bShare%E5%88%86%E4%BA%AB%E5%BF%AB%E4%B9%90%20-%20%E4%B8%AD%E5%9B%BD%E6%9C%80%E5%BC%BA%E5%A4%A7%E7%9A%84%E7%A4%BE%E4%BC%9A%E5%8C%96%E5%88%86%E4%BA%AB%E5%88%86%E4%BA%AB%E5%B7%A5%E5%85%B7%EF%BC%8C%E9%82%80%E8%AF%B7%E6%82%A8%E6%9D%A5%E4%BD%93%E9%AA%8C%EF%BC%81%20%20
	//&url=http%3A%2F%2Fwww.bshare.cn%2Fhelp%2FinstallImage%3Futm_source%3Dbshare%26utm_campaign%3Dbshare%26utm_medium%3Dsinaminiblog%26bsh_bid%3D574629092%26bsh_vuid%3D4879866
	//&source=bshare
	//&retcode=0
	//&pic=http%3A%2F%2Fstatic.bshare.cn%2Fimages%2Fshare-image.jpg
	//&ralateUid=
	$query = array(
			'url' => $url,
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'title' => $title,
			'pic' => $pic,
	);


	$query = http_build_query($query);
	return 'http://v.t.sina.com.cn/share/share.php?'.$query;
}

//分享到腾讯微博
function share_tencent($url,$title,$pic='') {
	//http://share.v.t.qq.com/index.php?
	//c=share
	//&a=index
	//&title=bShare%E5%88%86%E4%BA%AB%E5%BF%AB%E4%B9%90%20-%20%E4%B8%AD%E5%9B%BD%E6%9C%80%E5%BC%BA%E5%A4%A7%E7%9A%84%E7%A4%BE%E4%BC%9A%E5%8C%96%E5%88%86%E4%BA%AB%E5%88%86%E4%BA%AB%E5%B7%A5%E5%85%B7%EF%BC%8C%E9%82%80%E8%AF%B7%E6%82%A8%E6%9D%A5%E4%BD%93%E9%AA%8C%EF%BC%81%20%20
	//&site=http%3a%2f%2fwww.bshare.cn
	//&pic=http%3A%2F%2Fstatic.bshare.cn%2Fimages%2Fshare-image.jpg
	//&url=http%3A%2F%2Fwww.bshare.cn%2Fhelp%2FinstallImage%3Futm_source%3Dbshare%26utm_campaign%3Dbshare%26utm_medium%3Dqqmb%26bsh_bid%3D573789801%26bsh_vuid%3D4879866
	//&appkey=dcba10cb2d574a48a16f24c9b6af610c
	//&assname=${RALATEUID}
	
	$query = array(
			'url' => $url,
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'title' => $title,
			'pic' => $pic,
	);
	$query = http_build_query($query);
	return 'http://v.t.qq.com/share/share.php?'.$query;
}

//分享到腾讯QQ
function share_qq($url,$title='',$pic=''){
		$query = array(
			'url' => $url,
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'title' => $title,
	
			'pic' => $pic,
	);


	$query = http_build_query($query);
	return 'http://connect.qq.com/widget/shareqq/index.html?'.$query;
}

//分享到搜狐
function share_souhu($url,$title,$pic=''){
	$query = array(
			'url' => $url,
			//'url' => "http://".$_SERVER['HTTP_HOST'].__ROOT__.'/Mobile/index/guid?i=' . $user ["id"] . '&t=' . $user ['user_type'],
			'title' => $title,
			'&content'=> $title,
			'pic' => $pic,
	);


	$query = http_build_query($query);
	return 'http://t.sohu.com/third/post.jsp?'.$query;
}

/**
 * 文件的下载
 * $fileUrl：文件所在路径
 * */
function fileLoad($fileUrl,$filename=null)
{
	if(!empty($fileUrl))
	{
		$file = $fileUrl; // 要下载的文件
		if(empty($filename))
		{
			$filename=basename($file);
		}
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$encoded_filename = urlencode($filename);
		$encoded_filename = str_replace("+", "%20", $encoded_filename);
			
		ob_clean();
		header('Pragma: public');
		header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Cache-Control:pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding:binary');
		header('Content-Encoding:none');
		header('Content-type:multipart/form-data');
		//header('Content-Disposition:attachment; filename="'.$filename.'"'); //设置下载的默认文件名
		if (preg_match("/MSIE/", $ua)) {
			header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
		} else if (preg_match("/Firefox/", $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
		} else {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		}
		header('Content-length:'. filesize($file));
		$fp = fopen($file, 'r'); //读取数据，开始下载
		while(connection_status() == 0 && $buf = @fread($fp, 8192)){
			echo $buf;
		}
		fclose($fp);
		@flush();
		@ob_flush();
		
		//exit();
	}
}



?>