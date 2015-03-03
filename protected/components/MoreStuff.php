<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoreStuff
 *
 * @author khangld
 */
class MoreStuff extends CWidget {

    public $lang = '';

    public function init() {
        $this->lang = Yii::app()->language;
    }

    public function run() {

        if (Yii::app()->user->isGuest) {
            $this->render($this->lang . '/more-stuff');
        }
    }

}
