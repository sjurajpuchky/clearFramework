<?php
namespace cf;

/**
 * Description of Tools
 *
 * @author Juraj Puchký
 */
class Tools {
   public static function generateFilePath($file,$basedir,$baseurl) {
       //var_dump($file);
       do {
       $toSum = date("Y.m.d h:i:sa");
       $toSum .= basename($file);
       $toSum .= microtime();
       $sum = md5($toSum);
       $imageFileType = pathinfo($file["name"],PATHINFO_EXTENSION);
       $filename = $basedir.$sum.".".$imageFileType;
       } while(file_exists($filename));
       if(copy($file["tmp_name"],$filename)) {
           return $baseurl.$sum.".".$imageFileType;
       }
   }
}