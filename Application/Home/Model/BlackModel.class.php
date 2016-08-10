<?php

namespace Home\Model;

use Think\Model;

class BlackModel extends Model
{
	protected $connection = 'DB_DSN';
	protected $trueTableName  = 'yes_black';
		
	public function __construct()
	{
		parent::__construct();
		
	}

	//判断
	public function blackCountIp($ip)
	{
		$sql="select `count` from `yes_black` where `ip`='$ip'";
		$count=$this->query($sql);
		$number = empty($count)?0:$count[0]['count'];
		return $number;
	}
	
	//判断
	public function blackCountLoginName($loginName)
	{
		$minTime 	= 10;//错误间隔时间,分钟
		$time 		= 12;//等待时间,小时
		$cishu 		= 3;//最大错误次数

		$message = '';
		$shifenqian = strtotime("-".$minTime." minute");
		$waitTime = strtotime("-".$time." hours");
		$now = strtotime();
// 		$time1 = date("Y-m-d H:i:s",strtotime("-".$minTime." minute"));
// 		echo '开始';
		//查询十分钟内是否有登录密码错误,若没有,删除之前记录
		$rows = $this->field("`id`,`count`,`login_time`")->where("`ip`='".$loginName."'")
			->order("login_time desc")
			->limit("1")
			->select();
		$number 	= empty($rows)?0:$rows[0]['count'];
		$loginTime 	= empty($rows)?0:strtotime($rows[0]['login_time']);

		//如果(小于最大错误次数,并且大于错误间隔时间)或者(大于等于于最大错误次数 且 大于等待时间)恢复初始值0
		$huifu = false;
		if($number < $cishu)
		{
			if($shifenqian > $loginTime && $number > 0)
			{
				$huifu = true;
			}
		}
		else if($waitTime > $loginTime)
		{
			$huifu = true;
		}
		
		if($huifu)
		{
			$data 	= array("count"=>0);
			$result = $this->where("`id`='".$rows[0]["id"]."'")->save($data);

		}
		else 
		{
			if($number>=$cishu)
			{
				$dengdai = ceil(($loginTime-$waitTime)/3600);
				
				$message="您的登录次数已经达到上限,请等待".$dengdai."小时后重新登录!";
			}
		}

		return $message;
	}

	//判断
	public function blackIp($ip)
	{
		$sql="select `id` from `yes_black` where `ip`='$ip'";
		$count=$this->query($sql);
		$id = empty($count)?0:$count[0]['id'];
		if(empty($id))
		{
			$data=array();
			$data['ip']			= $ip;
			$data['count']		= 1;
			$data['login_time']	= date("Y-m-d H:i:s");
			$result = $this->add($data);

        }
        else 
        {
       		$sql="update `yes_black` set `count`=`count`+1 where id='$id'";
       		$this->execute($sql);
		}
	}
}

?>