<?php
use \yii\helpers\Html;
foreach ($models as $k=>$model):
    //var_dump($model->id);exit;//关键点一对多，
echo '<div class="cat item1">';
echo '<h3>'.Html::a($model->name,['list/list','id'=>$model->id]).'<b></b></h3>';
echo '<div class="cat_detail none">';
    foreach ($model->parent as $k2=>$model1):
echo "<dl $k2==0?class='dl_1st':''>";
        //var_dump($model1->id);exit;
echo '<dt>'.Html::a($model1->name,['list/list','id'=>$model1->id]).'</dt>';
        foreach ($model1->parent as $model2):
echo '<dd>';
echo Html::a($model2->name,['list/list','id'=>$model2->id]);
echo '</dd>';
        endforeach;
echo '</dl>';
    endforeach;
echo '</div>';
echo '</div>';
endforeach;
