<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>简介</th>
        <th>分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($article as $articles): ?>
        <tr>
            <td><?=$articles->id?></td>
            <td><?=$articles->name?></td>
            <td><?=$articles->intro?></td>
            <td><?=$articles->article_category->name?></td>
            <td><?=$articles->sort?></td>
            <td><?=\backend\models\Article::$statusOption[$articles->status]?></td>
            <td><?=date('Y-d-m',$articles->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$articles->id],['class'=>'btn btn-warning btn-xs']) ?>
                <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$articles->id],['class'=>'btn btn-danger btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(
    [
        'pagination'=>$page,
        'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'下一页',
    ]
);