<?php

namespace DotMaui;

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
     * @param type $apikey
     */
    public function __construct($apikey)
    {
        $this->apikey = $apikey;
    }


    /**
     *
     * @param type $url
     */
    public function MinifyHTMLFromUrl($url)
    {
        $this->data = sprintf('url=%s', $url);
        return $this->makeRequest('htmlmin', $this->data);
    }


    /**
     *
     * @param type $html
     * @return type
     */
    public function MinifyHTMLFromString($html)
    {
        $this->data = sprintf("html=%s", urlencode($html));
        return $this->makeRequest("htmlmin", $this->data);
    }


    /**
     *
     * @param type $url
     * @return type
     */
    public function MinifyCSSFromUrl($url)
    {
        $this->data = sprintf("url=%s", $url);
        return $this->makeRequest("cssmin", $this->data);
    }


    /**
     *
     * @param type $html
     * @return type
     */
    public function MinifyCSSFromString($html)
    {
        $this->data = sprintf("css=%s", urlencode($html));
        return $this->makeRequest("cssmin", $this->data);
    }


    /**
     *
     * @param type $url
     * @return type
     */
    public function MinifyJSFromUrl($url)
    {
        $this->data = sprintf("url=%s", $url);
        return $this->makeRequest("jsmin", $this->data);
    }


    /**
     *
     * @param type $html
     * @return type
     */
    public function MinifyJSFromString($html)
    {
        $this->data = sprintf("js=%s", urlencode($html));
        return $this->makeRequest("jsmin", $this->data);
    }


    /**
     *
     * @param \DotMaui\ImgResizerRequest $req
     * @param type $saveLocation
     * @throws Exception
     */
    public function saveImgResizedFromUrl($req, $saveLocation)
    {
        $this->data_img = sprintf("url=%s", urlencode($req->Url));

        if ($req->Url === NULL)
        {
            throw new Exception("Url required");
        }

        if ($req->Width == 0 && $req->Height == 0)
        {
            throw new Exception("Height or width required");
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
            throw new \Exception($this->response);
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
     * @param type $action
     * @param type $postData
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
            throw new \Exception($this->response);
        }

        return $this->responseFromServer;
    }

}
