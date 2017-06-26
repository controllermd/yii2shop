<table class="table table-bordered table-responsive" id="table">
    <thead>
    <tr>
        <th>权限名</th>
        <th>权限简介</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model): ?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['rbac/updatepermission','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/delpermission','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
   /**
    *
    * @var $this \yii\web\View
    * */

    $this->registerCssFile('@web/css/jquery.dataTables.min.css');
    $this->registerJsFile('@web/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
    $this->registerJs('$("#table").DataTable();')
?>

