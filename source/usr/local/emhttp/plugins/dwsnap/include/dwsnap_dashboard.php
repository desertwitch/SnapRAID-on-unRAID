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
        $snapdashActiveCfg = new SnapraidArrayConfiguration($_GET["config"]);

        $snapdashField_paritydisks = "<span class='snapdashhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snapdashActiveCfg->parity_disks_raw[2]))?:"-")."'>".count($snapdashActiveCfg->parity_disks_raw[2])." Disk(s)</span>" ?? "0 Disk(s)";
        $snapdashField_datadisks = "<span class='snapdashhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snapdashActiveCfg->data_disks_raw[2]))?:"-")."'>".count($snapdashActiveCfg->data_disks_raw[2])." Disk(s)</span>" ?? "0 Disk(s)";
        $snapdashField_cron = ucwords(str_replace("disable", "disabled", $snapdashActiveCfg->cron)) . " Schedule";
        $snapdashField_status = $snapdashActiveCfg->getFooterHTML("snapdashtip"); 

        $snapdashField_util = count($snapdashActiveCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snapdashActiveCfg->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $5}' 2>/dev/null") ?? "-")) : "-";
        $snapdashField_free = count($snapdashActiveCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snapdashActiveCfg->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $4}' 2>/dev/null") ?? "-"))) : "-";
        $snapdashField_used = count($snapdashActiveCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snapdashActiveCfg->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-"))) : "-";
        $snapdashField_total = count($snapdashActiveCfg->data_disks_raw[2]) > 0 ? htmlspecialchars(dwsnap_humanFileSize(trim(shell_exec("df -B1 ".implode(" ", array_map("escapeshellarg", $snapdashActiveCfg->data_disks_raw[2]))." --total 2>/dev/null | grep total 2>/dev/null | awk '{print $2}' 2>/dev/null") ?? "-"))) : "-";

        if($snapdashActiveCfg->lastsync !== "-") {
            $snapdashField_lastsync = dwsnap_time_ago($snapdashActiveCfg->lastsync, $snapdashActiveCfg->sync_expires);
            if($snapdashActiveCfg->lastnodiff !== "-" && !empty($snapdashField_lastsync)) {
                try {
                    $sdt_now = time();
                    $sdt_lastsync = strtotime($snapdashActiveCfg->lastsync);
                    $sdt_lastnodiff = strtotime($snapdashActiveCfg->lastnodiff);
                    $sdt_lastsync_diff = abs($sdt_now - $sdt_lastsync);
                    $sdt_lastnodiff_diff = abs($sdt_now - $sdt_lastnodiff);
                    $snap_lastnodiff_ago = dwsnap_time_ago($snapdashActiveCfg->lastnodiff, $snapdashActiveCfg->sync_expires);
                    if($sdt_lastnodiff_diff < $sdt_lastsync_diff) {
                        if (strpos($snap_lastnodiff_ago, "orange-text") !== false) {
                            $snapdashField_lastsync = str_replace("green-text", "orange-text", $snapdashField_lastsync);
                        } else {
                            $snapdashField_lastsync = str_replace("orange-text", "green-text", $snapdashField_lastsync);
                        }
                    }
                } catch (Throwable $e) { // For PHP 7
                    $snapdashField_lastsync = strip_tags(dwsnap_time_ago($snapdashActiveCfg->lastsync, $snapdashActiveCfg->sync_expires));
                } catch (Exception $e) { // For PHP 5
                    $snapdashField_lastsync = strip_tags(dwsnap_time_ago($snapdashActiveCfg->lastsync, $snapdashActiveCfg->sync_expires));
                }
            }
            $snapdashField_lastsync = "<span class='snapdashtip' title='".$snapdashActiveCfg->lastsync."'>".$snapdashField_lastsync."</span>";
        } else { 
            $snapdashField_lastsync = "<span class='orange-text'>Never</span>"; 
        }

        if($snapdashActiveCfg->lastscrub !== "-") { 
            $snapdashField_lastscrub = "<span class='snapdashtip' title='".$snapdashActiveCfg->lastscrub."'>".dwsnap_time_ago($snapdashActiveCfg->lastscrub, $snapdashActiveCfg->scrub_expires)."</span>";
        } else {
            $snapdashField_lastscrub = "<span class='orange-text'>Never</span>"; 
        }
        
        $snap_dashboards .= "Array Status: $snapdashField_status / Free: $snapdashField_free / $snapdashField_used used of $snapdashField_total ($snapdashField_util)|<td><span class='w18'>$snapdashField_paritydisks</span><span class='w18'>$snapdashField_datadisks</span><span class='w18'>$snapdashField_lastsync</span><span class='w18'>$snapdashField_lastscrub</span><span class='w26'>$snapdashField_cron</span></td>";
        
        unset($snapdashActiveCfg);
    }
    echo($snap_dashboards);
} catch (Throwable $e) { // For PHP 7
    echo("");
} catch (Exception $e) { // For PHP 5
    echo("");
}
?>
