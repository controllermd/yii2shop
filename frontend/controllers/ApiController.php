<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 11:51
 */

namespace frontend\controllers;


use backend\models\Article;
use backend\models\Article_category;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Goods_category;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller
{
    //关闭csrf跨站验证
    public $enableCsrfValidation = false;
    //实例化之后就可以调用，相当于构造函数
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //
    public function actionUserRegister(){

        $request = \Yii::$app->request;
        if($request->isPost){
            $member = new Member();
            $member->username = $request->post('username');
            $member->password_hash = \Yii::$app->security->generatePasswordHash($request->post('password_hash'));
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            if($member->validate()){
                $member->save(false);
                return ['status'=>1,'errorMsg'=>'成功','result'=>$member->toArray()];
            }
            //验证失败
            return ['status'=>-1,'errorMsg'=>$member->getErrors()];
        }
        return ['status'=>-1,'errorMsg'=>'请使用post提交'];
    }
    public function actionUserLogin(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $username = Member::findOne(['username'=>$request->post('username')]);
            if($username && \Yii::$app->security->validatePassword($request->post('password_hash'),$username->password_hash)){
                \Yii::$app->user->login($username);
                return ['status'=>1,'errorMsg'=>'登录成功'];
            }
            return ['status'=>-1,'errorMsg'=>'账号或密码错误'];
        }
        return ['status'=>-1,'errorMsg'=>'请用post提交'];
    }
    public function actionUserUpdatePwd(){
        $request = \Yii::$app->request;
        if(!\Yii::$app->user->isGuest){
            $member = Member::findOne(['id'=>\Yii::$app->user->id]);
            if ($request->isPost){
                //var_dump(\Yii::$app->security->validatePassword($request->post('password_hash'),$member->password_hash));exit;
                if(!\Yii::$app->security->validatePassword($request->post('password_hash'),$member->password_hash)){
                    return ['status'=>-1,'errorMsg'=>'旧密码错误'];
                }else{
                    if($request->post('password_new') != $request->post('password')){
                        return ['status'=>-1,'errorMsg'=>'两次输入密码不相同'];
                    }

                    $member->password_hash = \Yii::$app->security->generatePasswordHash($request->post('password_new'));
                    $member->save();
                    return ['status'=>1,'errorMsg'=>'','result'=>'修改成功'];
                }
            }
            return ['status'=>-1,'errorMsg'=>'请求方式错误'];
        }
    }
    public function actionGetUser(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'errorMsg'=>'请登录'];
        }
        return ['status'=>1,'errorMsg'=>'','result'=>\Yii::$app->user->identity->toArray()];
    }
    public function actionAddressAdd(){
        $request = \Yii::$app->request;
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'errorMsg'=>'请登录'];
        }else{
            if($request->isPost){
                //var_dump($request->post('province'));exit;
                $model = new Address();
                $model->name = $request->post('name');
                $model->details_address = $request->post('details_address');
                $model->tel = $request->post('tel');
                $model->province_id = $request->post('province');
                $model->city_id = $request->post('city');
                $model->district_id = $request->post('district');
                $model->member_id = \Yii::$app->user->id;
                //var_dump($model->validate());exit;
                if($model->validate()){
                    $model->save();
                    return ['status'=>1,'errorMsg'=>'添加成功','result'=>$model->toArray()];
                }
                return ['status'=>-1,'errorMsg'=>'添加失败'];
            }
            return ['status'=>-1,'errorMsg'=>'提交方式有错'];
        }
    }
    public function actionAddressUpdate(){
        $request = \Yii::$app->request;
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'errorMsg'=>'请登录'];
        }else{
            $id = $request->get('id');
            if($request->isPost){
                $models = Address::find()->where(['id'=>$id,'member_id'=>\Yii::$app->user->id])->all();
                foreach ($models as $model){
                    $model->name = $request->post('name');
                    $model->details_address = $request->post('details_address');
                    $model->tel = $request->post('tel');
                    $model->province_id = $request->post('province');
                    $model->city_id = $request->post('city');
                    $model->district_id = $request->post('district');
                    if($model->validate()){
                        $model->save();
                        return ['status'=>1,'errorMag'=>'','result'=>$model->toArray()];
                    }
                    return ['status'=>1,'errorMag'=>'修改失败'];
                }
            }
            return ['status'=>-1,'errorMsg'=>'提交方式错误'];
        }
    }
    public function actionAddressDel(){
        $request = \Yii::$app->request;
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'errorMsg'=>'请登录'];
        }else{
            $id = $request->get('id');
            $model = Address::find()->where(['id'=>$id,'member_id'=>\Yii::$app->user->id])->all();
            foreach ($model as $del){
                $del->delete();
                return ['status'=>1,'errorMag'=>'删除成功'];
            }
            return ['status'=>1,'errorMag'=>'删除失败'];
        }
    }
    public function actionAddressList(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'errorMsg'=>'请登录'];
        }else{
            $model = Address::find()->all();
            return ['status'=>1,'errorMag'=>'显示成功','result'=>$model];
        }
    }
    public function actionGoodsCategoryList(){
        $model = Goods_category::find()->all();//加一个父类id=0就可以打印出一级分类
        return ['status'=>1,'errorMag'=>'显示成功','result'=>$model];
    }
    public function actionGoodsCategorySon(){
        $id = \Yii::$app->request->get('id');
        $models = Goods_category::find()->where(['id'=>$id])->all();
        foreach ($models as $model){
            $model2 = Goods_category::find()->where(['tree'=>$model->tree])->andWhere(['>','lft',$model->lft])->andWhere(['<','rgt',$model->rgt])->all();
            return ['status'=>1,'errorMag'=>'','result'=>$model2];
        }
        return ['status'=>1,'errorMag'=>'显示失败'];
    }
    public function actionGoodsCategoryParent(){
        $id = \Yii::$app->request->get('id');
        $models = Goods_category::find()->where(['id'=>$id])->all();

        foreach ($models as $model){
            $model2 = Goods_category::find()->where(['tree'=>$model->tree])->andWhere(['id'=>$model->parent_id])->all();
            return ['status'=>1,'errorMag'=>'','result'=>$model2];
        }
        return ['status'=>-1,'errorMAg'=>'查找失败'];
    }
    public function actionCategoryGoods(){
        $ids = [];
        $ids[] = \Yii::$app->request->get('id');
        $parents = Goods_category::findAll(['parent_id'=>$ids]);
        foreach ($parents as $parent){
            $ids[] += $parent->id;
            $parents = Goods_category::findAll(['parent_id'=>$parent->id]);
            foreach ($parents as $parent){
                $ids[] += $parent->id;
            }
        }
        $models = Goods::find()->where(['in','goods_category_id',$ids])->all();
        $model = [];
        foreach ($models as $good){
            $model[] = $good;
        }
        return ['status'=>1,'errorMag'=>'','result'=>$model];
    }
    public function actionBrandGoods(){
        $id = \Yii::$app->request->get('id');
        $models = Goods::find()->where(['brand_id'=>$id])->all();
        $goods = [];
        foreach ($models as $model){
            $goods[] = $model;
        }
        return ['status'=>1,'errorMag'=>'','result'=>$goods];
    }
    public function actionArticleCategory(){
        $models = Article_category::find()->all();
        return ['status'=>1,'errorMag'=>'','result'=>$models];
    }
    public function actionCategoryArticle(){
        $id = \Yii::$app->request->get('id');
        $models = Article::findAll(['article_category_id'=>$id]);
        return ['status'=>1,'errorMag'=>'','result'=>$models];
    }
    public function actionArticleCategoryOne(){
        $id = \Yii::$app->request->get('id');
        $models = Article::findOne(['id'=>$id]);
        $models = Article_category::findOne(['id'=>$models->article_category_id]);
        return ['status'=>1,'errorMag'=>'','result'=>$models];
    }
    public function actionCartAdd(){
        $goods_id = \Yii::$app->request->get('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $member_id = \Yii::$app->user->id;
        if(\Yii::$app->user->isGuest){
            //开始cookie
            $cookies = \Yii::$app->request->cookies;
            //判断cookie中是否有这个值
            $cookie = $cookies->get('cart');
            if($cookie == null){
                $cart=[];
            }else{
                $cart = unserialize($cookie->value);
            }
            $cookies = \Yii::$app->response->cookies;
            if(key_exists($goods_id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            $model = new Cart();
            //$request = \Yii::$app->request;
            $model->member_id = $member_id;
            $model->goods_id = $goods_id;
            $model->amount = $amount;
            if($model->validate()){
                $model->save();
                return ['status'=>1,'errorMag'=>'','result'=>$model];
            }
            return ['status'=>-1,'errorMag'=>'添加失败'];
        }
    }
    public function actionCartUpdateAmount(){
        $goods_id = \Yii::$app->request->get('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if(\Yii::$app->user->isGuest){
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
            $member_id = \Yii::$app->user->id;
            $model = Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            $request = \Yii::$app->request;
            if($request->isPost){
                $model->amount = $amount;
                if($model->validate()){
                    $model->save();
                    return ['status'=>1,'errorMag'=>'','result'=>$model];
                }
                return ['status'=>1,'errorMag'=>'修改错误'];
            }
            return ['status'=>1,'errorMag'=>'请求方式错误'];
        }
    }
    public function actionCartDel(){
        $request = \Yii::$app->request;
        $goods_id = $request->post('goods_id');
        $amount = $request->post('amount');
        if(\Yii::$app->user->isGuest){
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');
                if($cookie == null){
                    $cart = [];
                }else{
                    $cart = unserialize($cookie->value);
                }
                $cookies = \Yii::$app->response->cookies;
            if($amount == 0){
                $cookies->remove($cart[$goods_id]);
            }
        }else{
            $member_id = \Yii::$app->user->id;
            $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            $model->delete();
            return ['status'=>1,'errorMag'=>'删除成功'];
        }
    }
    public function actionCartDelAll(){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->response->cookies;
            $cookies->removeAll();
        }else{
            $member_id = \Yii::$app->user->id;
            Cart::deleteAll(['member_id'=>$member_id]);
            return ['status'=>1,'errorMag'=>'删除成功'];
        }
    }
    public function actionCartGoodsAll(){
        //$request = \Yii::$app->request;
        $member_id = \Yii::$app->user->id;
        $cart = Cart::find()->where(['member_id'=>$member_id])->asArray()->all();
        $models = [];
        $amount = [];
        foreach ($cart as $model){
            $goods = Goods::find()->where(['id'=>$model['goods_id']])->asArray()->one();
            $amount['amount'] = $model['amount'];
            $models[] = array_merge($goods,$amount);
        }
        return ['status'=>1,'errorMag'=>'','result'=>$models];
    }
    public function actionModelPayment(){
        $request = \Yii::$app->request;
        $payment_id = $request->post('payment_id');
        if($request->isPost){
            $payment = Goods::Payment();
            $payment_name = $payment[$payment_id-1]['payment_name'];
            return ['status'=>1,'errorMag'=>'','result'=>$payment_name];
        }
        return ['status'=>1,'errorMag'=>'请求方式不对'];
    }
    public function actionModelDelivery(){
        $request = \Yii::$app->request;
        $delivery_id = $request->post('delivery_id');
        if($request->isPost){
            $delivery = Goods::Delivery();
            $delivery_name = $delivery[$delivery_id-1]['delivery_name'];
            return ['status'=>1,'errorMag'=>'','result'=>$delivery_name];
        }
        return ['status'=>1,'errorMag'=>'请求方式不对'];
    }
    public function actionOrder(){
        $request = \Yii::$app->request;
        $address_id = $request->post('address_id');
        $delivery_id = $request->post('delivery_id');
        $payment_id = $request->post('payment_id');
        $total = $request->post('total');
        $member_id = \Yii::$app->user->id;
        $order = new Order();
        if($request->isPost){
            $address = Address::findOne(['id'=>$address_id,'member_id'=>$member_id]);
            $order->create_time = time();
            $order->name = $address->name;
            $order->province = $address->province_id;
            $order->city = $address->city_id;
            $order->area = $address->district_id;
            $order->member_id = $address->member_id;
            $order->address = $address->details_address;
            $order->tel = $address->tel;
            $delivery = Goods::Delivery();
            $order->delivery_id = $delivery_id;
            $order->delivery_name = $delivery[$delivery_id-1]['delivery_name'];
            $order->delivery_price = $delivery[$delivery_id-1]['delivery_price'];
            $payment = Goods::Payment();
            $order->payment_id = $payment_id;
            $order->payment_name = $payment[$payment_id-1]['payment_name'];

            if($order->validate()){
                $trancaction = \Yii::$app->db->beginTransaction();//开启事物
                try{

                    //$order->save();
                    $carts = Cart::findAll(['member_id'=>$member_id]);
                    foreach ($carts as $cart){
                        $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                        if($goods == null){
                            throw new Exception('商品不存在');
                        }
                        if($cart->amount > $goods->stock){
                            throw new Exception('商品数量不够');
                        }
                        $ordergoods = new OrderGoods();
                        $ordergoods->order_id = $order->id;
                        $ordergoods->goods_id = $cart->goods_id;
                        $ordergoods->goods_name = $cart->goodsinfo->name;
                        $ordergoods->logo = $cart->goodsinfo->logo;
                        $ordergoods->price = $cart->goodsinfo->price;
                        $ordergoods->amount = $cart->amount;
                        $ordergoods->total = $total;
                        //$ordergoods->save();
                        $goods = Goods::findOne(['id'=>$ordergoods->goods_id]);
                        $goods->stock = ($goods->stock-$cart->amount);
                        //var_dump($goods->stock);exit;
                        $goods->save();
                    }
                    $trancaction->commit();
                    Cart::deleteAll(['member_id'=>$member_id]);
                    return ['status'=>1,'errorMAg'=>''];
                }catch (Exception $e){
                    $trancaction->rollBack();
                }
            }
            return ['status'=>-1,'errorMAg'=>'请求方式错误'];
        }
    }
    public function actionMemberOrder(){
        $order = Order::findAll(['member_id'=>\Yii::$app->user->id]);
        return ['status'=>1,'errorMAg'=>'','result'=>$order];
    }
    //点单取消
    public function actionOrderOver(){
        $order_id = \Yii::$app->request->post('order_id');
        $member_id = \Yii::$app->user->id;
        $orders = Order::findAll(['member_id'=>$member_id,'id'=>$order_id]);
        foreach ($orders as $order) {
            $ordergoods = OrderGoods::findOne(['order_id' => $order_id]);
            $goods = Goods::findOne(['id' => $ordergoods->goods_id]);
            $goods->stock += $ordergoods->amount;
            $goods->save();
            $ordergoods->delete();
            $order->delete();
        }
        return ['status'=>1,'errorMAg'=>''];
    }
    //验证码
    public function actions()
    {
        $model = new Member();
        $model->setScenario('captcha');
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
            //用api/captcha.html得到验证码
            //用api/captcha.html?refresh=1得到新验证码地址
            //将新地址打印出来
        ];
    }
    //上传文件
    public function actionUpload()
    {
        $img = UploadedFile::getInstanceByName('img');
        if($img){
            $fileName = '/upload/'.uniqid().'.'.$img->extension;
            $result = $img->saveAs(\Yii::getAlias('@webroot').$fileName,0);
            if($result){
                return ['status'=>'1','errorMag'=>'','data'=>$fileName];
            }
            return ['status'=>'-1','errorMag'=>$img->error];
        }
        return ['status'=>'-1','errorMag'=>'没有文件上传'];
    }
    //分页读取数据
    public function actionList(){
        //每页显示的条数
        $pageSize = \Yii::$app->request->get('pageSize',2);
        //当前第几页
        $page = \Yii::$app->request->get('page',1);
        //搜索
        $keyCodes = \Yii::$app->request->get('keyCode');
        $query = Goods::find();
        if($keyCodes){
            $query->andWhere(['like','name',$keyCodes]);
        }
        //总条数
        $total = $query->count();
        //获取当前页的商品数据
        $goods = $query->offset($pageSize*($page-1))->limit($pageSize)->asArray()->all();
        return ['status'=>'1','errorMag'=>'','result'=>[
            'total'=>$total,
            'pageSize'=>$pageSize,
            'page'=>$page,
            'goods'=>$goods
        ]];
    }
    //手机短信
    public function actionSendSms(){
        //确定发送短信时间超过1分钟
        $tel = \Yii::$app->request->post('tel');
        //验证手机号码
        if(!preg_match('/^1[13578]\d{9}$/',$tel)){
            return ['status'=>'-1','errorMag'=>'手机号码错误'];
        }
        //检测上次发送时间是否超过1分钟
        $value = \Yii::$app->cache->get('time_tel_'.$tel);
        $s = time()-$value;
        if($s<60){
            return ['status'=>'-1','errorMag'=>(60-$s).'秒后再试'];
        }
        $code = rand(1000,9999);
        $result = 1;
        if($result){
            //不能把验证码放到cookie中，别的都可以
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            \Yii::$app->cache->set('time_tel_'.$tel,time(),5*60);
            return ['status'=>'1','errorMag'=>''];
        }else{
            return ['status'=>'-1','errorMag'=>'短信发送失败'];
        }
    }
}