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

function getMemoryLimitInBytes() {
    try {
        $memoryLimit = ini_get('memory_limit');
        $unit = strtolower(substr($memoryLimit, -1));
        $size = (int)$memoryLimit;
        switch ($unit) {
            case 'g': // Gigabytes
                $size *= 1024;
            case 'm': // Megabytes
                $size *= 1024;
            case 'k': // Kilobytes
                $size *= 1024;
        }
        return (int)($size * 0.8);
    } catch(\Throwable $t) {
        error_log($t);
        return 104857600;
    } catch(\Exception $e) {
        error_log($e);
        return 104857600;
    }
}

try {
    if(!empty($_GET["config"])) {
        $snap_log_active_cfg = $_GET["config"];
        if(!empty($_GET["lines"])) {
            if($_GET["lines"] === "Max") {
                $snap_logger_lines = escapeshellarg(getMemoryLimitInBytes());
                $tailarg = "c";
            } else {
                $snap_logger_lines = escapeshellarg($_GET["lines"]);
                $tailarg = "n";
            }
        } else {
            $snap_logger_lines = escapeshellarg("500");
            $tailarg = "n";
        }
        if(file_exists("/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog")) {
            $snap_log = shell_exec("tail -$tailarg $snap_logger_lines '/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog' 2>/dev/null");
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
