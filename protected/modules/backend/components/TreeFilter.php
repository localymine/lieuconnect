<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TreeFilter
 *
 * @author khangld
 */
class TreeFilter extends CWidget {

    public $submit_form = '';
    public $submit_name = '';
    public $filter = '';
    public $multi_select = false;   // not allow multi select
    public $select = array();

    public function init() {
        
    }

    public function run(){
        $this->render('tree-filter');
    }
}
