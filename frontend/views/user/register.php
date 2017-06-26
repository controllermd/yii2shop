
<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
            $form=\yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li'
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]]
            );
            echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);
            echo $form->field($model,'password_hash')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'password2')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'email')->textInput(['class'=>'txt']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            $button =  \yii\helpers\Html::button('获取验证码',['id'=>'send_sms_button']);
            echo $form->field($model,'sms',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}　$button\n{hint}\n{error}"])->textInput(['class'=>'txt']);
            echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
            echo '<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" checked="checked"> 我已阅读并同意《用户注册协议》
						</li>';
            echo '<li>
                       <label for="">&nbsp;</label>
                       <input type="submit" value="" class="login_btn">
                  </li>';
            \yii\widgets\ActiveForm::end();
            echo '</ul>';
            ?>
        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

<?php
$url = \yii\helpers\Url::to(['user/send-sms']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $("#send_sms_button").click(function (){
    var time=5;
    var msg='';
    var that = $(this);
    that.prop('disabled',true);
            var interval = setInterval(function(){
                time--;
                if(time<=0){
                    clearInterval(interval);
                    msg = '获取验证码';
                    that.prop('disabled',false);
                }else{
                    msg = time + ' 秒后再次获取';
                    
                }

                that.text(msg);
            },1000);
    //发送验证码按钮被点击的时候
    var tel = $("#member-tel").val();
        $.post('$url',{tel:tel},function (data){
            if(data == 'success'){
                        console.log('短信发送成功');
                        alert('短信发送成功');
            }else{
                console.log(data);
            }
        });
    });
JS

));
