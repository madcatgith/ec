<?php

class GoogleTranslate
{

    private $_defUri = 'http://translate.google.com/translate_a/t?client=te&text={word}&sl={from}&tl={lang}&oe=utf-8';
    private $_uri    = '';

    public function __construct($from = 'ru')
    {
        $this->_defUri = strtr($this->_defUri, array('{from}' => urlencode($from)));
    }

    public function prepare($lang)
    {
        $this->_uri = strtr($this->_defUri, array('{lang}' => urlencode($lang)));
        return $this;
    }

    public function execute($word)
    {
        if ($this->_uri) {

            $header   = array();
            $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
            $header[] = "Accept-Language: uk,ru;q=0.8,en-us;q=0.5,en;q=0.3";
            $header[] = "Connection: keep-alive";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, strtr($this->_uri, array('{word}' => urlencode($word))));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:14.0) Gecko/20100101 Firefox/14.0.1");

            $body = curl_exec($ch);

            curl_close($ch);

            return (is_array($json = json_decode($body, true)) && isset($json['sentences'][0]['trans'])) ? $json['sentences'][0]['trans'] : '';

        } else {
            return null;
        }
    }

}
