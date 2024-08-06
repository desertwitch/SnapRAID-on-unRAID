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
exec("timeout -s9 30 /usr/local/emhttp/plugins/dwsnap/scripts/ziplogs", $snap_debug_result);
if(!empty($snap_debug_result)) {
    if(strpos(end($snap_debug_result), "DONE:") !== false) {
        $snap_debug_file = explode(":", end($snap_debug_result))[1];
        if(!empty($snap_debug_file) && file_exists($snap_debug_file)) {
            header("Content-Disposition: attachment; filename=\"" . basename($snap_debug_file) . "\"");
            header("Content-Type: application/octet-stream");
            header("Content-Length: " . filesize($snap_debug_file));
            header("Connection: close");
            readfile($snap_debug_file);
            unlink($snap_debug_file);
            exit;
        } else {
            echo("ERROR: The log package generation script has failed - bash backend returned filename, PHP could not find file.");
        }
    } else { 
        echo("ERROR: The log package generation script has failed - response from the bash backend:<br><pre>");
        echo(implode("\n",$snap_debug_result));
        echo("</pre>");
    }
} else {
    echo("ERROR: The log package generation script has failed - no response from the bash backend.");
}
?>
