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
if(!empty($_GET["config"])) {
    $snap_log_active_cfg = $_GET["config"];
    if(file_exists("/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog")) {
        $snap_log = file_get_contents("/var/lib/snapraid/logs/$snap_log_active_cfg-snaplog");
        if(!empty($snap_log)) {
            echo("<pre class='snaplog'>".htmlspecialchars($snap_log)."</pre>");
        } else {
            echo("<pre class='snaplog'></pre>");
        }
    } else {
        echo("<pre class='snaplog'></pre>");
    }
} else {
    echo("");
}
?>
