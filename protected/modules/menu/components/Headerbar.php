<?php

/**
 * Description of Headerbar
 *
 * @author khangld
 */
class Headerbar extends CWidget {

    //put your code here
    public function init() {
        // this method is called by CController::beginWidget()
    }

    public function run() {

        $this->render('headerbar');
    }

}
