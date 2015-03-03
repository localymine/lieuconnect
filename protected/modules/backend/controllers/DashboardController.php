<?php

class DashboardController extends MyController {

    public function actionIndex() {

        Yii::import('ext.gapi.gapi');

//        $ga_email = Yii::app()->params['ga_email'];
//        $ga_password = Yii::app()->params['ga_password'];
//        $ga_profile_id = Yii::app()->params['ga_profile_id'];
//        $ga_url = Yii::app()->params['ga_request_url'];

        $ga_email = 'leduongkhang@gmail.com';
        $ga_password = 'twin514soar8980';
        $ga_profile_id = '78138141';
        $ga_url = 'caucahaiden.com';
//        $ga_url = $_SERVER['REQUEST_URI'];

        $results = NULL;
        $chart = array();
        // google analytics api
        if ($ga_email != '' && $ga_password != '' && $ga_profile_id != '') {
            $ga = new gapi($ga_email, $ga_password);
            //        $ga->requestReportData($ga_profile_id, 'pagePath', array('pageviews', 'uniquePageviews', 'exitRate', 'avgTimeOnPage', 'entranceBounceRate'), null, 'pagePath == '.$ga_url);
            $ga->requestReportData($ga_profile_id, array('date'), array('pageviews', 'uniquePageviews', 'exitRate', 'avgTimeOnPage', 'entranceBounceRate', 'newVisits'), 'date');
            $results = $ga->getResults();

            //        $ga->requestReportData($ga_profile_id, array('date'), array('pageviews'), 'date', 'pagePath == ' . $ga_url);
            $ga->requestReportData($ga_profile_id, array('date'), array('pageviews'), 'date');
            $chart = $ga->getResults();
            
            $ga->requestReportData($ga_profile_id, array('week'), array('pageviews'), 'week');
            $chart_week = $ga->getResults();
            
            $ga->requestReportData($ga_profile_id, array('year'), array('pageviews'), 'year');
            $chart_year = $ga->getResults();

            $ga->requestReportData($ga_profile_id, array('hour'), array('pageviews'), 'hour');
            $chart_hour = $ga->getResults();
            
        }

        // summary posts
        $summary_post = Post::model()->total_post_type();

        $this->render('index', array(
            'summary' => $summary_post,
            'results' => $results,
            'chart' => $chart,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
