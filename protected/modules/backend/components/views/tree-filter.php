<?php
$roots = Post::item_alias($this->filter);

foreach ($roots as $key => $value): 
    if (isset($this->select) && in_array($key, $this->select)) $check = ' checked '; else $check = '';
?>
    <div class="checkbox">
        <label class="filter-block"><input class="<?php echo ($this->multi_select == false)? ('filter-select-one-' . $this->filter) : '' ?>" type="checkbox" form="<?php echo $this->submit_form?>" name="<?php echo $this->submit_name ?>" value="<?php echo $key ?>" <?php echo $check ?> /><label class="c-checked"><?php echo $value ?></label></label>
    </div>
<?php 
endforeach; 
?>

<?php
$script = <<< EOD
$('.filter-select-one-$this->filter').on('change',function(){
 var th = $(this), name = th.prop('name');
 if(th.is(':checked')){
     $('.filter-select-one-$this->filter').not($(this)).prop('checked',false);   
  }
});
EOD;

if ($this->multi_select == false) {
    Yii::app()->clientScript->registerScript("filter-select-one-$this->filter-" . rand(), $script);
}
?>