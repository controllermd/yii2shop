<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/22
 * Time: 11:44
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsController extends Controller
{

    public function actionGoods($id){
        $this->layout = 'goods';
        $model = Goods::findOne(['id'=>$id]);
        //var_dump($models);exit;
        return $this->render('goods',['model'=>$model]);
    }
    public function actionCatadd()
    {

        $goods_id = \Yii::$app->request->post('goods_id');
        //var_dump($goods_id);exit();
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        $id = \Yii::$app->user->id;
        //var_dump($goods_id);exit;
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        if (\Yii::$app->user->isGuest) {
            //没有登录
            //得到cookie里面的shuju
            $cookies = \Yii::$app->request->cookies;
            //得到cookie里面的数据
            $cookie = $cookies->get('cart');
            //var_dump($cookie);exit;
            //判断cookie里面的数据是否为空，避免报错
            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            //将的到的商品id和数量存带到cookie中
            $cookies = \Yii::$app->response->cookies;
            //判断购物车里面是否有这个商品
            if (key_exists($goods_id, $cart)) {
                //如果有就让的他的数量加一
                $cart[$goods_id] += $amount;
            } else {
                $cart[$goods_id] = $amount;
            }
            $cookie = new Cookie([
                'name' => 'cart', 'value' => serialize($cart)
            ]);
            //添加到cookie
            //var_dump($cookie);exit;
            $cookies->add($cookie);
            //var_dump($cookies);exit;
        } else {
            $cart = Cart::find()->where(['member_id'=>$id,'goods_id'=>$goods_id])->one();
            if($cart){
                $cart->amount += $amount;
            }else{
                $cart = new Cart();
                $cart->goods_id = $goods_id;
                $cart->member_id = $id;
                $cart->amount = $amount;
            }
            $cart->save();
        }
        return $this->redirect(['goods/cat']);
    }
    //购物车
    public function actionCat(){
        $this->layout = 'cat';
        if(\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            //var_dump($cookie);exit;
            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $goods_id => $amount):
                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            endforeach;
            return $this->render('cat',['models'=>$models]);
            //var_dump($models);exit;
        }else{
            $id = \Yii::$app->user->id;
            $model1 = Cart::find()->where(['member_id'=>$id])->asArray()->all();
            $goods_models = [];
            $amount = [];
            foreach ($model1 as $model){
                //var_dump($model);
                $goods = Goods::findOne(['id'=>$model['goods_id']]);//通过遍历就可以的到全部的goods，所以不用all
                $amount[$model['goods_id']] = $model['amount'];//数组在里面赋值的时候都是通过这种方法
                $goods_models[] = $goods;
            }
            //var_dump($goods_models,$amount);exit;
            if($amount != null){
                return $this->render('cat',['goods_models'=> $goods_models,'amount'=>$amount]);
            }else{
                return $this->render('cat',['goods_models'=> $goods_models]);
            }

        }
    }
    //一对多关系
    public function actionCartwo(){
        $models = Cart::find()->all();
        return $this->render('cartwo',['models'=>$models]);
    }
    public function actionUpdate(){

        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //var_dump($amount);exit;
        $goods = Goods::findOne(['id'=>$goods_id]);
        if(\Yii::$app->user->isGuest){
            $goods_id = \Yii::$app->request->post('goods_id');
            $amount = \Yii::$app->request->post('amount');
            //var_dump($amount);exit;
            $goods = Goods::findOne(['id'=>$goods_id]);
            if($goods == null){
                throw new NotFoundHttpException('商品不存在');
            }
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //var_dump($cart);exit;
            $cookies = \Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已经登录修改数据库
            $member_id = \Yii::$app->user->id;
            $cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            //var_dump($cart);exit;
            if($amount){
                $cart->amount = $amount;
                $cart->save();
            }
            $goods_id = \Yii::$app->request->get('id');
            Cart::deleteAll(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            return $this->redirect(['goods/cat']);
        }
    }
    public function actionCat2(){
        if(!\Yii::$app->user->isGuest){
            $this->layout = 'cat2';
            $member_id = \Yii::$app->user->id;
            $address = Address::findAll(['member_id'=>$member_id]);
            $models = Cart::find()->all();
            return $this->render('cat2',['address'=>$address,'models'=>$models]);
        }
        return $this->redirect(['user/login']);
    }
    public function actionOrder(){
        $model = new Order();
        $address_id = \Yii::$app->request->post('address_id');
        $delivery_id = \Yii::$app->request->post('delivery_id');
        $payment_id = \Yii::$app->request->post('payment_id');
        $total = \Yii::$app->request->post('total');
        $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->id]);
            $model->create_time = time();
            $model->name = $address->name;
            $model->province = $address->province_id;
            $model->city = $address->city_id;
            $model->area = $address->district_id;
            $model->member_id = $address->member_id;
            $model->address = $address->details_address;
            $model->tel = $address->tel;
            $delivery = Goods::Delivery();
            $model->delivery_id = $delivery_id;
            $model->delivery_name = $delivery[$delivery_id-1]['delivery_name'];
            $model->delivery_price = substr($delivery[$delivery_id-1]['delivery_price'],3);
            $payment = Goods::Payment();
            $model->payment_id = $payment_id;
            $model->payment_name = $payment[$payment_id-1]['payment_name'];
            $model->status = 1;
            $model->total = $total;
            //事务回滚
            $trancaction = \Yii::$app->db->beginTransaction();
            try{

                $model->save();
                //提交
                $carts = Cart::find()->where(['member_id'=>$address->member_id])->all();

                foreach ($carts as $cart){
                    $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);

                    if($goods==null){
                        throw  new Exception('商品不存在');
                    }

                    //如果需要的数量大于库存
                    if ($cart->amount > $goods->stock){
                        throw new Exception('商品的数量不够');
                    }
                    $model1 = new OrderGoods();
                    $model1->goods_id = $cart->goods_id;
                    $model1->goods_name = $cart->goodsinfo->name;
                    $model1->amount = $cart->amount;
                    $model1->logo = $cart->goodsinfo->logo;
                    $model1->price = $cart->goodsinfo->shop_price;
                    $model1->total = $cart->amount*$cart->goodsinfo->shop_price;
                    $model1->order_id = $model->id;
                    $model1->save();
                    $goods = Goods::findOne(['id'=>$model1->goods_id]);//扣除相应的数量
                    $goods->stock = $goods->stock-$model1->amount;
                    $goods->save(false);
                }
                $trancaction->commit();
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);//因为他是一个数组，不能用对象里面的方法，直接用模型调用方法

            }catch (Exception $e){
                $trancaction->rollBack();
                //回滚
            }
    }
    public function actionCat3(){
        $this->layout = 'cat3';
        return $this->render('cat3');
    }
    public function actionOrderlist(){
        $this->layout = 'list';
        $models = Order::findAll(['member_id'=>\Yii::$app->user->id]);
        return $this->render('orderlist',['models'=>$models]);
    }
    public function actionOrderExplain($id){


    }
    public function actionOrderdel($id){
        $ordergoods = OrderGoods::findOne(['id'=>$id]);
        $model = Order::findOne(['id'=>$ordergoods->order_id,]);
        $model->status = 0;
        $model->save(false);
        return $this->redirect(['goods/orderlist']);
    }

    //清除超时未付款的订单
    public function actionClear(){
        $models = Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->all();
        foreach ($models as $model){
            $model->status = 0;
            $model->save();
            foreach ($model->ordergoods as $good){
                Goods::updateAllCounters(['stock'=>$good->amount,'id'=>$good->goods_id]);
            }
        }
    }
}