<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>文章名</th>
        <th>文章内容</th>
        <th>操作</th>
    </tr>
    <?php foreach ($article as $articles): ?>
    <tr>
        <td><?=$articles->id?></td>
        <td><?=$articles->article->name?></td>
        <td><?=$articles->content?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['articledetail/edit','id'=>$articles->id],['class'=>'btn btn-warning btn-xs']) ?>
            <?=\yii\bootstrap\Html::a('删除',['articledetail/del','id'=>$articles->id],['class'=>'btn btn-danger btn-xs']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>