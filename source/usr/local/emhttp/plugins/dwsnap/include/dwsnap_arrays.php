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

$snap_arrays = "";
$snaparrayFiles = dwsnap_get_conf_files() ?? [];
$snaparrayActiveCfg = !empty($_GET['config']) ? $_GET['config'] : "";

foreach ($snaparrayFiles as $snaparrayFile) {

    $snaparrayCfgName = basename($snaparrayFile,".conf");
    $snaparrayCfg = new SnapraidArrayConfiguration($snaparrayCfgName);

    if($snaparrayCfg->cfgname == $snaparrayActiveCfg) {
        $snaparrayField_selected = "<i class='fa fa-check-square-o snaparraytip' title='This array is currently selected.'></i>";
    } else { 
        $snaparrayField_selected = "<a href='/Settings/dwsnapOps?snapr=".$snaparrayCfg->cfgname."' class='snaparraytip' style='cursor:pointer;color:inherit;text-decoration:none;' title='Click to switch to this array.'><i class='fa fa-square-o'></i></a>"; 
    }
    
    $snaparrayField_cfgname = "<span class='snaparraytip' title='/boot/config/plugins/dwsnap/config/".$snaparrayCfg->cfgname.".conf'>".strtoupper($snaparrayCfg->cfgname)."</span>";
    $snaparrayField_paritydisks = "<span class='snaparrayhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snaparrayCfg->parity_disks_raw[2]))?:"-")."'>".count($snaparrayCfg->parity_disks_raw[2])."</span>" ?? "0";
    $snaparrayField_datadisks = "<span class='snaparrayhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snaparrayCfg->data_disks_raw[2]))?:"-")."'>".count($snaparrayCfg->data_disks_raw[2])."</span>" ?? "0";
    $snaparrayField_contentfiles = "<span class='snaparrayhtmltip' title='".(implode("<br>", array_map("htmlspecialchars",$snaparrayCfg->content_files_raw[1]))?:"-")."'>".count($snaparrayCfg->content_files_raw[1])."</span>" ?? "0";
    $snaparrayField_cron = strtoupper(str_replace("disable", "-", $snaparrayCfg->cron));

    if($snaparrayCfg->lastsync !== "-") {
        $snaparrayField_lastsync = dwsnap_time_ago($snaparrayCfg->lastsync, $snaparrayCfg->sync_expires);
        if($snaparrayCfg->lastnodiff !== "-" && !empty($snaparrayField_lastsync)) {
            try {
                $st_now = time();
                $st_lastsync = strtotime($snaparrayCfg->lastsync);
                $st_lastnodiff = strtotime($snaparrayCfg->lastnodiff);
                $st_lastsync_diff = abs($st_now - $st_lastsync);
                $st_lastnodiff_diff = abs($st_now - $st_lastnodiff);
                $snap_lastnodiff_ago = dwsnap_time_ago($snaparrayCfg->lastnodiff, $snaparrayCfg->sync_expires);
                if($st_lastnodiff_diff < $st_lastsync_diff) {
                    if (strpos($snap_lastnodiff_ago, "orange-text") !== false) {
                        $snaparrayField_lastsync = str_replace("green-text", "orange-text", $snaparrayField_lastsync);
                    } else {
                        $snaparrayField_lastsync = str_replace("orange-text", "green-text", $snaparrayField_lastsync);
                    }
                }
            } catch (Throwable $e) { // For PHP 7
                $snaparrayField_lastsync = strip_tags(dwsnap_time_ago($snaparrayCfg->lastsync, $snaparrayCfg->sync_expires));
            } catch (Exception $e) { // For PHP 5
                $snaparrayField_lastsync = strip_tags(dwsnap_time_ago($snaparrayCfg->lastsync, $snaparrayCfg->sync_expires));
            }
        }
        $snaparrayField_lastsync = "<span class='snaparraytip' title='".$snaparrayCfg->lastsync."'>".$snaparrayField_lastsync."</span>";
    } else { 
        $snaparrayField_lastsync = "<span class='orange-text'>Never</span>"; 
    }

    if($snaparrayCfg->lastscrub !== "-") { 
        $snaparrayField_lastscrub = "<span class='snaparraytip' title='".$snaparrayCfg->lastscrub."'>".dwsnap_time_ago($snaparrayCfg->lastscrub, $snaparrayCfg->scrub_expires)."</span>";
    } else {
        $snaparrayField_lastscrub = "<span class='orange-text'>Never</span>"; 
    }

    $snaparrayField_status = $snaparrayCfg->getFooterHTML("snaparraytip");
    
    $snap_arrays .= "<tr><td>$snaparrayField_selected</td><td>$snaparrayField_cfgname</td><td>$snaparrayField_paritydisks</td><td>$snaparrayField_datadisks</td><td>$snaparrayField_contentfiles</td><td>$snaparrayField_cron</td><td><strong>$snaparrayField_lastsync</strong></td><td><strong>$snaparrayField_lastscrub</strong></td><td>$snaparrayField_status</td></tr>";
    
    unset($snaparrayCfg);
}

echo($snap_arrays);

?>
