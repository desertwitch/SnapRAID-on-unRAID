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
?>
