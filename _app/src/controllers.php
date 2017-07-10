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

$categoriesModel = new Categories();
$expensesModel = new Expenses();
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

/*
$_app->get(
    '/history',
    function () use ($_app, $budgetsModel) {
        return $_app['twig']->render(
            'history/index.html.twig',
            ['budgets' => $budgetsModel->findAll()]
        );
    }
);*/

$app->mount('/history', new MonthController());
$app->mount('/history/{$id}', new MonthController());