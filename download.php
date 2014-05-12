<?php
$path = dirname(__FILE__)."\\tmp\\";
$fullPath = $path.$_GET['filename'];

if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    switch ($ext) {
        case "csv":
            header('Content-Type: application/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="'.$path_parts["basename"].'";');
            break;
        default;
            header("Content-type: application/octet-stream");
            header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
}
fclose ($fd);
exit;
?>
?>