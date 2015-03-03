<?php
$level = 0;

$roots = TermTaxonomy::model()->roots($taxonomy, $show_in)->findAll();
foreach ($roots as $row): 
    if ($row->parent == 0) $level = 0;
    if (isset($select) && in_array($row->term_id, $select)) $check = ' checked '; else $check = '';
?>
    <div class="checkbox lv<?php echo $level ?>">
        <label class="filter-block"><input class="<?php echo ($multi_select == false)? ('select-one-' . $show_in) : '' ?>" type="checkbox" form="<?php echo $submit_form?>" name="<?php echo $submit_name ?>" value="<?php echo $row->term_id ?>" <?php echo $check ?> /><label class="c-checked"><?php echo $row->terms['name'] ?></label></label>
    </div>
<?php 
$this->_row($row->term_id, $level + 1);
endforeach; 
?>

<?php
$script = <<< EOD
$('.select-one-$show_in').on('change',function(){
 var th = $(this), name = th.prop('name');
 if(th.is(':checked')){
     $('.select-one-$show_in').not($(this)).prop('checked',false);
  }
});
EOD;

if ($multi_select == false) {
    Yii::app()->clientScript->registerScript("select-one-$show_in-" . rand(), $script);
}
?>