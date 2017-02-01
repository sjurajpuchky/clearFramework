<?php

if (isset($_COOKIE["lang"])) {
    $language = $_COOKIE["lang"];
}
if (isset($_GET["lang"])) {
    $language = $_GET["lang"];
    setcookie("lang", $language);
}


$lang = array();

switch ($language) {
    case "cs_CZ":
        loadPropertyFile('../res/cs_CZ.lang');
        break;
    case "en_US":
        loadPropertyFile('../res/en_US.lang');
        break;
    default:
        loadPropertyFile('../res/cs_CZ.lang');
}

function loadPropertyFile($fileName) {
    global $lang;
    if (file_exists(__DIR__ . "/" . $fileName)) {
        $fp = fopen(__DIR__ . "/" . $fileName, "r");
        while (!feof($fp)) {
            $line = fgets($fp);
            $rec = explode("=", $line);
            $lang[$rec[0]] = chop($rec[1]);
        }
        fclose($fp);
    } else {
        error_log(__DIR__ . "/" . $fileName . " file not found.");
    }
}

function _l($id) {
    global $lang;
    if (isset($lang[$id])) {
        echo $lang[$id];
    } else {
        echo $id;
    }
}

function __l($id) {
    global $lang;
    if (isset($lang[$id])) {
        return $lang[$id];
    } else {
        return $id;
    }
}
