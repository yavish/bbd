<?php
/**
 * View to edit a business .
 *
 * @var $business lmy\humhub\modules\bbd\models\Business
 * @var $isCreated bool true if the business is first created, false if an existing business is edited
 *
 * @author Sebastian Stumpf
 *
 */
lmy\humhub\modules\bbd\assets\Assets::register($this);

use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

?>


<div class="panel panel-default">
    <?php if ($business->isNewRecord) : ?>
        <div class="panel-heading"><strong>Create</strong> new business</div>
    <?php else: ?>
        <div class="panel-heading"><strong>Edit</strong> business</div>
    <?php endif; ?>

    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($business, 'room_number')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($business, 'title')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($business, 'description')->textarea(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($business, 'href')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($business, 'sort_order')->textInput(); ?>
        </div>

        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']); ?>

        <?php $form::end(); ?>
    </div>
</div>