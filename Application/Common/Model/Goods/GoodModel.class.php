<?php
namespace Common\Model\Goods;


class GoodModel extends \Think\Model
{
    protected $tableName = 'etago_goods';


    /**
     * 商品详细信息
     * @param string $goods_id
     * @return mixed
     */
    public function details($goods_id)
    {
        $result = array();
        //获取商品基本信息
        $goodDetails = $this->getGoodsInfoByGoodsId($goods_id);
        $result = $goodDetails;
        if (!empty($goodDetails)) {
            if (!empty($goodDetails['user_id'])) {
                //获取用户信息
                $userModel = new \Common\Model\User\UserModel();
                $userinfo = $userModel->getUserSmallInfoById($goodDetails['user_id']);
                if (!empty($userinfo)) {
                    $userinfo['face'] = getImageBaseUrl('user', $userinfo['face']);
                    $result = array_merge($userinfo, $result);
                }
            }
            //获取图片详细信息
            if ($goodDetails['goods_id']) {
                $goods_image = $this->getGoodsImageById($goodDetails['goods_id']);
                if(!empty($goods_image)){
                    $result['pics']['image_path'] = getImageBaseUrl('goods', $goods_image['image_path']);
                    $result['pics']['position'] = $goods_image['position'];
                    $result['pics']['img_id'] =  $goods_image['img_id'];
                    //获取图片锚点信息
                    $pics = $this->getGoodsImgstyles($goods_image['img_id']);
                    if($pics){
                        $result['pics']['imgstyle'] = $pics;
                    }
                    
                }
                
                

            }

        } else {
            return false;
        }
        return $result;
    }
    /**
     * 将产品表状态设置为不可用
     * @see \Think\Model::delete()
     */
    public function delete($user_id,$goods_id){
        if (!empty($user_id) && !empty($goods_id)){
            $where = array();
            $where['user_id'] = $user_id;
            $where['id'] = $goods_id;
            $data['del_flag'] = 1;
            return M('etago_goods')->where($where)->save($data);
        }else{
            return false;
        }
        
    }
    /**
     * 获取商品的tags
     * @param string $goods_id
     */
    public function getTagsByGoodsId($goods_id){
        $where = array(
            'goods_id' => $goods_id,
            'del_flag' => 0
        );
        return M('etago_goods_tags')->field('id,tag as name')->where($where)->select();
    }

    /**
     * 获取商品基本信息
     * @param array $goods_id
     */
    public function getGoodsInfoByGoodsId($goods_id)
    {
        $model = M('etago_goods');
        $map = array();
        $is_one = 1;
        if (is_array($goods_id)) {
            $is_one = 0;
            $map['goods.id'] = ['in', join(',', $goods_id)];
        } else {
            $map['goods.id'] = $goods_id;
        }
        $result = $model->alias('goods')->field('goods.user_id,goods.id as goods_id,goods.city_id,goods.title,goods.content,goods.price,goods.sale_type,goods.price_unit,goods.likenum,goods.longitude,goods.latitude,goods.pay_type,goods.service_type,goods.delivery_type,goods.type,goods.address,goods.buynum')->where($map);
        if ($is_one) {
            $result = $result->find();
        } else {
            $result = $result->select();
        }
        return $result;
    }
    /**
     * 获取图片的信息
     * @param unknown $img_id
     */
    public function getGoodsImgstyles($img_id){
        $where = array();
        $where['goods_img_id'] = $img_id;
        $field = array(
            'goods_img_id',
            'words',
            'font',
            'size',
            'color',
            'start_coordinate',
            'end_coordinate'
        );
        return M('etago_goods_imgstyle')->field($field)->where($where)->select();
    }

    /**
     * 返回商品图片详情结果集
     * @param $user_id
     * @param int $offset
     * @param int $pagesize
     */
    public function getGoodsPicByGoodsId($goods_id)
    {
        $where['gp.goods_id'] = ['in', $goods_id];
        $where['gp.del_flag'] = 0;
        $field = 'gp.id goods_img_id,gp.goods_id,gp.image_path,gpi.font,gpi.size,
        gpi.color,gpi.start_coordinate,gpi.end_coordinate,gpi.words';
        $join = 'LEFT JOIN etago_goods_imgstyle as gpi ON gpi.goods_img_id = gp.id';
        $model = M('etago_goods_img');
        return $model->alias('gp')->field($field)->where($where)->join($join)->select();
    }

    public function getGoodsImageById($goods_id, $position = 1)
    {
        $model = M('etago_goods_img');
        $map = array();
        $is_one = 1;
        if (is_array($goods_id)) {
            $is_one = 0;
            $map['goodsimg.goods_id'] = ['in', join(',', $goods_id)];
        } else {
            $map['goodsimg.goods_id'] = $goods_id;
        }
        $map['goodsimg.position'] = $position;
        $result = $model->alias('goodsimg')->field('goodsimg.id as img_id,goodsimg.goods_id,goodsimg.image_path,goodsimg.position')->where($map);
        if ($is_one) {
            $result = $result->find();
        } else {
            $result = $result->select();
        }
        return $result;
    }

    /**
     * 推荐商品
     * @param array $params
     */
    public function recommendContent($params)
    {
        //TODO 暂时不知道推荐规则
        $pageSize = $params['pageSize'];
        $offset = $params['offset'];
        $sql = "SELECT count(*) as total FROM etago_goods_pics WHERE del_flag = 0";
        $totalArray = $this->query($sql);
        $total = $totalArray[0]['total'];
        $sql = "SELECT goods.id ,pics.path FROM etago_goods as goods,etago_goods_pics as pics WHERE goods.id = pics.goods_id and goods.del_flag = 0 LIMIT $offset,$pageSize";
        $content = $this->query($sql);
        $offset += $pageSize;
        if (empty($content)) {
            $content = array();
        }
        return array(
            'content' => $content,
            'total' => $total,
            'offset' => $offset
        );
    }

    /**
     * 返回商品结果集
     * @param $user_id
     * @param int $offset
     * @param int $pagesize
     */
    public function getGoodsByUserId($user_id, $offset = 0, $pagesize = 10)
    {

        $offset = max(0, $offset);
        $where['g.user_id'] = ['in', $user_id];
        $where['g.del_flag'] = 0;
        $where['u.del_flag'] = 0;

        $join = " JOIN etago_user u ON u.id = g.user_id 
         JOIN etago_city c ON c.id = g.city_id
         JOIN etago_country cy ON cy.id = c.country_id";
        $field = "g.*,u.nickname,u.face,c.country_id,c.currency_code,c.currency_mark,c.mileage_unit,c.`name` city_name,cy.country_name";
        $model = M('etago_goods');
        return $model->index('id')->alias('g')->field($field)->where($where)->join($join)->order('add_time DESC')->limit($offset,
            $pagesize)->select();
    }

    /**
     * 根据商品id返回商品结果集
     * @param $user_id
     * @param int $offset
     * @param int $pagesize
     */
    public function getGoodsByGoodsId($goods_id, $offset = 0, $pagesize = 10)
    {

        $where['g.id'] = ['in', $goods_id];
        $where['g.del_flag'] = 0;
        $where['u.del_flag'] = 0;

        $join = "LEFT JOIN etago_user u ON u.id = g.user_id 
         LEFT JOIN etago_city c ON c.id = g.city_id
         LEFT JOIN etago_country cy ON cy.id = c.country_id";
        $field = "g.*,u.nickname,u.face,c.country_id,c.currency_code,c.currency_mark,c.mileage_unit,c.`name` city_name,cy.country_name";
        $model = M('etago_goods');
        return $model->index('id')->alias('g')->field($field)->where($where)->join($join)->order('g.add_time DESC')->limit($offset,
            $pagesize)->select();
    }

    /**
     * 获取商品点赞数据
     * @param $goods_id
     * @param $user_id
     * @return mixed
     */
    public function getGoodsIsLike($goods_id, $user_id)
    {
        $where['del_flag'] = 0;
        $where['user_id'] = $user_id;
        $where['goods_id'] = $goods_id;
        if (is_array($goods_id)) {
            $where['goods_id'] = ['in', $goods_id];
        }
        return M('etago_goods_like')->index('goods_id')->field('goods_id,is_like')->where($where)->select();
    }

    /**
     * 根据id查询指定商品
     * @param $goods_id
     * @param int $del_flag
     * @return mixed
     */
    public function getGoodsById($goods_id, $del_flag = 0)
    {
        $where['g.del_flag'] = $del_flag;
        $join = 'LEFT JOIN etago_user u ON u.id = g.user_id AND u.del_flag = 0 LEFT JOIN etago_goods_img gi ON gi.goods_id = g.id';
        $field = 'g.*,u.id user_id,u.nickname,u.username,u.face,u.gender,u.mobile,gi.image_path';
        if (is_array($goods_id)) {
            $where['g.id'] = ['in', $goods_id];
            return $this->alias('g')->field($field)->where($where)->join($join)->select();
        }

        $where['g.id'] = $goods_id;
        return $this->alias('g')->field($field)->where($where)->join($join)->find();
    }

    /**
     * 商品点赞
     * @param $goods_id 商品id
     * @param $user_id  用户id
     * @param int $like 1赞|0取消赞
     * @return bool
     */
    public function like($goods_id, $user_id, $like = 1)
    {
        $where['goods_id'] = $goods_id;
        $where['user_id'] = $user_id;
        $where['del_flag'] = 0;

        //缓存点赞数
//        $redis = new EtacarRedis();
//        $redis->connect('db1', '1');
//        $increment = $like?$like:-1;
//        $cache_key = 'goods_like';
//        $res = $redis->command()->ZINCRBY($cache_key,$increment);
        $count = 1;//从redis 集合获取
        $model = M('etago_goods_like');
        //更新
        if ($model->where($where)->find()) {
            if($model->is_like != $like){
                $model->update_time = time();
                $model->is_like = $like;
                if ($model->save()) {
                    $number = $like ? 1 : -1;
                    $count = $this->updateLikeNum($goods_id, $number);
                }

            }

        } else {
            //插入
            $model = M('etago_goods_like');
            $model->goods_id = $goods_id;
            $model->user_id = $user_id;
            $model->del_flag = 0;
            $model->add_time = time();
            $model->del_flag = 0;
            if ($model->add()) {
                $number = $like ? 1 : -1;
                $count = $this->updateLikeNum($goods_id, $number);
            }
        }

        return $count;
    }

    /**
     * 更新点赞数
     * @param $goods_id
     * @param int $num
     * @return mixed
     */
    private function updateLikeNum($goods_id, $num = 1)
    {
        $model = M('etago_goods');



        return $model->where(['id' => $goods_id])->setInc('likenum', $num);
    }

    public function addGoods($data)
    {
        $data['add_time'] = time();
        $goods_id = $this->add($data);
        return $goods_id;
    }

    public function addGoodsImg($user_id, $goods_id, $image_path, $position = 1)
    {
        $now_time = time();
        $sql = "INSERT INTO `etago_goods_img` (`user_id`,`goods_id`,`image_path`,`position`,`add_time`) VALUES";
        $sql .= " ('$user_id','$goods_id','$image_path', '$position',$now_time)";
        $this->execute($sql);
        $img_id = $this->getLastInsID();
        return $img_id;
    }

    public function addGoodsTags($user_id, $goods_id, $tags)
    {
        $res = false;
        if ($tags) {
            $sql = "INSERT INTO `etago_goods_tags` (`user_id`,`goods_id`,`tag`,`add_time`) VALUES";
            $values = "";
            $taginfo = explode(',', $tags);
            $now_time = time();
            foreach ($taginfo as $k => $v) {
                $values .= "('$user_id','$goods_id','$v','$now_time'),";
            }
            $sql = $sql . rtrim($values, ',');
            $res = $this->execute($sql);
        }
        return $res;
    }

    public function addGoodsImgStyle($goods_img_id, $data)
    {
        $res = false;
        if ($data) {
            $sql = "INSERT INTO `etago_goods_imgstyle` (`goods_img_id`,`words`,`font`,`size`,`color`,`start_coordinate`,`end_coordinate`) VALUES";
            $values = "";
            foreach ($data as $k => $v) {
                $words = $v['words'];
                $font = $v['font'];
                $size = $v['size'];
                $color = $v['color'];
                $start_coordinate = $v['start_coordinate'];
                $end_coordinate = $v['end_coordinate'];

                $values .= "('$goods_img_id','$words','$font','$size','$color','$start_coordinate','$end_coordinate'),";
            }
            $sql = $sql . rtrim($values, ',');
            $res = $this->execute($sql);
        }
        return $res;
    }

    public function getUserGoodsList($user_id, $offset, $pagesize)
    {
        $count = $this->countGoodsByUserId($user_id);
        $info = array();
        if ($count > 0) {
            $sql = "select g.id goods_id, gi.image_path from etago_goods g
    		left join etago_goods_img gi on g.id= gi.goods_id
    		where g.user_id= $user_id and gi.del_flag =0 limit $offset,$pagesize";
            $result = $this->query($sql);
            $info = array();
            foreach ($result as $v) {
                $info['data_list'][] = $v;
            }
            $info['offset'] = $offset + $pagesize;
            if ($info['offset'] > $count) {
                $info['offset'] = $count;
            }
            $info['count'] = $count;
        }
        return $info;
    }

    /**
     * 返回商品数
     * @param $user_id
     * @param int $offset
     * @param int $pagesize
     * @return mixed
     */
    public function countGoodsByUserId($user_id)
    {
        $where['user_id'] = is_array($user_id) ? ['in', $user_id] : $user_id;
        $where['del_flag'] = 0;
        return M('etago_goods')->where($where)->count();
    }

    /**
     * @desc 我点赞过得内容
     */
    public function getMyPraiseList($user_id, $offset, $pagesize)
    {
        $count = $this->getMyPraiseCount($user_id);
        $result = array();
        if ($count > 0) {
            $sql = "select g.id as goods_id,gi.image_path  from etago_goods_like p
				left join etago_goods g on p.goods_id= g.id
				left join etago_goods_img gi on p.goods_id = gi.goods_id
				where p.user_id= '" . $user_id . "' and p.is_like = 1 and g.del_flag=0 limit $offset,$pagesize";
            $info = $this->query($sql);
            $result['data_list'] = $info;
            $result['offset'] = $offset + $pagesize;
            if ($result['offset'] > $count) {
                $result['offset'] = $count;
            }
            $result['pagesize'] = $pagesize;
            $result['count'] = $count;
        }
        return $result;
    }

    /**
     * @desc 我赞过的内容总数
     */
    public function getMyPraiseCount($user_id)
    {
        $sql = "select count(p.id) as count from etago_goods_like p
				left join etago_goods g on p.goods_id= g.id
				where p.user_id= '" . $user_id . "' and p.is_like = 1 and g.del_flag=0";
        $ret = $this->query($sql);
        $count = $ret[0]['count'];
        return $count;
    }
    /**
     * @desc 获取用户交易/非交易商品数量
     */
    public function getIsSaleGoodsCount($map){
    	return M('etago_goods')->where($map)->count();
    }

}