<?
/* Copyright Derek Macias (parts of code from NUT package)
 * Copyright macester (parts of code from NUT package)
 * Copyright gfjardim (parts of code from NUT package)
 * Copyright SimonF (parts of code from NUT package)
 * Copyright desertwitch
 *
 * Copyright Dan Landon
 * Copyright Bergware International
 * Copyright Lime Technology
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 */
$dwsnap_cfg  = parse_ini_file("/boot/config/plugins/dwsnap/dwsnap.cfg");
$dwsnap_prio = trim(isset($dwsnap_cfg['PRIO']) ? htmlspecialchars($dwsnap_cfg['PRIO']) : 'disable');
$dwsnap_footer = trim(isset($dwsnap_cfg['FOOTER']) ? htmlspecialchars($dwsnap_cfg['FOOTER']) : 'disable');
$dwsnap_screenimg = trim(isset($dwsnap_cfg['SCREENIMG']) ? htmlspecialchars($dwsnap_cfg['SCREENIMG']) : 'disable');
$dwsnap_cron = trim(isset($dwsnap_cfg['CRON']) ? htmlspecialchars($dwsnap_cfg['CRON']) : 'disable');
$dwsnap_cronhour = trim(isset($dwsnap_cfg['CRONHOUR']) ? htmlspecialchars($dwsnap_cfg['CRONHOUR']) : '1');
$dwsnap_crondow = trim(isset($dwsnap_cfg['CRONDOW']) ? htmlspecialchars($dwsnap_cfg['CRONDOW']) : '0');
$dwsnap_crondom = trim(isset($dwsnap_cfg['CRONDOM']) ? htmlspecialchars($dwsnap_cfg['CRONDOM']) : '1');
$dwsnap_startnotify = trim(isset($dwsnap_cfg['STARTNOTIFY']) ? htmlspecialchars($dwsnap_cfg['STARTNOTIFY']) : 'disable');
$dwsnap_finishnotify = trim(isset($dwsnap_cfg['FINISHNOTIFY']) ? htmlspecialchars($dwsnap_cfg['FINISHNOTIFY']) : 'enable');
$dwsnap_touch = trim(isset($dwsnap_cfg['TOUCH']) ? htmlspecialchars($dwsnap_cfg['TOUCH']) : 'enable');
$dwsnap_diff = trim(isset($dwsnap_cfg['DIFF']) ? htmlspecialchars($dwsnap_cfg['DIFF']) : 'enable');
$dwsnap_sync = trim(isset($dwsnap_cfg['SYNC']) ? htmlspecialchars($dwsnap_cfg['SYNC']) : 'enable');
$dwsnap_prehash = trim(isset($dwsnap_cfg['PREHASH']) ? htmlspecialchars($dwsnap_cfg['PREHASH']) : 'disable');
$dwsnap_forcezero = trim(isset($dwsnap_cfg['FORCEZERO']) ? htmlspecialchars($dwsnap_cfg['FORCEZERO']) : 'disable');
$dwsnap_scrub = trim(isset($dwsnap_cfg['SCRUB']) ? htmlspecialchars($dwsnap_cfg['SCRUB']) : 'enable');
$dwsnap_scrubpercent = trim(isset($dwsnap_cfg['SCRUBPERCENT']) ? htmlspecialchars($dwsnap_cfg['SCRUBPERCENT']) : '5');
$dwsnap_scrubage = trim(isset($dwsnap_cfg['SCRUBAGE']) ? htmlspecialchars($dwsnap_cfg['SCRUBAGE']) : '10');
$dwsnap_scrubnew = trim(isset($dwsnap_cfg['SCRUBNEW']) ? htmlspecialchars($dwsnap_cfg['SCRUBNEW']) : 'disable');
$dwsnap_added = trim(isset($dwsnap_cfg['ADDED']) ? htmlspecialchars($dwsnap_cfg['ADDED']) : '-1');
$dwsnap_deleted = trim(isset($dwsnap_cfg['DELETED']) ? htmlspecialchars($dwsnap_cfg['DELETED']) : '-1');
$dwsnap_updated = trim(isset($dwsnap_cfg['UPDATED']) ? htmlspecialchars($dwsnap_cfg['UPDATED']) : '-1');
$dwsnap_moved = trim(isset($dwsnap_cfg['MOVED']) ? htmlspecialchars($dwsnap_cfg['MOVED']) : '-1');
$dwsnap_copied = trim(isset($dwsnap_cfg['COPIED']) ? htmlspecialchars($dwsnap_cfg['COPIED']) : '-1');
$dwsnap_restored = trim(isset($dwsnap_cfg['RESTORED']) ? htmlspecialchars($dwsnap_cfg['RESTORED']) : '-1');

$dwsnap_backend = trim(htmlspecialchars(shell_exec("find /var/log/packages/ -type f -iname 'snapraid-*' -printf '%f\n' 2> /dev/null")));

$dwsnap_laststart = trim(file_exists("/var/lib/snapraid/logs/laststart") ? htmlspecialchars(file_get_contents("/var/lib/snapraid/logs/laststart")) : "-");
$dwsnap_lastfinish = trim(file_exists("/var/lib/snapraid/logs/lastfinish") ? htmlspecialchars(file_get_contents("/var/lib/snapraid/logs/lastfinish")) : "-");
$dwsnap_lastsync = trim(file_exists("/boot/config/plugins/dwsnap/config/lastsync") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/lastsync")) : "-");
$dwsnap_lastscrub = trim(file_exists("/boot/config/plugins/dwsnap/config/lastscrub") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/lastscrub")) : "-");
$dwsnap_lastnodiff = trim(file_exists("/boot/config/plugins/dwsnap/config/lastnodiff") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/lastnodiff")) : "-");

$dwsnap_cfg_content = file_get_contents("/etc/snapraid.conf");
$dwsnap_parity_re = '/(.*?parity) (\/mnt\/disk.*?)\//m';
$dwsnap_data_re = '/data (.*?) (\/mnt\/disk.*?)\//m';

preg_match_all($dwsnap_parity_re, $dwsnap_cfg_content, $dwsnap_parity_disks, PREG_SET_ORDER);
preg_match_all($dwsnap_data_re, $dwsnap_cfg_content, $dwsnap_data_disks, PREG_SET_ORDER);

function dwsnap_time_ago($oldTime) {
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
            if(($timeCalc/60/60/24) > 6) {
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

function dwsnap_getFooterHTML() {
    global $dwsnap_parity_disks;
    global $dwsnap_data_disks;
    global $dwsnap_lastsync;
    global $dwsnap_lastscrub;
    global $dwsnap_lastnodiff;

    try {
        $snap_footer_html = "";
        if(!empty($dwsnap_parity_disks) && !empty($dwsnap_data_disks)) {
            $snap_all_disks_available = true;
            foreach ($dwsnap_parity_disks as $snap_parity_disk){
                $snap_disk_fs = htmlspecialchars(shell_exec("cat /etc/mtab | grep " . $snap_parity_disk[2] . " | awk '{print $3}'") ?? "-");
                if($snap_disk_fs == "-") { $snap_all_disks_available = false; }
            }
            foreach ($dwsnap_data_disks as $snap_data_disk){
                $snap_disk_fs = htmlspecialchars(shell_exec("cat /etc/mtab | grep " . $snap_data_disk[2] . " | awk '{print $3}'") ?? "-");
                if($snap_disk_fs == "-") { $snap_all_disks_available = false; }
            }
            if($snap_all_disks_available) {
                if($dwsnap_lastsync !== "-" && $dwsnap_lastscrub !== "-") {
                    $snap_lastsync_ago = dwsnap_time_ago($dwsnap_lastsync);
                    $snap_lastscrub_ago = dwsnap_time_ago($dwsnap_lastscrub);
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Sync Needed) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation red-text'></i></span>";
                    }
                    elseif($dwsnap_lastnodiff !== "-") {
                        $t_now = time();
                        $t_lastsync = strtotime($dwsnap_lastsync);
                        $t_lastnodiff = strtotime($dwsnap_lastnodiff);
                        $t_lastsync_diff = abs($t_now - $t_lastsync);
                        $t_lastnodiff_diff = abs($t_now - $t_lastnodiff);
                        $snap_lastnodiff_ago = dwsnap_time_ago($dwsnap_lastnodiff);
                        if($t_lastnodiff_diff < $t_lastsync_diff) {
                            if (strpos($snap_lastnodiff_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-bell orange-text'></i></span>";
                            } else {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-check green-text'></i></span>";
                            }
                        } else {
                            if (strpos($snap_lastsync_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-bell orange-text'></i></span>";
                            } else {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-check green-text'></i></span>";
                            }
                        }
                    } else {
                        if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                            $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Sync Needed) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation red-text'></i></span>";
                        } else {
                            if (strpos($snap_lastsync_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-bell orange-text'></i></span>";
                            } else {
                                $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-check green-text'></i></span>";
                            }
                        }
                    }
                } elseif ($dwsnap_lastsync !== "-" && $dwsnap_lastscrub == "-") {
                    $snap_lastsync_ago = dwsnap_time_ago($dwsnap_lastsync);
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Sync Needed) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'>SnapRAID<i class='fa fa-bell orange-text'></i></span>";
                    }
                } elseif ($dwsnap_lastsync == "-" && $dwsnap_lastscrub !== "-") {
                    $snap_lastscrub_ago = dwsnap_time_ago($dwsnap_lastscrub);
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Sync Needed) / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-exclamation red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'>SnapRAID<i class='fa fa-bell orange-text'></i></span>";
                    }
                } else {
                    if(file_exists("/boot/config/plugins/dwsnap/config/syncneeded")) {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Data Differences (Sync Needed) / Last Sync: Never / Last Scrub: Never'>SnapRAID<i class='fa fa-exclamation red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='snaptip' title='All disks are online and mounted / Last Sync: Never / Last Scrub: Never'>SnapRAID<i class='fa fa-bell orange-text'></i></span>";
                    }
                }
            } else {
                $snap_footer_html = "<span class='snaptip' title='At least one disk is not online and/or mounted'>SnapRAID<i class='fa fa-times red-text'></i></span>";
            }
        } else {
            $snap_footer_html = "<span class='snaptip' title='No parity and/or disks configured - did you use trailing slashes as required?'>SnapRAID<i class='fa fa-times red-text'></i></span>";
        }
        return $snap_footer_html;
    } catch (Throwable $e) { // For PHP 7
        return "";
    } catch (Exception $e) { // For PHP 5
        return "";
    }
}
?>
