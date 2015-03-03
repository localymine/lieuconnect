<?php

/**
 * Description of Logs
 *
 * @author khangld
 */
class Logs extends CAttributeCollection {

    public $root = 'logs';
    public $content = '';
    public $extension = 'csv';

    public function track() {

        $cf = Yii::app()->file;

        $log_path = $this->root . '/' . date('Y') . '/' . date('m') . '/' . date('d');
        $cf->createDir(0755, $log_path);

        $filename = date('H') . '.' . $this->extension;
        $myfile = $cf->set($log_path . '/' . $filename, true);
        $myfile->create();
        $myfile->setPermissions('0755');

        if (is_array($this->content)) {
            foreach ($this->content as $fields) {
                $line_content = implode(',', $fields) . PHP_EOL;
                $line_content = iconv("CP1257", "UTF-8", $line_content);
                $myfile->setContents($line_content, true, FILE_APPEND);
            }
        } else {
            $myfile->setContents($this->content . PHP_EOL, true, FILE_APPEND);
        }
    }

    public function read($date = '0000-00-00', $filename = '') {

        $cf = Yii::app()->file;

        $date = explode('-', $date);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        $log_path = $this->root . '/' . $year . '/' . $month . '/' . $day;

        if ($filename != '') {
            $myfile = $cf->set($log_path . '/' . $filename, true);
            $all_contents = $myfile->getContents();
        } else {
            $readfile = $cf->set($log_path);
            $load_files = $readfile->getContents();
            $row = 1;
            $all_contents = array();
            if ($load_files != false){
                foreach ($load_files as $file) {
                    if (($handle = fopen($file, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle)) !== FALSE) {
                            $all_contents[] = $data;
                        }
                        fclose($handle);
                    }
                }
            }
        }
        //
        return $all_contents;
    }

    /*
     * Step 1
     * cron: run this once after a day.
     */

    public function insert_search_activity($data = NULL) {

        $db = Yii::app()->db;
        $table = 'log_search';
        $columns = array(
            'id' => 'BIGINT UNSIGNED NOT NULL',
            'user' => 'BIGINT UNSIGNED DEFAULT 0', // user_id, not user = 0
            'username' => 'VARCHAR(64) DEFAULT NULL', // not user = ''
            'kind_of_student' => 'VARCHAR(64) DEFAULT NULL', // student college
            'activity' => "VARCHAR(64) DEFAULT NULL COMMENT 'scholarship, internship, college, all'", // search in form scholarship internship college All
            'keyword' => 'VARCHAR(512) DEFAULT NULL',
            'major' => 'VARCHAR(128) DEFAULT NULL',
            'location' => 'VARCHAR(128) DEFAULT NULL',
            'create_date' => "datetime DEFAULT '0000-00-00 00:00:00'",
        );
        $options = 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

        if ($db->schema->getTable($table, true) === null) {
            // table does not exist
            $command = $db->createCommand();
            $command->createTable($table, $columns, $options);
            $command->addPrimaryKey('id', $table, 'id');
            $command->alterColumn($table, 'id', 'BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT');
        } else {
            // table exists
            $command = $db->createCommand();
            $command->dropTable($table);
            $command->createTable($table, $columns, $options);
            $command->addPrimaryKey('id', $table, 'id');
            $command->alterColumn($table, 'id', 'BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT');
        }
        //
        $command = $db->createCommand();
        foreach ($data as $row) {
            $data = array(
                'user' => $row[0],
                'username' => $row[1],
                'kind_of_student' => $row[2],
                'activity' => $row[3],
                'keyword' => $row[4],
                'major' => $row[5],
                'location' => $row[6],
                'create_date' => $row[7],
            );
            $command->insert($table, $data);
        }
    }

    /*
     * Analys search_data from table log_search and add data to log_search_analys_data
     */

    public function analys_search_data($day_before = '') {
        $db = Yii::app()->db;
        $table = 'log_search_analys_data';
        $columns = array(
            'id' => 'BIGINT UNSIGNED NOT NULL',
            'kind_of_search' => "VARCHAR(64) DEFAULT NULL COMMENT 'scholarship, internship, college, all'", // search in form scholarship internship college all
            'kind_of_student' => "VARCHAR(64) DEFAULT NULL COMMENT 'high-school, college'", // high-school or college
            'kind_of_user' => "VARCHAR(64) DEFAULT NULL COMMENT 'user, non-user'", // user or non-user
            'total_use' => 'BIGINT UNSIGNED DEFAULT 0',
            'create_date' => "datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'activity in date'",
            'runcron_date' => "datetime DEFAULT '0000-00-00 00:00:00'",
        );
        $options = 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

        if ($db->schema->getTable($table, true) === null) {
            // table does not exist
            $command = $db->createCommand();
            $command->createTable($table, $columns, $options);
            $command->addPrimaryKey('id', $table, 'id');
            $command->alterColumn($table, 'id', 'BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        $current_date = date('Y-m-d H:i:s');
        if ($day_before == '') {
            $obj = new Date_Time_Calc($current_date, 'Y-m-d');
            $day_before = $obj->subtract('d', 1);
        }
        //
        $sql = " SELECT * 
                FROM `$table` 
                WHERE DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' 
                LIMIT 1";
        $data = $db->createCommand($sql)->queryAll();
        if ($data != NULL) {
            Yii::app()->end();
        }

        // scholarship - high-school - user
        $sql1 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'scholarship' AND kind_of_student LIKE 'high-school' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // scholarship - high-school - non-user
        $sql2 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'scholarship' AND kind_of_student LIKE 'high-school' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // scholarship - college - user
        $sql3 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'scholarship' AND kind_of_student LIKE 'college' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // scholarship - college - non-user
        $sql4 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'scholarship' AND kind_of_student LIKE 'college' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // internship - high-school - user
        $sql5 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'internship' AND kind_of_student LIKE 'high-school' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // internship - high-school - non-user
        $sql6 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'internship' AND kind_of_student LIKE 'high-school' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // internship - college - user
        $sql7 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'internship' AND kind_of_student LIKE 'college' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // internship - college - non-user
        $sql8 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'internship' AND kind_of_student LIKE 'college' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // college - high-school - user
        $sql9 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'college' AND kind_of_student LIKE 'high-school' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // college - high-school - non-user
        $sql10 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'college' AND kind_of_student LIKE 'high-school' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // college - college - user
        $sql11 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'college' AND kind_of_student LIKE 'college' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // college - college - non-user
        $sql12 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'college' AND kind_of_student LIKE 'college' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // all - user
        $sql13 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'all' AND user > 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        // all - non-user
        $sql14 = "   SELECT count(*) as `total`
                    FROM
                        log_search
                    WHERE activity LIKE 'all' AND user = 0 AND DATE_FORMAT(create_date, '%Y-%m-%d') LIKE '$day_before' ";

        $query = $db->createCommand($sql1)->queryAll();
        $total1 = $query[0]['total'];
        $query = $db->createCommand($sql2)->queryAll();
        $total2 = $query[0]['total'];
        $query = $db->createCommand($sql3)->queryAll();
        $total3 = $query[0]['total'];
        $query = $db->createCommand($sql4)->queryAll();
        $total4 = $query[0]['total'];
        $query = $db->createCommand($sql5)->queryAll();
        $total5 = $query[0]['total'];
        $query = $db->createCommand($sql6)->queryAll();
        $total6 = $query[0]['total'];
        $query = $db->createCommand($sql7)->queryAll();
        $total7 = $query[0]['total'];
        $query = $db->createCommand($sql8)->queryAll();
        $total8 = $query[0]['total'];
        $query = $db->createCommand($sql9)->queryAll();
        $total9 = $query[0]['total'];
        $query = $db->createCommand($sql10)->queryAll();
        $total10 = $query[0]['total'];
        $query = $db->createCommand($sql11)->queryAll();
        $total11 = $query[0]['total'];
        $query = $db->createCommand($sql12)->queryAll();
        $total12 = $query[0]['total'];
        $query = $db->createCommand($sql13)->queryAll();
        $total13 = $query[0]['total'];
        $query = $db->createCommand($sql14)->queryAll();
        $total14 = $query[0]['total'];

        //
        $data = array(
            array('scholarship', 'high-school', 'user', $total1),
            array('scholarship', 'high-school', 'non-user', $total2),
            array('scholarship', 'college', 'user', $total3),
            array('scholarship', 'college', 'non-user', $total4),
            array('internship', 'high-school', 'user', $total5),
            array('internship', 'high-school', 'non-user', $total6),
            array('internship', 'college', 'user', $total7),
            array('internship', 'college', 'non-user', $total8),
            array('college', 'high-school', 'user', $total9),
            array('college', 'high-school', 'non-user', $total10),
            array('college', 'college', 'user', $total11),
            array('college', 'college', 'non-user', $total12),
            array('all', '', 'user', $total13),
            array('all', '', 'non-user', $total14),
        );

        $command = $db->createCommand();
        foreach ($data as $row) {
            $data = array(
                'kind_of_search' => $row[0],
                'kind_of_student' => $row[1],
                'kind_of_user' => $row[2],
                'total_use' => $row[3],
                'create_date' => $day_before,
                'runcron_date' => $current_date,
            );
            $command->insert($table, $data);
        }
    }

}
