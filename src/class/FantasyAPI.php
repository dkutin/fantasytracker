<?php

include_once(__DIR__ . '/../helper.php');

/**
 * Class FantasyAPI
 */
class FantasyAPI
{

    /**
     * @var integer
     */
    private $uid;

    /**
     * FantasyAPI constructor.
     * @param $uid
     */
    function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    function makeAPIRequest($url)
    {
        $auth = getTokenCred($this->uid);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array('authorization: Bearer ' . $auth['access_token'],
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
                'Cache-Control: no-cache',
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            ),
        ));
        $resp = curl_exec($ch);
        curl_close($ch);
        if (strpos($resp, "token_expired") !== FALSE) {
            if ($this->refreshToken()) {
                return $this->makeAPIRequest($url);
            } else {
                print 'Trouble Getting Refresh Token...';
            }
        } else if (strpos($resp, "error") !== FALSE) {
            print "Error in making the API Request";
        } else if (strpos($resp, "Request denied") !== FALSE) {
            print "Request denied: " . $url;
        } else {
            $xml = simplexml_load_string($resp, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            return json_decode($json, TRUE);
        }
        return FALSE;
    }

    /**
     * @return bool
     */
    function refreshToken()
    {
        // If our auth file doesn't exist, make one
        if (!$auth = getTokenCred($this->uid)) {
            $this->initializeToken();
        }
        if(!$cred = getUserCred($this->uid)) {
            print 'User Consumer key and secret not init';
        }
        // If our token has not expired yet, return the existing auth
        if ($auth['expires'] > time()) {
            return TRUE;
        }

        $ch = curl_init();
        $post_values = [
            "redirect_uri" => "oob",
            "grant_type" => "refresh_token",
            "refresh_token" => $auth['refresh_token']
        ];
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.login.yahoo.com/oauth2/get_token',
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($cred['consumer_key'] . ":" . $cred['consumer_secret']),
                'Content-Type: application/x-www-form-urlencoded',
            ),
            CURLOPT_POSTFIELDS => http_build_query($post_values)
        ));
        $resp = curl_exec($ch);
        curl_close($ch);
        if (strpos($resp, "error") !== FALSE || empty($resp)) {
            print "Error getting Refresh Token";
            return FALSE;
        }
        $data = json_decode($resp, TRUE);
        $data['expires'] = time() + $data['expires_in'];
        updateTokenCred($this->uid, $data['access_token'], $data['refresh_token'], $data['expires'], $data['xoauth_yahoo_guid']);
        return TRUE;
    }

    function initializeToken()
    {
        $user = getUserCred($this->uid);
        $auth_code = readline('Go to: https://api.login.yahoo.com/oauth2/request_auth?client_id=' . $user['consumer_key'] . '&redirect_uri=oob&response_type=code&language=en-us and copy the code: ');
        $ch = curl_init();
        $post_values = [
            "client_id" => $user['consumer_key'],
            "client_secret" => $user['consumer_secret'],
            "redirect_uri" => "oob",
            "code" => $auth_code,
            "grant_type" => "authorization_code"
        ];
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.login.yahoo.com/oauth2/get_token',
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($user['consumer_key']. ":" . $user['consumer_secret']),
                'Content-Type: application/x-www-form-urlencoded',
            ),
            CURLOPT_POSTFIELDS => http_build_query($post_values)
        ));
        $resp = curl_exec($ch);
        curl_close($ch);
        if (strpos($resp, 'error') !== FALSE || empty($resp)) {
            print 'Error Initializing Token';
            return FALSE;
        }
        $data = json_decode($resp, TRUE);
        $data['expires'] = time() + $data['expires_in'];
        setTokenCred($data['access_token'], $data['refresh_token'], $data['expires'], $data['xoauth_yahoo_guid']);
        return TRUE;
    }
}

