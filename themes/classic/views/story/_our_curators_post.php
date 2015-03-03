<?php foreach ($model_user as $row): ?>
    <div class="col-md-4 item">
        <?php $avatar = ($row->profile->image != '') ? $row->profile->image : 'avatar.png' ?>
        <a href="<?php echo Yii::app()->createUrl('story/curator', array('id' => $row->id)) ?>">
            <img src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $avatar ?>" class="img-responsive"/>
            <h2><?php echo $row->username ?></h2>
        </a>
        <div id="social-wrapper">
            <div class="accounts">
            <?php
            $this->widget('SocialNetwork', array(
                'type' => 'twitter-follow',
                'acc_twitter' => $row->profile->acc_twitter,
            ));
            $this->widget('SocialNetwork', array(
                'type' => 'facebook-like',
                'data_href' => Yii::app()->createUrl('story/curator', array('id' => $row->id)),
            ));
            ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>