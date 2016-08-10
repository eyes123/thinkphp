<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Controller\CommonController;
require_once dirname(dirname(dirname(__FILE__))).'/Common/Common.class.php';
require_once dirname(dirname(dirname(__FILE__))).'/Common/SessionManage.class.php';
class IndexController extends Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

    //首页
    public function index()
    {
        CommonController::CheckAdmin();
        $this->display();
    }

	//登录页面及登录验证	 
	public function login()
	{
		$ip	 	 = getIp();
		$whiteDb = D("White");
		$blackDb = D("black");
		$count	 = $whiteDb->getWhitecount($ip);
		if($count == 0)
		{
			$countb	 = $blackDb->blackCountIp($ip);
			if($countb>2)
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
				echo "您的登录次数已经达到上限";
				exit;
			}
		}
		if(!empty($_REQUEST))
		{
			$userName = trim($_REQUEST['username']);
			$pwd      = trim($_REQUEST['password']);

			if(!empty($userName) && !empty($pwd))
			{
				$userDb = D("User");
				$user   = $userDb->login($userName, $pwd);

				if($user == 0)
				{
					$blackDb->blackIp($ip);
					$this->error('登录失败，用户名或密码有误！');
					//header('Location:'.__APP__.'/Home/Index/login');
					//exit;
				}
				else
				{
					//根据当前用户id,从用户权限表,获取权限id
					$userPowDb = new \Home\Model\UserPowModel();
					$user['action_id'] = $userPowDb->getUserPowIdByUserId($user['id']);
					\SessionManage::setSession(C('SystemRole'), $user);//(C('SystemRole'),$user);
					$functionDb = new\Home\Model\FunctionModel();
					$name = 'menuList'.$user['action_id'];
                    //通过权限id获取功能列表信息
					$menuList = $functionDb->getMenuListByActionId($user['action_id']);
					S($name,$menuList,3600);
// 					if($user['user_type'] == 'merchant')
// 					{
// 						header('Location:'.__APP__.'/Supplier/Index/index');
// 						exit;
// 					}

					header('Location:'.__APP__.'/Home/Index/index');
					exit;
				}
			}
		}
		$this->display();
	}
	
	//退出
	public function logout()
	{		

        \SessionManage::setSession(C('adminUser'), null);
        //session(null);
        cookie(null);
        header('Location:'.__APP__.'/Home/Index/login');
        exit;
	}

    //头部
    public function top()
    {
    	$user = session(C('SystemRole'));
    	$userTypes = C('user_type');
    	$userType = $user['user_type'];

    	$this->userType = $userTypes[$userType];
        $this->userName = $user['name'];
        $this->dateTime = date('Y-m-d H:i:s');
        $this->ip       = get_client_ip();
        $this->partner = $user;
    	$this->display();
    }

	//左边菜单
	public function left()
	{
		$menuList = array();
		//获取缓存数据
		$actionId = \SessionManage::getUserActionId();

		if(!empty($actionId))
		{
			$name = 'menuList'.$actionId;
			$menuList = S($name);
			if(empty($menuList))
			{
				$functionDb = new \Home\Model\FunctionModel();
				$menuList = $functionDb->getMenuListByActionId($actionId);
				S($name,$menuList,3600);
				//'这个是直接从数据库中读取的文件';
			}
		}

		$this->menuList = $menuList;
		$this->display();
	}
	
	//中间部分
	public function center()
    {
    	
    	$this->display();
    }
    
    //主要部分(右边菜单)
    public function main()
    {
    	$this->name = \SessionManage::getUserName();
    	$this->display();
    }
 
    //底部
    public function down()
    {
    	$this->display();
    }

    /**
     * 返回所有符合条件的结果，以数组返回
     */
    protected function fetchAll($sqlStr=null)
    {
    	if($sqlStr!=null)
    	{
    		$this->QueryString = $sqlStr;
    		// 			echo $sqlStr;
    	}
    	$this->query();
    	$array = array();
    	while($temp_array=$this->fetchArray($this->Resource))
    	{
    		$array[]=$temp_array;
    	}
    	return $array;
    }

    
}