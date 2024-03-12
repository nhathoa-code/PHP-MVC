<?php
if ($argc != 3 || $argv[1] !== 'migration') {
    echo "Usage: php createMigration.php migration <Create{tablename}Table>\n";
    exit(1);
}

$migrationName = ucfirst($argv[2]);
$className = $migrationName;
$filename = $migrationName . '.php';
$tableName = preg_replace('/([a-z])([A-Z])/', '$1_$2', $migrationName);
$tableName = strtolower($migrationName);
$tableName = str_replace(["create","table"],"",$tableName);

$fileContent = "<?php

namespace App\Migration;

class $className {
     public function up(\$db) {
        \$sql = \"CREATE TABLE IF NOT EXISTS $tableName (
                
            )
            \";
            \$db->migrate(\$sql);
    }

    public function down(\$db) {
        \$sql = \"DROP TABLE IF EXISTS $tableName\";
        \$db->migrate(\$sql);
    }
}";

file_put_contents(__DIR__ . "/migration/" . $filename, $fileContent);

echo "Migration '$migrationName' created successfully in '$filename'.\n";