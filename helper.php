<?php

/**
 * @param $data
 * @param $filename
 */
function writeToFile($data, $filename)
{
    $fp = fopen($filename, 'w');
    fwrite($fp, $data);
    fclose($fp);
}

/**
 * @param $folder
 */
function deleteAllFromFolder($folder) {
    $files = glob($folder);
    foreach($files as $file){
        if(is_file($file))
            unlink($file);
    }
}

/**
 * @param $filepath
 * @return mixed
 */
function getContents($filepath) {
    if (is_file($filepath)) {
        $data = file_get_contents($filepath);
        return json_decode($data,TRUE);
    }
}

