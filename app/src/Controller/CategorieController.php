<?php
/**
 * Categorie controller.
 */
namespace Controller;


use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Repository\CategorieRepository;
use Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
        $controller->get('/{id}', [$this, 'viewAction'])
            ->assert('id', '[1-9]\d*')
            ->bind('categorie_view');
        $controller->match('/add', [$this, 'addAction'])
            ->method('POST|GET')
            ->bind('categorie_add');
        $controller->match('/{id}/edit', [$this, 'editAction'])
            ->method('GET|POST')
            ->assert('id', '[1-9]\d*')
            ->bind('categorie_edit');
        $controller->match('/{id}/delete', [$this, 'deleteAction'])
            ->method('GET|POST')
            ->assert('id', '[1-9]\d*')
            ->bind('categorie_delete');

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

    /**
     * Add action.
     */
    public function addAction(Application $app, Request $request)
    {
        $categorie = [];

        $form = $app['form.factory']->createBuilder(CategorieType::class, $categorie)->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository = new CategorieRepository($app['db']);
            $categorieRepository->save($form->getData());

            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'message.element_successfully_added',
                ]
            );

            return $app->redirect($app['url_generator']->generate('categorie_index'), 301);
        }

        return $app['twig']->render(
            'categorie/add.html.twig',
            [
                'categorie' => $categorie,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Silex\Application                        $app     Silex application
     * @param int                                       $id      Record id
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function editAction(Application $app, $id, Request $request)
    {
        $categorieRepository = new CategorieRepository($app['db']);
        $categorie = $categorieRepository->findOneById($id);

        if (!$categorie) {
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'warning',
                    'message' => 'message.record_not_found',
                ]
            );

            return $app->redirect($app['url_generator']->generate('categorie_index'));
        }

        $form = $app['form.factory']->createBuilder(CategorieType::class, $categorie)->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($form->getData());

            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'message.element_successfully_edited',
                ]
            );

            return $app->redirect($app['url_generator']->generate('categorie_index'), 301);
        }

        return $app['twig']->render(
            'categorie/edit.html.twig',
            [
                'categorie' => $categorie,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete action
     */
    public function deleteAction(Application $app, $id, Request $request)
    {
        $categorieRepository = new CategorieRepository($app['db']);
        $categorie = $categorieRepository->findOneById($id);

        if (!$categorie) {
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'warning',
                    'message' => 'message.record_not_found',
                ]
            );

            return $app->redirect($app['url_generator']->generate('categorie_index'));
        }

        $form = $app['form.factory']->createBuilder(FormType::class, $categorie)->add('id', HiddenType::class)->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->delete($form->getData());

            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'message.element_successfully_deleted',
                ]
            );

            return $app->redirect(
                $app['url_generator']->generate('categorie_index'),
                301
            );
        }

        return $app['twig']->render(
            'categorie/delete.html.twig',
            [
                'categorie' => $categorie,
                'form' => $form->createView(),
            ]
        );
    }
}