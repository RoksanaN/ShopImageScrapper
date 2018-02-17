<?php

function streamZip($chunkDirectoryName) {
    $dir = 'result' . DIRECTORY_SEPARATOR . $chunkDirectoryName;

    if (!is_dir($dir)) {
        echo '404';
        die;
    }

    $handler = opendir($dir);
    $zip = new \ZipStream\ZipStream("$chunkDirectoryName.zip");

    while (false !== ($file = readdir($handler))) {
        if ($file != "." && $file != "..") {
            $fileName = $dir . DIRECTORY_SEPARATOR . $file;
            $zip->addFileFromPath($file, $fileName);
        }
    }

    $zip->finish();
}