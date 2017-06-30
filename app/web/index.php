<?php
/**
 * Comment put here...
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Model\Categories\Arr\Categories;
use Model\Expenses\Arr\Expenses;
use Model\Budgets\Arr\Budgets;
use Model\Datas\Arr\Datas;
use Model\Roles\Arr\Roles;
use Model\Users\Arr\Users;

$app = new Application();
$app['debug'] = true;
$app->register(new AssetServiceProvider());
$app->register(
    new TwigServiceProvider(),
    [
        'twig.path' => dirname(dirname(__FILE__)).'/templates',
    ]
);

$categoriesModel = new Categories();
$expensesModel = new Expenses();
$budgetsModel = new Budgets();
$datasModel = new Datas();
$rolesModel = new Roles();
$usersModel = new Users();

$app->get(
    '/categories',
    function () use ($app, $categoriesModel) {
        return $app['twig']->render(
            'categorie/index.html.twig',
            ['categories' => $categoriesModel->findAll()]
        );
    }
);

$app->get(
    '/categories/{id}',
    function ($id) use ($app, $categoriesModel) {
        return $app['twig']->render(
            'categorie/view.html.twig',
            ['categorie' => $categoriesModel->findOneById($id)]
        );
    }
);

$app->run();