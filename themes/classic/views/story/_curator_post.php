<?php $counter = 0;
$line_break = 3; ?>
<?php foreach ($posts as $row): ?>
    <div class="col-md-4 item">
        <!--<div class="avatar">-->
    <?php // $avatar = ($row->post_by->image != '') ? $row->post_by->image : 'avatar.png' ?>
            <!--<img width="40" src="<?php // echo Yii::app()->baseUrl ?>/avatars/<?php // echo $avatar ?>" class="avatar-img"/>-->
            <!--<div class="info">-->
                <!--<p class="name"><?php // echo $row->post_user->username ?></p>-->
                <!--<p class="date"><?php // echo Common::date_format($row->post_date, 'M d, Y') ?></p>-->
            <!--</div>-->
        <!--</div>-->

        <?php
        $class_label = '';
        $text_color = '';
        switch ($row->post_type) {
            case 'winner':
                $class_label = 'label-bronze';
                $text_color = 'text-bronze';
                break;
            case 'story':
                $class_label = 'label-info';
                $text_color = 'text-info';
                break;
            case 'scholarship':
                $class_label = 'label-success';
                $text_color = 'text-success';
                break;
            case 'internship':
                $class_label = 'label-warning';
                $text_color = 'text-warning';
                break;
            case 'college':
                $class_label = 'label-danger';
                $text_color = 'text-danger';
                break;
        }
        ?>
        <!--<span class="label <?php // echo $class_label ?>"><?php // echo ucwords($row->post_type) ?></span>-->
        
        <a href="javascript:void(0)"><i class="fa fa-circle <?php echo $text_color ?>" title="<?php echo Common::t(ucwords($row->post_type), 'post') ?>"></i></a>
        <em class="curator_date"><?php echo Common::date_format($row->post_date, 'M d, Y') ?></em>

        <a href="<?php echo Yii::app()->createUrl("$row->post_type/show", array('slug' => $row->slug)) ?>"><img src="<?php echo Yii::app()->baseUrl ?>/images/<?php echo $row->post_type ?>/<?php echo $row->image ?>" class="img-responsive"></a>
        <p class="comment">
            <a href="<?php echo Yii::app()->createUrl("$row->post_type/show", array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a>
        </p>
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