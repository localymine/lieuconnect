<table class="table table-vcenter table-striped">
    <tr>
        <th>Name</th>
        <th>Slug</th>
        <th>Posts</th>
        <th>&nbsp;</th>
    </tr>

    <?php
    $level = 0;

    $roots = TermTaxonomy::model()->roots($taxonomy, $show_in)->findAll();
    foreach ($roots as $row):
        if ($row->parent == 0)
            $level = 0;
        ?>

        <tr>
            <td><?php echo str_repeat('-', $step * $level) ?><?php echo $row->terms->name ?></td>
            <td><?php echo $row->terms->slug ?></td>
            <td align="right"><?php echo $row->count ?></td>
            <td align="right" class="block-options"><a class="edit btn btn-alt btn-sm btn-default btn-option" data-original-title="Edit" data-toggle="tooltip" href="<?php echo Yii::app()->createUrl($url_edit, array('id' => $row->term_taxonomy_id)) ?>"><i class="fa fa-pencil"></i></a> <?php if (!TermTaxonomy::model()->has_child($taxonomy, $row->term_taxonomy_id)): ?><a class="delete<?php echo ($num != '') ? ('-' . $num) : '' ?> btn btn-alt btn-sm btn-danger btn-option" data-original-title="Delete" data-toggle="tooltip" href="<?php echo Yii::app()->createUrl($url_delete, array('id' => $row->term_taxonomy_id)) ?>"><i class="fa fa-times"></i></a><?php endif; ?></td>
        </tr>

        <?php
        $this->_row($row->term_id, $level + 1);
    endforeach;
    ?>  
</table>

<script type="text/javascript">
    $('.delete<?php echo ($num != '') ? ('-' . $num) : '' ?>').on('click', function(e) {
        e.stopPropagation();
        if (confirm('Are you sure you want to delete this?\nCan not recover after delete.')) {
            return true;
        } else {
            return false;
        }
    });
</script>