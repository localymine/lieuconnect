<ul>
    <?php foreach ($model as $row): ?>
        <li class="resume-item" data-id="<?php echo $row->id ?>">
            <div class="curricular">
                <?php $interest = explode(',', $row->description); ?>
                <?php foreach ($interest as $value): ?>
                    <p><?php echo $value ?></p>
                <?php endforeach; ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>