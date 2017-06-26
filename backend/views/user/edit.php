<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username');
echo $form->field($admin,'role',['inline'=>true])->checkboxList(\backend\models\User::getRole());
echo $form->field($admin,'status',['inline'=>true])->radioList(\backend\models\User::$statusOption);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();