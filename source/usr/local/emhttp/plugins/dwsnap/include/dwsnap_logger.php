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
$snap_logger_retarr = [];

try {
    if(!empty($_GET["config"])) {
        $snap_log_active_cfg = $_GET["config"];
        if(file_exists("/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog")) {
            $snap_log = file_get_contents("/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog");
            if(!empty($snap_log)) {
                $snap_logger_retarr["success"]["response"] = "<pre class='snaplog'>".htmlspecialchars($snap_log)."</pre>";
            } else {
                $snap_logger_retarr["success"]["response"] = "<pre class='snaplog'></pre>";
            }
        } else {
            $snap_logger_retarr["success"]["response"] = "<pre class='snaplog'></pre>";
        }
    } else {
        $snap_logger_retarr["error"]["response"] = "Missing GET variables!";
    }
}
catch (\Throwable $t) {
    error_log($t);
    $snap_logger_retarr = [];
    $snap_logger_retarr["error"]["response"] = $t->getMessage();
}
catch (\Exception $e) {
    error_log($e);
    $snap_logger_retarr = [];
    $snap_logger_retarr["error"]["response"] = $e->getMessage();
}

echo(json_encode($snap_logger_retarr));
?>
