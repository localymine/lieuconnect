<a href="javascript:void()" class="btn btn-default trigger btn-go btn-apply" data-toggle="modal" data-backdrop="true" data-keyboard="true" data-target="#modal-signup-popup" data-post-id="<?php echo $this->post_id ?>"><?php echo $title ?></a>

<?php
if (Yii::app()->user->isGuest) {
    $script = <<< EOD
$(function() {
    $('#apply').on('click',function(e) {
        e.preventDefault();
        $('.signup-popup').modal('show');
    });
});
EOD;
} else {
    $script = <<< EOD
$(function() {
    $('.btn-apply').click(function(e) {
        e.preventDefault();
        $('#post_id').val($(this).data('post-id'));
        $('.recruited-popup').modal('show');
    })
});
EOD;
}
?>

<?php
Yii::app()->clientScript->registerScript('modal-login-apply-button' . rand(), $script, CClientScript::POS_END);
?>