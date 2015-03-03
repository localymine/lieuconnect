<option value=""><?php echo Common::t('Choose State/Region', 'account') ?></option>
<?php foreach($model as $row): ?>
<option value="<?php echo $row->stateID ?>"><?php echo $row->stateName ?></option>
<?php endforeach; ?>
