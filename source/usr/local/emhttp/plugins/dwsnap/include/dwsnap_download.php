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
exec("timeout -s9 30 /usr/local/emhttp/plugins/dwsnap/scripts/ziplogs", $snapdebugResult);
if(!empty($snapdebugResult)) {
	if(strpos(end($snapdebugResult), "DONE:") !== false) {
		$snapdebugFile = explode(":", end($snapdebugResult))[1];
		if(!empty($snapdebugFile) && file_exists($snapdebugFile)) {
			header("Content-Disposition: attachment; filename=\"" . basename($snapdebugFile) . "\"");
			header("Content-Type: application/octet-stream");
			header("Content-Length: " . filesize($snapdebugFile));
			header("Connection: close");
			readfile($snapdebugFile);
			unlink($snapdebugFile);
			exit;
		} else {
			echo("ERROR: The log package generation script has failed - bash backend returned filename, PHP could not find file.");
		}
	} else { 
		echo("ERROR: The log package generation script has failed - response from the bash backend:<br><pre>");
		echo(implode("\n",$snapdebugResult));
		echo("</pre>");
	}
} else {
	echo("ERROR: The log package generation script has failed - no response from the bash backend.");
}
?>
