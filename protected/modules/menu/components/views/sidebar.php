<?php
$avatar = (isset(Yii::app()->user->image)) ? (Yii::app()->user->image) : 'avatar.png';
?>
<div class="sidebar-scroll">
    <div class="sidebar-content">
        <a href="<?php echo Yii::app()->createUrl('/backend/dashboard') ?>" class="sidebar-brand">
            <i class="gi gi-wifi_alt"></i><strong>Lilely</strong>Connect
        </a>
        <div class="sidebar-section sidebar-user clearfix">
            <div class="sidebar-user-avatar">
                <a href="<?php echo Yii::app()->createUrl('/user/profile') ?>">
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/avatars/<?php echo $avatar ?>" alt="avatar" />
                </a>
            </div>
            <div class="sidebar-user-name"><?php echo Yii::app()->user->username; ?></div>
            <div class="sidebar-user-links">
                <a href="<?php echo Yii::app()->createUrl('/user/profile') ?>" data-toggle="tooltip" data-placement="bottom" title="Profile"><i class="gi gi-user"></i></a>
                <a href="#modal-user-settings" data-toggle="modal" class="enable-tooltip" data-placement="bottom" title="Settings"><i class="gi gi-cogwheel"></i></a>
                <a href="<?php echo Yii::app()->createUrl('/user/logout') ?>" data-toggle="tooltip" data-placement="bottom" title="Logout"><i class="gi gi-exit"></i></a>
            </div>
        </div>

        <ul class="sidebar-nav">
            <?php if ($admin == 1): ?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/dashboard') ?>" class="<?php if ($flags_open[0] == true): ?> active <?php endif;?>"><i class="gi gi-stopwatch sidebar-nav-icon"></i>Dashboard</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/statistics') ?>" class="<?php if ($flags_open[14] == true): ?> active <?php endif;?>"><i class="gi gi-charts sidebar-nav-icon"></i>Statistics</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/slide') ?>" class="<?php if ($flags_open[5] == true): ?> active <?php endif;?>"><i class="gi gi-film sidebar-nav-icon"></i>Home Slide Show</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/practiceTest') ?>" class="<?php if ($flags_open[9] == true): ?> active <?php endif;?>"><i class="fa fa-lightbulb-o sidebar-nav-icon"></i>Practice Test</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/advertise') ?>" class="<?php if ($flags_open[12] == true): ?> active <?php endif;?>"><i class="fa fa-bullhorn sidebar-nav-icon"></i>Advertise</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/recruit') ?>" class="<?php if ($flags_open[6] == true): ?> active <?php endif;?>"><i class="gi gi-share_alt sidebar-nav-icon"></i>Recruit</a>
            </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('/backend/resume') ?>" class="<?php if ($flags_open[7] == true): ?> active <?php endif;?>"><i class="fa fa-info sidebar-nav-icon"></i>Resume</a>
            </li>
            
            <li class="sidebar-header">
                <span class="sidebar-header-options clearfix"><i class="fa fa-cog"></i></span>
                <span class="sidebar-header-title"><strong>Management</strong></span>
            </li>
            
            <?php if ($admin == 1): ?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('backend/terms') ?>" class="<?php if ($flags_open[1] == true): ?> active <?php endif;?> "><i class="gi gi-list sidebar-nav-icon"></i>Categories</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('backend/tags') ?>" class="<?php if ($flags_open[4] == true): ?> active <?php endif;?> "><i class="gi gi-tags sidebar-nav-icon"></i>Tags</a>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('backend/feeling') ?>" class="<?php if ($flags_open[8] == true): ?> active <?php endif;?> "><i class="fa fa-comment sidebar-nav-icon"></i>Feeling</a>
            </li>
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[10] && $controller == 'page') echo " open bold" ?> "><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-puzzle-piece sidebar-nav-icon"></i>Static Pages</a>
                <ul <?php if ($flags_open[10] && $controller == 'page') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[10] && $flags_active[0]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/page') ?>">All Pages</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[10] && $flags_active[1]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/page/create') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'update') : ?>
                    <li>
                        <a class="<?php if ($flags_open[10] && $flags_active[2]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[11] && $controller == 'faq') echo " open bold" ?> "><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="fa fa-comments-o sidebar-nav-icon"></i>FAQ Pages</a>
                <ul <?php if ($flags_open[11] && $controller == 'faq') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[11] && $flags_active[0]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/faq') ?>">List All FAQ</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[11] && $flags_active[1]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/faq/create') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'update') : ?>
                    <li>
                        <a class="<?php if ($flags_open[11] && $flags_active[2]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="<?php echo Yii::app()->createUrl('backend/setting') ?>" class="<?php if ($flags_open[13] == true): ?> active <?php endif;?> "><i class="fa fa-gears sidebar-nav-icon"></i>Settings</a>
            </li>
            <?php endif; ?>
            
            <li class="sidebar-header">
                <span class="sidebar-header-options clearfix"><i class="fa fa-cog"></i></span>
                <span class="sidebar-header-title"><strong>Post content</strong></span>
            </li>
            
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[2] && strpos(strtolower($action), 'winner') || $action == 'winner') echo " open bold" ?>"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="hi hi-star-empty sidebar-nav-icon"></i>Monthly Winner</a>
                <ul <?php if ($flags_open[2] && strpos(strtolower($action), 'winner') || $action == 'winner') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[16]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/winner') ?>">All Posts</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[17]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/addWinner') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'updatewinner') : ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[18]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[2] && strpos(strtolower($action), 'story') || $action == 'story') echo " open bold" ?>"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-notes_2 sidebar-nav-icon"></i>Stories</a>
                <ul <?php if ($flags_open[2] && strpos(strtolower($action), 'story') || $action == 'story') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[0]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/story') ?>">All Posts</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[1]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/addStory') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'updatestory') : ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[2]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[3]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/pickStory') ?>">Our Picks</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[2] && strpos(strtolower($action), 'scholarship') || $action == 'scholarship') echo " open " ?>"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-cup sidebar-nav-icon"></i>Scholarship</a>
                <ul <?php if ($flags_open[2] && strpos(strtolower($action), 'scholarship') || $action == 'scholarship') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[4]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/scholarship') ?>">All Posts</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[5]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/addScholarship') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'updatescholarship') : ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[6]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[7]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/pickScholarship') ?>">Our Picks</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[2] && strpos(strtolower($action), 'internship') || $action == 'internship') echo " open " ?>"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-group sidebar-nav-icon"></i>Internship</a>
                <ul <?php if ($flags_open[2] && strpos(strtolower($action), 'internship') || $action == 'internship') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[8]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/internship') ?>">All Posts</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[9]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/addInternship') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'updateinternship') : ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[10]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[11]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/pickInternship') ?>">Our Picks</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="sidebar-nav-menu <?php if ($flags_open[2] && strpos(strtolower($action), 'college') || $action == 'college') echo " open " ?>"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-cargo sidebar-nav-icon"></i>Colleges</a>
                <ul <?php if ($flags_open[2] && strpos(strtolower($action), 'college') || $action == 'college') echo ' style="display:block;" ' ?> >
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[12]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/college') ?>">All Posts</a>
                    </li>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[13]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/addCollege') ?>">Add New</a>
                    </li>
                    <?php if ( strtolower($action) == 'updatecollege') : ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[14]) echo " active " ?>" href="#">Update</a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a class="<?php if ($flags_open[2] && $flags_active[15]) echo " active " ?>" href="<?php echo Yii::app()->createUrl('backend/post/pickCollege') ?>">Our Picks</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-header">
                <span class="sidebar-header-options clearfix"><i class="fa fa-users"></i></span>
                <span class="sidebar-header-title"><strong>User</strong></span>
            </li>
            
            <li>
                <a href="<?php echo Yii::app()->createUrl('user/profile'); ?>" class="<?php if ($module =='user' && $action=='profile' && $flags_active[0] == true) echo 'active' ?>"></i><i class="gi gi-user sidebar-nav-icon"></i>User Profile</a>
                <?php $actions = array('admin', 'create', 'update') ?>
                <?php if($admin == 1): ?>
                <a href="<?php echo Yii::app()->createUrl('user/admin'); ?>" class="<?php if (($module =='user' && $action != 'profile') || ($module =='user' && in_array($action, $actions))) echo 'active' ?>"></i><i class="gi gi-cogwheels sidebar-nav-icon"></i>Manage Users</a>
                <?php endif; ?>
            </li>
        </ul>

    </div>
</div>