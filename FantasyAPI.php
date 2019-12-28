<?php

include_once('constants.php');
include_once('helper.php');

class FantasyAPI
{

    private $credentials;
    private $auth_json_file;

    function __construct()
    {
        $this->auth_json_file = 'tmp/auth/auth_credentials_' . CONSUMER_KEY . '.json';
        if (file_exists($this->auth_json_file)) {
            $this->credentials = json_decode(file_get_contents($this->auth_json_file), TRUE);
        } else {
            $this->credentials = $this->initializeToken();
        }
        $this->credentials['expiry_time'] = filemtime($this->auth_json_file) + 3600;

        return $this;
    }

    function makeAPIRequest($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array('authorization: Bearer ' . $this->credentials['access_token'],
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
                'Cache-Control: no-cache',
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0'),
        ));
        $resp = curl_exec($curl);
        if (strpos($resp, "token_expired")) {
            if ($this->refreshToken()) {
                $this->makeAPIRequest($url);
            } else {
                print 'Trouble Getting Refresh Token...';
            }
        } else if (strpos($resp, "error")) {
            print "Error in making the API Request";
        } else {
            $xml = simplexml_load_string($resp, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            curl_close($curl);
            return json_decode($json, TRUE);
        }
        curl_close($curl);
        return FALSE;
    }

    function refreshToken()
    {
        // If our auth file doesn't exist, make one
        if (!file_exists($this->auth_json_file)) {
            return $this->initializeToken();
        }

        // If our token has not expired yet, return the existing auth
        if ($this->credentials['expiry_time'] > time()) {
            return json_decode($this->auth_json_file, TRUE);
        }

        $ch = curl_init();
        $post_values = [
            "redirect_uri" => "oob",
            "grant_type" => "refresh_token",
            "refresh_token" => $this->credentials['refresh_token']
        ];
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => AUTH_ENDPOINT,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode(CONSUMER_KEY . ":" . CONSUMER_SECRET),
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36'),
            CURLOPT_POSTFIELDS => http_build_query($post_values)
        ));
        $resp = curl_exec($ch);
        if (strpos($resp, "error") || empty($resp)) {
            curl_close($ch);
            print "Error getting Refresh Token";
            return FALSE;
        }
        curl_close($ch);
        writeToFile($resp, $this->auth_json_file);
        $this->credentials['expiry_time'] = time() + 3600;
        return json_decode($resp, TRUE);
    }

    function initializeToken()
    {
        $auth_code = readline('Go to: https://api.login.yahoo.com/oauth2/request_auth?client_id=' . CONSUMER_KEY . '&redirect_uri=oob&response_type=code&language=en-us and copy the code: ');
        $ch = curl_init();
        $post_values = [
            "client_id" => CONSUMER_KEY,
            "client_secret" => CONSUMER_SECRET,
            "redirect_uri" => "oob",
            "code" => $auth_code,
            "grant_type" => "authorization_code"
        ];
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => AUTH_ENDPOINT,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode(CONSUMER_KEY . ":" . CONSUMER_SECRET),
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Safari/537.36'),
            CURLOPT_POSTFIELDS => http_build_query($post_values)
        ));
        $resp = curl_exec($ch);
        if (empty($resp) || strpos($resp, 'error')) {
            curl_close($ch);
            print 'Error Initializing Token';
            return FALSE;
        }
        curl_close($ch);
        writeToFile($resp, $this->auth_json_file);
        $this->credentials['expiry_time'] = time() + 3600;
        return json_decode($resp, TRUE);
    }
}

