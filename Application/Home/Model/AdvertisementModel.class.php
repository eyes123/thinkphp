<?php

namespace Home\Model;

use Think\Model;
class AdvertisementModel extends Model
{
	protected $trueTableName = 'yes_advertisement';
	
	public function __construct()
	{
		parent::__construct();
	}
	

	
	//获取广告列表
	public function getAdList($limit,$where,$sort)
	{
		$sort = empty($sort)?'create_time desc':$sort;
		$field = array('id','title','create_time','product_price','view_count','is_sell','supply_price,short_title');
		
		$rows = $this->where($where)->field($field)->order($sort)->page($limit)->select();
		//echo $this->getLastSql();
		return $rows;
	}
	
	//获取广告列表
	public function getAdList2($where,$sort='create_time desc')
	{
		$sort = empty($sort)?'create_time desc':$sort;
		$field = array('id','title','create_time','product_price','clickcount','is_sell');
	
		$rows = $this->where($where)->field($field)->order($sort)->select();

		return $rows;
	}
	
	//获取已经删除的广告列表
	public function getAdTrash($limit)
	{
		$where = 'del_sign=1';
		$field = array('id','title','create_time','clickcount');
		$rows = $this->where($where)->field($field)->page($limit)->select();
		
		return $rows;
	}
	
	//获取广告标题
	public function getProductListByPartnerId($partnerId)
	{
		$field = array('id','title');
		$where = "merchant_id='".$partnerId."'";
		$rows = $this->where($where)->field($field)->select();
		return $rows;
	}
	
	//获取商品供货价
	public function getProductListByTitleId($titleId)
	{
		$field = array('supply_price');
		$where = "id='".$titleId."'";
		$rows = $this->where($where)->field($field)->select();
		return $rows[0]['supply_price'];
	}
	
	//获取广告费用列表
	public function getAdClickprice($where)
	{
		$rows = $this->field('id,click_price')->where($where)->select();
		return $rows;
	}
	
	//获取广告费用列表
	public function getAdRow($where)
	{
		$rows = $this->where($where)->select();
		//echo $this->getLastSql();exit;
		return $rows;
	}
	
	//获取广告总数目
	public function getAdCount($where)
	{
		
		$count = $this->where($where)->count();
		
		return $count;
	}
	
	//获取垃圾箱中的广告数目
	public function getAdTrashCount()
	{
		$where = 'del_sign=1';
		$count = $this->where($where)->count();
		
		return $count;
	}
	
	//根据id获取广告详细信息
	public function getAdDetail($id)
	{
		$where = 'id="'.$id.'"';
		$row   = $this->where($where)->select();
	
		return $row[0];
	}
	
	//编辑产品信息
	public function editAd($data)
	{
		$result = $this->where('id="'.$data['id'].'"')->save($data);

		if($result==1)
		{
			$data1['ad_id']			= $data['id'];
			$data1['short_title']	= $data['short_title'];
			$data1['shouji_url']	= $data['shouji_url'];
			$data1['click_price']	= $data['click_price'];
			$data1['product_desc']	= $data['product_desc'];
			$data1['product_price']	= $data['product_price'];
			$newDb = new NewModel();
			$newDb->editNew($data1);
			$dropDb = new DropModel();
			$dropDb->editDrop1($data1);
			
			$data2['ad_id']			= $data['id'];
			$data2['short_title']	= $data['short_title'];
			$data2['shouji_url']	= $data['shouji_url'];
			$data2['click_price']	= $data['click_price'];
			$data2['product_desc']	= $data['product_desc'];
			$data2['product_price']	= $data['product_price'];
			$data2['cat_id']		= $data['cat_id'];
			$expDb = new ExpModel();
			$expDb->editExp($data2);
 			//echo $expDb->getLastSql();exit;
			//产品选项相关功能
			//产品添加
			$prodLinkDb = new \Home\Model\ProdLinkModel();
			$prodLinkDb->editByOnEditAd($data);
		}
		return $result;
	}
	
	
	//将回收站中的产品撤销删除
	public function repealAd($adId)
	{
		$data = array();
		$data['del_sign'] = 0;
		$result = $this->where('id="'.$adId.'"')->save($data);


		return $result;		
	}
	
	//将产品上架
	public function shangJia($adId)
	{
		$data = array();
		$data['is_sell'] = 1;
		$result = $this->where('id="'.$adId.'"')->save($data);

		
		return $result;
	}
	
	//将产品下架
	public function xiaJia($adId)
	{
		$data = array();
		$data['is_sell'] = 0;
		$result = $this->where('id="'.$adId.'"')->save($data);

		
		if($result==1)
		{
			//同时删除降价表中数据
			$dropDb   = D("drop");
			$result2 = $dropDb->delAd1($adId);
			
			//同时删除体验表中数据
			$expDb   = D("exp");
			$result3 = $expDb->delAd1($adId);
			
// 			//同时删除新品当中的数据
// 			$newDb   = D("New");
// 			$result4 = $newDb->delAd1($adId);
			
			//同时删除九橙推荐当中的数据
			$jiucDb   = D("Jc");
			$result5 = $jiucDb->delAd1($adId);
			
			//同时删除橙推广推荐当中的数据
			$ctgDb   = D("Ctg");
			$result6 = $ctgDb->delAd1($adId);
		}
		return $result;
	}

	//批量删除
	public function delAds($ids)
	{
		$data['del_sign'] = 1;
		$data['is_sell'] = 0;
		$result = $this->where('id in('.$ids.')')->save($data);

		
		if($result==1)
		{
			//同时删除降价表中数据
			$dropDb   = D("drop");
			$result2 = $dropDb->delAds1($ids);
				
			//同时删除体验表中数据
			$expDb   = D("exp");
			$result3 = $expDb->delAds1($ids);
				
			//同时删除新品当中的数据
			$newDb   = D("New");
			$result4 = $newDb->delAds1($ids);
			
			//同时删除搭配表品当中的数据
			$matchDb   = D("Match");
			$result4 = $matchDb->delAds1($ids);
			
		}
		return $result;
	}
	
	//单个删除
	public function delAd($id)
	{
		$data['del_sign'] = 1;
		$data['is_sell'] = 0;
		$result= $this->where('id="'.$id.'"')->save($data);

		
		if($result==1)
		{

			//同时删除降价表中数据
			$dropDb   = D("drop");
			$result2 = $dropDb->delAd1($id);
	
			//同时删除体验表中数据
			$expDb   = D("exp");
			$result3 = $expDb->delAd1($id);
	
			//同时删除新品当中的数据
			$newDb   = D("New");
			$result4 = $newDb->delAd1($id);
			
			//同时删除新品当中的数据
			$matchDb   = D("Match");

			$result5 = $matchDb->delAd1($id);
		}
		return $result;
	}
	
    //单个删除回收站
	public function shanchu($id)
	{
		$result = $this->where('id="'.$id.'"')->delete();
		
		return $result;
		
	}
	
	//批量删除回收站
	public function shanchus($ids)
	{
		
		$result = $this->where('id in('.$ids.')')->delete();
		
		return $result;
	}
	
	//根据id获取广告的标题
	public function getAdTitle($where)
	{
		$result = $this->field('id,title,short_title')->where($where)->select();
		return $result;
	}
	
	//根据id获取广告的购买选项
	public function getAdOption($id)
	{
		$where  	= "id='".$id."'";
		$result		= $this->field('option')->where($where)->select();
		$option 	= '';
		if(!empty($result))
		{
			$option = $result[0]['option'];
		}
		return $option;
	}
	
	//根据id获取广告的购买选项集合
	public function getAdOptions($id)
	{
		$options = array();
		$where  	= "id='".$id."'";
		$result		= $this->field('option,remain,product_price')->where($where)->select();
		$option 	= '';
		if(!empty($result))
		{
			$options = objectToArray(json_decode($result[0]['option']));
			if(empty($options))
			{
				$options = array(array('attrs'=>'','price'=>'','now_number'=>'0'));
			}
			$options[0]['now_number'] = $result[0]['remain'];//$ad['remain'];
			$options[0]['price']	  = $result[0]['product_price'];//$ad['product_price'];
		}
		return $options;
	}

	//根据id 查询商品供货价
	public function getSupplyPrice($id)
	{
		$rows = $this->where("id='".$id."'")->field(array('supply_price'))->select();
		return $rows[0];
	}
	
	//编辑产品信息 商家供货价
	public function editSupplyPrice($data)
	{
		$result = $this->where("id='".$data['id']."'")->save($data);
		
		return $result;
	}

	//根据商家id查询商品名称
	public function getNamePartnerId($id)
	{
		$rows = $this->field(array('id','short_title','product_price'))->where("merchant_id='".$id."'")->select();
		
		return $rows;
	}

	//根据产品id查询商家id
	public function getPartnerId($id)
	{
		$id= $this->field(array('merchant_id'))->where("id='".$id."'")->select();
		return $id[0]['merchant_id'];
	}
	
	//根据产品id查询商家id
	public function getPartnerAdTitle($id)
	{
		$id= $this->field(array('id'))->where("id='".$id."'")->select();
		return $id[0]['id'];
	}
	

}

?>