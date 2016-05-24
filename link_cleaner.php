<?php

$linkDirectory = "uploads";
$expireTime = 5; // time to wait in minutes before deleting a link file

$dir = new DirectoryIterator($linkDirectory);
foreach ($dir as $fileinfo) {
    
    
    // Loop through every link file
    if (!$fileinfo->isDot() && !$fileinfo->isDir() && strpos($fileinfo, ".") === false) {
        
        // Check if link file has expired and delete it
        if($fileinfo->getCTime() + $expireTime*60 < time()){
            unlink($fileinfo->getPathname());
        }
    }
}