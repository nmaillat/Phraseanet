<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2015 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\ControllerProvider;

use Alchemy\Phrasea\Application as PhraseaApplication;
use Alchemy\Phrasea\Controller\LightboxController;
use Alchemy\Phrasea\Core\Event\ValidationEvent;
use Alchemy\Phrasea\Core\PhraseaEvents;
use Alchemy\Phrasea\Model\Entities\Basket;
use Alchemy\Phrasea\Model\Entities\BasketElement;
use Alchemy\Phrasea\Exception\SessionNotFound;
use Alchemy\Phrasea\Controller\Exception as ControllerException;
use Alchemy\Phrasea\Model\Entities\Token;
use Alchemy\Phrasea\Model\Manipulator\TokenManipulator;
use Silex\ControllerProviderInterface;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Lightbox implements ControllerProviderInterface, ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['controller.lightbox'] = $app->share(function () use ($app) {
            return new LightboxController($app);
        });
    }

    public function boot(Application $app)
    {
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->before([$this, 'redirectOnLogRequests']);

        $app['firewall']->addMandatoryAuthentication($controllers);

        $controllers
            // Silex\Route::convert is not used as this should be done prior the before middleware
            ->before($app['middleware.basket.converter'])
            ->before($app['middleware.basket.user-access']);

        $controllers->get('/', 'controller.lightbox:rootAction')
            ->bind('lightbox')
        ;

        $controllers->get('/ajax/NOTE_FORM/{sselcont_id}/', 'controller.lightbox:ajaxNoteFormAction')
            ->bind('lightbox_ajax_note_form')
            ->assert('sselcont_id', '\d+')
        ;

        $controllers->get('/ajax/LOAD_BASKET_ELEMENT/{sselcont_id}/', 'controller.lightbox:ajaxLoadBasketElementAction')
            ->bind('lightbox_ajax_load_basketelement')
            ->assert('sselcont_id', '\d+')
        ;

        $controllers->get('/ajax/LOAD_FEED_ITEM/{entry_id}/{item_id}/', function (Application $app, $entry_id, $item_id) {

            $entry = $app['repo.feed-entries']->find($entry_id);
            $item = $entry->getItem($item_id);

            if ($app['browser']->isMobile()) {
                $output = $app['twig']->render('lightbox/feed_element.html.twig', [
                    'feed_element' => $item,
                    'module_name'  => $item->getRecord($app)->get_title()
                    ]
                );

                return new Response($output);
            } else {
                $template_options = 'lightbox/feed_options_box.html.twig';
                $template_preview = 'common/preview.html.twig';
                $template_caption = 'common/caption.html.twig';

                if (!$app['browser']->isNewGeneration()) {
                    $template_options = 'lightbox/IE6/feed_options_box.html.twig';
                }

                $ret = [];
                $ret['number'] = $item->getRecord($app)->get_number();
                $ret['title'] = $item->getRecord($app)->get_title();

                $ret['preview'] = $app['twig']->render($template_preview, ['record'             => $item->getRecord($app), 'not_wrapped'        => true]);
                $ret['options_html'] = $app['twig']->render($template_options, ['feed_element'  => $item]);
                $ret['caption'] = $app['twig']->render($template_caption, ['view'   => 'preview', 'record' => $item->getRecord($app)]);

                $ret['agreement_html'] = $ret['selector_html'] = $ret['note_html'] = '';

                return $app->json($ret);
            }
        })
            ->bind('lightbox_ajax_load_feeditem')
            ->assert('entry_id', '\d+')
            ->assert('item_id', '\d+');

        $controllers->get('/validate/{basket}/', function (Application $app, $basket) {
            try {
                \Session_Logger::updateClientInfos($app, 6);
            } catch (SessionNotFound $e) {
                return $app->redirectPath('logout');
            }

            $repository = $app['repo.baskets'];

            $basket_collection = $repository->findActiveValidationAndBasketByUser(
                $app['authentication']->getUser()
            );

            if ($basket->getIsRead() === false) {
                $basket = $app['orm.em']->merge($basket);
                $basket->setIsRead(true);
                $app['orm.em']->flush();
            }

            if ($basket->getValidation() && $basket->getValidation()->getParticipant($app['authentication']->getUser())->getIsAware() === false) {
                $basket = $app['orm.em']->merge($basket);
                $basket->getValidation()->getParticipant($app['authentication']->getUser())->setIsAware(true);
                $app['orm.em']->flush();
            }

            $template = 'lightbox/validate.html.twig';

            if (!$app['browser']->isNewGeneration() && !$app['browser']->isMobile()) {
                $template = 'lightbox/IE6/validate.html.twig';
            }

            $response = new Response($app['twig']->render($template, [
                        'baskets_collection' => $basket_collection,
                        'basket'             => $basket,
                        'local_title'        => strip_tags($basket->getName()),
                        'module'             => 'lightbox',
                        'module_name'        => $app->trans('admin::monitor: module validation')
                        ]
                ));
            $response->setCharset('UTF-8');

            return $response;
        })
            ->bind('lightbox_validation')
            ->assert('basket', '\d+');

        $controllers->get('/compare/{basket}/', function (Application $app, Basket $basket) {

            try {
                \Session_Logger::updateClientInfos($app, 6);
            } catch (SessionNotFound $e) {
                return $app->redirectPath('logout');
            }

            $repository = $app['repo.baskets'];

            $basket_collection = $repository->findActiveValidationAndBasketByUser(
                $app['authentication']->getUser()
            );

            if ($basket->getIsRead() === false) {
                $basket = $app['orm.em']->merge($basket);
                $basket->setIsRead(true);
                $app['orm.em']->flush();
            }

            if ($basket->getValidation() && $basket->getValidation()->getParticipant($app['authentication']->getUser())->getIsAware() === false) {
                $basket = $app['orm.em']->merge($basket);
                $basket->getValidation()->getParticipant($app['authentication']->getUser())->setIsAware(true);
                $app['orm.em']->flush();
            }

            $template = 'lightbox/validate.html.twig';

            if (!$app['browser']->isNewGeneration() && !$app['browser']->isMobile()) {
                $template = 'lightbox/IE6/validate.html.twig';
            }

            $response = new Response($app['twig']->render($template, [
                        'baskets_collection' => $basket_collection,
                        'basket'             => $basket,
                        'local_title'        => strip_tags($basket->getName()),
                        'module'             => 'lightbox',
                        'module_name'        => $app->trans('admin::monitor: module validation')
                        ]
                ));
            $response->setCharset('UTF-8');

            return $response;
        })
            ->bind('lightbox_compare')
            ->assert('basket', '\d+');

        $controllers->get('/feeds/entry/{entry_id}/', function (Application $app, $entry_id) {
            try {
                \Session_Logger::updateClientInfos($app, 6);
            } catch (SessionNotFound $e) {
                return $app->redirectPath('logout');
            }

            $feed_entry = $app['repo.feed-entries']->find($entry_id);

            $template = 'lightbox/feed.html.twig';

            if (!$app['browser']->isNewGeneration() && !$app['browser']->isMobile()) {
                $template = 'lightbox/IE6/feed.html.twig';
            }

            $content = $feed_entry->getItems();
            $first = $content->first();

            $output = $app['twig']->render($template, [
                'feed_entry'  => $feed_entry,
                'first_item'  => $first,
                'local_title' => $feed_entry->getTitle(),
                'module'      => 'lightbox',
                'module_name' => $app->trans('admin::monitor: module validation')
                ]
            );
            $response = new Response($output, 200);
            $response->setCharset('UTF-8');

            return $response;
        })
            ->bind('lightbox_feed_entry')
            ->assert('entry_id', '\d+');

        $controllers->get('/ajax/LOAD_REPORT/{basket}/', function (Application $app, Basket $basket) {
            return new Response($app['twig']->render('lightbox/basket_content_report.html.twig', ['basket' => $basket]));
        })
            ->bind('lightbox_ajax_report')
            ->assert('basket', '\d+');

        $controllers->post('/ajax/SET_NOTE/{sselcont_id}/', function (Application $app, $sselcont_id) {
            $output = ['error' => true, 'datas' => $app->trans('Erreur lors de l\'enregistrement des donnees')];

            $request = $app['request'];
            $note = $request->request->get('note');

            if (is_null($note)) {
                Return new Response('You must provide a note value', 400);
            }

            $repository = $app['repo.basket-elements'];

            $basket_element = $repository->findUserElement($sselcont_id, $app['authentication']->getUser());

            $validationDatas = $basket_element->getUserValidationDatas($app['authentication']->getUser());

            $validationDatas->setNote($note);

            $app['orm.em']->merge($validationDatas);

            $app['orm.em']->flush();

            if ($app['browser']->isMobile()) {
                $datas = $app['twig']->render('lightbox/sc_note.html.twig', ['basket_element' => $basket_element]);

                $output = ['error' => false, 'datas' => $datas];
            } else {
                $template = 'lightbox/sc_note.html.twig';

                $datas = $app['twig']->render($template, ['basket_element' => $basket_element]);

                $output = ['error' => false, 'datas' => $datas];
            }

            return $app->json($output);
        })
            ->bind('lightbox_ajax_set_note')
            ->assert('sselcont_id', '\d+');

        $controllers->post('/ajax/SET_ELEMENT_AGREEMENT/{sselcont_id}/', function (Application $app, $sselcont_id) {
            $request = $app['request'];
            $agreement = $request->request->get('agreement');

            if (is_null($agreement)) {
                Return new Response('You must provide an agreement value', 400);
            }

            $agreement = $agreement > 0;

            $releasable = false;
            try {
                $ret = [
                    'error'      => true,
                    'releasable' => false,
                    'datas'      => $app->trans('Erreur lors de la mise a jour des donnes')
                ];

                $repository = $app['repo.basket-elements'];

                $basket_element = $repository->findUserElement(
                    $sselcont_id
                    , $app['authentication']->getUser()
                );
                /* @var $basket_element BasketElement */
                $validationDatas = $basket_element->getUserValidationDatas($app['authentication']->getUser());

                if (!$basket_element->getBasket()
                        ->getValidation()
                        ->getParticipant($app['authentication']->getUser())->getCanAgree()) {
                    throw new ControllerException('You can not agree on this');
                }

                $validationDatas->setAgreement($agreement);

                $participant = $basket_element->getBasket()
                    ->getValidation()
                    ->getParticipant($app['authentication']->getUser());

                $app['orm.em']->merge($basket_element);

                $app['orm.em']->flush();

                $releasable = false;
                if ($participant->isReleasable() === true) {
                    $releasable = $app->trans('Do you want to send your report ?');
                }

                $ret = [
                    'error'      => false
                    , 'datas'      => ''
                    , 'releasable' => $releasable
                ];
            } catch (ControllerException $e) {
                $ret['datas'] = $e->getMessage();
            }

            return $app->json($ret);
        })
            ->bind('lightbox_ajax_set_element_agreement')
            ->assert('sselcont_id', '\d+');

        $controllers->post('/ajax/SET_RELEASE/{basket}/', function (Application $app, Basket $basket) {

            $datas = ['error' => true, 'datas' => ''];

            try {
                if (!$basket->getValidation()) {
                    throw new ControllerException('There is no validation session attached to this basket');
                }

                if (!$basket->getValidation()->getParticipant($app['authentication']->getUser())->getCanAgree()) {
                    throw new ControllerException('You have not right to agree');
                }

                $agreed = false;
                /* @var $basket Basket */
                foreach ($basket->getElements() as $element) {
                    if (null !== $element->getUserValidationDatas($app['authentication']->getUser())->getAgreement()) {
                        $agreed = true;
                    }
                }

                if (!$agreed) {
                    throw new ControllerException($app->trans('You have to give your feedback at least on one document to send a report'));
                }

                /* @var $basket Basket */
                $participant = $basket->getValidation()->getParticipant($app['authentication']->getUser());

                $token = $app['manipulator.token']->createBasketValidationToken($basket);
                $url = $app->url('lightbox', ['LOG' => $token->getValue()]);

                $to = $basket->getValidation()->getInitiator($app)->getId();

                $app['dispatcher']->dispatch(PhraseaEvents::VALIDATION_DONE, new ValidationEvent($participant, $basket, $url));

                $participant->setIsConfirmed(true);

                $app['orm.em']->merge($participant);
                $app['orm.em']->flush();

                $datas = ['error' => false, 'datas' => $app->trans('Envoie avec succes')];
            } catch (ControllerException $e) {
                $datas = ['error' => true, 'datas' => $e->getMessage()];
            }

            return $app->json($datas);
        })
            ->bind('lightbox_ajax_set_release')
            ->assert('basket', '\d+');

        return $controllers;
    }

    /**
     * @param Request            $request
     * @param PhraseaApplication $app
     * @return RedirectResponse|null
     */
    public function redirectOnLogRequests(Request $request, PhraseaApplication $app)
    {
        if (!$request->query->has('LOG')) {
            return null;
        }

        if ($app['authentication']->isAuthenticated()) {
            $app['authentication']->closeAccount();
        }

        if (null === $token = $app['repo.tokens']->findValidToken($request->query->get('LOG'))) {
            $app->addFlash('error', $app->trans('The URL you used is out of date, please login'));

            return $app->redirectPath('homepage');
        }

        /** @var Token $token */
        $app['authentication']->openAccount($token->getUser());

        switch ($token->getType()) {
            case TokenManipulator::TYPE_FEED_ENTRY:
                return $app->redirectPath('lightbox_feed_entry', ['entry_id' => $token->getData()]);
            case TokenManipulator::TYPE_VALIDATE:
            case TokenManipulator::TYPE_VIEW:
               return $app->redirectPath('lightbox_validation', ['basket' => $token->getData()]);
        }

        return null;
    }
}
