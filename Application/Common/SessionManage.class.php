<?php

/**
 * @author: miaoli
 */

class SessionManage
{	
	/**
	 * 检测登录
	 */
	public static function checkLogin()
	{
		$user = self::getLoginUser();
		if(empty($user))
		{
			header('Location: '.__APP__.'/Home/Index/login');
			exit;
		}
	}

	/**
	 * 调用session
	 */
	public static function sessionStart()
	{
		if(!isset($_SESSION) && !ini_get('session.auto_start')){
			session_start();
		}
	}
	
	/**
	 * 清空session
	 */
	public static function clearSession()
	{
		session_unset();
		session_destroy();
	}
	
	/**
	 * 保存登录用户
	 */
	public static function setSessionUser($user)
	{
		self::setSession(C('SystemRole'),$user);
	}

	/**
	 * 获取登录用户
	 */
	public static function getLoginUser()
	{
		$optUser = self::getSession(C('SystemRole'));
		return $optUser;
	}

    /**
	 * 获取登录用户id
	 */
	public static function getUserId()
	{
		$id='';
		$optUser = self::getLoginUser();
		if(isset($optUser))
		{
			$id = $optUser['id'];
		}
		return $id;
	}
	
	/**
	 * 获取登录用户权限id
	 */
	public static function getUserActionId()
	{
		$actionId = '';
		$optUser  = self::getLoginUser();
		if(isset($optUser))
		{
			$id = $optUser['action_id'];
		}
		return $id;
	}

	/**
	 * 获取登录用户type
	 */
	public static function getUserType()
	{
		$type='';
		$optUser = self::getLoginUser();
		if(isset($optUser))
		{
			if(isset($optUser['user_type']))
			{
				$type = $optUser['user_type'];
			}
		}
		return $type;
	}
	
	/**
	 * 获取登录用户名称
	 */
	public static function getUserName()
	{
		$name='';
		$optUser = self::getLoginUser();
		if(isset($optUser))
		{
			$name = $optUser['name'];
		}
		return $name;
	}
	
	/**
	 * 添加返回url信息
	 */
	public static function addReturnUrl()
	{
		$key = "ks_ud";
		$value = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		$ulist = self::getSession($key);
		if (count($ulist) < 1)
		{
			$ulist = array();
			$ulist[]=$value;
			self::setSession($key, $ulist);
		}
		else
		{
			if ($ulist[(count($ulist) - 1)] != $value)
			{
				$ulist[]=$value;
				self::setSession($key, $ulist);
			}
		}
		// 		print_r($ulist);
	}
	
	/**
	 * 获取返回url信息
	 */
	public static function getReturnUrl()
	{
		$key = "ks_ud";
		$ulist = self::getSession($key);
		if (count($ulist) <= 1)
		{
			SessionManage::setSession(ShowMessage, '当前页已经是最终页面');
		}
		else
		{
			unset($ulist[(count($ulist)-1)]);
			SessionManage::setSession($key, $ulist);
			self::redirect($ulist[(count($ulist)-1)]);
		}
	}
	
	/**
	 * 跳转
	 * @param string $url
	 * @param string $notice
	 * @param string $error
	 */
	public static function redirect($url=null, $notice=null, $error=null) {
		$url = $url ? $url : $_SERVER['HTTP_REFERER'];
		$url = $url ? $url : '/';
		if ($notice)
		{
			SessionManage::setSession(ShowMessage, $notice);
		}
		if ($error)
		{
			SessionManage::setSession('error', $error);
		}
		header("Location: ".$url);
		exit;
	}

    /**
	 * 保存session
	 */
	public static function setSession($key,$value)
	{
		session($key,$value);//[$key] = $value;
	}
	
	/**
	 * 获取session
	 */
	public static function getSession($key,$once=false)
	{
		$value = session($key);
		if($once===true)
		{
			unset($_SESSION[$key]);
		}
		return $value;
	}
}
?>
