<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanguageSelector
 *
 * @author khangld
 */
class LanguageSelector extends CWidget {

    public function run() {

        $app = Yii::app();
        $route = $app->controller->route;
        $languages = $app->request->languages;
        $language = $app->language;
        $params = $_GET;
//        $params = array();
        
        array_unshift($params, $route);

//        $currentLang = Yii::app()->language;
//        $languages = Yii::app()->params->translatedLanguages;
        
        $this->render('language_selector', array(
            'route' => $route,
            'params' => $params,
            'languages' => $languages,
            'language' => $language,
        ));
    }

}
