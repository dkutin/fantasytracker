<?php

include_once(__DIR__ . '/constants.php');
include_once(__DIR__ . '/helper.php');

/**
 * Class FantasyAPI
 */
class FantasyAPI
{

    /**
     * @var bool|mixed
     */
    private $credentials;
    /**
     * @var string
     */
    private $auth_json_file;

    /**
     * FantasyAPI constructor.
     */
    function __construct()
    {
        $this->auth_json_file = TMP_AUTH_DIR . 'auth_credentials_' . CONSUMER_KEY . '.json';
        if (file_exists($this->auth_json_file)) {
            $this->credentials = json_decode(file_get_contents($this->auth_json_file), TRUE);
        } else {
            $this->credentials = $this->initializeToken();
        }
        $this->credentials['expiry_time'] = filemtime($this->auth_json_file) + 3600;

        return $this;
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    function makeAPIRequest($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array('authorization: Bearer ' . $this->credentials['access_token'],
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
        if (!file_exists($this->auth_json_file)) {
            $this->initializeToken();
        }

        // If our token has not expired yet, return the existing auth
        if ($this->credentials['expiry_time'] > time()) {
            return TRUE;
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
            ),
            CURLOPT_POSTFIELDS => http_build_query($post_values)
        ));
        $resp = curl_exec($ch);
        curl_close($ch);
        if (strpos($resp, "error") !== FALSE || empty($resp)) {
            print "Error getting Refresh Token";
            return FALSE;
        }
        writeToFile($resp, $this->auth_json_file);
        $this->credentials = json_decode($resp, TRUE);
        $this->credentials['expiry_time'] = time() + 3600;
        return TRUE;
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
            ),
            CURLOPT_POSTFIELDS => http_build_query($post_values)
        ));
        $resp = curl_exec($ch);
        curl_close($ch);
        if (strpos($resp, 'error') !== FALSE || empty($resp)) {
            print 'Error Initializing Token';
            return FALSE;
        }
        writeToFile($resp, $this->auth_json_file);
        $this->credentials = json_decode($resp, TRUE);
        $this->credentials['expiry_time'] = time() + 3600;
        return TRUE;
    }
}

