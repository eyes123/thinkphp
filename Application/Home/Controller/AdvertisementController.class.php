<?php
namespace Home\Controller;
use Home\Common\Controller\CommonController;
require_once dirname(dirname(dirname(__FILE__))).'/Common/SessionManage.class.php';
require_once dirname(dirname(dirname(__FILE__))).'/Common/Common.class.php';
class AdvertisementController extends CommonController
{
	//广告管理->广告列表
	public function index() 
	{
		\SessionManage::checkLogin();
		//拼接搜索条件
		$where = array();
		$where = $this->jointWhere();
		$where['del_sign'] = '0';
		
		$pWhere = $_REQUEST;
		unset($pWhere['parent_cat']);
		$pWhere['cat_id'] = $where['cat_id'];
		
		//获取符合条件的广告
		$adDb      = D('Advertisement');		
		$count     = $adDb->getAdCount($where);	
        $limit     = $this->sepePage($count,$pWhere);
		$ads       = $adDb->getAdList($limit,$where,urldecode($_REQUEST['sort']));
// 		echo $adDb->getLastSql();exit;
//		print_r($limit);exit;
		$this->ads = $ads;
	
//		//获取商家
//		$partnerDb = D('partner');
//		$merchants = $partnerDb->getPartnerList1();
//		$this->merchants = $merchants;
//
//		//获取最上级分类
//		$catDb = D("category");
//		$cats = $catDb->where(array('parent_id'=>'0'))->select();
//		$this->cats = $cats;
		$this->where = urlencode(json_encode($where));
		$this->display();
	}
	
	//拼接搜索搜索条件
	public function jointWhere()
	{
		$where = array();	
		//搜索条件拼接
		if(!empty($_REQUEST['submit']))
		{
			if(!empty($_REQUEST['title']))
			{
				$where['title'] = array('like','%'.trim($_REQUEST['title']).'%');
				$this->titleWhere = trim($_REQUEST['title']);
			}
			if(!empty($_REQUEST['short_title']))
			{
				$where['short_title'] = array('like','%'.trim($_REQUEST['short_title']).'%');
				$this->short_titleWhere = trim($_REQUEST['short_title']);
			}
		
			if(!empty($_REQUEST['merchant_id']))
			{
// 				exit;
				$where['merchant_id'] = $_REQUEST['merchant_id'];
				$this->merchantWhere  = $_REQUEST['merchant_id'];
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
		
		if(!empty($_REQUEST['parent_cat'])) {
			$parentCats = array();
 			$parentIds = $_REQUEST['parent_cat'];
 			$catIds = $parentIds;
 			$db = M('yes_category');
			foreach ($parentIds as $k=>$parentId) {
				if(empty($parentId))
					break;
				if($k == 0)
				    $parentCats[$parentId] = $db->where(array('parent_id'=>'0'))->field('id,cat_name')->select();
				else 
					$parentCats[$parentId] = $db->where(array('parent_id'=>$catIds[$k-1]))->field('id,cat_name')->select();
			}
			$this->parentCats = $parentCats;
			
			$parentId = array_pop($_REQUEST['parent_cat']);
			
			while($parentId < 0)
			{
				$parentId = array_pop($_REQUEST['parent_cat']);
					
				if(empty($parentId))
					break;
			}
			if(!empty($parentId))
			    $where['cat_id'] = $parentId;
		}
		
	   	if(!empty($_REQUEST['cat_id']))	{
	   		$where['cat_id'] = $_REQUEST['cat_id'];
	   	}
	   	
	   	if(isset($_REQUEST['issell']))
	   	{
	   		if($_REQUEST['issell']!='-1')
	   		{
	   			$this->issell=$_REQUEST['issell'];
	   			$where['is_sell'] = $_REQUEST['issell'];

	   		}
	   	}
	   	
	   	if(!empty($_REQUEST['where'])) 
	   	{
	   		$where = json_decode(urldecode($_REQUEST['where']),true);
	   	}
	    
		return $where;
	}
	
	//添加广告
	public function add()
	{
		\SessionManage::checkLogin();
		
		if(session('?picArr')) {
			session('picArr',null);
		}
		if(session('?shoujiPicArr')) {
			session('shoujiPicArr',null);
		}
		
		//获取商家
		$partnerDb = new \Home\Model\PartnerModel();
		$merchants = $partnerDb->getPartnerList1();
		$this->merchants = $merchants;
	
		//获取最上级分类
		$categoryDb = new \Home\Model\CategoryModel();
		$cats = $categoryDb->where(array('parent_id'=>'0'))->select();
		$content = $this->getSelectChange('');
		//获取品牌
		$brandDb = D('brand');
		$rows = $brandDb->getLIst();
		//var_dump($rows);exit;
		$this->content = $content;
		$this->rows=$rows;
		$this->cats = $cats;
		$this->display();
	}

	//通过ajax上传图片
	public function uploadImgAjax()
	{
		$fileBtnName = 'file';
		if(isset($_GET['file']))
		{
			$fileBtnName = trim($_GET['file']);
			if(empty($fileBtnName))
			{
				$fileBtnName = 'file';
			}
		}
	
		$name = $this->uploadByName($fileBtnName);

		$url = C('web_root').C('pic_dir').$name;
		$module = __MODULE__;
		$public = __ROOT__.'/Public';
		
		$sessionArray = session('picArr');
		
		$id = 1;
		if(!empty($sessionArray))
		{
			$id = array_push($sessionArray, $name);
		}
		else
		{
			$sessionArray = array($name);
		}
		session('picArr',null);
		session('picArr',$sessionArray);
		
		$str = <<<EOD
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="$public/Js/Jquery.js"></script>		
		<script type="text/javascript"> 
		
		$(document).ready(function()
		{
			alert('上传成功');
		    var obj = $('.displayphoto', parent.document);
		    	
			obj.append('<div id="uploadImg$id" style="position:relative" class="upload_img_item" ><img width="100px" height="100px" src="$url"  alt="产品图片$id" /><a class="delete_btn" target="uploadframe" style="position:absolute;top:0px;right:0px;display:none;" href="$module/Advertisement/deleteImg?id=$id" title="删除"><img src="$public/images/icon_del.gif" alt="删除"/></a></div>');

			obj.find('.upload_img_item').mouseover(function()
			{
				$(this).find('.delete_btn').css('display','block');
			});	

			obj.find('.upload_img_item').mouseout(function()
			{
				$(this).find('.delete_btn').css('display','none');
			});		
		});	
		</script>
							
EOD;
		echo $str;
	}
	
	//通过ajax上传图片
	public function newUploadImgAjax()
	{

		$pre = '';
		if(isset($_GET['pre']))
		{
			$pre = trim($_GET['pre']);
		}
		
		//$pageId = isset($_POST['page_id'])?trim($_POST['page_id']):(isset($_GET['page_id'])?trim($_GET['page_id']):"");
		$fileBtnName = $pre.'_file';
		$className = $pre."_displayphoto";
		
		$message = '';
		
		if(!isset($_FILES[$fileBtnName]))
		{
			$message = '上传图片的参数有误';
		}
		else 
		{
			//$sessionKey = $pageId.'picArr';
			$data	= $this->uploadByName2($fileBtnName);
			$message = $data["message"];
			$name = $data["path"];
			if($message=='')
			{
				$url	= C('web_root').C('pic_dir').$name;
				$sessionArray = array();
				if(!empty($_POST[$pre]))
				{
					$sessionArray = explode(';', $_POST[$pre]) ;
				}
				//print_r($_POST);//exit;
				$id	= 1;
				if(!empty($sessionArray))
				{
					$id = array_push($sessionArray, $name);
				}
				else
				{
					$sessionArray = array($name);
				}
			}
			//session($sessionKey,null);
			//session($sessionKey,$sessionArray);
		}
		$public = __ROOT__.'/Public';
		$module = __MODULE__;
		$divId	= $pre.'_Img'.$id;
		$one	= isset($_GET['one'])?trim($_GET['one']):"n";
		$value	= '';
		if(!empty($sessionArray))
		{
			$value = implode(';', $sessionArray);
		}
		if($message=='')
		{
			if(!empty($_GET["type"]))
			{
				if(!empty($name))
				{
					echo $name;
				}
				exit;
			}
		}
// 		print_r($_GET);
		//echo $name;
		//exit;
		$str = <<<EOD
	
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="$public/Js/Jquery.js"></script>
		<script type="text/javascript">
	
		$(document).ready(function()
		{
			if('$message')
			{
				alert('$message');
			}
			else
			{
				alert('上传成功');
			    var obj = $('.$className', parent.document);
			  	if('$one'=='y')
			  	{
			  		obj.html('<div id="$divId" style="position:relative" class="upload_img_item" ><img width="100px" height="100px" src="$url"  alt="图片$id" /></div>');
			  	}
			  	else
			  	{
			  		obj.append('<div id="$divId" style="position:relative" class="upload_img_item" ><img width="100px" height="100px" src="$url"  alt="图片$id" /><a class="delete_btn" style="position:absolute;top:0px;right:0px;display:none;" onclick="newDeleteImg(\'$pre\',$id)" title="删除"><img src="$public/images/icon_del.gif" alt="删除"/></a></div>');
			  		
					obj.find('.upload_img_item').mouseover(function()
					{
						$(this).find('.delete_btn').css('display','block');
					});
			
					obj.find('.upload_img_item').mouseout(function()
					{
						$(this).find('.delete_btn').css('display','none');
					});
				}
			  	$("input[name='$pre']", parent.document).val('$value');
			  	$("#hf_$pre", parent.document).val('$value');
			}
		});
		</script>
				
EOD;
		echo $str;
	}
	
	//通过ajax删除图片
	public function newDeleteImg()
	{
		if(!empty($_REQUEST['id']))
		{
			$pre = '';
			if(isset($_GET['pre']))
			{
				$pre = trim($_GET['pre']);
			}
			//print_r($_POST);
			//$pageId = isset($_POST['page_id'])?trim($_POST['page_id']):isset($_GET['page_id'])?trim($_GET['page_id']):"";
			//$sessionKey = $pageId.'picArr';
			$public = __ROOT__.'/Public/';
			$id = $_REQUEST['id'];
				
			$pics = explode(';', $_POST[$pre]) ;
			$sid = $id-1;
			$path = getPublicImgPath().$pics["$sid"];
			//echo $path;exit;
			//unlink($path);
			unset($pics[$sid]);
			
			$value = '';
			if(!empty($pics))
			{
				$value = implode(';', $pics);
			}
			//print_r($_POST);
			$divId = $pre.'_Img'.$id;
			$className = $pre."_displayphoto";
			$str = <<<EOD
	
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<script type="text/javascript" src="$public/Js/Jquery.js"></script>
			<script type="text/javascript">
	
			$(document).ready(function()
			{
			    var obj = $('.$className', parent.document);
			    obj.children('#$divId').remove();
			    obj.find('.upload_img_item').mouseover(function()
				{
					$(this).find('.delete_btn').css('display','block');
				});
		
				obj.find('.upload_img_item').mouseout(function()
				{
					$(this).find('.delete_btn').css('display','none');
				});
			    $("input[name='$pre']", parent.document).val('$value');
			});
			</script>
EOD;
			echo $str;
		}
	}

	//通过ajax删除图片
	public function deleteImg()
	{
		if(!empty($_REQUEST['id']))
		{
			$public = __ROOT__.'/Public/';
			$id = $_REQUEST['id'];
			
 			$pics = session('picArr');
 			$sid = $id-1;
 			$path = $public.'Upload/Img/'.$pics["$sid"];
 			$rows = unlink($path);
 			unset($pics["$sid"]);
 			session('picArr',null);
 			session('picArr',$pics);
			$str = <<<EOD
						
			<script type="text/javascript" src="$public/Js/Jquery.js"></script>
			<script type="text/javascript">
				
			$(document).ready(function()
			{			    			    
			    var obj = $('.displayphoto', parent.document);
			    
			    obj.children('#uploadImg$id').remove();			    				
			});
			</script>
				
EOD;
			echo $str;
		}
	}
	
	//通过ajax上传图片
	public function uploadImgAjax2()
	{
		$sessionPicArr = 'shoujiPicArr';
		$file = $_FILES['shoujiFile'];
		//print_r($file);exit;
		$name = $this->upload(array($file));
			
		//echo '$name:'.$name;exit;
		$url = C('web_root').C('pic_dir').$name;
// 		echo ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
// 				<script type="text/javascript">
// 			alert("'.$url.'");
// 		</script>';
		//$module = __MODULE__;
		$public = __ROOT__.'/Public';
		$sessionArray = session($sessionPicArr);
		$id = 1;
		if(!empty($sessionArray))
		{
			$id = array_push($sessionArray, $name);
		}
		else
		{
			$sessionArray = array($name);
		}
		//session($sessionPicArr,null);
		session($sessionPicArr,$sessionArray);
		$str = <<<EOD
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="$public/Js/Jquery.js"></script>		
		<script type="text/javascript"> 
		
		$(document).ready(function()
		{
			alert('上传成功');
		    var obj = $('.shouji_displayphoto', parent.document);
		    	
			obj.html('<div style="position:relative" class="upload_img_item" ><img width="100px" height="100px" src="$url"  alt="手机图片" /></div>');
	
		});	
		</script>
							
EOD;
		echo $str;
	}
	
	//通过ajax删除手机图片
	public function deleteShoujiImg()
	{
		$type = 'shoujiImg';
		$sessionPicArr = 'shoujiPicArr';
	
		$public = __ROOT__.'/Public/';
			
		$pics = session($sessionPicArr);
		
		foreach ($pics as $key=>$pic)
		{
			$path = $public.'Upload/Img/'.$pic;
			$rows = unlink($path);
			unset($pics[$key]);
		}
		session($sessionPicArr,null);
		//session($sessionPicArr,$pics);
	
		$str = <<<EOD
	
		<script type="text/javascript" src="$public/Js/Jquery.js"></script>
		<script type="text/javascript">
	
		$(document).ready(function()
		{
		    var obj = $('.shouji_displayphoto', parent.document);
		    obj.html('');
		});
		</script>
	
EOD;
		echo $str;
	}

	/**
	 * 上传图片函数
	 */
	private function uploadByName($name='file')
	{
		
		$files = array($_FILES[$name]);
		$fileName = '';
		$config = array(
				'maxSize'    =>    3145728,
				'rootPath'   =>    './Public/Upload/Img/',
				'savePath'   =>    '',
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    true,
				'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		
		$fileInfo = $upload->upload($files);
		
		if(!$fileInfo)
		{
			$this->error($upload->getError());
		}
		else
		{
			foreach ($fileInfo as $file)
			{
				$fileName .= $file['savepath'].$file['savename'];
				$fileName .= ';';
			}
		}
		return substr($fileName, 0,-1);
	}
	
	/**
	 * 上传图片函数
	 */
	private function uploadByName2($name='file')
	{
		$message = '';
		$path = '';

		$files = array($_FILES[$name]);
		$fileName = '';
		$config = array(
				'maxSize'    =>    3145728,
				'rootPath'   =>    './Public/Upload/Img/',
				'savePath'   =>    '',
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    true,
				'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
	
		$fileInfo = $upload->upload($files);
	
		if(!$fileInfo)
		{
			$message = $upload->getError();
		}
		else
		{
			foreach ($fileInfo as $file)
			{
				$fileName .= $file['savepath'].$file['savename'];
				$fileName .= ';';
			}
			$path = substr($fileName, 0,-1);
		}
		$data = array('path'=>$path,'message'=>$message);
		return $data;
	}
	
	/**
	 * 上传图片函数
	 */
	private function upload($files=null)
	{
    	if($files==null)
    	{
        	$files = array($_FILES['file']);
    	}
		$fileName = '';
		$config = array(
				'maxSize'    =>    3145728,
				'rootPath'   =>    './Public/Upload/Img/',
				'savePath'   =>    '',
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
				'autoSub'    =>    true,
				'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类

		$fileInfo = $upload->upload($files);
		
		if(!$fileInfo)
		{
			$this->error($upload->getError());
		}
		else 
		{
			foreach ($fileInfo as $file)
			{
				$fileName .= $file['savepath'].$file['savename'];
				$fileName .= ';';
			}
		}
		return substr($fileName, 0,-1);	
	}

	

    
    
}
?>