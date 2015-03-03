<select id="<?php echo $id ?>" name="<?php echo $submit_name ?>" class="form-control" size="1" <?php if ($submit_form != '') echo " form='$submit_form'" ?> >
    <?php if ($this->update == TRUE):?>
    <option value="-1">None</option>
    <option value="0">Root</option>
    <?php else: ?>
    <option value="0">None</option>
    <?php endif; ?>
<?php
$level = 0;

$roots = TermTaxonomy::model()->roots($taxonomy, $show_in)->findAll();
foreach ($roots as $row): 
    if ($row->parent == 0) $level = 0;
    if (isset($select) && in_array($row->term_id, $select)) $check = ' selected '; else $check = '';
?>
    <?php if ($this->update == TRUE):?>
        <?php if (isset($select_update) && in_array($row->term_id, $select_update)) $check = ' selected '; else $check = ''; ?>
        <?php if (isset($select) && in_array($row->term_id, $select)):?>
        <?php continue; ?>
        <?php else: ?>
            <option class="slv<?php echo $level ?>" value="<?php echo $row->term_id ?>" <?php echo $check ?>><?php echo str_repeat('-', $step * $level) ?><?php echo $row->terms['name'] ?></option>
        <?php endif; ?>
    <?php else: ?>
        <option class="slv<?php echo $level ?>" value="<?php echo $row->term_id ?>" <?php echo $check ?>><?php echo str_repeat('-', $step * $level) ?><?php echo $row->terms['name'] ?></option>
    <?php endif; ?>
<?php 
$this->_row($row->term_id, $level + 1);
endforeach; 
?>
</select>