<?php
/**
 * Month controller.
 */
namespace Controller;

use Model\Budgets\Arr\Budgets;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MonthController.
 *
 * @package Controller
 */
class MonthController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @param \Silex\Application $app Silex application
     *
     * @return \Silex\ControllerCollection Result
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
        $budgetsModel = new Budgets();

        return $app['twig']->render(
            'history/index.html.twig',
            ['budgets' => $budgetsModel->findAll()]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)
    {
        $budgetsModel = new Budgets();

        return $app['twig']->render(
            'history/view.html.twig',
            ['budgets' => $budgetsModel->findOneById($id)]
        );
    }

}