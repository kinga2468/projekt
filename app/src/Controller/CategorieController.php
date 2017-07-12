<?php
/**
 * Categorie controller.
 */
namespace Controller;

//use Model\Categories\Arr\Categories;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Repository\CategorieRepository;
//use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategorieController.
 *
 * @package Controller
 */
class CategorieController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     */
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get('/', [$this, 'indexAction'])->bind('categorie_index');
        $controller->get('/page/{page}', [$this, 'indexAction'])
            ->value('page', 1)
            ->bind('categorie_index_paginated');
        $controller->get('/{id}', [$this, 'viewAction'])->bind('categorie_view');

        return $controller;
    }

    /**
     * Index action.
     */
    public function indexAction(Application $app, $page = 1)
    {
        $categorieRepository = new CategorieRepository($app['db']);

        return $app['twig']->render(
            'categorie/index.html.twig',
            ['paginator' => $categorieRepository->findAllPaginated($page)]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)
    {
        $categorieRepository = new CategorieRepository($app['db']);

        return $app['twig']->render(
            'categorie/view.html.twig',
            ['categorie' => $categorieRepository->findOneById($id)]
        );
    }

}