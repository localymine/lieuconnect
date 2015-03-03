<ul id="extra">
<?php foreach ($model as $row): ?>
    <li class="resume-item">
        <div class="items">
            <p>
                <span class="activity"><?php echo $row->activity ?></span>
                <span class="date">
                    <?php if ($row->uptonow == 1): ?>
                        <span class="start"><?php echo $row->start ?></span> ~ <span class="end">Now</span>
                    <?php else: ?>
                        <span class="start"><?php echo $row->start ?></span> ~ <span class="end"><?php echo $row->end ?></span>
                    <?php endif; ?>

                </span>
            </p>
            <p class="position">Position: <?php echo $row->position ?></p>
            <p class="description">Description: <?php echo $row->description ?></p>
        </div>
    </li>
<?php endforeach; ?>
</ul>