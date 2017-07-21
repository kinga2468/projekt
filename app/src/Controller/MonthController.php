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
class MonthController implements ControllerProviderInterface      //klasa dla controllerów
{
    /**
     * Routing settings.
     */
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];                              //definiuje kontrolery?
        $controller->get('/', [$this, 'indexAction'])->bind('month_index');     //jeśli w adresie url dostaniesz ... to wykonaj jakąś akcje
        $controller->get('/page/{page}', [$this, 'indexAction'])                //zrób bind
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
     * Index action - show only this month which user is logged
    */
    public function indexAction(Application $app)
    {
        $monthRepository = new MonthRepository($app['db']);

        $token = $app['security.token_storage']->getToken();
        if (null !== $token) {
            $user = $token->getUser();
            $userLogin = $user->getUsername();
        }

        return $app['twig']->render(
            'history/index.html.twig',
            ['paginator' => $monthRepository->getUserMonth($userLogin),
            //['paginator' => $monthRepository->findAllPaginated($page, 'month', 'date_to'),
            //'route_name' => 'month_index_paginated'
            ]
        );
    }

    /**
     * View action.
     */
    public function viewAction(Application $app, $id)                            //wyświetla konkretną krotkę
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
    public function addAction(Application $app, Request $request)                               //funkcja dodawania, wstrzyknięcie w nagłówku obiektu Request do kontrolera.
    {
        $month = [];

        $token = $app['security.token_storage']->getToken();
        if (null !== $token) {
            $user = $token->getUser();
            $userLogin = $user->getUsername();
        }

        $form = $app['form.factory']->createBuilder(MonthType::class, $month)->getForm();      //tworzymy formularz
        $form->handleRequest($request);                                                        //bindujemy do formularza dane wpisane przez użytkownika (znajdujące się w obiekcie $Request).

        if ($form->isSubmitted() && $form->isValid()) {                                        //jeśli dane chciały być przesłane oraz dane są wpisane poprawnie
            $monthRepository = new MonthRepository($app['db']);
            $monthRepository->save($form->getData(), $userLogin);

            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'message.element_successfully_added',
                ]
            );

            return $app->redirect($app['url_generator']->generate('month_index'), 301);       //a potem przekieruj na strone month index
        }

        return $app['twig']->render(                                                       //skorzystaj z szablonu add z katalogu history
            'history/add.html.twig',
            [
                'month' => $month,                               //gdzie month to zmienna month
                'form' => $form->createView(),                   //a form to funckja wyświetl
            ]
        );
    }
}