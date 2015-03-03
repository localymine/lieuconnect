<?php

class Breadcrumb extends CWidget {

    public $current_page = '';
    public $path = array();
    
    //put your code here
    public function init() {
        // this method is called by CController::beginWidget()
    }

    public function run() {
        
        $this->render('breadcrumb', array(
            'path' => $this->path,
            'current_page' => $this->current_page,
        ));
    }

}
