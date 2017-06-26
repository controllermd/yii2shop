<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username');
echo $form->field($admin,'password_hash')->passwordInput();
echo $form->field($admin,'password2')->passwordInput();
echo $form->field($admin,'role',['inline'=>true])->checkboxList(\backend\models\User::getRole());
echo $form->field($admin,'code')->widget(\yii\captcha\Captcha::className());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();