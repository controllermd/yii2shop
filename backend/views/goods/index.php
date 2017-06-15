<?php
/*
use kartik\select2\Select2;
$form=\yii\bootstrap\ActiveForm::begin();
//$data是键值对数组哦，key-value ,下面所声明的所有$data均为键值对数组，以该数组为例
$data = [2 => 'widget', 3 => 'dropDownList', 4 => 'yii2'];
echo $form->field($goods, 'title')->widget(Select2::className(), [
    'data' => $data,
    'options' => ['multiple' => true,'placeholder' => '请选择 ...'],
]);
\yii\bootstrap\ActiveForm::end();
echo \yii\bootstrap\Html::beginForm(['goods/index'],'get');
echo \yii\bootstrap\Html::textInput('key');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
echo \yii\bootstrap\Html::endForm();*/
$form=\yii\bootstrap\ActiveForm::begin([
    'method'=>'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($goods,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($goods,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($goods,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
echo $form->field($goods,'maxPrice')->textInput(['placeholder'=>'￥'])->label('->');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-default','style'=>'position:absolute']);
\yii\bootstrap\ActiveForm::end();
?>


<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>logo图片</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $good): ?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=\yii\bootstrap\Html::img($good->logo)?></td>
            <td><?=$good->parent?$good->parent->name:'没有分类'?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=\backend\models\Goods::$saleOption[$good->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$statusOption[$good->status]?></td>
            <td><?=$good->sort?></td>
            <td><?=$good->create_time?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-warning btn-xs']) ?>
                <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-danger btn-xs']) ?>
                <?=\yii\bootstrap\Html::a('查看相册',['img/index','id'=>$good->id],['class'=>'btn btn-success btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页'
]);

