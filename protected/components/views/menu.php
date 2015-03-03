<div class="navbar-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <nav class="navbar navbar-default" role="navigation">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav nav-menu">
                            <li class="active"><a class="home" href="<?php echo Yii::app()->createUrl('home') ?>">Home</a>
                            </li>

                            <li class="dropdown main-menu nav-menu-dropdown no-border">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown"><span class="dropdownImg">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span></a>
                                <ul class="dropdown-menu">
                                    <li class="lilely-icon <?php echo ($controller == 'story') ? '' : '' ?>"><a href="<?php echo Yii::app()->createUrl('story') ?>"><?php echo Common::t('LilelyConnect', 'home') ?></a></li>
                                    <li class="scholarship-icon <?php echo ($controller == 'scholarship' || $controller == 'winner') ? 'active' : '' ?>"><a href="<?php echo Yii::app()->createUrl('scholarship') ?>"><?php echo Common::t('Scholarship', 'home') ?></a></li>
                                    <li class="intership-icon <?php echo ($controller == 'internship') ? 'active' : '' ?>"><a href="<?php echo Yii::app()->createUrl('internship') ?>"><?php echo Common::t('Internship', 'home') ?></a></li>
                                    <li class="colleges-icon <?php echo ($controller == 'college') ? 'active' : '' ?>"><a href="<?php echo Yii::app()->createUrl('college') ?>"><?php echo Common::t('Colleges', 'home') ?></a></li>
                                </ul>
                            </li>
                        </ul>
                        
                        <!-- search all -->
                        <?php $this->widget('SearchAll') ?>
                        <!-- /search all -->

                        <!-- language -->
                        <?php $this->widget('LanguageSelector') ?>
                        <!-- /language -->

                        <div class="dropdown nav-menu-dropdown user-logged-frame">
                            <a href="#" class="navbar-right profile-button dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i></a>
                            <?php if (Yii::app()->user->isGuest): ?>
                                <?php $this->widget('LoginForm') ?>
                            <?php else: ?>
                                <ul class="dropdown-menu user-logged">
                                    <li>
                                        <a href="<?php echo Yii::app()->createUrl('profile') ?>"><?php echo Common::t('My Student Center', 'home') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo Yii::app()->createUrl('account/logout') ?>" class="logout"><i class="fa fa-power-off fa-fw"></i><?php echo Common::t('Logout', 'home') ?></a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </div>
        </div>
    </div>
</div>
