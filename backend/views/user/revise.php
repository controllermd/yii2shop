<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'new_password')->passwordInput();
echo $form->field($model,'password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('修改密码',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();