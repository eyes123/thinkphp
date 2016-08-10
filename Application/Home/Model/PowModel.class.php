<?php
namespace Home\Model;

use Think\Model;
use Org\Util\Page;

class PowModel extends Model
{
	protected $trueTableName = 'act_pow';
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加权限操作
	 */
	public function addPow($data)
	{
		$result = $this->add($data);
// 		print_r($data);exit;
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('添加权限，权限:'.$data["function_name"], $mResult);
		return $result;
	}	

	/**
	 * 删除权限操作
	 */
	public function deletePowByIds($ids)
	{
		$affectRow = $this->where("id in ($ids)")->delete();

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
	 * 编辑权限
	 */
	public function editPow($data)
	{
		$actionId= $data["id"];
		$result = $this->where("id = '".$actionId."'")->save($data);
		

		$functionDb = D('Function');
		$name = 'menuList'.$actionId;
		$menuList = $functionDb->getMenuListByActionId($actionId);
		S($name,$menuList,3600);
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('编辑权限，关联id:'.$id.'，修改名称为：'.$powName, $mResult);
		return $result;
	}
	/**
	 * 通过功能id值查询功能
	 */
	public function getPowById($id)
	{
		$arr = $this->where('id='.$id)->select();
		$one = empty($arr)?null:$arr[0];
		return $one;
	}
	/**
	 *获取列表
	 */
	public function getPowList($where='1=1',$order = ' id ',$pageSize = 0)
	{
		$expression = array();
		if($pageSize==0)
		{
			$pageSize = C('pageCount');
		}
		$count = $this->where($where)->count();
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$expression['list'] = $this->where($where)->order($order)->limit($nowPage-1,$pageSize)->select();
// 		print_r($this->getLastSql());
		import('Org.Util.Page');
		$page      = new Page((int)$count,$pageSize,$_GET);
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$expression['page'] = $page->show();
// 		print_r($expression);exit;
		return $expression;
	}
	
	/**
	 *获取列表
	 */
	public function getPowLists($where = '1=1',$order = ' id ')
	{
		$arr = $this->where($where)->order($order)->select();
		return $arr;
	}

	/**
	 * 通过功能id值找到权限的名字
	 */
	public function getPowNameById($id)
	{
		$arr = $this->field('pow_name')->where('id='.$id)->select();
		$name = empty($arr)?"":$arr[0]['pow_name'];
		return $name;
	}
	
	/**
	 * 通过功能id值找到权限的数据
	 */
	public function getPowDataById($id)
	{
		$arr = $this->field('data')->where('id='.$id)->select();
		$data = empty($arr)?"":$arr[0]['data'];
// 		echo 	$this->getLastSql();
		return $data;
	}
	
	/**
	 * 通过功能id值找到功能的名字
	 */
	public function getPowCountByName($name)
	{
		$count = $this->where("pow_name='.$name'")->count();
// 		print_r($this->getLastSql());exit;
		return $count;
	}
	/**
	 * 通过条件查询数据条数
	 */
	public function getPowCountByWhere($where)
	{
		$count = $this->where($where)->count();
// 		print_r($count);exit;
		return $count;
	}
	
	/**
	 * 查询"查看所有订单"权限
	 */
	public function isSelectAllOrder($actionId)
	{
		$flag = $this->isHaveOpt($actionId,'SelectAllOrder');
		return $flag;
	}
	
	/**
	 * 查询"查看所有"权限
	 */
	public function isSelectAll($actionId)
	{
		$flag = $this->isHaveOpt($actionId,'SelectAll');
		return $flag;
	}
	
	/**
	 * 查询"查看所有订单"权限
	 */
	public function isHaveOpt($actionId,$path)
	{
		$flag = false;
		if(!empty($actionId) && !empty($path))
		{
			$data = $this->getPowDataById($actionId);
			//var_dump($data);exit;
			if(!empty($data))
			{
				$functionDb = new \Home\Model\FunctionModel();
				$id = $functionDb->getFuncIdByPath($path);
				$array = explode(',', $data);
				if(!empty($array))
				{
					if(in_array($id,$array))
					{
						$flag = true;
					}
				}
			}
		}
		return $flag;
	}
}
?>