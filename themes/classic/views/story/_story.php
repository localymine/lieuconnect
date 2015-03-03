<?php $counter = 0; $line_break = 3; ?>
<?php foreach ($posts as $row): ?>
    <div class="col-md-4 item">
        <div class="avatar">
            <?php $avatar = ($row->post_by->image != '') ? $row->post_by->image : 'avatar.png' ?>
            <a href="<?php echo Yii::app()->createUrl('story/curator', array('id' => $row->post_author)) ?>"><img width="40" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $avatar ?>" class="avatar-img"/></a>
            <div class="info">
                <p class="name"><a href="<?php echo Yii::app()->createUrl('story/curator', array('id' => $row->post_author)) ?>"><?php echo $row->post_user->username ?></a></p>
                <p class="date"><?php echo Common::date_format($row->post_date, 'M d, Y') ?></p>
            </div>
        </div>
        <div class="comment">
            <?php $image_entry = ($row->image != '') ? $row->image : '0.jpg'; ?>
            <a href="<?php echo Yii::app()->createUrl('story/show', array('slug' => $row->slug)) ?>">
                <img src="<?php echo Yii::app()->baseUrl ?>/images/story/<?php echo $image_entry ?>" class="img-responsive">
                <?php if ($row->post_excerpt != ''): ?>
                <?php echo Common::strip_nl_truncate($row->post_excerpt, 1000)  ?>
                <?php else: ?>
                <?php echo Common::strip_nl_truncate($row->post_content, 150)  ?>
                <?php endif; ?>
            </a>
            <div class="quote-author-tag">
                <?php
                    $tags = TermRelationships::model()->get_relate_terms($row->id, 'tag')->findAll();
                    $arr_tags = NULL;
                    if ($tags != NULL){
                        foreach ($tags as $tag) {
                            $t_tag = $tag->termtaxonomy->terms->localized($this->lang)->name;
                            $arr_tags[] = '#' . $t_tag;
                        }
                    }
                ?>
                <?php echo isset($arr_tags) ? join($arr_tags, ', ') : '' ?>
            </div>
        </div>
        <div id="social-wrapper">
            <div class="accounts">
            <?php
            $this->widget('SocialNetwork', array(
                'type' => 'twitter-share',
                'data_href' => Yii::app()->createUrl('story/show', array('slug' => $row->slug)),
            ));
            $this->widget('SocialNetwork', array(
                'type' => 'facebook-share',
                'data_href' => Yii::app()->createUrl('story/show', array('slug' => $row->slug)),
            ));
            ?>
            </div>
        </div>
    </div>
    <?php $counter++; ?>
    <?php if ($counter % $line_break === 0): ?>
    <div class="clearfix visible-lg visible-md"></div>
    <?php endif; ?>
<?php endforeach; ?>
<div class="clearfix visible-lg visible-md"></div>