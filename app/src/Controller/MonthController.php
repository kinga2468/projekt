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
     * Index action.
     */
    public function indexAction(Application $app, $page = 1)                      //wyświetla wszystko od strony 1
    {
        $monthRepository = new MonthRepository($app['db']);                       //działa na bazie danych

        return $app['twig']->render(                                              //zwraca
            'history/index.html.twig',                                            //szablon index z katalogu history
            ['paginator' => $monthRepository->findAllPaginated($page)]            //używa funkcji find all paginated od strony 1
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
            ['month' => $monthRepository->findOneById($id)]                      //wykorzystuje funkcje find one by id
        );
    }

    /**
     * Add action.
     */
    public function addAction(Application $app, Request $request)                               //funkcja dodawania, wstrzyknięcie w nagłówku obiektu Request do kontrolera.
    {
        $month = [];                                                                             //tablica month jest pusta

        $form = $app['form.factory']->createBuilder(MonthType::class, $month)->getForm();      //tworzymy formularz
        $form->handleRequest($request);                                                        //bindujemy do formularza dane wpisane przez użytkownika (znajdujące się w obiekcie $Request).

        if ($form->isSubmitted() && $form->isValid()) {                                        //jeśli dane chciały być przesłane oraz dane są wpisane poprawnie
            $monthRepository = new MonthRepository($app['db']);
            $monthRepository->save($form->getData());                                          //zapisz dane

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