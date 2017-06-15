<?php
use yii\web\JsExpression;
use xj\uploadify\Uploadify;
use kartik\select2\Select2;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name');
echo $form->field($goods,'sn');
/*//$data是键值对数组哦，key-value ,下面所声明的所有$data均为键值对数组，以该数组为例
$data = [2 => 'widget', 3 => 'dropDownList', 4 => 'yii2'];
echo $form->field($goods, 'title')->widget(Select2::className(), [
    'data' => $data,
    'options' => ['multiple' => true,'placeholder' => '请选择 ...'],
]);*/
echo $form->field($goods,'logo')->hiddenInput();
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
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($goods->logo){
    echo \yii\helpers\Html::img($goods->logo,['id'=>'img_logo','height'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
}
echo $form->field($goods,'goods_category_id')->hiddenInput();
//echo $form->field($goods,'goods_category_id')->dropDownList($goods_category);
echo \yii\helpers\Html::tag('ul','',['id'=>'treeDemo','class'=>'ztree']);
echo $form->field($goods,'brand_id')->dropDownList($brand);
echo $form->field($goods,'shop_price');
echo $form->field($goods,'market_price');
echo $form->field($goods,'stock');
echo $form->field($goods,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::$saleOption);
echo $form->field($goods,'status',['inline'=>true])->radioList(\backend\models\Goods::$statusOption);
echo $form->field($goods,'sort');
echo $form->field($goodsintro,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
//var_dump($zNodes);exit;
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event,treeID,treeNode){
                    //console.log(treeNode.id);
                    $("#goods-goods_category_id").val(treeNode.id);
                }
            }
        };
       
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = $goods_category;
  
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开所有节点
            zTreeObj.expandAll(true);
            //当前的父节点，根据id
            var node = zTreeObj.getNodeByParam("id",$("#goods-goods_category_id").val(),null);
            zTreeObj.selectNode(node);
        });
JS

);
$this->registerJs($js);

?>