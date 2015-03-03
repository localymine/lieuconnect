<?php

/**
 * This is the model class for table "log_search_analys_user".
 *
 * The followings are the available columns in table 'log_search_analys_user':
 * @property string $id
 * @property string $kind_of_search
 * @property string $kind_of_student
 * @property string $kind_of_user
 * @property string $total_use
 * @property string $create_date
 */
class Statistics {

    public $table_search = 'log_search_analys_data';
    
    /**
     * @input from date to date
     * @return string how many times used search function
     * 
     */
    public function total_times_use_search($from, $to) {
        
        $db = Yii::app()->db;
        $table = $this->table_search;
        if ($db->schema->getTable($table, true) !== null) {
            // table does not exist
            $sql = "";
            
            $command = $db->createCommand()
                    ->select('kind_of_search, SUM(total_use) as total, create_date')
                    ->from($table)
                    ->where("(DATE_FORMAT(create_date, '%Y-%m-%d') BETWEEN '$from' AND '$to')")
                    ->group('kind_of_search, create_date');
            $query = $command->queryAll();
            
            return $query;
        }
    }
    
    public function total_times_use_search2($from, $to) {
        
        $db = Yii::app()->db;
        $table = $this->table_search;
        if ($db->schema->getTable($table, true) !== null) {
            // table does not exist
            $sql = "";
            
            $command = $db->createCommand()
                    ->select('kind_of_search, SUM(total_use) as total')
                    ->from($table)
                    ->where("(DATE_FORMAT(create_date, '%Y-%m-%d') BETWEEN '$from' AND '$to')")
                    ->group('kind_of_search');
            $query = $command->queryAll();
            
            return $query;
        }
    }
    
    /**
     * @input from date to date
     * @return string how many times used search function of student (for high-school & college)
     * 
     */
    public function total_times_use_search_of_student($from, $to) {
        
        $db = Yii::app()->db;
        $table = $this->table_search;
        if ($db->schema->getTable($table, true) !== null) {
            // table does not exist
            $sql = "";
            
            $command = $db->createCommand()
                    ->select('kind_of_student, SUM(total_use) as total, create_date')
                    ->from($table)
                    ->where("kind_of_student != '' AND (create_date BETWEEN '$from' AND '$to')")
                    ->group('kind_of_student, create_date')
                    ->order('kind_of_student DESC');
            $query = $command->queryAll();
            
            return $query;
        }
    }
    
    /**
     * @input from date to date
     * @return string How many times used search function of user / non-user
     * 
     */
    public function total_times_use_search_user($from, $to) {
        
        $db = Yii::app()->db;
        $table = $this->table_search;
        if ($db->schema->getTable($table, true) !== null) {
            // table does not exist
            $sql = "";
            
            $command = $db->createCommand()
                    ->select('kind_of_user, SUM(total_use) as total, create_date')
                    ->from($table)
                    ->where("(create_date BETWEEN '$from' AND '$to')")
                    ->group('kind_of_user, create_date')
                    ->order('kind_of_user DESC');
            $query = $command->queryAll();
            
            return $query;
        }
    }
    
    
}
