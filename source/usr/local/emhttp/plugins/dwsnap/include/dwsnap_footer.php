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
    $snap_footer = "<a href='/Settings/dwsnapOps' style='cursor:pointer;color:inherit;text-decoration:none;'>SnapRAID</a>";
    $snap_footer_files = dwsnap_get_conf_files() ?? [];
    foreach ($snap_footer_files as $snap_footer_file) {
        $snap_footer_cfg_name = basename($snap_footer_file,".conf");
        $snap_footer_cfg_obj = new SnapraidArrayConfiguration($snap_footer_cfg_name);
        $snap_footer .= $snap_footer_cfg_obj->getFooterHTML("snapfootertip");
        unset($snap_footer_cfg_obj);
    }
    echo($snap_footer);
} catch (Throwable $e) { // For PHP 7
    echo("");
} catch (Exception $e) { // For PHP 5
    echo("");
}
?>
