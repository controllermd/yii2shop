<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>管理员名</th>
        <th>管理员状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin): ?>
    <tr>
        <td><?=$admin->id?></td>
        <td><?=$admin->username?></td>
        <td><?=\backend\models\User::$statusOption[$admin->status]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$admin->id],['class'=>'btn btn-warning btn-xs']) ?>
            <?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$admin->id],['class'=>'btn btn-danger btn-xs']) ?>
            <?=\yii\bootstrap\Html::a('修改密码',['user/revise','id'=>$admin->id],['class'=>'btn btn-danger btn-xs']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

