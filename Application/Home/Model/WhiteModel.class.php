<?php

namespace Home\Model;
use Think\Model;

class WhiteModel extends Model
{
	protected $trueTableName  = 'yes_white';
		
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取所有用户
	 */
	public function getWhitecount($ip)
	{
		$sql = "select count(ip) as number from yes_white where ip='$ip'";
		$count = $this->query($sql);
		if(empty($count[0]))
		{
			$number = 0;
		}
		else
		{
			$number = $count[0]['number'];
		}
		return $number;
	}

}

?>