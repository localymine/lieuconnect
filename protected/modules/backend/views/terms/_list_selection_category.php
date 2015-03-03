<option value="0">None</option>
<?php
$level = 0;

$roots = TermTaxonomy::model()->roots($taxonomy, $show_in)->findAll();
foreach ($roots as $row): 
    if ($row->parent == 0) $level = 0;
    if ( isset($select) && in_array($row->term_id, $select) ) $check = ' selected '; else $check = '';
?>
    <?php if ($update == 1):?>
        <?php if (isset($select) && in_array($row->term_id, $select)):?>
        
        <?php else: ?>
            <option class="slv<?php echo $level ?>" value="<?php echo $row->term_id ?>" <?php echo $check ?>><?php echo str_repeat('-', $step * $level) ?><?php echo $row->terms['name'] ?></option>
        <?php endif; ?>
    <?php else: ?>
            <option class="slv<?php echo $level ?>" value="<?php echo $row->term_id ?>" <?php echo $check ?>><?php echo str_repeat('-', $step * $level) ?><?php echo $row->terms['name'] ?></option>
    <?php endif; ?> 
<?php 
_row($row->term_id, $taxonomy, $show_in, $level + 1, $step, $select, $update);
endforeach; 
?>

<?php
function _row($p_id, $taxonomy, $show_in,  $level, $step, $select = NULL, $update = FALSE) {

        $model = TermTaxonomy::model()->by_parent($taxonomy, $show_in, $p_id)->findAll();
        foreach ($model as $row) {
            if ($p_id == $row->parent) $level = $level; else $level++;
            if ( isset($select) && in_array($row->term_id, $select) ) $check = ' selected '; else $check = '';
            if ($update == 1){
                if (isset($select) && in_array($row->term_id, $select)){
                    
                }else{
                    echo '<option class="slv-' . $level . '" value="' . $row->term_id . '" ' . $check . '>' . str_repeat('-', $step * $level) . $row->terms['name'] . '</option>';
                }
            } else {
                echo '<option class="slv-' . $level . '" value="' . $row->term_id . '" ' . $check . '>' . str_repeat('-', $step * $level) . $row->terms['name'] . '</option>';
            }

            _row($row->term_id, $taxonomy, $show_in, $level + 1, $step, $select, $update);
        }
    }
?>