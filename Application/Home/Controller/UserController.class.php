<?php

namespace Home\Controller;
use Home\Common\Controller\CommonController;
use Org\Util\Page;
use Think\Template\Driver\Mobile;
require_once dirname(dirname(dirname(__FILE__))).'/Common/SessionManage.class.php';
require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/Common/Common.class.php';

class UserController extends CommonController 
{

	public function index()
    {
		$userDb = D('user');
		import('Org.Util.Page');
		$count = $userDb->where(array('user_type'=>'administrator'))->count();
		$pageCount = C('pageCount');
		$page = new Page((int)$count,$pageCount);
		
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$limit = $nowPage.','.$page->listRows;
		
		$users = $userDb->page($limit)->where(array('user_type'=>'administrator'))->order('create_time desc')->select();
		
		$this->page = $page->show();
		$this->users = $users;
		$this->display();
	}
		
	//用户列表
	public function user()
	{
		\SessionManage::checkLogin();
		$userDb = D('user');
		import('Org.Util.Page');
		$count = $userDb->count();
		$pageCount = C('pageCount');
		$page      = new Page((int)$count,$pageCount);
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$limit     = $nowPage.','.$page->listRows;
		$users     = $userDb->page($limit)->select();
	
		$this->page  = $page->show();
		$this->users = $users;
	
		$this->display();
	}

	//拼接搜索搜索条件
	public function jointWhere()
	{
		$where = array();
	
		//搜索条件拼接
		if(!empty($_REQUEST['submit']))
		{
			if(!empty($_REQUEST['name']))
			{
				$where['name'] = array('like','%'.$_REQUEST['name'].'%');
				$this->titleWhere = $_REQUEST['name'];
			}
			if(!empty($_REQUEST['start_time']))
			{
				$where['create_time'] = array('EGT',$_REQUEST['start_time']);
				$this->startWhere     = $_REQUEST['start_time'];
			}
	
			if(!empty($_REQUEST['end_time']))
			{
				if(!empty($where['create_time'])) {
					unset($where['create_time']);
					$where['create_time'] = array('between',array($_REQUEST['start_time'],$_REQUEST['end_time']));
				} else {
					$where['create_time'] = array('ELT',$_REQUEST['end_time']);
				}
				$this->endWhere       = $_REQUEST['end_time'];
			}
	
		}
		
		if(!empty($_REQUEST['where'])) {
			$where = json_decode(urldecode($_REQUEST['where']),true);
		}
		 
		return $where;
	}

	//添加用户
	public function add()
	{
		\SessionManage::checkLogin();
        $userDb = D('user');
	    if(!empty($_REQUEST['submit']))
		{
//            if (!$userDb->create()) {
//                // 如果创建失败 表示验证没有通过 输出错误提示信息
//                exit($userDb->getError());
//            }
//            else {
                $s = $userDb->getUserCountWhere($_REQUEST['user_name']);
                if (!empty($s)) {
                    echo "用户名已经存在,请重新添加用户名";
                } else {
                    $data = array();
                    $data['id'] = $userDb->create_guid();
                    $data['user_type'] = 'administrator';
                    $data['name'] = $_REQUEST['user_name'];
                    $data['passwd'] = md5($_REQUEST['user_pwd']);
                    $data['card_num'] = $_REQUEST['card_num'];
                    $data['card_owner'] = $_REQUEST['card_owner'];
                    $data['bank_name'] = $_REQUEST['bank_name'];

                    $result = $userDb->addData($data);
                    $userpowDb = D('UserPow');
                    $userId = $userDb->getLastInsID();
                    $datas = array();
                    $datas['pow_id'] = $_REQUEST['pow_id'];
                    $datas['user_id'] = $data['id'];
                    $userpow = $userpowDb->addUserPow($datas);

                    $url = __MODULE__ . '/user/index';
                    if ($result) {
                        $this->success('添加成功！', $url);
                        exit;
                    } else {
                        $this->error('添加失败！', $url);
                        exit;
                    }
                }
            }
		       
//		}
		
		//查询权限名称
		$powDb=D('pow');
		$pow = $powDb->getPowLists();
		$this->pow = $pow;
		$this->one = $_REQUEST;
		$this->display();
	}
	
	//编辑用户
	public function edit()
	{
		\SessionManage::checkLogin();
		if(!empty($_REQUEST['user_id']))
		{
			$userDb = D('user');
			$userpowDb=D('UserPow');
			if(!empty($_REQUEST['act']) && ($_REQUEST['act'] == 'editSubmit'))
			{
				$ss = $userDb->getUserCountWhere1($_REQUEST['user_name'],$_REQUEST['user_id']);
				if(!empty($ss))
				{
					echo "用户名已经存在,请重新编辑用户名";
				}
				else 
				{
					$data['id']         = $_REQUEST['user_id'];
					$data['user_type']  = $_REQUEST['user_type'];
					$data['name']       = $_REQUEST['user_name'];
					$data['card_num']   = $_REQUEST['card_num'];
					$data['card_owner'] = $_REQUEST['card_owner'];
					$data['bank_name']  = $_REQUEST['bank_name'];
					
					if(!empty($_REQUEST['user_pwd']))
					{
				        $data['passwd']    = md5($_REQUEST['user_pwd']);					
					}
					$result = $userDb->editUser($data);
					
					$url  = $_REQUEST['user_type']=='merchant'?'supplier':'index';
					$url  = __MODULE__.'/user/'.$url;
					
					$datas=array();
					
					$datas['pow_id']=$_REQUEST['pow_id'];
					$datas['user_id']=$_REQUEST['user_id'];
					$where = "user_id='".$datas['user_id']."'";
					$count = $userpowDb->getUserPowCountByWhere($where);
					if($count>0)
					{
						$result0 = $userpowDb->editUserPow($datas,$where);
					}
					else 
					{
						$result0 = $userpowDb->addUserPow($datas,$where);
					}
					if($result || $result0)
					{
						$this->success('修改成功！',$url);
						exit;
					}
					else
					{
						$this->error('修改失败！',$url);
						exit;
					}
				}
			}
			$actionId=$userpowDb->getUserPowIdByUserId($_REQUEST['user_id']);
			//查询权限名称
			$powDb=D('pow');
			$pow = $powDb->getPowLists();
			$this->pow = $pow;
			$id = $_REQUEST['user_id'];
			$user = $userDb->getUserById($id);
			$this->user = $user;
			$this->actionId=$actionId;
			$this->display();
		}
	}
	
	//删除用户
	public function delete()
	{
		//if::批量删除 ;elseif::单个删除
		if(!empty($_REQUEST['checkname']))
		{
			$ids = implode(',', $_REQUEST['checkname']);
			$ids = str_replace(',', '","', $ids);
			$ids = '"'.$ids.'"';
	
			$userDb   = D("user");
			$result = $userDb->delAds($ids);
	
		}
		elseif(!empty($_REQUEST['user_id']))
		{
			$userDb   = D("user");
			$result = $userDb->delAd($_REQUEST['user_id']);
		}
// 		print_r($_REQUEST['user_type']);exit;
		$url  = $_REQUEST['user_type']=='merchant'?'supplier':'index';
		$url  = __MODULE__.'/user/'.$url;
		if(!empty($result))
		{
			$this->success('删除成功',$url);
			exit;
		}
		else
		{
			//添加失败
			$this->error('删除失败！',$url);
			exit;
		}
	}

    //编辑登录用户
    public function editlogin()
    {
        if(!empty($_REQUEST['submit']))
        {
            $userId=\SessionManage::getUserId();
            $userDb=D('User');
            $users=$userDb->getUserById($userId);
            $user_pw=md5($_REQUEST['user_pw']);

            if($users['passwd']==$user_pw)
            {
                if(!empty($_REQUEST['user_pwd']))
                {
                    if($_REQUEST['user_pwd']==$_REQUEST['confirm_pwd'])
                    {
                        $data=array();
                        $data['id']=$userId;
                        $data['passwd']=md5($_REQUEST['user_pwd']);
                        $result=$userDb->editUser($data);

                        if($result)
                        {
                            $this->success('修改成功！',__MODULE__/Index/index);
                            exit;
                        }
                        else
                        {
                            $this->error('修改失败！',__MODULE__/Index/index);
                            exit;
                        }
                    }
                    else
                    {
                        echo  "两次输入的密码不一致";
                    }
                }
                else
                {
                    echo "新密码输入有误！";
                }
            }
            else
            {
                echo "原密码输入有误！";
            }
        }
        $this->display();
    }


}

?>