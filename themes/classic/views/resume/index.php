<?php
/* @var $this ScholarshipController */

$this->pageTitle = Common::t('Resume', 'post');

Common::register_css(Yii::app()->theme->baseUrl . '/css/datepicker.css');
Common::register_js(Yii::app()->theme->baseUrl . '/js/bootstrap-datepicker.js', CClientScript::POS_END);
//
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/additional-methods.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.bootstrap.popover.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/localization/messages_' . $this->lang . '.js', CClientScript::POS_END);

Common::register_js(Yii::app()->theme->baseUrl . '/js/myresume.js', CClientScript::POS_END);

$script_hide_interest = <<< EOD
$('#interestList .glyphicon-plus').hide();
EOD;
if ($data_interest != NULL) {
    Common::register_script('resume-form', $script_hide_interest);
}
?>

<div class="resume-list">
    <ul>
        <!-- Extra Curricular List -->
        <li data-id="<?php echo $model[0]->id ?>" id="curricularList">
            <h5><?php echo $model[0]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#curricularModal" title="<?php echo Common::t('Add', 'post') ?>"></span>
                <?php $this->renderPartial('_extra', array('model' => $data_extra)) ?>
            </div>
        </li>
        <!-- Experience List -->
        <li data-id="<?php echo $model[1]->id ?>" id="experienceList">
            <h5><?php echo $model[1]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#experienceModal" title="<?php echo Common::t('Add', 'post') ?>"></span>
                <?php $this->renderPartial('_experience', array('model' => $data_exper)) ?>
            </div>
        </li>
        <!-- Education List -->
        <li  data-id="<?php echo $model[2]->id ?>" id="educationList">
            <h5><?php echo $model[2]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#educationModal"></span>
                <?php $this->renderPartial('_education', array('model' => $data_education)) ?>
            </div>
        </li>
        <!-- Honors & Award List -->
        <li  data-id="<?php echo $model[3]->id ?>" id="awardList">
            <h5><?php echo $model[3]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#awardModal"></span>
                <?php $this->renderPartial('_honor_award', array('model' => $data_honor)) ?>
            </div>
        </li>
        <!-- Specialties & Skills List -->
        <li  data-id="<?php echo $model[4]->id ?>" id="skillList">
            <h5><?php echo $model[4]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#skillModal"></span>
                <?php $this->renderPartial('_specialties_skills', array('model' => $data_skill)) ?>
            </div>
        </li>
        <!-- Interests List -->
        <li  data-id="<?php echo $model[5]->id ?>" id="interestList">
            <h5><?php echo $model[5]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#interestModal"></span>
                <?php $this->renderPartial('_interests', array('model' => $data_interest)) ?>
            </div>
        </li>
        <!-- Favorites List -->
        <li  data-id="<?php echo $model[6]->id ?>" id="favoriteList">
            <h5><?php echo $model[6]->type ?></h5>
            <div class="list">
                <span class="glyphicon glyphicon-plus hand" data-toggle="modal" data-target="#favoriteModal"></span>
                <?php $this->renderPartial('_favorites', array('model' => $data_favorite)) ?>
            </div>
        </li>
    </ul>
</div>

<!-- Modal Extra-Curricular -->
<div class="modal fade recruited-popup" tabindex="-1" id="curricularModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content resume-frame">
            <div >
                <h2 class="button-add">Add Extra-Curricular & Activity</h2>
                <h2 class="button-update">Update Extra-Curricular & Activity</h2>
                <h2 class="button-delete">Delete Extra-Curricular & Activity</h2>
                <form id="form-resume-extra" name="form-resume-extra" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="activity" class=" col-sm-3 col-md-3 control-label">Activity</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('activity', $model_extra->activity, array('class' => 'form-control activity-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="position" class=" col-sm-3 col-md-3  control-label">Position</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('position', $model_extra->position, array('class' => 'form-control position-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class=" col-sm-3 col-md-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textArea('description', $model_extra->description, array('class' => 'form-control description-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <?php echo CHtml::checkBox('uptonow', $model_extra->uptonow, array('class' => 'check-text')) ?> I Am Currently Involved In This Activity
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start" class=" col-sm-3 col-md-3 control-label">Start</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('start', $model_extra->start, array('data-date-format' => 'yyyy-mm-dd', 'readonly' => 'readonly', 'class' => 'form-control datepicker start-text')); ?>
                        </div>
                    </div>
                    <div id="end_extra_block" class="form-group">
                        <label for="end" class=" col-sm-3 col-md-3 control-label">End</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('end', $model_extra->start, array('data-date-format' => 'yyyy-mm-dd', 'readonly' => 'readonly', 'class' => 'form-control datepicker end-text')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Experience -->
<div class="modal fade recruited-popup" tabindex="-1" id="experienceModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2 class="button-add">Add Work Experience</h2>
                <h2 class="button-update">Update Work Experience</h2>
                <h2 class="button-delete">Delete Work Experience</h2>
                <form id="form-resume-experience" name="form-resume-experience" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="employer" class="col-sm-3 col-md-3 control-label">Employer</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('employer', $model_exper->employer, array('class' => 'form-control employer-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="position" class=" col-sm-3 col-md-3  control-label">Position/Title</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('position', $model_exper->position, array('class' => 'form-control position-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="industry" class=" col-sm-3 col-md-3  control-label">Industry</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('industry', '', CHtml::listData(Industry::model()->findAll(), 'id', 'title'), array('class' => 'form-control industry-text', 'empty' => '-- Choose Industry --')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class=" col-sm-3 col-md-3  control-label">Description</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textArea('description', $model_exper->description, array('class' => 'form-control description-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <?php echo CHtml::checkBox('uptonow', $model_exper->uptonow, array('class' => 'check-text')) ?> I Am Currently Involved In This Activity
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start" class=" col-sm-3 col-md-3  control-label">Start</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('start', $model_exper->start, array('data-date-format' => 'yyyy-mm-dd', 'readonly' => 'readonly', 'class' => 'form-control datepicker-start datepicker start-text')); ?>
                        </div>
                    </div>
                    <div id="end_exper_block" class="form-group">
                        <label for="end" class=" col-sm-3 col-md-3  control-label">End</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('end', $model_exper->start, array('data-date-format' => 'yyyy-mm-dd', 'readonly' => 'readonly', 'class' => 'form-control datepicker-end datepicker end-text')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Education -->
<div class="modal fade recruited-popup" tabindex="-1" id="educationModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2 class="button-add">Add High School Info </h2>
                <h2 class="button-update">Update High School Info</h2>
                <h2 class="button-delete">Delete High School Info</h2>
                <form id="form-resume-education" role="form" class="form-horizontal">

                    <div class="form-group">
                        <label for="school_name" class=" col-sm-3 col-md-3  control-label">High School Name</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('school_name', $model_education->school_name, array('class' => 'form-control highschool-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="grade_year" class=" col-sm-3 col-md-3 gradyear-text control-label">Grade year</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('grade_year', '', Common::get_years_from_current(), array('class' => 'form-control')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gpa" class=" col-sm-3 col-md-3  control-label">GPA</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('gpa', $model_education->gpa, array('class' => 'form-control gpa-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="class_rank" class=" col-sm-3 col-md-3  control-label">Class Rank</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('class_rank', $model_education->class_rank, array('class' => 'form-control classrank-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Honors & Award -->
<div class="modal fade recruited-popup" tabindex="-1" id="awardModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2>Honors & Awards</h2>
                <form id="form-resume-honor-award" role="form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php echo CHtml::textArea('description', $model_honor->description, array('class' => 'form-control award-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Specialties & Skills -->
<div class="modal fade recruited-popup" tabindex="-1" id="skillModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2>Specialties & Skills</h2>
                <form id="form-resume-skill" role="form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php echo CHtml::textArea('description', $model_skill->description, array('class' => 'form-control skill-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Interests List -->
<div class="modal fade recruited-popup" tabindex="-1" id="interestModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2>Please select some interests below</h2>
                <form id="form-resume-interest" role="form" class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-3">Academics</div>
                        <?php $c = 0; $brl = 3; ?>
                        <?php foreach (ResumeInterest::item_alias('academics') as $value): ?>
                        <?php if ($c % $brl == 0): ?>
                            <div class="clearfix"></div>
                            <div class="col-sm-3">&nbsp;</div>
                        <?php endif; ?>
                        <div class="col-sm-3">
                            <label><input type="checkbox" data-text="<?php echo $value ?>"><?php echo $value ?></label>
                        </div>
                        <?php $c++; ?>
                        <?php endforeach; ?>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3">Extra-Curricular</div>
                         <?php $c = 0; $brl = 3; ?>
                        <?php foreach (ResumeInterest::item_alias('extra-curricular') as $value): ?>
                        <?php if ($c % $brl == 0): ?>
                            <div class="clearfix"></div>
                            <div class="col-sm-3">&nbsp;</div>
                        <?php endif; ?>
                        <div class="col-sm-3">
                            <label><input type="checkbox" data-text="<?php echo $value ?>"><?php echo $value ?></label>
                        </div>
                        <?php $c++; ?>
                        <?php endforeach; ?>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="button" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="button" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Favorites -->
<div class="modal fade recruited-popup" tabindex="-1" id="favoriteModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2>Favorites</h2>
                <form id="form-resume-favorite" role="form" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form-label">Music</label>
                            <?php echo CHtml::textArea('music', $model_favorite->music, array('class' => 'form-control music-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Quotes</label>
                            <?php echo CHtml::textArea('quote', $model_favorite->quote, array('class' => 'form-control quotes-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form-label">TV Show</label>
                            <?php echo CHtml::textArea('tvshow', $model_favorite->tvshow, array('class' => 'form-control tv-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Books</label>
                            <?php echo CHtml::textArea('book', $model_favorite->book, array('class' => 'form-control book-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="form-label">Movies</label>
                            <?php echo CHtml::textArea('movie', $model_favorite->movie, array('class' => 'form-control movie-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Web Sites</label>
                            <?php echo CHtml::textArea('website', $model_favorite->website, array('class' => 'form-control web-text', 'row' => '3', 'style' => 'resize:vertical;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-add">Save</button>
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Update</button>
                            <button type="button" class="btn btn-primary btn btn-go button-delete">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>