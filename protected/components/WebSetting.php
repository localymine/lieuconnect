<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebSetting
 *
 * @author khangld
 */
class WebSetting extends CApplicationComponent {

    function getValue($key) {
        $model = Setting::model()->findByAttributes(array('name' => $key));
        return $model->value;
    }

}
