<ul class="breadcrumb">
    <li>
        <i class="fa fa-home"></i>
        <a href="#">Home</a>
        <span class="divider"><i class="fa fa-angle-right"></i></span>
    </li>
    <?php foreach ($path as $key => $value): ?>
        <?php if ($value != ''): ?>
            <li><a href="<?php echo Yii::app()->createUrl($value); ?>"><?php echo $key ?></a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
        <?php else: ?>
            <li><a href="#"><?php echo $key ?></a>
                <span class="divider"><i class="fa fa-angle-right"></i></span>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    <li class="active"><?php echo $current_page; ?></li>
</ul>