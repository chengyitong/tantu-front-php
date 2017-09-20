<?php
//  首页
class SdkAction extends Action {
    
    //登录地址
    public function login($type = null) {
        empty($type) && $this->error('参数错误');

        //加载ThinkOauth类并实例化一个对象
        $name = ucfirst(strtolower($type)) . 'SDK';
        $names = "Common\OauthSDK\sdk" . "\\" . $name;
        $oauth = new $names();

        //跳转到授权页面
        redirect($oauth->getRequestCodeURL());
    }

}