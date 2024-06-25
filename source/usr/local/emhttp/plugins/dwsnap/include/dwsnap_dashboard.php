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
    $return_html = "";
    if(!empty($_GET["config"])) {
        $snapdashCfg = new SnapraidArrayConfiguration($_GET["config"]);

        $snapdashField_paritydisks = "<span class='snapdashhtmltip' title='".implode("<br>", $snapdashCfg->parity_disks_raw[2])."'>".count($snapdashCfg->parity_disks_raw[2])." Disk(s)</span>" ?? "0 Disk(s)";
        $snapdashField_datadisks = "<span class='snapdashhtmltip' title='".implode("<br>", $snapdashCfg->data_disks_raw[2])."'>".count($snapdashCfg->data_disks_raw[2])." Disk(s)</span>" ?? "0 Disk(s)";
        $snapdashField_cron = ucwords(str_replace("disable", "disabled", $snapdashCfg->cron)) . " Schedule";
        $snapdashField_status = $snapdashCfg->getFooterHTML("snapdashtip"); 

        $snapdashField_util = count($snapdashCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(trim(shell_exec("df -B1 ".implode(" ", $snapdashCfg->data_disks_raw[2])." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $5}' 2>/dev/null") ?? "-")) : "-";
        $snapdashField_free = count($snapdashCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", $snapdashCfg->data_disks_raw[2])." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $4}' 2>/dev/null") ?? "-"))) : "-";
        $snapdashField_used = count($snapdashCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", $snapdashCfg->data_disks_raw[2])." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-"))) : "-";
        $snapdashField_total = count($snapdashCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", $snapdashCfg->data_disks_raw[2])." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $2}' 2>/dev/null") ?? "-"))) : "-";

        if($snapdashCfg->lastsync !== "-") {
            $snapdashField_lastsync = dwsnap_time_ago($snapdashCfg->lastsync, $snapdashCfg->sync_expires);
            if($snapdashCfg->lastnodiff !== "-" && !empty($snapdashField_lastsync)) {
                try {
                    $sdt_now = time();
                    $sdt_lastsync = strtotime($snapdashCfg->lastsync);
                    $sdt_lastnodiff = strtotime($snapdashCfg->lastnodiff);
                    $sdt_lastsync_diff = abs($sdt_now - $sdt_lastsync);
                    $sdt_lastnodiff_diff = abs($sdt_now - $sdt_lastnodiff);
                    $snap_lastnodiff_ago = dwsnap_time_ago($snapdashCfg->lastnodiff, $snapdashCfg->sync_expires);
                    if($sdt_lastnodiff_diff < $sdt_lastsync_diff) {
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
        
        $return_html .= "Array Status: $snapdashField_status / Free: $snapdashField_free / $snapdashField_used used of $snapdashField_total ($snapdashField_util)|<td><span class='w18'>$snapdashField_paritydisks</span><span class='w18'>$snapdashField_datadisks</span><span class='w18'>$snapdashField_cron</span><span class='18'>$snapdashField_lastsync</span><span class='w26'>$snapdashField_lastscrub</span></td>";
        
        unset($snapdashCfg);
    }
    echo($return_html);
} catch (Throwable $e) { // For PHP 7
    echo("");
} catch (Exception $e) { // For PHP 5
    echo("");
}
?>
