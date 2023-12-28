<?php

/** AID dont delete */
namespace Core;

use Models\User;
/**
 * Class Application
 *
 * This class represents an application and defines its properties and methods.
 */
class Application
{
    /**
     * The event name before a request is made.
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';

    /**
     * The event name after a request is made.
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * An array of event listeners.
     *
     * @var array
     */
    protected array $eventListeners = [];

    /**
     * The static instance of the Application class.
     *
     * @var Application
     */
    public static Application $app;

    /**
     * The base path of the application.
     *
     * @var string
     */
    public static string $BASE_PATH;

    /**
     * The container for dependencies.
     *
     * @var Container
     */
    private $container = [];

    /**
     * The router instance.
     *
     * @var Router
     */
    public Router $router;

    /**
     * The database instance.
     *
     * @var Database
     */
    public Database $db;

    /**
     * The session instance.
     *
     * @var Session
     */
    public Session $session;
    /**
     * 
     * @var BaseController
     */
    public ?BaseController $controller = null;
    /**
     * The user instance.
     *
     * @var User|null
     */
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
        $primaryValue = $this->session->get('usernumber');
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
     * Checks if the user is an admin.
     * 
     * @return bool Returns true if the user is an admin, false otherwise.
     */
    public static function isAdmin()
    {
        if (self::$app->user) {
            return self::$app->user->get_role() === 'admin';
        }
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
     /**
      * Triggers a specific event by calling all the callback functions associated with that event.
      *
      * @param string $eventName The name of the event to trigger.
      * @return void
      */
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
        unset($user->DAOs);
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
        self::$app->session->remove('usernumber');
    }
    /**
     * Returns the user's id.
     *
     * @return int The user's id.
     */

    public static function get_user_id()   
    {
        return Application::$app->user->get_id();
    }   
}