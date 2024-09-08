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
$snap_arrays_retarr = [];

try {
    $snap_arrays = "";
    $snap_array_active_cfg = !empty($_GET['config']) ? $_GET['config'] : "error";
    $snap_array_files = dwsnap_get_conf_files() ?? [];

    foreach ($snap_array_files as $snaparrayFile) {
        $snap_array_cfg_name = basename($snaparrayFile,".conf");
        $snap_array_cfg_obj = new SnapraidArrayConfiguration($snap_array_cfg_name);

        if($snap_array_cfg_obj->cfgname == $snap_array_active_cfg) {
            $snap_array_field_selected = "<i class='fa fa-check-square-o'></i>";
        } else { 
            $snap_array_field_selected = "<a href='/Settings/dwsnapOps?snapr=".$snap_array_cfg_obj->cfgname."' style='cursor:pointer;color:inherit;text-decoration:none;'><i class='fa fa-square-o'></i></a>"; 
        }
        
        $snap_array_field_cfgname = "<span class='snaparraytip' title='/boot/config/plugins/dwsnap/config/".$snap_array_cfg_obj->cfgname.".conf'>".strtoupper($snap_array_cfg_obj->cfgname)."</span>";
        $snap_array_field_paritydisks = "<span class='snaparrayhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snap_array_cfg_obj->parity_disks_raw[2]))?:"-")."'>".(count($snap_array_cfg_obj->parity_disks_raw[2]) ?? "0")."</span>";
        $snap_array_field_datadisks = "<span class='snaparrayhtmltip' title='".(implode("<br>", array_map("htmlspecialchars", $snap_array_cfg_obj->data_disks_raw[2]))?:"-")."'>".(count($snap_array_cfg_obj->data_disks_raw[2]) ?? "0")."</span>";
        $snap_array_field_contentfiles = "<span class='snaparrayhtmltip' title='".(implode("<br>", array_map("htmlspecialchars",$snap_array_cfg_obj->content_files_raw[1]))?:"-")."'>".(count($snap_array_cfg_obj->content_files_raw[1]) ?? "0")."</span>";
        $snap_array_field_cron = strtoupper(str_replace("disable", "-", $snap_array_cfg_obj->cron));

        if($snap_array_cfg_obj->lastsync !== "-") {
            $snap_array_field_lastsync = dwsnap_time_ago($snap_array_cfg_obj->lastsync, $snap_array_cfg_obj->sync_expires);
            if($snap_array_cfg_obj->lastnodiff !== "-" && !empty($snap_array_field_lastsync)) {
                $snap_array_field_lastsync = dwsnap_consider_nodiff($snap_array_field_lastsync, $snap_array_cfg_obj->lastnodiff, $snap_array_cfg_obj->lastsync, $snap_array_cfg_obj->sync_expires);
            }
            $snap_array_field_lastsync = "<span class='snaparraytip' title='".$snap_array_cfg_obj->lastsync."'>".$snap_array_field_lastsync."</span>";
        } else { 
            $snap_array_field_lastsync = "<span class='orange-text'>Never</span>"; 
        }

        if($snap_array_cfg_obj->lastscrub !== "-") { 
            $snap_array_field_lastscrub = "<span class='snaparraytip' title='".$snap_array_cfg_obj->lastscrub."'>".dwsnap_time_ago($snap_array_cfg_obj->lastscrub, $snap_array_cfg_obj->scrub_expires)."</span>";
        } else {
            $snap_array_field_lastscrub = "<span class='orange-text'>Never</span>"; 
        }

        $snap_array_field_status = $snap_array_cfg_obj->getFooterHTML("snaparraytip");
        
        $snap_arrays .= "<tr><td>$snap_array_field_selected</td><td>$snap_array_field_cfgname</td><td>$snap_array_field_paritydisks</td><td>$snap_array_field_datadisks</td><td>$snap_array_field_contentfiles</td><td>$snap_array_field_cron</td><td>$snap_array_field_lastsync</td><td>$snap_array_field_lastscrub</td><td>$snap_array_field_status</td></tr>";
        
        unset($snap_array_cfg_obj);
    }
    $snap_arrays_retarr["success"]["response"] = $snap_arrays;
}
catch (\Throwable $t) {
    error_log($t);
    $snap_arrays_retarr = [];
    $snap_arrays_retarr["error"]["response"] = $t->getMessage();
}
catch (\Exception $e) {
    error_log($e);
    $snap_arrays_retarr = [];
    $snap_arrays_retarr["error"]["response"] = $e->getMessage();
}
echo(json_encode($snap_arrays_retarr));
?>
