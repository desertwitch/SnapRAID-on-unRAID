<?php
if(file_exists("/var/log/snaplog")) {
    $snaplog = file_get_contents("/var/log/snaplog");
    if(!empty($snaplog)) {
        echo("<pre>".."</pre>");
    } else {
        echo("file exists but empty");
    }
} else {
    echo("file does not exist");
}
?>
