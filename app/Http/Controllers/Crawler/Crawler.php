<?php

namespace App\Http\Controllers\Crawler;



class Crawler {

    public function __construct() {
    
        $obj = new Cda();
        $obj->run();
    }

}
