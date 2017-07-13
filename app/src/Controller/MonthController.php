<?php
/**
 * Month controller.
 */
namespace Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Repository\MonthRepository;
use Form\MonthType;
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
     */
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get('/', [$this, 'indexAction'])->bind('month_index');
        $controller->get('/page/{page}', [$this, 'indexAction'])
            ->value('page', 1)
            ->bind('month_index_paginated');
        $controller->get('/{id}', [$this, 'viewAction'])
            ->assert('id', '[1-9]\d*')
            ->bind('month_view');
        $controller->match('/add', [$this, 'addAction'])
            ->method('POST|GET')
            ->bind('month_add');

        return $controller;
    }

    /**
     * Index action.
     */
    public function indexAction(Application $app, $page = 1)
    {
        $monthRepository = new MonthRepository($app['db']);

        return $app['twig']->render(
            'history/index.html.twig',
            ['paginator' => $monthRepository->findAllPaginated($page)]
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

    /**
     * Add action.
     */
    public function addAction(Application $app, Request $request)
    {
        $month = [];

        $form = $app['form.factory']->createBuilder(MonthType::class, $month)->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $monthRepository = new MonthRepository($app['db']);
            $monthRepository->save($form->getData());

            return $app->redirect($app['url_generator']->generate('month_index'), 301);
        }

        return $app['twig']->render(
            'history/add.html.twig',
            [
                'month' => $month,
                'form' => $form->createView(),
            ]
        );
    }
}