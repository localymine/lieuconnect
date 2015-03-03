<ul id="honor">
    <?php foreach($model as $row): ?>
    <li data-id="<?php echo $row->id ?>" class="resume-item">
        <div class="curricular">
            <p class="award"><?php echo $row->description ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>