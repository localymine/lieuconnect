<?php foreach ($posts as $row): ?>
    <?php
    $model_profile = Yii::app()->getModule('user');
    $user = $model_profile->user($row->post_author);
    ?>
    <li class="" data-postid="<?php echo $row->id ?>">
        <div class="move-pick"><a id="left" class="ui-icon-left pull-left pl10"><i class="fa fa-long-arrow-left"></i></a><a id="right" class="ui-icon-right pull-right pr10"><i class="fa fa-long-arrow-right"></i></a></div>
        <div class="avatar">
            <?php if ($user->profile->image == ''): ?>
                <img class="img-circle" width="50" src="<?php echo Yii::app()->baseUrl ?>/avatars/avatar.png"/>
            <?php else: ?>
                <img class="img-circle" width="50" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $user->profile->image ?>"/>
            <?php endif; ?>
        </div>
        <div class="hold-img-news">
            <img class="img-news" align="left" width="126" style="padding:5px" src="<?php echo Yii::app()->baseUrl ?>/images/internship/<?php echo $row->image ?>" />
        </div>
        <div><?php echo (strlen($row->post_title) < 32) ? $row->post_title : mb_substr($row->post_title, 1, 29) . '...' ?><br/><?php echo $row->award ?></div>
        <a href="<?php echo Yii::app()->createUrl('backend/post/updateInternship/' . $row->id) ?>" class="ui-icon-edit pull-left pl10" title="Edit"><i class="fa fa-pencil"></i></a>
        <a class="ui-icon ui-icon-plus pull-right pr10" title="Add"><i class="fa fa-plus"></i></a>
    </li>  

<?php endforeach; ?>