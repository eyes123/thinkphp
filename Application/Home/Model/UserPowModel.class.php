<?php
namespace Home\Model;

use Think\Model;

class UserPowModel extends Model
{
	protected $trueTableName = 'act_user_pow';
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加权限操作
	 */
	public function addUserPow($data)
	{
		$result = $this->add($data);
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('添加权限，权限:'.$data["function_name"], $mResult);
//		$optlogDb=new OptlogModel();
//		$optlogDb->OptLog('添加用户权限,id:'.$data['id'],$result);
// 		echo $this->getLastSql();exit;
		return $result;
	}	

	/**
	 * 删除权限操作
	 */
	public function deleteUserPowByIds($ids)
	{
		$affectRow = $this->where("id in ($ids)")->delete();
//		$optlogDb=new OptlogModel();
//		$optlogDb->OptLog('删除用户权限操作,id:'.$ids,$affectRow);
		$result=false;
		if($affectRow > 0)
		{
			$result = true;
		}
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('删除权限，关联id:'.$id, $mResult);
		return $result;
	}
	
	/**
	 * 编辑功能名
	 */
	public function editUserPow($data,$where='')
	{
		if($where=='')
		{
			$where = "id = '".$data["id"]."'";
		}
		$result = $this->where($where)->save($data);
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('编辑权限，关联id:'.$id.'，修改名称为：'.$powName, $mResult);
//		$optlogDb=new OptlogModel();
//		$optlogDb->OptLog('编辑用户功能名,id:'.$data['id'],$result);
		return $result;
	}
	
	/**
	 * 通过功能id值查询功能
	 */
	public function getUserPowById($id)
	{
		$arr = $this->where('id='.$id)->select();
		$one = empty($arr)?null:$arr[0];
		return $one;
	}
	
	/**
	 * 通过功能id值查询功能
	 */
	public function getUserPowById1($ids)
	{
		$arr = $this->where("pow_id in ($ids)")->select();
		$one = empty($arr)?null:$arr[0];
		return $one;
	}

	/**
	 *获取列表
	 */
	public function getUserPowLists($where = '1=1',$order = ' order_no ')
	{
		$arr = $this->where($where)->order($order)->select();
		return $arr;
	}
	
	/**
	 * 通过用户id值找到权限的id
	 */
	public function getUserPowIdByUserId($id)
	{
		$arr = $this->field('pow_id')->where("user_id='$id'")->select();
		$name = empty($arr)?"":$arr[0]['pow_id'];
		return $name;
	}
	
	/**
	 * 通过功能id值找到功能的名字
	 */
	public function getUserPowCountByName($name)
	{
		$count = $this->where("pow_name='.$name'")->count();
// 		print_r($this->getLastSql());exit;
		return $count;
	}
	/**
	 * 通过功能id值找到功能的名字
	 */
	public function getUserPowCountByWhere($where)
	{
		$count = $this->where($where)->count();
		return $count;
	}
}
?>