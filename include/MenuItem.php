<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace cf;
/**
 * Description of MenuItem
 *
 * @author Juraj Puchký
 */
class MenuItem {
    private $menuId;
    private $label;
    function __construct($menuId,$label) {
        $this->menuId = $menuId;
        $this->label = $label;
    }
    public function __toString() {
        $ret = "<a class=\"menuItem\" href=\"?m=$this->menuId\">";
        if($_GET["m"]===$this->menuId) {
            $ret .= "<b>";
        }
        $ret .= $this->label;
        if($_GET["m"]===$this->menuId) {
            $ret .= "</b>";
        }
        $ret .= "</a>";
        return $ret;
    }
    function getMenuId() {
        return $this->menuId;
    }

    function getLabel() {
        return $this->label;
    }

    function setMenuId($menuId) {
        $this->menuId = $menuId;
    }

    function setLabel($label) {
        $this->label = $label;
    }


}
