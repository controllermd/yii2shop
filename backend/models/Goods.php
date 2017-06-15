<?php

namespace backend\models;

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
    public static $saleOption = [1=>'在售',0=>'下架'];
    public static $statusOption = [1=>'正常',0=>'回收站'];
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public $title;
    public $content;
    public function getParent(){
        return $this->hasOne(Goods_category::className(),['id'=>'goods_category_id']);
    }
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function getGalleries()
    {
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
            'id' => 'ID',
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

}
