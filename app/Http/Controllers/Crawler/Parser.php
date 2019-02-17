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

    public $html = [];
    public $html_array = [];

    public function __construct($html) {
        $this->load($html);

        return false;
    }

    public function get() {
        if ($video = $this->getJson(21)) {
            return $video;
        }
    }

    private function load($html) {
        try {
            preg_match_all("/<\w(?:\".*?\"|'.*?'|.*?)*?>/", $html, $this->html);
            return $this->parse();
        } catch (\Exception $ex) {
//            throw new \Exception('error' . $ex->getMessage());
        }
    }

    public function getJson($index = null, $array = true) {
        $element = $this->getDom($index);
        if (isset($element['div']['attribute']['player_data'][0])) {
            $json = trim($element['div']['attribute']['player_data'][0], "'");
            return json_decode($json, $array);
        }
        return false;
    }

    public function getDom($index = null) {
        return $this->html_array[$index] ?? $this->html_array;
    }

    public function parse($first = 'html', $i = 0) {

        foreach ($this->html[0] as $tag) {
            $this->html_array[] = $this->clear($tag);
        }
    }

    public function clear($param) {
        $array = [];
        try {
            preg_match_all("/<([\w]*)\s(.*)>|([\w]*)/", $param, $tmp);
            if (!empty($tmp[2])) {
                preg_match_all("/([\w]*)=(\"(.*?)\"|'(.*?)')/", $tmp[2][0], $attr);
                $tags = trim($tmp[1][0], '"');
                $array[$tags] = [];
                foreach ($attr[1] as $key => $row) {
                    $array[$tags]['attribute'][trim($row, '"')] = explode(' ', trim($attr[2][$key], '"'));
                }
            } else {
                $array[$tags]['attribute'] = [];
            }
        } catch (Exception $ex) {
            
        }
        return $array;
    }

    public function attribute($param) {
        $attributes = [];
        if ($param->length) {
            foreach ($param as $name => $attr) {
                $attributes[$attr->nodeName] = explode(' ', $attr->value);
            }
        }
        return $attributes;
    }

    public function find($param = '') {

        preg_match_all("/(?<tag>[\w]{1,})((\[)(?<attr>[\w]*)=\"(?<value>.*)\"(\])){0,}/", $param, $tmp);
        if (empty($tmp['tag'])) {
            return [];
        }
        return array_filter($this->html_array, function($row) use ($tmp) {
            $tagname = $tmp['tag'][0];
            $attr = $tmp['attr'][0] ?? null;
            $value = $tmp['value'][0] ?? null;
            return isset($row[$tagname]) && isset($row[$tagname]['attribute'][$attr]) && in_array($value, $row[$tagname]['attribute'][$attr]);
        });
    }

}
