<?php
namespace cf;

/**
 * Description of Hidden
 *
 * @author Juraj Puchký
 */
class Hidden {
      private $fieldName;
      private $value;
      function __construct($fieldName,$value) {
          $this->fieldName = $fieldName;
          $this->value = $value;
      }
      public function __toString() {
          $ret = "<input type=\"hidden\" name=\"".$this->fieldName."\" value=\"".$this->value."\">";
          return $ret;
      }
}
