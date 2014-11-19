<?php


require_once "../vendor/autoload.php";

include __DIR__ . "/bootstrap.php";


try {
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();
} catch (\Exception $e) {
    $di["apiResponse"]->errorUnauthorized();
}
// EOF
