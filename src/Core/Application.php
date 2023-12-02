<?php

namespace Core;

use Core\Auth\User;
class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    protected array $eventListeners = [];
    public static Application $app;
    public static string $BASE_PATH;
    private $container = [];
    public Router $router;
    public Database $db;
    public Session $session;
    public ?User $user = null;

    public function __construct($rootPath)
    {
        self::$BASE_PATH = $rootPath;
        self::$app = $this;
        $this->container = new Container();
        $this->container->set(Request::class, function () {
            $request = new Request();
            $request->setBaseUrl(BASE_PATH);
            return $request;
        });
        $this->container->set(Response::class, function () {
            return new Response();
        });
        $this->container->set(Router::class, function () {
            return new Router($this->container);
        });
        $this->container->set(Session::class, function () {
            return new Session();
        });
        $this->router = $this->container->get(Router::class);
        $this->session = $this->container->get(Session::class);
        $this->db = Database::getInstance();
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
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->container->get(Response::class)->abort(500, $e->getMessage());
        }
    }
     public function triggerEvent($eventName)
    {
        $callbacks = $this->eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }
    public function login(User $user)
    {
        $this->user = $user;
        $className = get_class($user);
        $primaryKey = $className::primaryKey();
        $value = $user->{$primaryKey};
        Application::$app->session->set('user', $value);

        return true;
    }

    public function logout()
    {
        $this->user = null;
        self::$app->session->remove('user');
    }
}