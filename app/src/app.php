<?php
/**
 * Init application.
 */
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;

$app = new Application();
$app->register(new AssetServiceProvider());
$app->register(new ServiceControllerServiceProvider()); //żeby móc używać dev
$app->register(new HttpFragmentServiceProvider()); //żeby nie wyskakiwał error 500 czy dev
$app->register(new FormServiceProvider());            //do formularzy
$app->register(new ValidatorServiceProvider());      //do walidacji formularzy
$app->register(new SessionServiceProvider());          //do sesji
use Silex\Provider\SecurityServiceProvider;           //do logowania

$app->register(
    new TwigServiceProvider(),
    [
        'twig.path' => dirname(dirname(__FILE__)).'/templates',
    ]
);

$app->register(new LocaleServiceProvider());
$app->register(
    new TranslationServiceProvider(),
    [
        'locale' => 'pl',
        'locale_fallbacks' => array('en'),
    ]
);
$app->extend('translator', function ($translator, $app) {
    $translator->addResource('xliff', __DIR__.'/../translations/messages.en.xml', 'en', 'messages');
    $translator->addResource('xliff', __DIR__.'/../translations/validators.en.xml', 'en', 'validators');
    $translator->addResource('xliff', __DIR__.'/../translations/messages.pl.xml', 'pl', 'messages');
    $translator->addResource('xliff', __DIR__.'/../translations/validators.pl.xml', 'pl', 'validators');

    return $translator;
});

$app->register(
    new DoctrineServiceProvider(),
    [
        'db.options' => [
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'projekt2',
            'user'      => 'user',
            'password'  => 'user',
            'charset'   => 'utf8',
            'collate' => 'utf8mb4_unicode_ci',
            'driverOptions' => [
                1002 => 'SET NAMES utf8',
            ],
        ],
    ]
);
/*ustawić ut-8 */
$dbh = new PDO ('mysql:host=localhost; dbname=projekt2', 'user', 'user');
$dbh -> query ('SET NAMES utf8');
$dbh -> query ('SET CHARACTER_SET utf8_unicode_ci');

$app->register(
    new SecurityServiceProvider(),
    [
        'security.firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'pattern' => '^.*$',
                'form' => [
                    'login_path' => 'auth_login',
                    'check_path' => 'auth_login_check',
                    'default_target_path' => 'month_index',
                    'username_parameter' => 'login_type[login]',
                    'password_parameter' => 'login_type[password]',
                ],
                'anonymous' => true,
                'logout' => [
                    'logout_path' => 'auth_logout',
                    'target_url' => 'month_index',
                ],
                'users' => function () use ($app) {
                    return new Provider\UserProvider($app['db']);
                },
            ],
        ],
        'security.access_rules' => [
            ['^/auth.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'],
            ['^/.+$', 'ROLE_USER'],
        ],
        'security.role_hierarchy' => [
            'ROLE_ADMIN' => ['ROLE_USER'],
        ],
    ]
);

//dump($app['security.encoder.bcrypt']->encodePassword('test-admin', ''));

return $app;