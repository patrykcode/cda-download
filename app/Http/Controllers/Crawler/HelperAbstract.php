<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Crawler;

class HelperAbstract {

    public $url = '';
    public $header_result = [];
    public $login = 'l';
    public $password = '';
    public $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36';
    public $cookie_file;
    public $save_path;
    public $counter = 1;
    public $post;
    public $redirect = '';

    public function __construct() {
        $this->cookie_file = public_path('.temp/cookie.txt');
        $this->save_path = public_path('.temp/');
        $this->mkdir($this->save_path);
    }

    public function getRegex($regex = [], $text) {

        $l = $regex[0];
        $r = $regex[1];
        $reg = "/$l(.*)$r/";
       
        $match = [];
        preg_match($reg, $text, $match);
        return $match ? $match[1] : null;
    }

    public function _createPostString($aPostFields) {
        foreach ($aPostFields as $key => $value) {
            $aPostFields[$key] = urlencode($key) . '=' . urlencode($value);
        }
        return implode('&', $aPostFields);
    }

    public function _saveHtmlPage($url, $text, $dir = null) {

        try {
            $data = $text;
            $dir = $this->mkdir($this->save_path . ($dir ?? date('YmdH')));
            $url_save = $dir . '/' . md5($url) . '.html';
            file_put_contents($url_save, $data);
        } catch (Exception $ex) {
            file_put_contents($this->save_path . 'log', date('Y-m-d H:i:s') . ' -- [error] - bład zapisu strony:' . $url . "\n" . $ex->getMessage());
        }
    }

    public function mkdir($path) {
        if (!is_dir($path)) {
            mkdir($path, 0700);
        }
        return $path;
    }

}
