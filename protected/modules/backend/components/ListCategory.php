<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ListCategory
 *
 * @author khangld
 */
class ListCategory extends CWidget {

    public $num = '';
    public $page;
    public $limit;
    public $step = 3;
    public $taxonomy = 'category';
    public $show_in = 'major';
    public $url_edit = '';
    public $url_delete = '';

    public function init() {
        
    }

    public function run() {

        $this->render('list-category', array(
            'num' => $this->num,
            'step' => $this->step,
            'taxonomy' => $this->taxonomy,
            'show_in' => $this->show_in,
            'url_edit' => $this->url_edit,
            'url_delete' => $this->url_delete,
        ));
    }

    public function _row($p_id, $level) {

        $model = TermTaxonomy::model()->by_parent($this->taxonomy, $this->show_in, $p_id)->findAll();
        foreach ($model as $row) {
            if ($p_id == $row->parent) $level = $level; else $level++;
            
            $num = ($this->num != '') ? ('-' . $this->num) : '';
            echo '
            <tr>
            <td>' . str_repeat('-', $this->step * $level) . $row->terms->name . '</td>
            <td>' . $row->terms->slug . '</td>
            <td align="right">' . $row->count . '</td>
            <td align="right" class="block-options"><a class="edit btn btn-alt btn-sm btn-default btn-option" data-original-title="Edit" data-toggle="tooltip" href="' . Yii::app()->createUrl($this->url_edit, array('id' => $row->term_taxonomy_id)) . '"><i class="fa fa-pencil"></i></a> <a class="delete' . $num . ' btn btn-alt btn-sm btn-danger btn-option" data-original-title="Delete" data-toggle="tooltip" href="' . Yii::app()->createUrl($this->url_delete, array('id' => $row->term_taxonomy_id)) . '"><i class="fa fa-times"></i></a></td>
        </tr>
            ';

            $this->_row($row->term_id, $level + 1);
        }
    }
}
