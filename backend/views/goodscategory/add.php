<?php
/**
 * @var $this \yii\web\View
*/
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->hiddenInput();
echo \yii\helpers\Html::tag('ul','',['id'=>'treeDemo','class'=>'ztree']);
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
/*<link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>*/
//$this->registerCssFile();
//$this->regusterJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
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
                    $("#goods_category-parent_id").val(treeNode.id);
                }
            }
        };
       
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = $category;
  
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开所有节点
            zTreeObj.expandAll(true);
            //当前的父节点，根据id
            var node = zTreeObj.getNodeByParam("id",$("#goods_category-parent_id").val(),null);
            zTreeObj.selectNode(node);
        });
JS

);
$this->registerJs($js);

?>
