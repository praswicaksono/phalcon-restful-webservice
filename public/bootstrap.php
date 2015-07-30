<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    [
        "Jowy\\Phrest" => __DIR__ . "/../app"
    ],
    true
)->register();

$di = new \Phalcon\DI\FactoryDefault();

$di->setShared(
    "config",
    function () {
        require __DIR__ . "/../app/Config/Config.php";
        return new \Phalcon\Config($config);
    }
);

$di->setShared(
    "db",
    function () use ($di) {
        return new \Phalcon\Db\Adapter\Pdo\Mysql(
            [
                "host" => $di["config"]->database->host,
                "username" => $di["config"]->database->username,
                "password" => $di["config"]->database->password,
                "port" => $di["config"]->database->port,
                "dbname" => $di["config"]->database->dbname
            ]
        );
    }
);

$di->setShared(
    "apiResponse",
    function () {
        $response = new \Jowy\Phrest\Core\Response(new \League\Fractal\Manager());
        $response->setPhalconResponse(new \Phalcon\Http\Response());
        return $response;
    }
);

$di->set(
    "router",
    function () {
        $router = new \Phalcon\Mvc\Router\Annotations(false);

        $files = array_diff(scandir(__DIR__ . "/../app/Controllers/"), array('..', '.'));

        foreach ($files as $file) {
            $file = array_slice(preg_split('/(?=[A-Z])/', $file), 1);
            $router->addResource("Jowy\\Phrest\\Controllers\\" . $file[0]);
        }

        return $router;
    }
);

$di->set(
    "view",
    function () {
        $view = new \Phalcon\Mvc\View();

        $view->disable();

        return $view;
    }
);

$di->setShared(
    "dispatcher",
    function () use ($di) {
        $eventsManager = $di->getShared('eventsManager');
        $security = new \Jowy\Phrest\Core\Security($di);

        $eventsManager->attach("dispatch", $security, 2);

        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;

    }
);

$di->setShared(
    "security",
    function () {
        $security = new \Phalcon\Security();

        $security->setWorkFactor(12);

        return $security;
    }
);

\Phalcon\DI::setDefault($di);
