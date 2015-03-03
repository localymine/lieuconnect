<ul>
    <?php foreach($model as $row): ?>
    <li data-id="<?php echo $row->id ?>" class="resume-item"><span class="glyphicon glyphicon-minus" title="<?php echo Common::t('Delete', 'post') ?>"></span>
        <div class="curricular">
            <p class="highschool"><?php echo $row->school_name ?></p>
            <p>Grab Year: <span class="gradyear"><?php echo $row->grade_year ?></span></p>
            <p>GAP: <span class="gpa"><?php echo $row->gpa ?></span></p>
            <p>Class rank: <span class="classrank"><?php echo $row->class_rank ?></span></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>