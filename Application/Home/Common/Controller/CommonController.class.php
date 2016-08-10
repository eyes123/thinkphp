<?php
namespace Home\Common\Controller;
use Think\Controller;
use Org\Util\Page;
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/Common/SessionManage.class.php';

class CommonController extends Controller 
{
	public function __construct()
	{
		parent::__construct();
		if(!empty($_REQUEST))
		{
			foreach ($_REQUEST as $key=>$value)
			{
				if(!is_array($_REQUEST[$key]))
				{
					$_REQUEST[$key] = urldecode($value);
				}
			}
		}
	}

	//验证用户是否登录
	public static function CheckAdmin()
	{
		\SessionManage::checkLogin();
	}
	/**
	 * 分页函数
	 * @param 总记录数 $count
	 */
	public function sepePage($count,$param,$pageCount=0)
	{
		import('Org.Util.Page');
		if($pageCount==0)
		{
			$pageCount = C('pageCount');
		}
		$page      = new Page((int)$count,$pageCount,$param);
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$limit     = $nowPage.','.$page->listRows;

		$this->pageCount = $pageCount;
		$this->currentPage = $nowPage;
		$this->page = $page->show();
		return $limit;
	}
	
	/**
	 * 分页函数
	 * @param 总记录数 $count
	 */
	public function getLimit($count,$pageCount=0)
	{
		if($pageCount==0)
		{
			$pageCount = C('pageCount');
		}
		$nowPage   = isset($_GET['p'])?$_GET['p']:1;
		$limit     = ($nowPage-1)*$pageCount.','.$pageCount;
		return $limit;
	}
}

?>