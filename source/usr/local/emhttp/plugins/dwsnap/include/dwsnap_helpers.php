<?
/* Copyright Derek Macias (parts of code from NUT package)
 * Copyright macester (parts of code from NUT package)
 * Copyright gfjardim (parts of code from NUT package)
 * Copyright SimonF (parts of code from NUT package)
 * Copyright Dan Landon (parts of code from Web GUI)
 * Copyright Bergware International (parts of code from Web GUI)
 * Copyright Lime Technology (any and all other parts of Unraid)
 *
 * Copyright desertwitch (as author and maintainer of this file)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 */

function dwsnap_humanFileSize($sizeObj,$unit="") {
    try {
        $size = intval($sizeObj);
        if($size) {
            if( (!$unit && $size >= 1000000000000) || $unit == "TB")
                return rtrim(rtrim(number_format(($size/1000000000000),2), "0"), ".") . " TB";
            if( (!$unit && $size >= 1000000000) || $unit == "GB")
                return rtrim(rtrim(number_format(($size/1000000000),2), "0"), ".") . " GB";
            if( (!$unit && $size >= 1000000) || $unit == "MB")
                return rtrim(rtrim(number_format(($size/1000000),2), "0"), ".") . " MB";
            if( (!$unit && $size >= 1000) || $unit == "KB")
                return rtrim(rtrim(number_format(($size/1000),2), "0"), ".") . " KB";
            return number_format($size) . " B";
        } else {
            return "-";
        }
    } catch (Throwable $e) { // For PHP 7
        return "-";
    } catch (Exception $e) { // For PHP 5
        return "-";
    }
}

function dwsnap_get_conf_files() {
    $files = glob("/boot/config/plugins/dwsnap/config/*.conf") ?? [];
    if(!empty($files)) {
        $files_bak = $files;
        try {
            usort($files, function ($a, $b) {
                if ($a != "/boot/config/plugins/dwsnap/config/primary.conf" && $b == "/boot/config/plugins/dwsnap/config/primary.conf") {
                    return 1;
                } else {
                    return 0;
                }
            });
        } catch (Throwable $e) { // For PHP 7
            $files = $files_bak;
        } catch (Exception $e) { // For PHP 5
            $files = $files_bak;
        }
    }
    return $files;
}

function dwsnap_time_ago($oldTime, $alertThreshold = 7) {
    try {
        if(!is_int($alertThreshold)) {
            $alertThreshold = intval($alertThreshold);
        }
    } catch (Throwable $e) { // For PHP 7
        $alertThreshold = 7;
    } catch (Exception $e) { // For PHP 5
        $alertThreshold = 7;
    }
    try {
        $timeCalc = strtotime("now") - strtotime($oldTime);
        if ($timeCalc >= (60*60*24*30*12*2)){
            $timeCalc = "<span class='orange-text'>" . intval($timeCalc/60/60/24/30/12) . " years ago</span>";
        }else if ($timeCalc >= (60*60*24*30*12)){
            $timeCalc = "<span class='orange-text'>" . intval($timeCalc/60/60/24/30/12) . " year ago</span>";
        }else if ($timeCalc >= (60*60*24*30*2)){
            $timeCalc = "<span class='orange-text'>" . intval($timeCalc/60/60/24/30) . " months ago</span>";
        }else if ($timeCalc >= (60*60*24*30)){
            $timeCalc = "<span class='orange-text'>" . intval($timeCalc/60/60/24/30) . " month ago</span>";
        }else if ($timeCalc >= (60*60*24*2)){
            if(($timeCalc/60/60/24) > $alertThreshold) {
                $timeCalc = "<span class='orange-text'>" . intval($timeCalc/60/60/24) . " days ago</span>";
            } else {
                $timeCalc = "<span class='green-text'>" . intval($timeCalc/60/60/24) . " days ago</span>";
            }
        }else if ($timeCalc >= (60*60*24)){
            $timeCalc = "<span class='green-text'>" . "1 day ago</span>";
        }else if ($timeCalc >= (60*60*2)){
            $timeCalc = "<span class='green-text'>" . intval($timeCalc/60/60) . " hours ago</span>";
        }else if ($timeCalc >= (60*60)){
            $timeCalc = "<span class='green-text'>" . intval($timeCalc/60/60) . " hour ago</span>";
        }else if ($timeCalc >= 60*2){
            $timeCalc = "<span class='green-text'>" . intval($timeCalc/60) . " minutes ago</span>";
        }else if ($timeCalc >= 60){
            $timeCalc = "<span class='green-text'>" . intval($timeCalc/60) . " minute ago</span>";
        }else if ($timeCalc > 0){
            $timeCalc = "<span class='green-text'>" . $timeCalc . " seconds ago</span>";
        }
        return $timeCalc;
    } catch (Throwable $e) { // For PHP 7
        return false;
    } catch (Exception $e) { // For PHP 5
        return false;
    }
}

function dwsnap_hour_options($time){
    $options = '';
        for($i = 0; $i <= 23; $i++){
            $options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $options .= ' selected';

            $options .= '>'.$i.':00</option>';
        }
    return $options;
}

function dwsnap_dom_options($time){
    $options = '';
        for($i = 1; $i <= 31; $i++){
            $options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $options .= ' selected';

            $options .= '>'.$i.'</option>';
        }
    return $options;
}

function dwsnap_threshold_options($time){
    $options = '';
        for($i = 2; $i <= 28; $i++){
            $options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $options .= ' selected';

            $options .= '>'.$i.'</option>';
        }
    return $options;
}

function dwsnap_array_options($selected){
    $files = dwsnap_get_conf_files();
    $options = '';
    foreach ($files as $file) {
            $bfile = basename($file,".conf");
            $options .= '<option value="'.$bfile.'"';
            if($bfile === $selected)
                $options .= ' selected';

            $options .= '>'.$bfile.'</option>';
        }
    return $options;
}
?>
