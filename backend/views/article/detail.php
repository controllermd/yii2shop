<?=\yii\bootstrap\Html::a('返回首页',['article/index'],['class'=>'btn btn-info'])?>
<table class="table">
    <tr>
        <td align="center"><?=$articled->article->name?></td>
    </tr>
        <tr>

            <td>&emsp;&emsp;<?=$articled->content?></td>
        </tr>
</table>