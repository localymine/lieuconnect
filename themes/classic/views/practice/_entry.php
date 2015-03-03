<?php foreach ($result as $row): ?>
<li>
    <a href="<?php echo $row->link ?>" onclick="ga('send','event','<?php echo $row->title ?>','<?php echo $row->link ?>')" target="_blank">
        <?php echo $row->title ?>
    </a>
</li>
<?php endforeach; ?>
