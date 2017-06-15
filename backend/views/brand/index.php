<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>简介</th>
        <th>logo图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brand as $brands): ?>
    <tr>
        <td><?=$brands->id?></td>
        <td><?=$brands->name?></td>
        <?php var_dump($brands->logo?$brands->logo:null) ?>
        <td><?=$brands->logo?\yii\bootstrap\Html::img($brands->logo,['width'=>100]):null?></td>
        <td><?=\backend\models\Brand::$statusOption[$brands->status]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brands->id],['class'=>'btn btn-warning btn-xs']) ?>
            <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brands->id],['class'=>'btn btn-danger btn-xs']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页'
]);