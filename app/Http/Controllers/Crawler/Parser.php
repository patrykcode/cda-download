<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Crawler;

/**
 * Description of Parser
 *
 * @author Patryk
 */
class Parser {

    public $html_array = [];
    public $tag = [
        'name' => 'div',
        'attrs' => [['player_data' => '\'(.*?)\'']]
    ];
    public $data = [];
    public $qualities = [];
    public $html = [];

    public function __construct($html) {
        $this->html = $html;
        $data = $this->getStringData($this->findTag($this->tag));
        $this->data = $this->decode($data);
        $this->qualities = $this->findTag([
            'name' => 'a',
            'attrs' => [
                ['data-quality' => '".*?"'],
                ['href' => '"(.*?)"']
            ]
        ]);
        return $this;
    }

    private function decode($string = '') {
        return json_decode($string, true);
    }

    public function getQuality() {
        $array = [];
        if (isset($this->qualities[1]) && isset($this->qualities[2])) {
            foreach ($this->qualities[1] as $key => $quality) {
                $array[$this->qualities[2][$key]] = $quality;
            }
        }

        return $array;
    }

    private function getStringData($data) {
        return isset($data[1][0]) ? $data[1][0] : null;
    }

    public function getData() {
        return $this->data;
    }

    public function findTag($tag = [], $find = '.*?>(.*?)<') {
        $match = [];
        $attrs = '';
        foreach ($tag['attrs'] as $attr) {

            $k = key($attr);

            $attrs .= $k . '=' . $attr[$k] . '\s*';
        }
        $this->regex = '/<' . $tag['name'] . '.*?' . $attrs . $find . '\/' . $tag['name'] . '>/s';

        preg_match_all($this->regex, $this->html, $match);
//        dd($match);
        return !empty($match) ? $match : null;
    }

}
