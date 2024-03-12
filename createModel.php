<?php
if ($argc != 3 || $argv[1] !== 'model') {
    echo "Usage: php createModel.php model <ModelName>\n";
    exit(1);
}

$modelName = ucfirst($argv[2]);
$className = $modelName;
$filename = $modelName . '.php';
$table = strtolower($modelName);

$fileContent = "<?php

namespace App\Models;
use Mvc\Core\Model;

class $className extends Model {
    protected \$table = '{$table}s';
}";

if(!file_exists(__DIR__ . "/app/Models")){
    mkdir(__DIR__ . "/app/Models");
}
file_put_contents(__DIR__ . "/app/Models/" . $filename, $fileContent);

echo "Model '$modelName' created successfully in '$filename'.\n";