<?php
/**
 * View to edit a link category.
 *
 * @uses $category the category object.
 * @uses $isCreated true if the category is first created, false if an existing category is edited.
 *
 * @author Sebastian Stumpf
 *
 */
humhub\modules\linklist\Assets::register($this);

use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

?>


<div class="panel panel-default">
    <?php if ($category->isNewRecord) : ?>
        <div class="panel-heading"><strong>Create</strong> new category</div>
    <?php else: ?>
        <div class="panel-heading"><strong>Edit</strong> category</div>
    <?php endif; ?>
    <div class="panel-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($category, 'title')->textInput(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($category, 'description')->textarea(); ?>
        </div>

        <div class="form-group">
            <?= $form->field($category, 'sort_order')->textInput(); ?>
        </div>

        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']); ?>

        <?php $form::end(); ?>
    </div>
</div>
