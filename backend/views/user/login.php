<?php

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($login,'username');
echo $form->field($login,'password_hash')->passwordInput();
echo $form->field($login,'code')->widget(\yii\captcha\Captcha::className());
echo $form->field($login,'cookie')->checkbox();
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();