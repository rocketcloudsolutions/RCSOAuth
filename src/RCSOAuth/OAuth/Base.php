<?php

namespace RCSOAuth\OAuth;

class Base {

    protected $request_url;
    protected $authorize_url;
    protected $access_token_url;
    protected $api_url;
    protected $consumer_key;
    protected $consumer_secret;

    public function setRequestUrl($request_url){
        $this->request_url = $request_url;
    }

    public function setAuthorizeUrl($auth_url){
        $this->authorize_url = $auth_url;
    }

    public function setAccessTokenUrl($access_token_url){
        $this->access_token_url = $access_token_url;
    }

    public function setAPIUrl($url){
        $this->api_url = $url;
    }

    public function setConsumerKey($key){
        $this->consumer_key = $key;
    }

    public function setConsumerSecret($secret){
        $this->consumer_secret = $secret;
    }



    function call($command) {

        session_start();
        if(!isset($_GET['oauth_token']) && $_SESSION['state']==1) $_SESSION['state'] = 0;

        try {
            $oauth = new \OAuth($this->consumer_key,$this->consumer_secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
            $oauth->enableDebug();
            if(!isset($_GET['oauth_token']) && !$_SESSION['state']) {
                $request_token_info = $oauth->getRequestToken($this->request_url);
                $_SESSION['secret'] = $request_token_info['oauth_token_secret'];
                $_SESSION['state'] = 1;
                header('Location: '.$this->authorize_url.'?oauth_token='.$request_token_info['oauth_token']);
                exit;
            } else if($_SESSION['state']==1) {
                $oauth->setToken($_GET['oauth_token'],$_SESSION['secret']);
                $access_token_info = $oauth->getAccessToken($this->access_token_url);
                error_log("acc token info " . $access_token_info, 1,
                    "dustinmoorman@gmail.com");
                $_SESSION['state'] = 2;
                $_SESSION['token'] = $access_token_info['oauth_token'];
                $_SESSION['secret'] = $access_token_info['oauth_token_secret'];
            }

            $oauth->setToken($_SESSION['token'],$_SESSION['secret']);
            $oauth->fetch("{$this->api_url}$command");

            $json = json_decode($oauth->getLastResponse());

        } catch(\OAuthException $E) {
            return $E->lastResponse;
        }
        return $json;
    }
}