<?php
if ($argc != 3 || $argv[1] !== 'controller') {
    echo "Usage: php createController.php controller <ModelName>\n";
    exit(1);
}

$modelName = ucfirst($argv[2]);
$className = $modelName;
$filename = $modelName . '.php';

$fileContent = "<?php

namespace App\Controllers;

class $className {
    
}";

if(!file_exists(__DIR__ . "/app/Controllers")){
    mkdir(__DIR__ . "/app/Controllers");
}
file_put_contents(__DIR__ . "/app/Controllers/" . $filename, $fileContent);

echo "Controller '$modelName' created successfully in '$filename'.\n";