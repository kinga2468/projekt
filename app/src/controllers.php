<?php
/**
 * Routing and controllers.
 */
/*do bazy danych */
use Model\Categories\Arr\Categories;
use Model\Expenses\Arr\Expenses;
use Model\Budgets\Arr\Budgets;
use Model\Datas\Arr\Datas;
use Model\Roles\Arr\Roles;
use Model\Users\Arr\Users;

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

$app->get(
    '/history',
    function () use ($app, $budgetsModel) {
        return $app['twig']->render(
            'history/index.html.twig',
            ['budgets' => $budgetsModel->findAll()]
        );
    }
);