<?php

namespace cf;

class FormField {

    private $fieldName;
    private $fieldType;
    private $label;
    private $required = false;
    private $default;
    private $onchange;

    function __construct($fieldName, $fieldType, $label, $required = false, $default = "", $onchange = "") {
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->label = $label;
        $this->required = $required;
        $this->default = $default;
        $this->onchange = $onchange;
    }

    public function __toString() {
        $ret = "<tr>";
        $ret .= "<td class=\"formLabel\">";
        switch ($this->fieldType) {
            case "submit":
                break;
            default:
                $ret .= "<label for=\"" . $this->fieldName . "\">" . $this->label . "</label>";
        }
        $ret .= "</td>";
        $ret .= "<td class=\"formField\">";
        switch ($this->fieldType) {
            case "submit":
                $ret .= "<input id=\"" . $this->fieldName . "\" name=\"" . $this->fieldName . "\" type=\"" . $this->fieldType . "\" value=\"" . $this->label . "\">";
                break;
            default:
                $ret .= "<input id=\"" . $this->fieldName . "\" name=\"" . $this->fieldName . "\" type=\"" . $this->fieldType . "\" " . ($this->required ? "required" : "") . " value=\"" . $this->default . "\" onchange=\"$this->onchange\">";
        }
        $ret .= "</td>";
        $ret .= "</tr>";
        return $ret;
    }

}
