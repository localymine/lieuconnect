<ul>
    <?php foreach($model as $row): ?>
    <li data-id="<?php echo $row->id ?>" class="resume-item"><span class="glyphicon glyphicon-minus" title="<?php echo Common::t('Delete', 'post') ?>"></span>
        <div class="curricular">
            <p class="skill"><?php echo $row->description ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>