<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wish\WishAuth;

class WishAccount extends Model
{
    protected $table = 'wish_account';

    protected $guarded = [];

    public function getAccessToken($type='prod')
    {
        $default = ['success'=>0,'message'=>'获取Access Token失败','access_token'=>''];
        // Access Token不为空，判断是否过期
        if (! empty($this->access_token)) {
            $timeOut = trim($this->access_token_timeout_at);
            if(!empty($timeOut) && (strtotime($timeOut) > time())) {
                return ['success'=>1,'message'=>'','access_token'=>$this->access_token];
            }
        }

        // 更换新Token
        if(! empty($this->refresh_token)) {
            $client_id = trim($this->client_id);
            $client_secret = trim($this->secret_id);

            if(empty($client_id) || empty($client_secret)) {
                $default['message'] = "Access Token过期，使用Refresh Token换取Access Token失败(Client_id 或 Client_secret为空，无法换取Token)";
                return $default;
            }

            //
            try {
                $auth = new WishAuth($client_id,$client_secret,$type);
                $response = $auth->refreshToken($this->refresh_token);
                if($response->getStatusCode() > 0) {
                    $default['message'] = "Access Token过期，使用Refresh Token换取Access Token失败(状态码:".$response->getStatusCode()." Msg:".$response->getMessage();
                } else {
                    // 返回的Refresh Token，跟之前一样
                    $this->freshToken($response->getData());
                    return ['success'=>1,'message'=>'','access_token'=>$response->getData()->access_token];
                }
            } catch (Exception $e) {
                $default['message'] = "Access Token过期，使用Refresh Token换取Access Token失败(状态码:4000 Msg:Unauthorized access)";
                return $default;
            }
        }

        return $default;
    }

    // 一次性code换取Token access_token和refresh_token
    public function setToken($code,$type='prod') {

        $client_id = trim($this->client_id);
        $client_secret = trim($this->secret_id);
        $redirect_uri = trim($this->redirect_uri);

        if(empty($client_id) || empty($client_secret) || empty($redirect_uri) || empty($code)) {
            return "信息不完整，无法完成授权。";
        }

        try {
            $auth = new WishAuth($client_id,$client_secret,$type);
            $response = $auth->getToken($code,$redirect_uri);

            $this->freshToken($response->getData());
        } catch (Exception $e) {
            return "4000 或 1016 异常。";
        }
    }

    // 保存Token信息
    protected function freshToken($data) {
        if (is_object($data)) {
            $this->refresh_token = $data->refresh_token;
            $this->refresh_token_timeout_at = date("Y-m-d H:i:s", time() + 1 * 12 * 30 * 24 * 3600);
            $this->access_token = $data->access_token;
            $this->access_token_timeout_at = date("Y-m-d H:i:s", time() + $data->expires_in - 2 * 24 * 3600);

            $this->save();
        }
    }
    //
}
