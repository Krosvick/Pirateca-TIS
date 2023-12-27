<?php

namespace Controllers;

use Core\BaseController;


class InfoController extends BaseController {

    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());

    }
    //just render the page
    public function infopage() {
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