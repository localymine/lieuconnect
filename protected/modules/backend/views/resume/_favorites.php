<ul id="favorite">
    <?php foreach ($model as $row): ?>
    <li class="resume-item" data-id="<?php echo $row->id ?>">
        <div class="curricular">
            <p class="music"><?php echo $row->music ?></p>
            <p class="quote"><?php echo $row->quote ?></p>
            <p class="tv"><?php echo $row->tvshow ?></p>
            <p class="book"><?php echo $row->book ?></p>
            <p class="movie"><?php echo $row->movie ?></p>
            <p class="web"><?php echo $row->website ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>