<div class="main-frame">
    <div class="feature">
        <div class="dropdown">
            <span class="lilely-btn faq-btn"href="#"><?php echo Common::t('About', 'footer') ?></span>
            </ul>
        </div>
    </div>
    <div class="main faq">
        <?php if(isset($model->post_content)): ?>
            <?php echo $model->post_content ?>
        <?php endif; ?>
    </div>
</div>

<?php $this->widget('MoreStuff') ?>