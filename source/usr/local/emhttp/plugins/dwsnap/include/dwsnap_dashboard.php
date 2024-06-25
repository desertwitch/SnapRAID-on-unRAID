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

if(!empty($_GET["config"])) {

    $snapdashCfg = new SnapraidArrayConfiguration($_GET["config"]);

    $snapdashField_paritydisks = "<span class='snapdashtip' title='".implode("<br>", $snapdashCfg->parity_disks_raw[2])."'>".count($snapdashCfg->parity_disks_raw[2])."</span>" ?? "0";
    $snapdashField_datadisks = "<span class='snapdashtip' title='".implode("<br>", $snapdashCfg->data_disks_raw[2])."'>".count($snapdashCfg->data_disks_raw[2])."</span>" ?? "0";

    if($snapdashCfg->lastsync !== "-") {
        $snapdashField_lastsync = dwsnap_time_ago($snapdashCfg->lastsync, $snapdashCfg->sync_expires);
        if($snapdashCfg->lastnodiff !== "-" && !empty($snapdashField_lastsync)) {
            try {
                $st_now = time();
                $st_lastsync = strtotime($snapdashCfg->lastsync);
                $st_lastnodiff = strtotime($snapdashCfg->lastnodiff);
                $st_lastsync_diff = abs($st_now - $st_lastsync);
                $st_lastnodiff_diff = abs($st_now - $st_lastnodiff);
                $snap_lastnodiff_ago = dwsnap_time_ago($snapdashCfg->lastnodiff, $snapdashCfg->sync_expires);
                if($st_lastnodiff_diff < $st_lastsync_diff) {
                    if (strpos($snap_lastnodiff_ago, "orange-text") !== false) {
                        $snapdashField_lastsync = str_replace("green-text", "orange-text", $snapdashField_lastsync);
                    } else {
                        $snapdashField_lastsync = str_replace("orange-text", "green-text", $snapdashField_lastsync);
                    }
                }
            } catch (Throwable $e) { // For PHP 7
                $snapdashField_lastsync = strip_tags(dwsnap_time_ago($snapdashCfg->lastsync, $snapdashCfg->sync_expires));
            } catch (Exception $e) { // For PHP 5
                $snapdashField_lastsync = strip_tags(dwsnap_time_ago($snapdashCfg->lastsync, $snapdashCfg->sync_expires));
            }
        }
        $snapdashField_lastsync = "<span class='snapdashtip' title='".$snapdashCfg->lastsync."'>".$snapdashField_lastsync."</span>";
    } else { 
        $snapdashField_lastsync = "<span class='orange-text'>Never</span>"; 
    }

    if($snapdashCfg->lastscrub !== "-") { 
        $snapdashField_lastscrub = "<span class='snapdashtip' title='".$snapdashCfg->lastscrub."'>".dwsnap_time_ago($snapdashCfg->lastscrub, $snapdashCfg->scrub_expires)."</span>";
    } else {
        $snapdashField_lastscrub = "<span class='orange-text'>Never</span>"; 
    }

    $snapdashField_status = $snapdashCfg->getFooterHTML();
    
    $return_html .= "<td>$snapdashField_paritydisks</td><td>$snapdashField_datadisks</td><td><strong>$snapdashField_lastsync</strong></td><td><strong>$snapdashField_lastscrub</strong></td><td>$snapdashField_status</td>";
    
    unset($snapdashCfg);

}

echo($return_html);

?>
