
<table class="table table-bordered" id="table">
    <tr>
        <th>分类名</th>
        <th>上级分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $models): ?>
    <tr data-tree="<?=$models->tree?>" data-lft="<?=$models->lft?>" data-rgt="<?=$models->rgt?>">
        <td><?=str_repeat('- ',$models->depth).$models->name?>
        <span class="on glyphicon glyphicon-chevron-down" style="float: right"></span></td>
        <td>
            <?=$models->parent_id?$models->parent->name:'没有父类了'?>
        </td>
        <td><?=$models->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['goodscategory/edit','id'=>$models->id],['class'=>'btn btn-warning btn-xs']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php
/**
 * @var \yii\base\View
 */
$js = <<<JS
    $(".on").click(function (){
        
        //获取树  获取lft  获取rgt 
        //注意点这个获取到的值都要转化类型 parseInt()
        var tr = $(this).closest('tr');
        var tree = parseInt(tr.attr('data-tree'));
        var lft = parseInt(tr.attr('data-lft'));
        var rgt = parseInt(tr.attr('data-rgt'));
        //显示还是隐藏 hasClass() 方法检查被选元素是否包含指定的 class。
        var show = $(this).hasClass('glyphicon-chevron-up');
        //切换图标  toggleClass()  如果不存在则添加类，如果已设置则删除之。这就是所谓的切换效果。
        $(this).toggleClass('glyphicon glyphicon-chevron-up');
        $(this).toggleClass('glyphicon glyphicon-chevron-down');
        //遍历的到你要改变的tr
        $("#table tr").each(function (){
            //是否是同一棵数  左值要大于lft  右值要小于rgt
            
            if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt){
                show?$(this).fadeIn():$(this).fadeOut();
                
            }
        });
        
        
        
    });
JS;
$this->registerJs($js);
