<ul>
    <?php foreach($model as $row): ?>
    <li data-id="<?php echo $row->id ?>" class="resume-item"><span class="glyphicon glyphicon-minus" title="<?php echo Common::t('Delete', 'post') ?>"></span>
        <div class="curricular">
            <p>
                <span class="activity"><?php echo $row->activity ?></span>
                <span class="date">
                    <?php if($row->uptonow == 1): ?>
                        <span class="start"><?php echo $row->start ?></span> ~ <span class="end">Now</span>
                    <?php else: ?>
                        <span class="start"><?php echo $row->start ?></span> ~ <span class="end"><?php echo $row->end ?></span>
                    <?php endif; ?>
                        
                </span>
            </p>
            <p class="position"><?php echo $row->position ?></p>
            <p class="description"><?php echo $row->description ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>