<?php
/**
 * Categorie controller.
 */
namespace Controller;

use Model\Categories\Arr\Categories;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $controller->get('/', [$this, 'indexAction']);
        $controller->get('/{id}', [$this, 'viewAction']);

        return $controller;
    }

    /**
     * Index action.
     */
    public function indexAction(Application $app)
    {
        $categorieModel = new Categories();

        return $app['twig']->render(
            'categorie/index.html.twig',
            ['categorie' => $categorieModel->findAll()]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)
    {
        $categorieModel = new Categories();

        return $app['twig']->render(
            'categorie/view.html.twig',
            ['categorie' => $categorieModel->findOneById($id)]
        );
    }
}