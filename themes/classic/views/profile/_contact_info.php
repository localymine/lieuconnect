<p>
    <span class="ccc"><?php echo Common::t('Address', 'account') ?>:</span> <span class="address"><?php echo $profile->address ?></span>
    <span class="glyphicon glyphicon-edit hand" data-toggle="modal" data-target="#contactModal"><?php echo Common::t('Edit', 'account') ?></span>
</p>
<p><span class="ccc"><?php echo Common::t('City', 'account') ?>:</span> <span class="city"><?php echo isset($profile->city_ref->cityName)?($profile->city_ref->cityName):'' ?></span></p>
<p><span class="ccc"><?php echo Common::t('State', 'account') ?>:</span> <span class="state"><?php echo isset($profile->state_ref->stateName)?($profile->state_ref->stateName):'' ?></span></p>
<p><span class="ccc"><?php echo Common::t('Country', 'account') ?>:</span> <span class="country"><?php echo isset($profile->country_ref->countryName)?($profile->country_ref->countryName):'' ?></span></p>
<p><span class="ccc"><?php echo Common::t('Zipcode', 'account') ?>:</span> <span class="zip"><?php echo $profile->zipcode ?></span></p>
<p><span class="ccc"><?php echo Common::t('What would you like to study?', 'account') ?></span><br/>- <span class="description"><?php echo $profile->expectation ?></span></p>
<p><span class="ccc"><?php echo Common::t('Grade year', 'account') ?>:</span> <span class="gradeyear"><?php echo $profile->grade_year ?></span></p>
<p><span class="ccc"><?php echo Common::t('GPA', 'account') ?>:</span> <span class="gpa"><?php echo $profile->gpa ?></span></p>