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

$return_html = "";
$files = dwsnap_get_conf_files();

foreach ($files as $file) {

    $iterCfgName = basename($file,".conf");
    $iterCfg = new SnapraidArrayConfiguration($iterCfgName);

    if($iterCfg->cfgname == $dwsnap_active_cfg->cfgname) {
        $iterField_selected = "<i class='fa fa-hand-o-right'></i>";
    } else { 
        $iterField_selected = ""; 
    }
    
    $iterField_cfgname = "<span class='snaphtmltip' title='/boot/config/plugins/dwsnap/config/".$iterCfg->cfgname.".conf'>".strtoupper($iterCfg->cfgname)."</span>";
    $iterField_paritydisks = "<span class='snaphtmltip' title='".implode("<br>", $iterCfg->parity_disks_raw[2])."'>".count($iterCfg->parity_disks_raw[2])."</span>" ?? "0";
    $iterField_datadisks = "<span class='snaphtmltip' title='".implode("<br>", $iterCfg->data_disks_raw[2])."'>".count($iterCfg->data_disks_raw[2])."</span>" ?? "0";
    $iterFields_contentfiles = "<span class='snaphtmltip' title='".implode("<br>", $iterCfg->content_files_raw[1])."'>".count($iterCfg->content_files_raw[1])."</span>" ?? "0";
    $iterField_cron = strtoupper(str_replace("disable", "-", $iterCfg->cron));

    if($iterCfg->lastsync !== "-") {
        $iterField_lastsync = dwsnap_time_ago($iterCfg->lastsync, $iterCfg->sync_expires);
        if($iterCfg->lastnodiff !== "-" && !empty($iterField_lastsync)) {
            try {
                $st_now = time();
                $st_lastsync = strtotime($iterCfg->lastsync);
                $st_lastnodiff = strtotime($iterCfg->lastnodiff);
                $st_lastsync_diff = abs($st_now - $st_lastsync);
                $st_lastnodiff_diff = abs($st_now - $st_lastnodiff);
                $snap_lastnodiff_ago = dwsnap_time_ago($iterCfg->lastnodiff, $iterCfg->sync_expires);
                if($st_lastnodiff_diff < $st_lastsync_diff) {
                    if (strpos($snap_lastnodiff_ago, "orange-text") !== false) {
                        $iterField_lastsync = str_replace("green-text", "orange-text", $iterField_lastsync);
                    } else {
                        $iterField_lastsync = str_replace("orange-text", "green-text", $iterField_lastsync);
                    }
                }
            } catch (Throwable $e) { // For PHP 7
                $iterField_lastsync = strip_tags(dwsnap_time_ago($iterCfg->lastsync, $iterCfg->sync_expires));
            } catch (Exception $e) { // For PHP 5
                $iterField_lastsync = strip_tags(dwsnap_time_ago($iterCfg->lastsync, $iterCfg->sync_expires));
            }
        }
        $iterField_lastsync = "<span class='snaphtmltip' title='".$iterCfg->lastsync."'>".$iterField_lastsync."</span>";
    } else { 
        $iterField_lastsync = "<span class='orange-text'>Never</span>"; 
    }

    if($iterCfg->lastscrub !== "-") { 
        $iterField_lastscrub = "<span class='snaphtmltip' title='".$iterCfg->lastscrub."'>".dwsnap_time_ago($iterCfg->lastscrub, $iterCfg->scrub_expires)."</span>";
    } else {
        $iterField_lastscrub = "<span class='orange-text'>Never</span>"; 
    }

    $iterField_status = $iterCfg->getFooterHTML();
    
    if(!empty($iterField_selected)) {
        $return_html .= "<tr style='border: 2px solid;'><td>$iterField_selected</td><td>$iterField_cfgname</td><td>$iterField_paritydisks</td><td>$iterField_datadisks</td><td>$iterFields_contentfiles</td><td>$iterField_cron</td><td><strong>$iterField_lastsync</strong></td><td><strong>$iterField_lastscrub</strong></td><td>$iterField_status</td></tr>";
    } else {
        $return_html .= "<tr><td>$iterField_selected</td><td>$iterField_cfgname</td><td>$iterField_paritydisks</td><td>$iterField_datadisks</td><td>$iterFields_contentfiles</td><td>$iterField_cron</td><td><strong>$iterField_lastsync</strong></td><td><strong>$iterField_lastscrub</strong></td><td>$iterField_status</td></tr>";
    }
    unset($iterCfg);
}

echo($return_html);

?>
