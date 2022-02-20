<?php 
class ScanApi{
    private string $SecretKey = "key-is-key";
    private string $phpmusselurl = "https://accadmynist.000webhostapp.com/files.zip";
    private string $server = 'https://accadmynist.000webhostapp.com/api.php';
    //make variable to store the config file
    private $config;
    public function __construct(){
        if (!file_exists('av-config.json')) {
            $this->config = fopen('av-config.json', 'w+');
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
        if ($activeFile) {
            all($activeFile);
        } else {
            return false;
        }
     
    }
    //activate the script
    public function activate(){
        $configData = json_decode(file_get_contents('av-config.json'));
        $configData->active = true;
        $jsonData = json_encode($configData);
        file_put_contents('av-config.json', $jsonData);
        return true;
    }
    public function quarintineFile($files = array()){
        //make a quarintine folder if not exist
        if (!file_exists('quarantine')) {
            mkdir('quarantine', 0777, true);
        }
        //move the files to quarantine folder
        foreach ($files as $file) {
            rename($file, 'quarantine/' . basename($file));
        }
        return true;

    }
    public function scan(){
        //run in background
        if (!file_exists('scanner')) {
            $this->downloadDependencies();
        } else {
            if (substr(php_uname(), 0, 7) == "Windows") {
                pclose(popen("start /B " . 'php scanner/base.php', "r"));
            } else {
                exec('php scanner/base.php' . " > /dev/null &");
            }
        }
        return true;
    }
    public function downloadDependencies(){
        try {
            mkdir('scanner');
            $url        =  $this->phpmusselurl;
            $file_name  =   basename($url);
            //save the file by using base name
            if (file_put_contents($file_name, file_get_contents($url))) {
                $zip = new ZipArchive;
                $res = $zip->open($file_name);
                if ($res === TRUE) {
                    $zip->extractTo('scanner');
                    $zip->close();
                    //delete the zip file
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
    protected function updateServer($message = ""){
        //post request to update the serve
    }
    //get all quarantine files
    public function getQuarantineFiles(){
        $files = array();
        if (file_exists('quarantine')) {
            $files = scandir('quarantine');
        }
        return $files;
    }
        // //

}
class RequestsHandler{
    //make variable to store the data of config file
    protected $config;
    protected $isValidIp = '127.0.0.1';
    public function __construct(){
         $scanner= new ScanApi();
        if (!file_exists('av-config.json')) {
            //return http response of faulier
            return false;
        } else {
            $this->config= json_decode(file_get_contents('av-config.json'));
        }
        //check if scanner folder exist
        if (!file_exists('scanner')) {
            $scanner->downloadDependencies();
        }
        //check if the ip is valid
        if (!$this->isValidIp === ($_SERVER['REMOTE_ADDR'])) {
            return false;
        }
        //check if the request param of post request 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //check if the request is for scan
            if ($_POST['action'] === 'scan') {
                $scanner->scan();
            }
            //check if the request is for activate
            if ($_POST['action'] === 'activate') {
                $scanner->activate();
            }
            //check if the request is for quarintine
            if ($_POST['action'] === 'quarintine') {
                $scanner->quarintineFile($_POST['files']);
            }
            //check if the request is for getQuarantineFiles
            if ($_POST['action'] === 'getQuarantineFiles') {
                $files = $scanner->getQuarantineFiles();
                echo json_encode($files);
            }
        }   
    }
}

new RequestsHandler();
?>