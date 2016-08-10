<?php

namespace Home\Model;

use Think\Model;

class UserModel extends Model
{
	protected $connection = 'DB_DSN';
	protected $trueTableName  = 'yes_user';
		
	public function __construct()
	{
		parent::__construct();
		
	}
//
//    //自动验证
//    protected $_validate =array(
//        array('verify','require','验证码必须'),//默认情况下用正则验证
//        array('name','','用户名已经存在!',0,'unique',1),//在添加用户的时候验证一下用户是否唯一
//    );
	
	/**
	 * 
	 * 功能：用户登录函数
	 * 返回：
	 *     登录失败--0
	 *     登录成功--返回用户信息
	 */
	public function login($userName, $pwd)
	{
		$arr['name']   = $userName;
		$arr['passwd'] = md5($pwd);
		//$arr['passwd'] = $pwd;

		$rows = $this->where($arr)->select();
// 		echo $this->getLastSql();exit;
		if(count($rows) <= 0)
		{
			return 0;
		}

		return $rows['0'];
	}
	
	/**
	 * 获取用户的条数
	 */
	public function getUserCount()
	{
		$count = $this->count();
		return $count;
	}
	/**
	 * 判断用户名是否重复
	 */
	public function getUserCountWhere($name)
	{
		$count = $this->where("name='$name'")->count();
		return $count;
	}
	/**
	 * 判断用户名是否重复
	 */
	public function getUserCountWhere1($name,$id)
	{
		$count = $this->where("name='$name' and id!='$id'")->count();
		return $count;
	}
	
	/**
	 * 获取所有用户
	 */
	public function getUseList($limit)
	{
		$rows=$this->page($limit)->select();
		return $rows;
	}
	
	/**
	 * 获取商户类型的用户
	 */
	public function getMerchant()
	{
		$rows=$this->where('user_type="merchant"')->select();
		
		return $rows;
	}
	/**
	 * 获取商户类型的用户
	 */
	public function getMerchant1($id)
	{
		$rows = $this->where("id='$id' and user_type='merchant'")->select();
// 				print_r($rows[0]);exit;
		return $rows[0];

	}
	
	//添加
	public function addData($data)
	{
		if(empty($data['id']))
		{
			$data['id']=$this->create_guid();
		}
		if(empty($data['card_owner']))
		{
			$data['card_owner']='null';
		}
		if(empty($data['card_num']))
		{
			$data['card_num']='null';
		}
		if(empty($data['bank_name']))
		{
			$data['bank_name']='null';
		}
		if(empty($data['phone']))
		{
			$data['phone']='null';
		}


            $result = $this->add($data);
		
		return $result;
	}
	
	//获取用户信息
	public function getUserById($id)
	{
		$user=null;
		$rows=$this->where("id='".$id."'")->select();
		if(!empty($rows))
		{
			$user = $rows['0'];
			if($user['card_owner']=='null')
			{
				$user['card_owner']='';
			}
			if($user['card_num']=='null')
			{
				$user['card_num']='';
			}
			if($user['bank_name']=='null')
			{
				$user['bank_name']='';
			}
			if($user['phone']=='null')
			{
				$user['phone']='';
			}
		}
// 		echo $this->getLastSql();
		return $user;
	}
	
	//修改用户信息
	public function editUser($data)
	{
		$result= $this->where("id='".$data['id']."'")->save($data);
		
		return $result;
	}

	//批量删除
	public function delAds($ids)
	{
		
		$result= $this->where('id in('.$ids.')')->delete();
		
		return $result;
	}
	
	//单个删除
	public function delAd($id)
	{
		
		$result=$this->where('id="'.$id.'"')->delete();
		
		return $result;
	}
	
	/**
	 * 查询名称
	 * @param unknown $appId
	 */
	public function getUserName($id)
	{
		$name = '';
		$rows = $this->field('name')->where("id='".$id."'")->select();
		if(!empty($rows))
		{
			$name = $rows[0]["name"];
		}
		return $name;
	}
}

?>