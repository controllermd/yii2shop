<table class="table table-bordered table-responsive">
    <tr>
        <th>角色名</th>
        <th>角色简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['rbac/editrole','name'=>$model->name],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/delrole','name'=>$model->name],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>