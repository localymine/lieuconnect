<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
    <?php $flag = 0; ?>
    <?php foreach ($model as $row): ?>
        <?php $active = ($flag == 0) ? 'active' : '' ?>
        <li data-target="#myCarousel" data-slide-to="<?php echo $row->sorting ?>" class="<?php $active ?>"></li>
        <?php $flag++; ?>
    <?php endforeach; ?>
    </ol>
    <div class="carousel-inner">
        <?php $flag = 0; ?>
        <?php foreach ($model as $row): ?>
        <?php $active = ($flag == 0) ? 'active' : '' ?>
        <div class="item <?php echo $active ?>">
            <img src="<?php echo Yii::app()->baseUrl ?>/images/slide/<?php echo $row->image ?>" class="image-responsive" alt="<?php echo $row->title ?>">
            <div class="container">
                <div class="carousel-caption">
                    <h1><?php echo $row->title ?></h1>
                    <p><?php echo $row->description ?></p>
                </div>
            </div>
        </div>
        <?php $flag++; ?>
        <?php endforeach; ?>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div>