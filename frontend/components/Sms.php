<?php
namespace frontend\components;

use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use yii\base\Component;

class Sms extends Component{
    public $app_key;
    public $app_secret;
    public $sign_name;
    public $template_code;
    public $_num;
    public $param=[];

    //设置手机号码
    public function setNum($num){
        $this->_num = $num;
        return $this;
    }
    //设置短信内容
    public function setParam(array $param){
        $this->param = $param;
        return $this;
    }
    //设置签名
    public function setSign($sign){
        $this->sign_name = $sign;
        return $this;
    }
    //设计短信模板
    public function setTemple($id){
        $this->template_code = $id;
        return $this;
    }
    //发送短信
    public function send(){
        $client = new Client(new App(['app_key'=>$this->app_key,'app_secret'=>$this->app_secret]));
        $req    = new AlibabaAliqinFcSmsNumSend();
        $req->setRecNum($this->_num)//设置发给谁
        ->setSmsParam($this->param)
            ->setSmsFreeSignName($this->sign_name)//设置签名
            ->setSmsTemplateCode($this->template_code);//设置模板
        return $client->execute($req);
    }
}