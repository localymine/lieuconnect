<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SelectionCategory
 *
 * @author khangld
 */
class SelectionSearchCategory extends CWidget {
    
    public $lang = 'vi';
    public $step = 5;
    public $taxonomy = 'category';
    public $show_in = 'major';
    public $id = '';
    public $empty = '';
    public $submit_form = '';
    public $submit_name = '';
    public $onChange = '';
    public $select = array();
    public $select_update = array();    // selected value on update
    public $update = FALSE;
    public $class = '';
    

    public function init() {
        $this->lang = Yii::app()->language;
        $this->empty = Common::t('All categories');
    }

    public function run() {
        
        $this->render('selection-search-category', array(
            'id' => $this->id,
            'submit_form' => $this->submit_form,
            'submit_name' => $this->submit_name,
            'select'    => $this->select,
            'select_update'    => $this->select_update,
            'update'    => $this->update,
            'step'      => $this->step,
            'taxonomy' => $this->taxonomy,
            'show_in' => $this->show_in,
        ));
    }
    
    public function _row($p_id, $level) {

        $model = TermTaxonomy::model()->by_parent($this->taxonomy, $this->show_in, $p_id)->findAll();
        foreach ($model as $row) {
            if ($p_id == $row->parent) $level = $level; else $level++;
            if ( isset($this->select) && in_array($row->term_id, $this->select) ) $check = ' selected '; else $check = '';
            if ($this->update == TRUE){
                if ( isset($this->select_update) && in_array($row->term_id, $this->select_update) ) $check = ' selected '; else $check = '';
                if (isset($this->select) && in_array($row->term_id, $this->select)){
                    continue;
                }else{
                    echo '<option class="slv-' . $level . '" value="' . $row->term_id . '" ' . $check . '>' . str_repeat('-', $this->step * $level) . $row->terms['name'] . '</option>';
                }
            } else {
                echo '<option class="slv-' . $level . '" value="' . $row->term_id . '" ' . $check . '>' . str_repeat('-', $this->step * $level) . $row->terms['name'] . '</option>';
            }

            $this->_row($row->term_id, $level + 1);
        }
    }
}
