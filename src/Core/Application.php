<?php

namespace Core;

use Core\Auth\User;
class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    public static Application $app;
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?BaseController $controller = null;
    public ?User $user = null;
    public View $view;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = Database::getInstance();
        $this->view = new View();
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $this->user = User::findOne($primaryValue);
        } else {
            $this->user = null;
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }
    public function run()
    {
        try {
            $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }
    public function triggerEvent($eventName)
    {
        $callbacks = $this->router->getEventCallbacks($eventName);
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }
    public function on($eventName, $callback)
    {
        $this->router->on($eventName, $callback);
    }
}