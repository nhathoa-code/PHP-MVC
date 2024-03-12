<?php
if ($argc != 3 || $argv[1] !== 'middleware') {
    echo "Usage: php createMiddleware.php middleware <middleName>\n";
    exit(1);
}

$middlewareName = ucfirst($argv[2]);
$className = $middlewareName;
$filename = $middlewareName . '.php';

$fileContent = "<?php

namespace App\Middlewares;
use Mvc\Core\Middleware;

class $className implements Middleware{
    public function handle(\$request) {
        // Implement middleware logic here
        return \$request;
    }
}";

if(!file_exists(__DIR__ . "/app/Middlewares")){
    mkdir(__DIR__ . "/app/Middlewares");
}
file_put_contents(__DIR__ . "/app/Middlewares/" . $filename, $fileContent);

echo "Middleware '$middlewareName' created successfully in '$filename'.\n";