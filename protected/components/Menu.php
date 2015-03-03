<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author khangld
 */
class Menu extends CWidget {

    public $lang = 'vi';
    
    public function init() {
        $this->lang = Yii::app()->language;
    }

    public function run() {
        
        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;
        
        $this->render('menu', array(
            'controller' => $controller,
            'action' => $action,
        ));
    }

}
