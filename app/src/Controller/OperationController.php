<?php
/**
 * Operation controller.
 */
namespace Controller;

use Model\Expenses\Arr\Expenses;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OperationController.
 *
 * @package Controller
 */
class OperationController implements ControllerProviderInterface
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
        $operationModel = new Expenses();

        return $app['twig']->render(
            'operation/index.html.twig',
            ['operation' => $operationModel->findAll()]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)
    {
        $operationModel = new Expenses();

        return $app['twig']->render(
            'operation/view.html.twig',
            ['operation' => $operationModel->findOneById($id)]
        );
    }
}