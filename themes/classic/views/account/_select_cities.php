<option value=""><?php echo Common::t('Choose City', 'account') ?></option>
<?php foreach($model as $row): ?>
<option value="<?php echo $row->cityID ?>"><?php echo $row->cityName ?></option>
<?php endforeach; ?>
