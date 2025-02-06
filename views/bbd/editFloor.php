<?php
/**
 * View to edit a business floor.
 *
 * @uses $floor the floor object.
 * @uses $isCreated true if the floor is first created, false if an existing floor is edited.
 *
 * @author Sebastian Stumpf
 *
 */
lmy\humhub\modules\bbd\assets\Assets::register($this);
 
use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

?>


<div class="panel panel-default">
    <?php if ($floor->isNewRecord) : ?>
        <div class="panel-heading"><strong>Create</strong> new floor</div>
    <?php else: ?>
        <div class="panel-heading"><strong>Edit</strong> floor</div>
    <?php endif; ?>
    <div class="panel-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($floor, 'title')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($floor, 'description')->textarea(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($floor, 'sort_order')->textInput(); ?>
        </div>

        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']); ?>

        <?php $form::end(); ?>
    </div>
</div>