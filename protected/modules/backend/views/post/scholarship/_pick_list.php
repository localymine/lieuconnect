<?php foreach ($posts as $row): ?>
    <?php
    $model_profile = Yii::app()->getModule('user');
    $user = $model_profile->user($row->post->post_author);
    ?>
    <li data-postid="<?php echo $row->post->id ?>" class="ui-draggable" style="display: inline-block; width: 70px;">
        <div class="move-pick"><a id="left" class="ui-icon-left pull-left pl10"><i class="fa fa-long-arrow-left"></i></a><a id="right" class="ui-icon-right pull-right pr10"><i class="fa fa-long-arrow-right"></i></a></div>
        <div class="avatar">
            <?php if ($user->profile->image == ''): ?>
                <img class="img-circle" width="50" src="<?php echo Yii::app()->baseUrl ?>/avatars/avatar.png"/>
            <?php else: ?>
                <img class="img-circle" width="50" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $user->profile->image ?>"/>
            <?php endif; ?>
        </div>
        <div class="hold-img-news" style="width: 68px; height: 70px;">
            <img width="126" align="left" src="/lilely/images/scholarship/<?php echo $row->post->image ?>" style="padding: 5px; width: 70px;" class="img-news">
        </div>
        <div><?php echo (strlen($row->post->post_title) < 32) ? $row->post->post_title : mb_substr($row->post->post_title, 1, 29) . '...' ?><br/><?php echo $row->post->award ?></div>
        <a title="Edit" class="ui-icon-edit pull-left pl10" href="<?php echo Yii::app()->createUrl('backend/post/updateScholarship/' . $row->post->id) ?>"><i class="fa fa-pencil"></i></a>
        <a class="ui-icon ui-icon-trash pull-right pr10" title="Delete"><i class="fa fa-trash-o"></i></a>
    </li>
<?php endforeach; ?>