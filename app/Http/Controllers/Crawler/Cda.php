<?php

namespace App\Http\Controllers\Crawler;

use App\Http\Controllers\Crawler\Parser;

class Cda extends HelperAbstract {

    public function __construct() {

        $this->cookie_file = public_path('.temp/cookie.txt');
        $this->save_path = public_path('.temp/');
        $this->mkdir($this->save_path);
    }

    public function run($link = 'https://www.cda.pl/video/268080968') {

        $url = $this->parseUrl($link);

        $page = $this->getPage("https://ebd.cda.pl/620x386/" . $url['id'] . "?" . $url['query'] ?? 'wersja=480p', 'https://cda.pl', null, 1);

        if ($page['result']["http_code"] == '200') {
            $parse = new Parser($page['response']);
            $quanlity = $parse->find('a[class="quality-btn"]');
            $video = $parse->get();
            return $video;
        }
    }

    public function index($link) {

        $url = $this->parseUrl($link);
  
        $page = $this->getPage("https://ebd.cda.pl/620x386/" . $url['id'] . "?" . ($url['query'] ?? 'wersja=480p'), 'https://cda.pl', null, 1);

        if ($page['result']["http_code"] == '200') {
            $parse = new Parser($page['response']);
            $video = $parse->get();
            $quality = $parse->find('a[class="quality-btn"]');
            return response()->json(compact('video', 'quality'));
        } else {
            $error = true;
            return response()->json(compact('error'));
        }
    }

    public function download(\Illuminate\Http\Request $request) {

        $link = $request->input('url');
        if (strpos($link, 'https://www.cda.pl/video/') !== FALSE) {
            return $this->index($link);
        } else {
            $error = true;
            return response()->json(compact('error'));
        }
    }

    public function parseUrl($url) {
        $url = parse_url($url);
        $tmp = explode('/', $url['path']);
        $url['id'] = end($tmp);
        return $url;
    }

    public function getPage($url = '', $refferer = '', $post = null, $follow = 0, $ajax = 0, $save_path = '.pages', $ssl_ver = 0) {
        $result = [];
        $response = null;
        $file = $this->save_path . $save_path . '/' . md5($url) . '.html';

        if (file_exists($file)) {
            $result['http_code'] = 200;
            $response = file_get_contents($file);
        } else {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_REFERER, $refferer);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_ver);
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'RSA');
                curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                $header = array('Accept-Language: pl-pl,pl;q=0.5');

                if (!is_null($post)) {
                    if ($ajax) {
                        $header = array("X-Requested-With: XMLHttpRequest", "Content-Type: application/json; charset=utf-8", 'Accept-Language: pl-pl,pl;q=0.5');
                    } else {
                        $header = array("Content-Type:application/x-www-form-urlencoded");
                    }
                    if (!empty($post)) {
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_createPostString($post));
                    }
                }
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);
                curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);

                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

                $response = curl_exec($ch);
                $this->_saveHtmlPage($url, $response, $save_path);

                $this->header_result['all'] = curl_getinfo($ch);

                $this->header_result['size'] = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $this->header_result['head'] = curl_setopt($ch, CURLOPT_HEADER, 1);
                $this->header_result['header'] = substr($response, 0, $this->header_result['size']);
//            $this->header_result['body'] = substr($response, $this->header_result['size']);
                $this->header_result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->header_result['last_url'] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                $redirect = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
                $this->header_result['redirect_url'] = $redirect ? $redirect : null;
                $result = $this->header_result;

                curl_close($ch);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return compact('result', 'response');
    }

}
