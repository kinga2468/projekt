<?php
/**
 * Routing and controllers.
 */

/*do bazy danych */
use Model\Categories\Arr\Categories;
use Model\Expenses\Arr\Expenses;
use Model\Datas\Arr\Datas;
use Model\Roles\Arr\Roles;
use Model\Users\Arr\Users;

use Controller\MonthController;
use Controller\CategorieController;
use Controller\OperationController;

$categoriesModel = new Categories();
$expensesModel = new Expenses();
$datasModel = new Datas();
$rolesModel = new Roles();
$usersModel = new Users();

$app->mount('/history', new MonthController());
$app->mount('/categories', new CategorieController());
$app->mount('/operation', new OperationController());