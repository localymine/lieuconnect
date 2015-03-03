<ul id="exper">
    <?php foreach($model as $row): ?>
    <li data-id="<?php echo $row->id ?>" class="resume-item">
        <div class="curricular">
            <p>
                <span class="employer"><?php echo $row->employer ?></span>
                <span class="date">
                    <?php if($row->uptonow == 1): ?>
                        <span class="start"><?php echo $row->start ?></span> ~ <span class="end">Now</span>
                    <?php else: ?>
                        <span class="start"><?php echo $row->start ?></span> ~ <span class="end"><?php echo $row->end ?></span>
                    <?php endif; ?>
                        
                </span>
            </p>
            <p class="industry">Industry: <?php echo $row->industry->title ?></p>
            <p class="position">Position: <?php echo $row->position ?></p>
            <p class="description">Description: <?php echo $row->description ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>