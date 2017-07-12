<?php
/**
 * Operation controller.
 */
namespace Controller;

//use Model\Expenses\Arr\Expenses;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Repository\OperationRepository;
//use Symfony\Component\HttpFoundation\Request;

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
        $controller->get('/', [$this, 'indexAction'])->bind('operation_index');
        $controller->get('/{id}', [$this, 'viewAction'])->bind('operation_view');

        return $controller;
    }

    /**
     * Index action.
     */
    public function indexAction(Application $app)
    {
        $operationRepository = new OperationRepository($app['db']);

        return $app['twig']->render(
            'operation/index.html.twig',
            ['operation' => $operationRepository->findAll()]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)
    {
        $operationRepository = new OperationRepository($app['db']);

        return $app['twig']->render(
            'operation/view.html.twig',
            ['operation' => $operationRepository->findOneById($id)]
        );
    }

}