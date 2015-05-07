<?php


namespace Application;


use Phalcon\Config\Adapter\Ini;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Router;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class Bootstrap
{
    /**
     * @var \Phalcon\DI
     */
    protected $di;

    public function __construct()
    {
        $this->di = new DiApplication();
        $this->setDb();
        $this->setRoutes();
        $this->setSession();
    }

    /**
     * Sets a Db service
     */
    public function setDb()
    {
        $config = $this->getIniConfiguration('application');

        $this->di->set('db', [
            'className' => 'Phalcon\Db\Adapter\Pdo\Mysql',
            'arguments' => [
                [
                    'type' => 'parameter',
                    'value' => [
                        'host' => $config->db->host,
                        'username' => $config->db->username,
                        'password' => $config->db->password,
                        'dbname' => $config->db->name
                    ]
                ]
            ]
        ]);
    }

    /**
     * Sets a Session service
     */
    public function setSession()
    {
        $this->di->set('session', function() {
            $session = new SessionAdapter();
            $session->start();
            return $session;
        });
    }

    /**
     * Sets a Router service
     */
    public function setRoutes()
    {
        $this->di->set('router', function() {
            // create a router without default rotes
            $router = new Router(false);

            // set a 404 page
            $router->notFound([
                'module' => 'admin',
                'controller' => 'error',
                'action' => 'notFound'
            ]);

            $routes = $this->getIniConfiguration('routes');
            foreach ($routes as $route) {
                $router->add($route->pattern, [
                    'module' => $route->module,
                    'controller' => $route->controller,
                    'action' => $route->action
                ]);
            }

            return $router;
        });
    }

    /**
     * Creates an Application instance and calls registerModules()
     *
     * @return \Phalcon\Mvc\Application
     */
    public function createApplication()
    {
        $application = new Application($this->di);

        $this->registerModules($application);

        return $application;
    }

    /**
     * Registers modules
     *
     * @param \Phalcon\Mvc\Application$application
     */
    public function registerModules(Application $application)
    {
        $application->registerModules($this->getIniConfiguration('modules')->toArray());
    }

    /**
     * Gets concrete Ini configuration instance by name
     *
     * @param string $name
     * @return \Phalcon\Config\Adapter\Ini
     */
    public function getIniConfiguration($name)
    {
        return new Ini("../application/configs/{$name}.ini");
    }

}