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

if(empty($_GET["editfile"])) {
    exit;
}

$base = '/boot/config/plugins/dwsnap/config/';
$file = realpath($_GET['editfile']);
$escfile = escapeshellarg($file);
$editfile = 'Invalid File';

if(!empty($_GET["lines"])) {
    if($_GET["lines"] === "Max") {
        $snap_archive_lines = escapeshellarg(getMemoryLimitInBytes());
        $tailarg = "c";
    } else {
        $snap_archive_lines = escapeshellarg($_GET["lines"]);
        $tailarg = "n";
    }
} else {
    $snap_archive_lines = escapeshellarg("500");
    $tailarg = "n";
}

if(file_exists($file)) {
    $editfile = shell_exec("tail -$tailarg $snap_archive_lines $escfile 2>/dev/null");
}

echo json_encode($editfile);
?>
