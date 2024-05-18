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
        for($i = 1; $i <= 28; $i++){
            $options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $options .= ' selected';

            $options .= '>'.$i.'</option>';
        }
    return $options;
}

function dwsnap_getFooterHTML() {
    global $dwsnap_parity_disks;
    global $dwsnap_data_disks;
    global $dwsnap_lastsync;
    global $dwsnap_lastscrub;
    global $dwsnap_lastnodiff;
    global $dwsnap_sync_expires;
    global $dwsnap_scrub_expires;

    try {
        $snap_footer_html = "";
        $snap_ramdisk_util = trim(htmlspecialchars(shell_exec("df --output=pcent /var/lib/snapraid 2>/dev/null | tr -dc '0-9' 2>/dev/null") ?? "-"));
        if(!empty($dwsnap_parity_disks) && !empty($dwsnap_data_disks)) {
            $snap_all_disks_available = true;
            foreach ($dwsnap_parity_disks as $snap_parity_disk){
                $snap_disk_fs = htmlspecialchars(shell_exec("cat /etc/mtab 2>/dev/null | grep " . $snap_parity_disk[2] . " 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-");
                if($snap_disk_fs == "-") { $snap_all_disks_available = false; }
            }
            foreach ($dwsnap_data_disks as $snap_data_disk){
                $snap_disk_fs = htmlspecialchars(shell_exec("cat /etc/mtab 2>/dev/null | grep " . $snap_data_disk[2] . " 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-");
                if($snap_disk_fs == "-") { $snap_all_disks_available = false; }
            }
            if($snap_all_disks_available) {
                if($dwsnap_lastsync !== "-" && $dwsnap_lastscrub !== "-") {
                    $snap_lastsync_ago = dwsnap_time_ago($dwsnap_lastsync, $dwsnap_sync_expires);
                    $snap_lastscrub_ago = dwsnap_time_ago($dwsnap_lastscrub, $dwsnap_scrub_expires);
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        }
                    }
                    elseif($dwsnap_lastnodiff !== "-") {
                        $t_now = time();
                        $t_lastsync = strtotime($dwsnap_lastsync);
                        $t_lastnodiff = strtotime($dwsnap_lastnodiff);
                        $t_lastsync_diff = abs($t_now - $t_lastsync);
                        $t_lastnodiff_diff = abs($t_now - $t_lastnodiff);
                        $snap_lastnodiff_ago = dwsnap_time_ago($dwsnap_lastnodiff, $dwsnap_sync_expires);
                        if($t_lastnodiff_diff < $t_lastsync_diff) {
                            if (strpos($snap_lastnodiff_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-clock-o orange-text'></i></span>";
                                }
                            } else {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {   
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-check green-text'></i></span>";
                                }
                            }
                        } else {
                            if (strpos($snap_lastsync_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-clock-o orange-text'></i></span>";
                                }
                            } else {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-check green-text'></i></span>";
                                }
                            }
                        }
                    } else {
                        if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                            if($snap_ramdisk_util > 90) {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                            } else {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                            }
                        } else {
                            if (strpos($snap_lastsync_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-clock-o orange-text'></i></span>";
                                }
                            } else {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-check green-text'></i></span>";
                                }
                            }
                        }
                    }
                } elseif ($dwsnap_lastsync !== "-" && $dwsnap_lastscrub == "-") {
                    $snap_lastsync_ago = dwsnap_time_ago($dwsnap_lastsync, $dwsnap_sync_expires);
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        }
                    } else {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'>SnapRAID<i class='fa fa-clock-o orange-text'></i></span>";
                        }
                    }
                } elseif ($dwsnap_lastsync == "-" && $dwsnap_lastscrub !== "-") {
                    $snap_lastscrub_ago = dwsnap_time_ago($dwsnap_lastscrub, $dwsnap_scrub_expires);
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        }
                    } else {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-clock-o orange-text'></i></span>";
                        }
                    }
                } else {
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        }
                    } else {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / RAM Disk > 90% / Last Sync: Never / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation-triangle red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: Never / Last Scrub: Never'>SnapRAID<i class='fa fa-clock-o orange-text'></i></span>";
                        }
                    }
                }
            } else {
                if($snap_ramdisk_util > 90) {
                    $snap_footer_html = "<span class='snaptip' title='At least one disk is not online and/or mounted / RAM Disk > 90%'>SnapRAID<i class='fa fa-times red-text'></i></span>";
                } else {
                    $snap_footer_html = "<span class='snaptip' title='At least one disk is not online and/or mounted'>SnapRAID<i class='fa fa-times red-text'></i></span>";
                }
            }
        } else {
            if($snap_ramdisk_util > 90) {
                $snap_footer_html = "<span class='snaptip' title='No parity and/or data disks are configured / RAM Disk > 90%'>SnapRAID<i class='fa fa-times red-text'></i></span>";
            } else {
                $snap_footer_html = "<span class='snaptip' title='No parity and/or data disks are configured'>SnapRAID<i class='fa fa-times red-text'></i></span>";
            }
        }
        return $snap_footer_html;
    } catch (Throwable $e) { // For PHP 7
        return "";
    } catch (Exception $e) { // For PHP 5
        return "";
    }
}
?>
