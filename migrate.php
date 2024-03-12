<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
use Mvc\Core\Migration;
use App\Migration\CreateCategoriesTable;
use App\Migration\CreateProductsTable;
use App\Migration\CreateProductCategoriesTable;
use App\Migration\CreateProductColorsTable;
use App\Migration\CreateProductSizesTable;
use App\Migration\CreateProductColorsSizesTable;
use App\Migration\CreateUsersTable;
use App\Migration\CreateUserMetaTable;
use App\Migration\CreatePasswordResetsTable;
use App\Migration\CreateOrdersTable;
use App\Migration\CreateOrderMetaTable;
use App\Migration\CreateOrderItemsTable;
use App\Migration\CreateCouponsTable;
use App\Migration\CreateCouponUsageTable;
use App\Migration\CreateEmailQueueTable;
use App\Migration\CreateWishListTable;
use App\Migration\CreatePointHistoryTable;


$manager = new Migration();

$manager->addMigration(new CreateProductsTable());
$manager->addMigration(new CreateCategoriesTable());
$manager->addMigration(new CreateProductCategoriesTable());
$manager->addMigration(new CreateProductColorsTable());
$manager->addMigration(new CreateProductSizesTable());
$manager->addMigration(new CreateProductColorsSizesTable());
$manager->addMigration(new CreateUsersTable());
$manager->addMigration(new CreatePasswordResetsTable());
$manager->addMigration(new CreateUserMetaTable());
$manager->addMigration(new CreateOrdersTable());
$manager->addMigration(new CreateOrderMetaTable());
$manager->addMigration(new CreateOrderItemsTable());
$manager->addMigration(new CreateCouponsTable());
$manager->addMigration(new CreateCouponUsageTable());
$manager->addMigration(new CreateEmailQueueTable());
$manager->addMigration(new CreateWishListTable());
$manager->addMigration(new CreatePointHistoryTable());

$command = isset($argv[1]) ? $argv[1] : '';

switch ($command) {
    case 'migrate':
        $manager->migrate();
        echo "Migrations completed successfully.\n";
        break;
    case 'rollback':
        $manager->rollback();
        echo "Rollback completed successfully.\n";
        break;
    default:
        echo "Usage: php migrate.php [migrate|rollback]\n";
        break;
}