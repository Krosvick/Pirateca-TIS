<?php

namespace Controllers;

use Core\BaseController;


class InfoController extends BaseController {

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
        return $this->render("infomartion", $optionals);
    }
    
}

?>