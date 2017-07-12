<?php
/**
 * Month controller.
 */
namespace Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Repository\MonthRepository;


/**
 * Class MonthController.
 *
 * @package Controller
 */
class MonthController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     */
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get('/', [$this, 'indexAction'])->bind('month_index');
        $controller->get('/{id}', [$this, 'viewAction'])->bind('month_view');

        return $controller;
    }

    /**
     * Index action.
     */
    public function indexAction(Application $app)
    {
        $monthRepository = new MonthRepository($app['db']);

        return $app['twig']->render(
            'history/index.html.twig',
            ['month' => $monthRepository->findAll()]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)
    {
        $monthRepository = new MonthRepository($app['db']);

        return $app['twig']->render(
            'history/view.html.twig',
            ['month' => $monthRepository->findOneById($id)]
        );
    }

}