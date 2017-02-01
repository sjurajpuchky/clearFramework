<?php


class Ad {

    public static function getKeyWordsFromRequest() {
        $gkeywords = explode(",", urldecode($_GET["keywords"]));
        $gtitles = explode(";", urldecode($_GET["titles"]));

        $keywords = array();
        $nkeywords = array();
        foreach ($gkeywords as $keyword) {
            if (strlen($keyword) > 3) {
                $keywords[strtoupper(trim($keyword, " "))] = strtoupper(trim($keyword, " "));
                $nkeywords[strtoupper(trim($key, " "))] ++;
            }
        }
        foreach ($gtitles as $title) {
            $title = str_replace(",", " ", $title);
            $title = str_replace("  ", " ", $title);
            $title = str_replace("  ", " ", $title);
            $titlekeys = explode(" ", $title);

            foreach ($titlekeys as $key) {
                if (strlen($key) > 3) {
                    $nkeywords[strtoupper(trim($key, " "))] ++;
                }
            }
        }
        foreach ($nkeywords as $key => $value) {
            if ($value >= 2) {
                $keywords[$key] = $key;
            }
        }
        return $keywords;
    }

    /**
     * Make view counter record
     * @global type $pwdb
     * @param type $ban_id banner id
     * @param type $format banner_468x60
     * @param type $lkeywords keywords
     * @param type $type rotator|bestprice
     */
    public static function makeViewStats($ban_id, $format, $lkeywords, $type, $url,$ad_id) {
        global $pwdb;
        $assoc = array("ipaddress" => $_SERVER["REMOTE_ADDR"],
            "ban_id" => $ban_id,
            "format" => $format,
            "requested" => "NOW()",
            "ref" => $lkeywords,
            "type" => $type,
            "source" => "view",
            "url" => $url,
            "ad_id" => $ad_id);
        $pwdb->insert("payway_counter", $assoc);
    }

    /**
     * Make click counter record
     * @global type $pwdb
     * @param type $ban_id banner id
     * @param type $format banner_468x60
     * @param type $lkeywords keywords
     * @param type $type rotator|bestprice
     */
    public static function makeClickStats($ban_id, $format, $lkeywords, $type = "rotator", $url,$ad_id) {
        global $pwdb;
        $assoc = array("ipaddress" => $_SERVER["REMOTE_ADDR"],
            "ban_id" => $ban_id,
            "format" => $format,
            "requested" => "NOW()",
            "ref" => $lkeywords,
            "type" => $type,
            "source" => "click",
            "url" => $url,
            "ad_id" => $ad_id);
        $pwdb->insert("payway_counter", $assoc);
    }

    public static function makeBannerCount($ad_id, $counter = 0) {
        global $pwdb;
        $assoc = array("counter" => $counter + 1);
        $pwdb->update("payway_ads", $assoc, "ad_id='" . $ad_id . "'");
    }

    public static function makeLinkCount($link_id, $counter = 0) {
        global $pwdb;
        $assoc = array("counter" => $counter + 1);
        $pwdb->update("payway_link", $assoc, "link_id='" . $link_id . "'");
    }

}
