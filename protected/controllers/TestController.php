<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestController
 *
 * @author khangld
 */
class TestController extends FrontController {

    public $root = 'logs';
    public $content = '';
    
    public function actionIndex() {
        
        echo '<pre>';
        
        echo date('Y-m-d H:i:s');
        echo '<br>';
        
//        $log = new Logs();
//        $log->root = 'logs/search';
//        $log->content = array (
//            array('222', '222', '222', '222'),
//        );
//        $log->track();

        //
        
        $log = new Logs();
        $log->root = 'logs/search';
        $obj = new Date_Time_Calc(date('Y-m-d'), 'Y-m-d');
        $day_before = $obj->subtract('d', 1);
        $data = $log->read($day_before);
        
        //
        $log->insert_search_activity($data);
        $log->analys_search_data($day_before);
        
    }

}
