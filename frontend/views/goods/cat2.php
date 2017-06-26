<?php
$this->registerCssFile('@web/style/fillin.css');
$this->registerJsFile('@web/js/cart2.js',['depends'=>\yii\web\JqueryAsset::className()]);

?>
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">

                <p>
                    <?php foreach ($address as $ress): ?>
                        <?php /*var_dump($ress->id) */?>
                    <input type="radio" value="<?=$ress->id?>" name="address_id"/><?php echo $ress->name.''.$ress->tel.'　'.$ress->province_id.'　'.$ress->city_id.'　'.$ress->district_id?><br />
                    <?php endforeach; ?>
                </p>

            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $model2 = \frontend\models\Goods::Delivery();
                        foreach ($model2 as $key=>$value):
                    ?>
                    <tr <?=$key==0?'class="cur"':''?>>
                        <td>
                            <input type="radio" name="delivery" value="<?=$value['delivery_id']?>" /><?=$value['delivery_name']?>

                        </td>
                        <td><?=$value['delivery_price']?></td>
                        <td><?=$value['delivery_explain']?></td>
                    </tr>
                    <?php endforeach;?>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php
                    $model3 = \frontend\models\Goods::Payment();
                    foreach ($model3 as $k=>$v):
                    ?>
                    <tr <?=$k==0?'class="cur"':''?>>
                        <td class="col1"><input type="radio" name="pay" value="<?=$v['payment_id']?>" /><?=$v['payment_name']?></td>
                        <td class="col2"><?=$v['payment_explain']?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>

        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                $money = 0;
                foreach ($models as $model):
                    $total += $model->amount;
                    $money += $model->goodsinfo->shop_price*$model->amount;
                    ?>
                <tr>
                    <td class="col1"><?=\yii\helpers\Html::a(\yii\helpers\Html::img('http://admin.yii2shop.com/'.$model->goodsinfo->logo))?><strong><?=\yii\helpers\Html::a($model->goodsinfo->name)?></strong></td>
                    <td class="col3">￥<?=$model->goodsinfo->shop_price?>.00</td>
                    <td class="col4"><?=$model->amount?></td>
                    <td class="col5"><span>￥<?=$model->goodsinfo->shop_price*$model->amount?>.00</span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul id="put">
                            <li>
                                <span> <?=$total?>件商品，总商品金额：</span>
                                <em>￥<?=$money?>.00</em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥240.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">

        <a href="javascript:;"><span>提交订单</span></a>
        <p>应付总额：<strong></strong></p>

    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
 * @var $this \yii\web\View
 *
 */
$url = \yii\helpers\Url::to(['goods/order']);
$url2 = \yii\helpers\Url::to(['goods/cat3']);
$csrf = \Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(

    <<<JS
    var next = null;
    $("input[name=delivery]").click(function (){
        var parents = $(this).closest('tr').find('td:eq(1)').html();
        $("#put li:eq(2) em").text(parents);
        var money = $("#put").find("li:eq(0) em").text();
        //console.log(parents);exit();
        var fanx = $("#put").find("li:eq(1) em").text();
        var moneys = parseInt(money.substring(1))-parseInt(parents.substring(1))-parseInt(fanx.substring(2));
        $("#put li:eq(2) em").text(parents);
        $("#put li:eq(3) em").text(moneys+'.00');
        $(".fillin_ft strong").text(moneys+'.00');
    });
    $(".fillin_ft").click(function (){
        var total = $(".fillin_ft").find("p").text();
        var address_id = $("input[name='address_id']:checked").val();
        var delivery_id = $("input[name=delivery]:checked").val();
        var payment_id = $("input[name=pay]:checked").val();
        $.post("$url",{address_id:address_id,delivery_id:delivery_id,payment_id:payment_id,total:total.substring(5),"_csrf-frontend":"$csrf"},function (res){
            document.location.href="$url2";
        })
    });
JS

));
