<?php
$this->registerCssFile('@web/style/order.css');
$this->registerCssFile('@web/style/home.css');

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
                <dd class="cur"><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd><b>.</b><a href="">账户信息</a></dd>
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
        <div class="order_hd">
            <h3>我的订单</h3>
            <dl>
                <dt>便利提醒：</dt>
                <dd>待付款（0）</dd>
                <dd>待确认收货（0）</dd>
                <dd>待自提（0）</dd>
            </dl>

            <dl>
                <dt>特色服务：</dt>
                <dd><a href="">我的预约</a></dd>
                <dd><a href="">夺宝箱</a></dd>
            </dl>
        </div>

        <div class="order_bd mt10">
            <table class="orders">
                <thead>
                <tr>
                    <th width="10%">订单号</th>
                    <th width="20%">订单商品</th>
                    <th width="10%">收货人</th>
                    <th width="20%">订单金额</th>
                    <th width="20%">下单时间</th>
                    <th width="10%">订单状态</th>
                    <th width="10%">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model)://一对多就是数组，一对一是对象?>
                    <?php foreach ($model->ordergoods as $model1): ?>
                <tr>
                    <td><a href=""><?=$model1->id?></a></td>
                    <td><a href=""><?=\yii\helpers\Html::img("http://admin.yii2shop.com/".$model1->logo)?></a></td>
                    <td><?=$model->name?></td>
                    <td>￥<?=$model->delivery_price?> 货到付款</td>
                    <td><?=date("Y-m-d H:i:s",$model->create_time)?></td>
                    <td><?=frontend\models\Order::$statusOption[$model->status]?></td>
                    <td><?=\yii\helpers\Html::a("查看",['goods/orderexplain','id'=>$model->id])?> |
                        <?=\yii\helpers\Html::a("删除",['goods/orderdel','id'=>$model1->id])?>
                    </td>
                </tr>
                <?php
                    endforeach;
                    endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->
