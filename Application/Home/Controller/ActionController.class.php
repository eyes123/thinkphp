<?php
namespace Home\Controller;
use Home\Common\Controller\CommonController;
require_once dirname(dirname(dirname(__FILE__))).'/Common/Common.class.php';

class ActionController extends CommonController
{
    //权限管理模块

    // 递归获取树
    public function  showTree($array)
    {
        $expand = '0';//默认0
        $style = '';//默认''
        extract($array);

        $html = "\n<ul>";
        if(!empty($p1))
        {
            if($p1[0]["parent_id"]=="0")
            {
                $expand = '1';//默认0
                $style = 'style="display: none;"';//默认''
            }
        }
        foreach($p1 as $key => $val)
        {

            $html .= "\n<li class='node'>\n<ul><li class='item'><input id='expand_$expand' name='expand_".$val['id']."' type='button' value='' /></li>";
            $html .= "\n<li class='item'><input id='name_".$val['id']."' name='name_".$val['id']."' type='text' value='".$val['name']."' /></li>";
            $html .= "\n<li class='item'><input id='order_no_".$val['id']."' name='order_no_".$val['id']."' type='text' value='".$val['order_no']."' /></li>";
            $html .= "\n<li class='item'><input id='path_".$val['id']."' name='path_".$val['id']."' type='text' value='".$val['path']."' /></li>";
            $html .= "\n<li class='item'><input id='".$val['id']."' name='save_".$val['id']."' type='button' value='' />";
            $html .= "<input id='".$val['id']."' class='add_1' name='add_".$val['id']."' type='button' value='' />";
            $html .= "<input id='".$val['id']."' name='edit_".$val['id']."' type='button' value='' />";
            $html .= "<input id='".$val['id']."' name='del_".$val['id']."' type='button' value='' /></li>";
            if(array_key_exists('child_node',$val))
            {

                $html.="\n<li class='node' $style>".$this->showTree(array('p1' => $val["child_node"]))."</li>";
            }
            $html .= "</ul>\n</li>";
        }
        return $html.='</ul>';
    }

	//功能列表
	public function index()
	{

		\SessionManage::checkLogin();
		$functionDb = new \Home\Model\FunctionModel();
		$functionss = $functionDb->getFuncLists("1=1");
//      print_r($functionss);exit;

		$functions = $functionDb->getFuncListByFunctions($functionss,"0");
		$str = $this->showTree(array("p1"=>$functions));
		$this->functions = $functions;
		// 		echo $str;
		$this->str = $str;
		$this->display();
	}
	
	//功能编辑
	public function function_opt()
	{
		\SessionManage::checkLogin();
		$function = null;
		//获取最上级分类
		$functionDb = D('function');
		$fi = '';
		if(!empty($_GET['fi']))
		{
			$fi = $_GET['fi'];
			$function = $functionDb->getFunctionById($fi);
		}
		if(!empty($_POST))
		{
	// 		print_r($function);
			$data['name']        = trim($_POST['name']);
			$data['path']        = trim($_POST['path']);
			$data['is_show']     = trim($_POST['is_show']);
			$data['parent_id']   = trim($_POST['parent_id']);
			$data['order_no']    = trim($_POST['order_no']);
			$data['is_usable']   = 1;
	
			$message = '';
			if(empty($data['name'] ))
			{
				$message = '功能名称不能为空';
			}
			if($message=='')
			{
				//传来了id则为编辑操作，否则为插入
				if(!empty($fi))
				{
					$data['id']  = $fi;
					$result      = $functionDb->editFunction($data);
					$success 	 = '编辑成功！';
					$fail   	 = '编辑失败！';
				}
				else
				{
					$result = $functionDb->addFunc($data);
					$success = '添加成功！';
					$fail    = '添加失败！';
				}
					
				if($result)
				{
					//添加或修改成功
					$this->success($success,__MODULE__.'/action/index');
					exit;
				}
				else
				{
					//添加或修改失败
					$message = $fail;
// 					exit;
				}
			}
		}
		$this->message = $message;
		$this->funcList =  $functionDb->getFuncLists("id!='$fi'");
		$this->one = $function;
		$this->display();
	}

 	//添加功能
    public function insert()
    {
    	\SessionManage::checkLogin();
        if(!empty($_POST)) //如果是提交
        {
        	$functionDb = D('function');
            $data['name']		=	empty($_POST['name_'])?'新功能':$_POST['name_'];
            $data['path']		=	$_POST['path_'];
            $data['order_no']	=	empty($_POST['order_no_'])?0:$_POST['order_no_'];
            $data['parent_id']	=	empty($_POST['hf_id'])?0:$_POST['hf_id'];

            $result = $functionDb->addFunc($data);
            if(empty($result))
            {
            	$this->error('操作失败！',__MODULE__.'/action');
            }
            else
            {
            	//储存到缓存
            	/** start **/
            	/** end **/
            	$this->success('操作成功！',__MODULE__.'/action');
            }
        }
    }
    
   //保存功能   
    public function save()
    {
    	
    	if(!empty($_POST)) //如果是提交
    	{
    		$functionDb = D('function');
    		$data['id'] = $id = $_POST['hf_id'];
    		$data['name']		=	empty($_POST['name_'.$id])?'新功能':$_POST['name_'.$id];
    		$data['path']		=	$_POST['path_'.$id];
    		$data['order_no']	=	empty($_POST['order_no_'.$id])?0:$_POST['order_no_'.$id];
    	
    		$result = $functionDb->editFunction($data);
    		if(empty($result))
    		{
    			$this->error('操作失败！',__MODULE__.'/action');
    		}
    		else
    		{
    			//储存到缓存
    			/** start **/
    			/** end **/
    			$this->success('操作成功！',__MODULE__.'/action');
    		}
    	}
    }
    
    //删除功能
    public function delete()
    {
    	
    	if(!empty($_POST)) //如果是提交
    	{
//     		print_r($_POST);
    		$functionDb = D('function');
    		$id = $_POST['hf_id'];
    		$result = $functionDb->deleteFuncByIds($id);
    		//echo '$id:'.$id."<br/>";
//     		print_r($functionDb->getLastSql());
    		myMessage2("删除", __MODULE__.'/action', __MODULE__.'/action', $result);
    	}
    }

    //权限列表
    public function pow_list()
    {
    	\SessionManage::checkLogin();
    	$powDb = D('pow');
        $powDb = new \Home\Model\PowModel();

    	$pows = $powDb->getPowList("1=1");
    	$this->pows = $pows;

    	$this->display();
    }
    
    //添加权限
    public function pow_opt()
    {
    	$functionDb = D('function');
    	$powDb = D('pow');
    	
    	$functionss = $functionDb->getFuncLists("1=1");
    	$functions = $functionDb->getFuncListByFunctions($functionss,"0");
    	
    	$url='';
    	$id = '';
    	$ids = array();
    	
    	if(!empty($_GET['pow_id']))
    	{
    		$id = $_GET['pow_id'];
    		$pow = $powDb->getPowById($id);
    		
    		if(!empty($pow))
    		{
    			if(!empty($pow['data']))
    			{
    				$idss = explode(',', $pow['data']);
    			}
    		}
    		$this->one = $pow;
    	}
    	if(!empty($_POST)) //如果是提交
    	{
    		$this->one = $_POST;
    		$message = '';
    	
    		$name = trim($_POST['pow_name']);
    	
    		/** 检测权限名称 **/
    		if(empty($name))
    		{
    			$message = '权限名称不能为空';
    		}
    		else
    		{
    			$count = $powDb->getPowCountByWhere("id!='$id' and pow_name='$name'");
    			//echo $powDb->getLastSql();
    			if($count>0)
    			{
    				$message = '已存在相同的权限名称';
    			}
    		}
    		if($message=='')
    		{
    			//编辑的数据
    			$data['pow_name'] =	$name;
    			if(!empty($_POST['function']))
    			{
    				$idss = $_POST['function'];
    				$data['data'] = implode(",", $idss);
    			}
    			else 
    			{
    				$data['data'] = '';
    			}
    			//echo $data['data'];exit;
    			if(empty($id))
    			{
    				$result = $powDb->addPow($data);
    			}
    			else
    			{
    				$data['id'] = $id;
    				$result = $powDb->editPow($data);
    			}
    			if(!empty($result))
    			{
    				$url = __MODULE__.'/action/pow_list';
    				myMessage2('保存', $url,'',$result);exit;
    			}
    			$message = empty($result)?'保存失败！':'保存成功！';
    		}
    	}
    	foreach ($idss as $key=>$value)
    	{
    		$ids[$value]='checked="checked"';
    	}
//     	print_r($ids);
    	$this->ids = $ids;
    	$this->functions = $functions;
    	$this->message = $message;
    	$this->url = $url;
    	$this->display();
    }
    
    //删除权限
    public function pow_delete()
    {
    	
    	$result = 0;
    	$powDb = D('pow');
    	$ids='';
    	if(!empty($_REQUEST['pow_id']))
    	{
    		$ids = $_REQUEST['pow_id'];
    	}
    	else if(!empty($_REQUEST['checkname']))
    	{
    		$ids = implode(',', $_REQUEST['checkname']);
    		$ids = str_replace(',', '","', $ids);
    		$ids = '"'.$ids.'"';
    	}
//     	echo '$ids:'.$ids;exit;
    	if(!empty($ids))
    	{
    		$userpowDb = D('UserPow');
    		$pow_id=$userpowDb->getUserPowById1($ids);
//     		print_r($pow_id);exit;
    		if(empty($pow_id))
    		{
    			$result = $powDb->deletePowByIds($ids);
    			$message='删除';
    		}
    		else 
    		{
    			$message='该权限下有用户，删除';
    		}
    		
    	}
    	

		myMessage2($message,__MODULE__.'/action/pow_list',__MODULE__.'/action/pow_list',$result);
    }
}

?>