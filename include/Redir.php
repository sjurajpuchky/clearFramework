<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace cf;

/**
 * Description of Redir
 *
 * @author Zdena Puchká
 */
class Redir {
    private $url;
    function __construct($url) {
       $this->url = $url;
    }
    
    public function redir() {
        header("Location: ".$this->url);
    }
}
