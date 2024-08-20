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
require_once '/usr/local/emhttp/plugins/dwsnap/include/dwsnap_config.php';

try {
    $snap_dashboards = "";
    if(!empty($_GET["config"])) {
        $snap_dash_active_cfg = $_GET['config'];
        $snap_dash_cfg_obj = new SnapraidArrayConfiguration($snap_dash_active_cfg);

        $snap_dash_field_paritydisks = "<span class='snapdashhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snap_dash_cfg_obj->parity_disks_raw[2]))?:"-")."'>".(count($snap_dash_cfg_obj->parity_disks_raw[2]) ?? "0")." Disk(s)</span>";
        $snap_dash_field_datadisks = "<span class='snapdashhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snap_dash_cfg_obj->data_disks_raw[2]))?:"-")."'>".(count($snap_dash_cfg_obj->data_disks_raw[2]) ?? "0")." Disk(s)</span>";
        $snap_dash_field_cron = ucwords(str_replace("disable", "disabled", $snap_dash_cfg_obj->cron)) . " Schedule";
        $snap_dash_field_status = $snap_dash_cfg_obj->getFooterHTML("snapdashtip"); 

        $snap_dash_field_util = count($snap_dash_cfg_obj->data_disks_raw[2]) > 0 ? htmlspecialchars(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snap_dash_cfg_obj->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $5}' 2>/dev/null") ?? "-")) : "-";
        $snap_dash_field_free = count($snap_dash_cfg_obj->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snap_dash_cfg_obj->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $4}' 2>/dev/null") ?? "-"))) : "-";
        $snap_dash_field_used = count($snap_dash_cfg_obj->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snap_dash_cfg_obj->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-"))) : "-";
        $snap_dash_field_total = count($snap_dash_cfg_obj->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snap_dash_cfg_obj->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $2}' 2>/dev/null") ?? "-"))) : "-";

        if($snap_dash_cfg_obj->lastsync !== "-") {
            $snap_dash_field_lastsync = dwsnap_time_ago($snap_dash_cfg_obj->lastsync, $snap_dash_cfg_obj->sync_expires);
            if($snap_dash_cfg_obj->lastnodiff !== "-" && !empty($snap_dash_field_lastsync)) {
                $snap_dash_field_lastsync = dwsnap_consider_nodiff($snap_dash_field_lastsync, $snap_dash_cfg_obj->lastnodiff, $snap_dash_cfg_obj->lastsync, $snap_dash_cfg_obj->sync_expires);
            }
            $snap_dash_field_lastsync = "<span class='snapdashtip' title='".$snap_dash_cfg_obj->lastsync."'>".$snap_dash_field_lastsync."</span>";
        } else { 
            $snap_dash_field_lastsync = "<span class='orange-text'>Never</span>"; 
        }

        if($snap_dash_cfg_obj->lastscrub !== "-") { 
            $snap_dash_field_lastscrub = "<span class='snapdashtip' title='".$snap_dash_cfg_obj->lastscrub."'>".dwsnap_time_ago($snap_dash_cfg_obj->lastscrub, $snap_dash_cfg_obj->scrub_expires)."</span>";
        } else {
            $snap_dash_field_lastscrub = "<span class='orange-text'>Never</span>"; 
        }
        
        $snap_dashboards .= "Array Status: $snap_dash_field_status / Free: $snap_dash_field_free / $snap_dash_field_used used of $snap_dash_field_total ($snap_dash_field_util)|<td><span class='w18'>$snap_dash_field_paritydisks</span><span class='w18'>$snap_dash_field_datadisks</span><span class='w18'>$snap_dash_field_lastsync</span><span class='w18'>$snap_dash_field_lastscrub</span><span class='w26'>$snap_dash_field_cron</span></td>";
        
        unset($snap_dash_cfg_obj);
    }
    echo($snap_dashboards);
} catch (Throwable $e) { // For PHP 7
    echo("");
} catch (Exception $e) { // For PHP 5
    echo("");
}
?>
