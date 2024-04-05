<?
$base     = '/boot/config/plugins/dwsnap/config/';
$editfile = realpath($_POST['editfile']);

if(file_exists($editfile) && array_key_exists('editdata', $_POST)){
    // remove carriage returns
    $editdata = str_replace("\r", '', $_POST['editdata']);

    // create directory on flash drive if missing (shouldn't happen)
    if(! is_dir($base)){
        mkdir($base);
    }

    // save conf file to local system
    $return_var = file_put_contents($editfile, $editdata);
}else{
    $return_var = false;
}

if($return_var)
    $return = ['success' => true, 'saved' => $editfile];
else
    $return = ['error' => $editfile];

echo json_encode($return);
?>
