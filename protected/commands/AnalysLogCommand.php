<?php

/*
 * In your crontab add:
 * 
 * local
 * yiic sitemap index --type=News --limit=5
 * yicc analysLog
 * yiic analysLog index date=2014-09-18
 * 
 * server
 * php /path/to/cron.php test 
 * php /path/to/console.php analysLog
 *  
 */

class AnalysLogCommand extends CConsoleCommand {

    public function actionInit() {
        
    }

    public function actionIndex($date = NULL) {

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
