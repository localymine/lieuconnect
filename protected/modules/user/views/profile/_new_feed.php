<?php foreach ($posts as $row): ?>
<?php
$user = UserModule::user($row->post_author);
?>
<li class="media">
    <?php if ($user->profile->image == ''): ?>
        <a href="#" class="pull-left">
            <img width="64" src="<?php echo Yii::app()->baseUrl ?>/avatars/avatar.png" alt="Avatar" class="img-circle" />
        </a>
    <?php else: ?>
        <a href="#" class="pull-left">
            <img width="64" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $user->profile->image ?>" alt="Avatar" class="img-circle" />
        </a>
    <?php endif; ?>
    <div class="media-body">
        <p class="push-bit">
            <span class="text-muted pull-right">
                <small><?php echo Common::get_time_duration($row->post_date) ?></small>
            </span>
            <strong><a href="#"><?php echo $user->username ?></a> published a new <?php echo $row->post_type ?>.</strong>
        </p>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li>ID: <strong><?php echo $row->id ?></strong></li>
            <?php
            foreach (Yii::app()->params['translatedLanguages'] as $l => $lang):
                if ($l === Yii::app()->params['defaultLanguage']) {
                    $suffix = '';
                    $active = ' active';
                } else {
                    $suffix = '_' . $l;
                    $active = '';
                }
                ?>
                <li class="<?php echo $active ?>"><a href="#<?php echo $lang . $row->id ?>" data-toggle="tab"><img width="24" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></a></li>
            <?php endforeach; ?>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php
            foreach (Yii::app()->params['translatedLanguages'] as $l => $lang):
                if ($l === Yii::app()->params['defaultLanguage']) {
                    $suffix = '';
                    $active = ' active';
                } else {
                    $active = '';
                    $suffix = '_' . $l;
                }
                ?>
                <div class="tab-pane fade in <?php echo $active ?>" id="<?php echo $lang . $row->id; ?>">
                    <div class="block clearfix">
                        <?php 
                            $image = 'image' . $suffix;
                            $post_title = 'post_title' . $suffix; 
                            $provided_by = 'provided_by' . $suffix;
                            $award = 'award' . $suffix;
                            $deadline = 'deadline';
                            $post_content = 'post_content' . $suffix;
                        ?>
                        <h5>
                            <a href="#">
                                <strong><?php echo $row->post_title ?></strong>
                                <?php if ($row->post_type == 'scholarship'): ?>
                                &nbsp;&bull;&nbsp;<?php echo $row->{$provided_by} ?>
                                &nbsp;&bull;&nbsp;<?php echo $row->{$award} ?>
                                &nbsp;&bull;&nbsp;<strong>Dead line:</strong> <?php echo $row->{$deadline} ?>
                                <?php endif; ?>
                            </a>
                        </h5>
                        <p><img align="left" width="100" style="padding:5px" src="<?php echo Yii::app()->baseUrl ?>/images/<?php echo $row->post_type ?>/<?php echo $row->{$image} ?>" /><?php echo mb_substr(strip_tags($row->{$post_content}), 1, 300) . '...' ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</li>
<?php endforeach; ?>