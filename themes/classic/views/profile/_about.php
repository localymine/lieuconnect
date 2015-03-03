<p>
    <span class="ccc"><?php echo Common::t('Fullname', 'account') ?>: </span> <span class="firstname"><?php echo $profile->firstname ?> </span> <span class="lastname"><?php echo $profile->lastname ?> </span>
    <span class="glyphicon glyphicon-edit hand" data-toggle="modal" data-target="#aboutModal"><?php echo Common::t('Edit', 'account') ?></span>
</p>
<p><span class="ccc"><?php echo Common::t('Email', 'account') ?>:</span> <span class="email"><?php echo $user->email ?></span></p>
<p><span class="ccc"><?php echo Common::t('Phone', 'account') ?>:</span> <span class="phone"><?php echo $profile->phone ?></span></p>
<p><span class="ccc"><?php echo Common::t('Birthday', 'account') ?>:</span> <span class="birthday"><?php echo $profile->birth_date ?></span></p>
<p><span class="ccc"><?php echo Common::t('Gender', 'account') ?>:</span> <span class="gender"><?php echo AUsers::itemAlias('Gender', $profile->gender) ?></span><span class="change-password hand" data-toggle="modal" data-target="#passwordModal"><?php echo Common::t('Change your password', 'account') ?></span></p>