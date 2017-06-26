<?php

namespace frontend\models;

use Yii;
/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public $title;
    public $content;
    public function getImg(){
        return $this->hasMany(Img::className(),['goods_id'=>'id']);
    }
    public function rules()
    {
        return [
            [['name', 'market_price', 'shop_price','stock','sort'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
            [['market_price'],'compare', 'compareAttribute' =>'shop_price', 'operator' => '>=','message'=>'市场价格应大于商品价格'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'content'=>'商品详情'
        ];
    }
    public static function Delivery(){
        return [
            ['delivery_id'=>1,'delivery_name'=>'普通快递送货上门','delivery_price'=>'￥10.00','delivery_explain'=>'	每张订单不满499.00元,运费15.00元, 订单4...'],['delivery_id'=>2,'delivery_name'=>'特快专递','delivery_price'=>'￥40.00','delivery_explain'=>'	每张订单不满499.00元,运费40.00元, 订单4...'],['delivery_id'=>3,'delivery_name'=>'加急快递送货上门','delivery_price'=>'￥40.00','delivery_explain'=>'	每张订单不满499.00元,运费40.00元, 订单4...'],['delivery_id'=>4,'delivery_name'=>'平邮','delivery_price'=>'￥10.00','delivery_explain'=>'	每张订单不满499.00元,运费15.00元, 订单4...']];
    }
    public static function Payment(){
        return [
            ['payment_id'=>1,'payment_name'=>'货到付款','payment_explain'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
            ['payment_id'=>2,'payment_name'=>'在线支付','payment_explain'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
            ['payment_id'=>3,'payment_name'=>'上门自提','payment_explain'=>'自提时付款，支持现金、POS刷卡、支票支付'],
            ['payment_id'=>4,'payment_name'=>'邮局汇款','payment_explain'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
        ];
    }
}
