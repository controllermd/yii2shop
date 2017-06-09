<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'article_id')->dropDownList(\yii\helpers\ArrayHelper::map($article_datail,'id','name'));
echo $form->field($article,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
