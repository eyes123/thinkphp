<?php
namespace Home\Model;

use Think\Model;
use Org\Util\Page;

class FunctionModel extends Model
{
	protected $trueTableName = 'act_function';
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加功能名称操作
	 */
	public function addFunc($data)
	{
		$result = $this->add($data);
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('添加功能名称，功能名称:'.$data["function_name"], $mResult);
		return $result;
	}	

	/**
	 * 删除功能名称操作
	 */
	public function deleteFuncByIds($ids)
	{
		$affectRow = $this->where("id in ($ids)")->delete();
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('删除功能名称，关联id:'.$id, $mResult);

		
		return $affectRow;
	}
	
	/**
	 * 编辑功能名
	 */
	public function editFunction($data)
	{
		$affectRow = $this->where("id = '".$data["id"]."'")->save($data);
		$result=false;
		if($affectRow > 0)
		{
			$result = true;
		}
// 		$mResult=$result?'【成功】':'【失败】';
// 		$this->sysoptlog->addOptLog('编辑功能名称，关联id:'.$id.'，修改名称为：'.$powName, $mResult);
		return $result;
	}
	/**
	 * 通过功能id值查询功能
	 */
	public function getFunctionById($id)
	{
		$arr = $this->where('id='.$id)->select();
		$one = empty($arr)?null:$arr[0];
		return $one;
	}
	/**
	 *获取列表
	 */
	public function getFuncList($where='1=1',$order = ' order_no ',$pageSize = 0)
	{
		$arr = array();
		if($pageSize==0)
		{
			$pageSize = C('pageCount');
		}
		$count = $this->where($where)->count();
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$arr['list'] = $this->where($where)->order($order)->limit($nowPage-1,$pageSize)->select();
		import('Org.Util.Page');
		
		$page      = new Page((int)$count,$pageSize,$_GET);
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$arr['page'] = $page->show();

		return $arr;
	}
	
	/**
	 *获取列表
	 */
	public function getFuncLists($where = '1=1',$order = ' order_no ')
	{
		$arr = $this->where($where)->order($order)->select();
		return $arr;
	}

	/**
	 * 通过功能id值找到功能的名字
	 */
	public function getFuncNameById($id)
	{
		$arr = $this->field('name')->where('id='.$id)->select();
		$name = empty($arr)?"":$arr[0]['name'];
		return $name;
	}


	/**
	 * 根据用户id查询页面菜单
	 */
	public function getMenuListByActionId($actionId)
	{
		$menu = array();
		$db = new \Home\Model\PowModel();
        //根据权限id 查询功能id的集合
		$data = $db->getPowDataById($actionId);
		if(!empty($data))
		{
            //功能id 是否存在查询出来的data集合里面
			$functionss = $this->getFuncLists("id in ($data) and is_show!=0 and is_usable!=0");
		}

		if(!empty($functionss[0]))
		{
			$i=0;
			foreach ($functionss as $key=>$function0)
			{
				if($function0['parent_id']=="0")
				{
					$menu[$i] = $function0;
					$j=0;
					$menu[$i]['child_node'] = array();
					foreach ($functionss as $key=>$function1)
					{
						if($function1['parent_id']==$function0["id"])
						{
							$menu[$i]['child_node'][$j] = $function1;
							$j++;
						}
					}
					if($j>0)
					{
						$i++;
					}
				}
			}
		}
		return $menu;
	}
	
	/**
	 * 获取功能列表数据
	 * @param unknown $functions
	 * @param unknown $id
	 * @return Ambigous <multitype:unknown , multitype:NULL Ambigous <string, mixed> >
	 */
	public function getFuncListByFunctions($functions,$id)
	{
		$array = array();
		if(!empty($functions[0]))
		{
			$i=0;
			foreach ($functions as $key=>$function)
			{
				if($function['parent_id']==$id)
				{
					$array[$i] = $function;

					$array[$i]['child_node'] = $this->getFuncListByFunctions($functions,$function['id']);
					$i++;
				}
			}

		}
		return $array;
	}
	
	/**
	 * 通过条件查询数据条数
	 */
	public function getFuncCountByWhere($where)
	{
		$count = $this->where($where)->count();
		return $count;
	}
	
	/**
	 * 通过path查询功能id
	 */
	public function getFuncIdByPath($path)
	{
		$id='';
		$rows = $this->field("id")->where("path='$path'")->select();
		if(!empty($rows[0]))
		{
			$id = $rows[0]["id"];
		}
		return $id;
	}
}
?>