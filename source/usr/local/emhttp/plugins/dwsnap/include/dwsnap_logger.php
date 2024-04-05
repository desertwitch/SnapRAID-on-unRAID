<?php
if(file_exists("/var/log/snaplog")) {
    $snaplog = file_get_contents("/var/log/snaplog");
    if(!empty($snaplog)) {
        echo("<pre>".$snaplog."</pre>");
    } else {
        echo("<pre>LOG FILE FOUND BUT IS EMPTY</pre>");
    }
} else {
    echo("<pre>NO LOG FILE FOUND</pre>");
}
?>
