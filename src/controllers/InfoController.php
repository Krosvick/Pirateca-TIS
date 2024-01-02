<?php

namespace Controllers;

use Core\BaseController;


/**
 * Class InfoController
 *
 * This class is a constructor method for the InfoController class, which extends the BaseController class.
 * It calls the parent constructor to inherit the properties and methods of the BaseController class.
 */
class InfoController extends BaseController {

    /**
     * InfoController constructor.
     *
     * @param object $container The container object.
     * @param array $routeParams The route parameters.
     */
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());

    }

 
    /**
     * Method to display the information page.
     *
     * This method creates an array called $data with a single key-value pair, and another array called $metadata with two key-value pairs.
     * Then, it creates a third array called $optionals that contains the $data and $metadata arrays.
     * Finally, it calls the render() method of the parent class, passing the string "information" as the first argument and the $optionals array as the second argument.
     *
     * @return void The result of calling the render() method of the parent class with the arguments "information" and the $optionals array.
     */
    public function infoPage() {
        $data = [
            'title' => 'Info'
        ];
        $metadata = [
            'title' => 'Info',
            'description' => 'Info page',
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];
        return $this->render("information", $optionals);
    }

}
?>