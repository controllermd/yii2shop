<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name');
echo $form->field($article,'intro')->textarea();
echo $form->field($article,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($article_categorys,'id','name'));
echo $form->field($article_detail,'content')->textarea();
echo $form->field($article,'sort');
echo $form->field($article,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();