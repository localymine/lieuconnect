<?php foreach ($result as $row): ?>
    <tr>
        <?php $flag_read = $row->read_later[0]->flag_read; ?>
        <td style="width:7%;text-align:left;"><input type="checkbox" name="item[]" data-number="<?php echo $row->id ?>" value="<?php echo $row->id ?>"></td>
        <?php if ($flag_read == 0): // unread ?>
            <td style="width:70%;"><a href="<?php echo Yii::app()->createUrl($row->post_type . '/show', array('slug' => $row->slug)) ?>"><strong><?php echo $row->post_title ?></strong></a></td>
            <td style="text-align:right"><strong><?php echo Common::get_time_duration($row->read_later[0]->create_date) ?></strong></td>
        <?php else: ?>
            <td style="width:70%;"><a href="<?php echo Yii::app()->createUrl($row->post_type . '/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></td>
            <td style="text-align:right"><?php echo Common::get_time_duration($row->read_later[0]->create_date) ?></td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>