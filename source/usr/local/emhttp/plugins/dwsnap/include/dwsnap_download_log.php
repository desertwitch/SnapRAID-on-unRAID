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
if(isset($_GET["config"])) {
    $snap_log_active_cfg = $_GET["config"];
    $snap_log_active_cfg_file = "/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog";
    if(file_exists($snap_log_active_cfg_file)) {
        $snap_log_timestamp = date("Y-m-d_H-i-s");
        $snap_log_dl_filename = "$snap_log_active_cfg-snaplog_$snap_log_timestamp.log";
        header("Content-Disposition: attachment; filename=\"$snap_log_dl_filename\"");
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . filesize($snap_log_active_cfg_file));
        header("Connection: close");
        readfile($snap_log_active_cfg_file);
        exit;
    } else {
        die("Log file does not exist (yet), try again...");
    }
}
?>
