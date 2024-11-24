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

function dwsnap_humanFileSize($sizeObj, $targetDecs = 2, $format1024 = false, $threshold = 0.98, $unit = "") {
    try {
        $size = floatval($sizeObj);
        if ($size < 0) return "0 B";

        $base = $format1024 ? 1024 : 1000;

        if ($size) {
            if ((!$unit && $size >= $base ** 4) || $unit == "TB") {
                $result = $size / ($base ** 4);
                if ($result >= $base * $threshold && !$unit) {
                    return rtrim(rtrim(number_format(($size / ($base ** 5)), $targetDecs), "0"), ".") . ($format1024 ? " PiB" : " PB");
                }
                return rtrim(rtrim(number_format($result, $targetDecs), "0"), ".") . ($format1024 ? " TiB" : " TB");
            }
            if ((!$unit && $size >= $base ** 3) || $unit == "GB") {
                $result = $size / ($base ** 3);
                if ($result >= $base * $threshold && !$unit) {
                    return rtrim(rtrim(number_format(($size / ($base ** 4)), $targetDecs), "0"), ".") . ($format1024 ? " TiB" : " TB");
                }
                return rtrim(rtrim(number_format($result, $targetDecs), "0"), ".") . ($format1024 ? " GiB" : " GB");
            }
            if ((!$unit && $size >= $base ** 2) || $unit == "MB") {
                $result = $size / ($base ** 2);
                if ($result >= $base * $threshold && !$unit) {
                    return rtrim(rtrim(number_format(($size / ($base ** 3)), $targetDecs), "0"), ".") . ($format1024 ? " GiB" : " GB");
                }
                return rtrim(rtrim(number_format($result, $targetDecs), "0"), ".") . ($format1024 ? " MiB" : " MB");
            }
            if ((!$unit && $size >= $base) || $unit == "KB") {
                $result = $size / $base;
                if ($result >= $base * $threshold && !$unit) {
                    return rtrim(rtrim(number_format(($size / ($base ** 2)), $targetDecs), "0"), ".") . ($format1024 ? " MiB" : " MB");
                }
                return rtrim(rtrim(number_format($result, $targetDecs), "0"), ".") . ($format1024 ? " KiB" : " KB");
            }
            return number_format($size) . " B";
        } else {
            return "-";
        }
    } catch (\Throwable $t) {
        return "-";
    } catch (\Exception $e) {
        return "-";
    }
}

function dwsnap_get_conf_files() {
    $snap_files = glob("/boot/config/plugins/dwsnap/config/*.conf") ?? [];
    if(!empty($snap_files)) {
        $snap_files_bak = $snap_files;
        try {
            usort($snap_files, function ($a, $b) {
                if ($a != "/boot/config/plugins/dwsnap/config/primary.conf" && $b == "/boot/config/plugins/dwsnap/config/primary.conf") {
                    return 1;
                } else {
                    return 0;
                }
            });
        } catch (\Throwable $t) {
            $snap_files = $snap_files_bak;
        } catch (\Exception $e) {
            $snap_files = $snap_files_bak;
        }
    }
    return $snap_files;
}

function dwsnap_time_ago($oldTime, $alertThreshold = 7) {
    try {
        if(!is_int($alertThreshold)) {
            $alertThreshold = intval($alertThreshold);
        }
    } catch (\Throwable $t) {
        $alertThreshold = 7;
    } catch (\Exception $e) {
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
            $timeCalc = "<span class='green-text'>1 day ago</span>";
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
    } catch (\Throwable $t) {
        error_log($t);
        return false;
    } catch (\Exception $e) {
        error_log($e);
        return false;
    }
}

function dwsnap_consider_nodiff($inputHtml, $lastNoDiff, $lastSync, $syncExpires) {
    $outputHtml = $inputHtml;
    $outputHtml_onErr = $inputHtml;
    try {
        $st_now = time();
        $st_lastsync = strtotime($lastSync);
        $st_lastnodiff = strtotime($lastNoDiff);
        $st_lastsync_diff = abs($st_now - $st_lastsync);
        $st_lastnodiff_diff = abs($st_now - $st_lastnodiff);
        $snap_lastnodiff_ago = dwsnap_time_ago($lastNoDiff, $syncExpires);
        if($st_lastnodiff_diff < $st_lastsync_diff) {
            if (strpos($snap_lastnodiff_ago, "orange-text") !== false) {
                $outputHtml = str_replace("green-text", "orange-text", $inputHtml);
            } else {
                $outputHtml = str_replace("orange-text", "green-text", $inputHtml);
            }
        }
        return $outputHtml;
    } catch (\Throwable $t) {
        return $outputHtml_onErr;
    } catch (\Exception $e) {
        return $outputHtml_onErr;
    }
}

function dwsnap_hour_options($time){
    $snap_options = '';
        for($i = 0; $i <= 23; $i++){
            $snap_options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $snap_options .= ' selected';

            $snap_options .= '>'.$i.':00</option>';
        }
    return $snap_options;
}

function dwsnap_dom_options($time){
    $snap_options = '';
        for($i = 1; $i <= 31; $i++){
            $snap_options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $snap_options .= ' selected';

            $snap_options .= '>'.$i.'</option>';
        }
    return $snap_options;
}

function dwsnap_threshold_options($time){
    $snap_options = '';
        for($i = 2; $i <= 28; $i++){
            $snap_options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $snap_options .= ' selected';

            $snap_options .= '>'.$i.'</option>';
        }
    return $snap_options;
}

function dwsnap_array_options($selected){
    $snap_files = dwsnap_get_conf_files();
    $snap_options = '';
    foreach ($snap_files as $snap_file) {
            $snap_file_base = basename($snap_file,".conf");
            $snap_options .= '<option value="'.$snap_file_base.'"';
            if($snap_file_base === $selected)
                $snap_options .= ' selected';

            $snap_options .= '>'.$snap_file_base.'</option>';
        }
    return $snap_options;
}
?>
