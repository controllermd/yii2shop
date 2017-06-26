<?php
namespace backend\widgets;
use backend\models\Menu;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;
class MenuWidget extends Widget
{
    public function run()
    {

        NavBar::begin([
            'brandLabel' => '今夕后台管理',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['/goods/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
        } else {

            //根据用户的权限显示菜单
            /*$menuItems[] = ['label'=>'用户管理','items'=>[
                ['label'=>'添加用户','url'=>['admin/add']],
                ['label'=>'用户列表','url'=>['admin/index']]
            ]];*/
            //获取所有一级菜单
            $menus = Menu::findAll(['parent_id'=>0]);
            foreach ($menus as $menu){
                $item = ['label'=>$menu->name,'items'=>[]];
                foreach ($menu->parent as $child){
                    //根据用户权限判断，该菜单是否显示can判断是否有权限
                    if(\Yii::$app->user->can($child->url)){
                        $item['items'][] = ['label'=>$child->name,'url'=>[$child->url]];
                    }
                }
                //如果该一级菜单没有子菜单，就不显示
                if(!empty($item['items'])){
                    $menuItems[] = $item;
                }
            }
            $menuItems[] = ['label' => '修改密码', 'url' => ['/user/revise']];
            $menuItems[] = '<li>'
                . Html::beginForm(['/user/logout'], 'post')
                . Html::submitButton(
                    '退出 (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}