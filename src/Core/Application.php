<?php

/** AID dont delete */
namespace Core;

use Models\User;
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

    /**
     * Checks if the user is a guest.
     *
     * @return bool Returns true if the user is a guest, false otherwise.
     */
    public static function isGuest()
    {
        return !self::$app->user;
    }
    /**
     * Runs the application.
     *
     * @return void
     */
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

    /**
     * Adds a callback function to the event listeners array for a given event name.
     *
     * @param string $eventName The name of the event to listen for.
     * @param callable $callback The callback function to be executed when the event is triggered.
     * @return void
     */
    public function on($eventName, $callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }
    /**
     * Logs in a user.
     *
     * This method is responsible for logging in a user by setting the `user` property of the `Application` instance,
     * storing the user's primary key value in the session, and storing the user object itself in the session.
     *
     * @param User Object to be logged in.
     * @return bool ¿Login was successful? then "True".
     */
    public function login(User $user)
    {
        $this->user = $user;
        $className = get_class($user);
        $primaryKey = $className::primaryKey();
        $value = $user->{"get_$primaryKey"}();
        Application::$app->session->set('usernumber', $value);
        Application::$app->session->set('user', $user);

        return true;
    }

    /**
     * Logs out the user by setting the 'user' property to null and removing the 'user' key from the session.
     *
     * @return void
     */
    public function logout()
    {
        $this->user = null;
        self::$app->session->remove('user');
    }
}