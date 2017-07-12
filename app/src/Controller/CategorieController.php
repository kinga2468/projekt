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
        $controller->get('/{id}', [$this, 'viewAction'])->bind('categorie_view');

        return $controller;
    }

    /**
     * Index action.
     */
    public function indexAction(Application $app)
    {
        $categorieRepository = new CategorieRepository($app['db']);

        return $app['twig']->render(
            'categorie/index.html.twig',
            ['categorie' => $categorieRepository->findAll()]
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