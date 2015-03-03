<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TreeCategory
 *
 * @author khangld
 */
class TreeCategory extends CWidget {

    public $taxonomy = 'category';
    public $show_in = 'major';
    public $submit_form = '';
    public $submit_name = '';
    public $multi_select = false;
    public $select = array();

    public function init() {
        
    }

    public function run() {
//        $level = 0;
//
//        $roots = TermTaxonomy::model()->roots()->findAll();
//        foreach ($roots as $row) {
//            if ($row->parent == 0) $level = 0;
//            echo $level . '-' . $row->term_id . '-' . $row->terms['name'] . '<br/>';
//            $this->_row($row->term_id, $level + 1);
//        }

        $this->render('tree-category', array(
            'submit_form' => $this->submit_form,
            'submit_name' => $this->submit_name,
            'multi_select' => $this->multi_select,
            'select'    => $this->select,
            'taxonomy' => $this->taxonomy,
            'show_in' => $this->show_in,
        ));
    }

    public function _row($p_id, $level) {

        $model = TermTaxonomy::model()->by_parent($this->taxonomy, $this->show_in, $p_id)->findAll();
        foreach ($model as $row) {
            if ($p_id == $row->parent) $level = $level; else $level++;
//            echo $level . '-' . $row->term_id . '-' . $row->terms['name'] . '<br/>';
            if ( isset($this->select) && in_array($row->term_id, $this->select)) $check = ' checked '; else $check = '';
            echo '
            <div class="checkbox lv' . $level . '">
                <label><input type="checkbox" form="' . $this->submit_form . '" name="'. $this->submit_name . '" value="' .  $row->term_id . '" ' . $check  . ' />' .  $row->terms['name'] . '</label>
            </div>';

            $this->_row($row->term_id, $level + 1);
        }
    }

}
