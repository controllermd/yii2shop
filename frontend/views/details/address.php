<?php

$url=\yii\helpers\Url::toRoute(['get-region']);
?>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php
            if(isset($models)) {
                foreach ($models as $model1): ?>
                    <dl>
                        <dt><?php echo $model1->id . '.' . $model1->name . ' ' . $model1->province_id . ' ' .$model1->city_id . ' ' .$model1->district_id . ' ' . $model1->details_address . ' ' . $model1->tel ?></dt>
                        <dd>
                            <?= \yii\helpers\Html::a('修改', ['details/edit', 'id' => $model1->id]) ?>
                            <?= \yii\helpers\Html::a('删除', ['details/del', 'id' => $model1->id]) ?>
                            <?=\yii\helpers\Html::a(\frontend\models\Address::$statusOption[$model1->status],['details/detail','id'=>$model1->id])?>
                        </dd>
                    </dl>
                <?php endforeach;
            }
            ?>
        </div>

        <div class="address_bd mt10">
            <h4><?=isset($_GET['id'])?'修改地址信息':'新增地址信息'?></h4>
            <?php
            $form=\yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li',
                    ],

                ]]
            );
            echo '<ul>';
            
            echo $form->field($model,'name')->textInput(['class'=>'txt']);
            /*echo $form->field($model, 'province')->widget(\chenkby\region\Region::className(),[
                'model'=>$model,
                'url'=>$url,
                'province'=>[
                    'attribute'=>'province',
                    'items'=>\frontend\models\Locations::getRegion(),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
                ],
                'city'=>[
                    'attribute'=>'city',
                    'items'=>\frontend\models\Locations::getRegion($model['province']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
                ],
                'district'=>[
                    'attribute'=>'district',
                    'items'=>\frontend\models\Locations::getRegion($model['city']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
                ]
            ]);*/

            echo '<li><label for="">所在地区：</label>';
            echo $form->field($model,'province',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择省=']);
            echo $form->field($model,'city',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择市=']);
            echo $form->field($model,'district',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择县=']);
            echo '</li>';
            echo $form->field($model,'details_address')->textInput(['class'=>'txt address']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            echo $form->field($model,'rememberMe')->checkbox(['class'=>'check'])->label('　');
            echo '<br /><br />';
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存">
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>
        </div>
    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerJsFile('@web/js/address.js');
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    //填充省的数据
    $(address).each(function (){
    var option = '<option value="'+this.name+'">'+this.name+'</option>';
    $('#address-province').append(option);
    });
    //当第一个框里面的数据发生改变的时候触发事件
    $('#address-province').change(function (){
        var op = '<option value="">=请选择县=</option>';
        $("#address-district").html(op);
        //获取当前选中的值
        var province = $(this).val();
        $(address).each(function (){
            if(this.name == province){
                var option = '<option value="">=请选择市=</option>';
                $(this.city).each(function(){
                    option += '<option value="'+this.name+'">'+this.name+'</option>';
                });
                $('#address-city').html(option);
            }
        });
    });
    $("#address-city").change(function(){
        var city = $(this).val();//当前选中的城市
        $(address).each(function(){
            if(this.name == $("#address-province").val()){
            var option = '<option value="">=请选择县=</option>';
                $(this.city).each(function(){
                    if(this.name == city){
                        //遍历到当前选中的城市了
                        option = '<option value="">=请选择县=</option>';
                        $(this.area).each(function(i,v){
                            option += '<option value="'+v+'">'+v+'</option>';  
                        });
                        $("#address-district").html(option);
                    }
                });
            }
        });
    });
JS

));
$js = '';
if($model->province_id){
    $js .='$("#address-province").val("'.$model->province_id.'");';
}
if($model->city_id){
    $js .= '$("#address-province").change();$("#address-city").val("'.$model->city_id.'");';
}
if($model->district_id){
    $js .= '$("#address-city").change();$("#address-district").val("'.$model->district_id.'");';
}
$this->registerJs($js);