<!--<div class="reward" >-->
<?php if (isset($this->acc_twitter) && $this->acc_twitter != ''): ?>
    <?php
    $parts = explode('@', $this->acc_twitter);
    ?>
    <div class = "fl">
        <a href="https://twitter.com/<?php echo $parts[0] ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $parts[0] ?></a>
    </div>
<?php endif; ?>
<!--</div>-->