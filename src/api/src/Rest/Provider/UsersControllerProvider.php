<?php
namespace Rest\Provider;

use Rest\Controller\UsersController;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class UsersControllerProvider implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $app['users.controller'] = function () use ($app) {
            return new UsersController(
                $app['request_stack']->getCurrentRequest(),
                $app['users.repository'],
                $app['validator'],
                $app['monolog']);
        };

        $controllers = $app['controllers_factory'];

        $controllers->get('/', "users.controller:getUsersJsonAction");
        $controllers->post('/', "users.controller:postJsonAction");
        $controllers->delete('/{user_id}', "users.controller:deleteJsonAction")->assert('user_id', '\d+');
        $controllers->put('/{user_id}', "users.controller:putJsonAction")->assert('user_id', '\d+');
        $controllers->get('/{user_id}', "users.controller:getUserJsonAction")->assert('user_id', '\d+');

        return $controllers;
    }
}