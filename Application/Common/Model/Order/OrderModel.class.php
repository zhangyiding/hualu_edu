<?php
namespace Common\Model\Order;

class OrderModel extends \Think\Model{
    protected $tableName = 'etago_order';

    public function addOrder($data){
        $res = $this->add($data);
        return $res;
    }

    public function modifyOrder($oid,$data){
        $res = $this->where("id=$oid")->save($data);
        return $res;
    }
    
    public function getOrderByTradeNo($trade_no,$source,$user_id=0){
        $is_one = 1;
        if(is_array($trade_no)){
            $is_one = 0;
            $map['trade_no'] = ['in',join(',', $trade_no)];
        }else{
            $map['trade_no'] = $trade_no;
        }
        if($user_id){
            if($source==1){
                $map['buyer_id'] = $user_id;
            }else{
                $map['saler_id'] = $user_id;
            }
        }

        $result = $this->where($map);
        if($is_one){
            $result = $result->find();
        }else{
            $result = $result->select();
        }
        return $result;
    }
    
	public function getOrderList($user_id,$status,$source,$offset,$pagesize){
	    $sql = "select trade_no,city_id,total_fee,status,delivery_type,
	     service_type,address,order_type,buy_time,pay_time from etago_order where ";
	    if($source==1){
	        $sql.=" buyer_id=$user_id";
	    }else{
	        $sql.=" saler_id=$user_id";
	    }
	    $sql.=" and status in($status) order by buy_time desc limit $offset,$pagesize";
	    $result = $this->query($sql);
	    $m_ordergoods = new \Common\Model\Order\OrderGoodsModel();
	    foreach ($result as $k=>$v){
	        $res_goods = $m_ordergoods->getOrderGoodsByTradeNo($v['trade_no']);
	        $result[$k]['goods_id'] = $res_goods[0]['goods_id'];
	    }
	    return $result;
	}
	
	public function getOrderListCount($user_id,$status,$source){
	    $sql = "select count(id) as count from etago_order where ";
	    if($source==1){
	        $sql.=" buyer_id=$user_id";
	    }else{
	        $sql.=" saler_id=$user_id";
	    }
	    $sql.=" and status in($status)";
	    $result = $this->query($sql);
	    $count = 0;
	    if(!empty($result[0]['count'])){
	        $count = $result[0]['count'];
	    }
	    return $count;
	}
	
	
	
}