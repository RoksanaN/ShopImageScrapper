<?php

function streamZip($chunkDirectoryName) {
    $filename = __DIR__ . DIRECTORY_SEPARATOR . 'result' . DIRECTORY_SEPARATOR . 'MI5149' . DIRECTORY_SEPARATOR . 'MI5149_1.jpg';

    $zip = new \ZipStream\ZipStream('example.zip');

    $zip->addFileFromPath('MI5149_1.jpg', $filename);

    $zip->finish();
}