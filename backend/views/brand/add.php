<?php
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($brand,'name');
echo $form->field($brand,'intro')->textarea();
echo $form->field($brand,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,//跨站验证
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将成功后的图片地址（data.fileUrl）写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功后的图片地址（data.fileUrl）写入logo标签里面
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($brand->logo){
    echo \yii\helpers\Html::img($brand->logo,['id'=>'img_logo','height'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
}
echo $form->field($brand,'sort');
echo $form->field($brand,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($brand,'code')->widget(\yii\captcha\Captcha::className());
echo \yii\bootstrap\Html::submitButton('新增',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();