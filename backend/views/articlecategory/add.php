<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name');
echo $form->field($article,'intro')->textarea();
echo $form->field($article,'sort');
echo $form->field($article,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($article,'is_help',['inline'=>true])->radioList([1=>'文章',0=>'帮助文档']);
echo $form->field($article,'code')->widget(\yii\captcha\Captcha::className());
echo \yii\bootstrap\Html::submitButton('新增',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();