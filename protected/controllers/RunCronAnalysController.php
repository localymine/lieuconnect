<?php

/**
 * Description of RunCronAnalysController
 *
 * @author khangld
 */
class RunCronAnalysController extends FrontController {

    private $allow_ips = array(
        '127.0.0.1',
	'69.164.195.172'
    );

    public function actionIndex($date = NULL) {

        $ip = $_SERVER['SERVER_ADDR'];

        if (!in_array($ip, $this->allow_ips)) {
            echo 'Access denied.';
            Yii::app()->end();
        }

        $log = new Logs();
        $log->root = 'logs/search';
        $current_date = date('Y-m-d');

        if (isset($date) && $date != NULL) {
            $date = explode('=', $date[0]);
            $day_before = $date[1];
        } else {
            $obj = new Date_Time_Calc($current_date, 'Y-m-d');
            $day_before = $obj->subtract('d', 1);
        }

        echo "..." . PHP_EOL;
        echo "*----------------------------------------------------------------*" . PHP_EOL;
        echo "- Date Run cron: $current_date" . PHP_EOL;
        echo "- Date Analytics Data: $day_before" . PHP_EOL;
        echo "*----------------------------------------------------------------*" . PHP_EOL;
        echo "..." . PHP_EOL;

        $data = $log->read($day_before);
        //
        $log->insert_search_activity($data);
        //
        $log->analys_search_data($day_before);

        echo "done" . PHP_EOL;
        return 1;
    }

}
