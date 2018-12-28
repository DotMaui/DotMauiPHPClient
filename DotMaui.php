<?php

namespace DotMaui;

require_once "Models/ImgResizerRequest.php";
require_once "Models/Paste.php";

/**
 *
 */
class Client
{
    private $ENDPOINT = "https://api.dotmaui.com/client/";
    private $CLIENT_VERSION = "1.0";
    private $apikey;
    private $data;
    private $data_img;
    private $data_request;
    private $url_request;
    private $ch;
    private $responseFromServer;
    private $response;
    private $result;


    /**
     *
     * @param string $apikey
     * @throws \Exception
     */
    public function __construct($apikey)
    {

        if (!function_exists('curl_version')) {
            throw new \Exception("cURL must be enabled.");
        }

        $this->apikey = $apikey;

    }


    /**
     *
     * @param string $url
     * @return mixed
     */
    public function MinifyHTMLFromUrl($url)
    {
        $this->data = sprintf('url=%s', $url);
        return $this->makeRequest('htmlmin', $this->data);
    }


    /**
     *
     * @param string $html
     * @return string
     */
    public function MinifyHTMLFromString($html)
    {
        $this->data = sprintf("html=%s", urlencode($html));
        return $this->makeRequest("htmlmin", $this->data);
    }


    /**
     *
     * @param string $url
     * @return string
     */
    public function MinifyCSSFromUrl($url)
    {
        $this->data = sprintf("url=%s", $url);
        return $this->makeRequest("cssmin", $this->data);
    }


    /**
     *
     * @param string $html
     * @return string
     */
    public function MinifyCSSFromString($html)
    {
        $this->data = sprintf("css=%s", urlencode($html));
        return $this->makeRequest("cssmin", $this->data);
    }


    /**
     *
     * @param string $url
     * @return string
     */
    public function MinifyJSFromUrl($url)
    {
        $this->data = sprintf("url=%s", $url);
        return $this->makeRequest("jsmin", $this->data);
    }


    /**
     *
     * @param string $html
     * @return string
     */
    public function MinifyJSFromString($html)
    {
        $this->data = sprintf("js=%s", urlencode($html));
        return $this->makeRequest("jsmin", $this->data);
    }

    /**
     * @param $url
     * @param $saveLocation
     * @param int $delay
     * @param bool $fullpage
     * @param string $viewport
     * @return bool
     * @throws \Exception
     */
    public function saveScreenshotFromUrl($url, $saveLocation, $delay = 0, $fullpage = false, $viewport = "1440x900")
    {

        if (empty($url))
        {
            throw new \Exception("Url required");
        }

        $params = sprintf("url=%s&delay=%u&fullpage=%s&viewport=%s", urlencode($url), $delay, ($fullpage ? "true" : "false"), $viewport);

        $this->data_request = sprintf("&apikey=%s&%s", $this->apikey, $params);
        $this->url_request  = sprintf("%s%s/%s/", $this->ENDPOINT, $this->CLIENT_VERSION, "capture");
        $this->result = true;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url_request);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_request);
        curl_setopt($this->ch, CURLOPT_ENCODING, "UTF-8");
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);

        $this->responseFromServer = curl_exec($this->ch);
        $this->response = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        curl_close($this->ch);

        if ($this->response != 200)
        {
            throw new \Exception($this->responseFromServer);
        }

        $fp = fopen($saveLocation, "w");

        if (!$fp)
        {
            $this->result = false;
            throw new \Exception('File open failed.');
        }

        fwrite($fp, $this->responseFromServer);
        fclose($fp);

        return $this->result;
    }

    /**
     *
     * @param \DotMaui\ImgResizerRequest $req
     * @param string $saveLocation
     * @return bool
     * @throws \Exception
     */
    public function saveImgResizedFromUrl($req, $saveLocation)
    {
        $this->data_img = sprintf("url=%s", urlencode($req->Url));

        if (empty($req->Url))
        {
            throw new \Exception("Url required");
        }

        if ($req->Width == 0 && $req->Height == 0)
        {
            throw new \Exception("Height or width required");
        }

        if ($req->Width != 0)
        {
            $this->data_img .= sprintf("&width=%s", $req->Width);
        }

        if ($req->Height != 0)
        {
            $this->data_img .= sprintf("&height=%s", $req->Height);
        }

        $this->data_request = sprintf("&apikey=%s&%s", $this->apikey, $this->data_img);
        $this->url_request = sprintf("%s%s/%s/", $this->ENDPOINT, $this->CLIENT_VERSION, "imgresize");
        $this->result = true;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url_request);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_request);
        curl_setopt($this->ch, CURLOPT_ENCODING, "UTF-8");
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);

        $this->responseFromServer = curl_exec($this->ch);
        $this->response = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        curl_close($this->ch);

        if ($this->response != 200)
        {
            throw new \Exception($this->responseFromServer);
        }


        $fp = fopen($saveLocation, "w");
        if (!$fp)
        {
            $this->result = false;
            throw new \Exception('File open failed.');
        }

        fwrite($fp, $this->responseFromServer);
        fclose($fp);

        return $this->result;

    }

    public function ListingPastes($offset = 0, $limit = 500, $beauty = false)
    {
        $beauty_param = ($beauty) ? "true" : "false";
        $this->data = sprintf("cmd=ls&limit=%d,%d&beauty=%s", $offset, $limit, $beauty_param);
        return $this->makeRequest("pastebin", $this->data);
    }

    /**
     * @param Paste $paste
     * @return string
     */
    public function CreatePaste($paste)
    {

        $data_string = "text=%s&title=%s&language=%s&theme=%s&exposure=%s&author=%s&expiration=%s";
        $this->data = sprintf($data_string, $paste->Text, $paste->Title, $paste->Language, $paste->Theme, $paste->Exposure, $paste->Author, $paste->Expiration);
        return $this->makeRequest("pastebin/new", $this->data);

    }

    /**
     * @param string $uid
     * @return string
     */
    public function DeletePaste($uid)
    {

        $this->data = sprintf("uid=%s", $uid);
        return $this->makeRequest("pastebin/del", $this->data);

    }

    /**
     * @param string $uid
     * @return string
     */
    public function GetCompletePaste($uid) {

        $this->data = sprintf("uid=%s", $uid);
        return $this->makeRequest("pastebin/get", $this->data);

    }


    /**
     *
     * @param string $action
     * @param string $postData
     * @return mixed
     * @throws \Exception
     */
    private function makeRequest($action, $postData)
    {
        $this->data_request = sprintf("apikey=%s&%s", $this->apikey, $postData);
        $this->url_request = sprintf("%s%s/%s/", $this->ENDPOINT, $this->CLIENT_VERSION, $action);

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url_request);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_request);
        curl_setopt($this->ch, CURLOPT_ENCODING, "UTF-8");
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);

        $this->responseFromServer = curl_exec($this->ch);
        $this->response = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        curl_close($this->ch);

        if ($this->response != 200)
        {
            throw new \Exception($this->responseFromServer);
        }

        return $this->responseFromServer;
    }

}