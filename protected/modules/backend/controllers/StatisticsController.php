<?php

class StatisticsController extends MyController {

    public function actionIndex() {

        $statistic = new Statistics;
        $startDate = '';
        $endDate = '';

        $cur_date = date('Y-m-d');
        if (isset($_POST['startDate']) && $_POST['startDate'] != '') {
            if ($_POST['endDate'] != '') {
                $day_before = $_POST['startDate'];
                $to_date = $_POST['endDate'];
            } else {
                $day_before = $_POST['startDate'];
                $obj = new Date_Time_Calc($cur_date, 'Y-m-d');
                $to_date = $obj->subtract('d', 1);
            }
            $startDate = $day_before;
            $endDate = $to_date;
        } else {
            $obj = new Date_Time_Calc($cur_date, 'Y-m-d');
            $to_date = $obj->subtract('d', 1);
            $obj = new Date_Time_Calc($to_date, 'Y-m-d');
            $day_before = $obj->subtract('d', 6);
        }

        $data_use_search_function = NULL;
        $data_use_search_function_2 = NULL;
        $data_use_search_function_of_student = NULL;
        $data_use_search_function_user = NULL;

        $data = $statistic->total_times_use_search($day_before, $to_date);
        // ['Date', 'all', 'college', 'internship', 'scholarship'],
        if (isset($data)) {
            foreach ($data as $row) {
                $d = Common::date_format($row['create_date'], 'Y-m-d');
                $k = $row['kind_of_search'];
                $data_use_search_function[$d][$k][] = $row['total'];
            }

            $data_use_search_function_2 = $statistic->total_times_use_search2($day_before, $to_date);
        }

        $data = $statistic->total_times_use_search_of_student($day_before, $to_date);
        if (isset($data)) {
            foreach ($data as $row) {
                $d = Common::date_format($row['create_date'], 'Y-m-d');
                $k = $row['kind_of_student'];
                $data_use_search_function_of_student[$d][$k][] = $row['total'];
            }
        }

        $data = $statistic->total_times_use_search_user($day_before, $to_date);
        if (isset($data)) {
            foreach ($data as $row) {
                $d = Common::date_format($row['create_date'], 'Y-m-d');
                $user = $row['kind_of_user'];
                $data_use_search_function_user[$d][$user][] = $row['total'];
            }
        }

        $this->render('index', array(
            'data_use_search_function' => $data_use_search_function,
            'data_use_search_function_2' => $data_use_search_function_2,
            'data_use_search_function_of_student' => $data_use_search_function_of_student,
            'data_use_search_function_user' => $data_use_search_function_user,
            'from' => $day_before,
            'to' => $to_date,
            'startDate' => $startDate,
            'endDate' => $endDate,
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

}
