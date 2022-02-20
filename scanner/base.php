<?php

// Path to vendor directory.
$Vendor = __DIR__ . DIRECTORY_SEPARATOR . 'vendor';

// Composer's autoloader.
require $Vendor . DIRECTORY_SEPARATOR . 'autoload.php';
// Execute the scan.
function ScanOnCommand($Samples)
{
    $Loader = new \phpMussel\Core\Loader();
    $Scanner = new \phpMussel\Core\Scanner($Loader);
    $Scanner->scan($Samples);
    $Results = json_encode([
        'origin' => $_SERVER[$Loader->Configuration['core']['ipaddr']],
        'objects_scanned' => $Loader->InstanceCache['ObjectsScanned'] ?? 0,
        'detections_count' => $Loader->InstanceCache['DetectionsCount'] ?? 0,
        'scan_errors' => $Loader->InstanceCache['ScanErrors'] ?? 1,
        'hash_references' => $Loader->HashReference,
        'why_flagged' => array($Loader->ScanResultsText),
        'why_flaggeds' => array($Loader->PEData),
    ]);

    // Cleanup.
    unset($Scanner, $Loader);
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-type: application/json');
    return $Results;
}
//get first argument
function sendDatatoServer($postData, $url)
{
    //post data to server using 
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
        //,CURLOPT_FOLLOWLOCATION => true
    ));
    // //
    //get response
    $output = curl_exec($ch);
    //Print error if any
    if (curl_errno($ch)) {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
    

}
function scanfile($file)
{
    $sendabledata = ScanOnCommand($file);
    sendDatatoServer($sendabledata, 'http://127.0.0.1:3000/');
}
function recurseDir($dir)
{
    $dh = new DirectoryIterator($dir);
    foreach ($dh as $item) {
        if (!$item->isDot()) {
            if ($item->isDir()) {
                if ("$dir/$item" !== "/scanner" && substr("$dir/$item", 0, 3) !== './.') {
                    recurseDir("$dir/$item");
                }
            } else {
                $str1 = substr("$dir", 2);
                echo "scanning $str1/$item \n";
                scanfile("$str1/$item");

            }
        }
    }
}
recurseDir('.');