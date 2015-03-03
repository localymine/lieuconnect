<?php foreach ($result as $row): ?>
    <li>
        <?php $flag_read = $row->read_later[0]->flag_read; ?>
        <input type="checkbox" name="item[]" data-number="<?php echo $row->id ?>" value="<?php echo $row->id ?>" id="<?php echo $row->id ?>">
        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-4 no-padding">
                <?php $image = ($row->image != '') ? $row->image : '0.jpg'; ?>
                <a href="<?php echo Yii::app()->createUrl($row->post_type . '/show', array('slug' => $row->slug)) ?>">
                    <img src="<?php echo Yii::app()->baseUrl . '/images/' . $row->post_type . '/' . $image ?>" class="img-responsive">
                </a>
                    <?php if ($flag_read == 0): // unread ?>
                        <p><strong><?php echo $row->post_title ?></strong></p>
                        <p><i class="fa fa-circle text-color-<?php echo $row->post_type ?>" title="<?php echo $row->post_type ?>"></i> <span><strong><?php echo Common::t('by', 'post') ?> <?php echo $row->post_user->username ?></strong></span></p>
                        <p><span><strong><?php echo Common::t('bookmark', 'post') ?> <?php echo Common::get_time_duration($row->read_later[0]->create_date) ?></strong></span></p>
                    <?php else: ?>
                        <p><?php echo $row->post_title ?></p>
                        <p><i class="fa fa-circle text-color-<?php echo $row->post_type ?>" title="<?php echo $row->post_type ?>"></i> <span><?php echo Common::t('by', 'post') ?> <?php echo $row->post_user->username ?></span></p>
                        <p><span><?php echo Common::t('bookmark', 'post') ?> <?php echo Common::get_time_duration($row->read_later[0]->create_date) ?></span></p>
                    <?php endif; ?>
            </div>
            <div class="col-xs-7">
                <?php if ($flag_read == 0): // unread ?>
                <p>
                    <a href="<?php echo Yii::app()->createUrl($row->post_type . '/show', array('slug' => $row->slug)) ?>"><strong><?php echo Common::strip_nl_truncate($row->post_content, 750) ?></strong></a>
                </p>
                <?php else: ?>
                <p>
                    <a href="<?php echo Yii::app()->createUrl($row->post_type . '/show', array('id' => $row->slug)) ?>"><?php echo Common::strip_nl_truncate($row->post_content, 750) ?></a>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </li>
<?php endforeach; ?>