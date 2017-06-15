<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14
 * Time: 9:54
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;


class GoodsForm extends Model
{
    public $name;
    public $sn;
    public $maxPrice;
    public $minPrice;

    public function rules()
    {
        return [
            [['name','sn'],'string'],
            ['minPrice','double'],
            ['maxPrice','double'],
        ];
    }
    public function search(ActiveQuery $quest){
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $quest->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $quest->andWhere(['like','sn',$this->sn]);
        }
        if($this->maxPrice){
            $quest->andWhere(['<=','shop_price',$this->maxPrice]);
        }
        if($this->minPrice){
            $quest->andWhere(['>=','shop_price',$this->minPrice]);
        }
    }
}