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

use humhub\modules\linklist\assets\Assets;
use humhub\modules\linklist\models\Category;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;

/* @var Category $category */

Assets::register($this);
?>
<div class="panel panel-default">
    <?php if ($category->isNewRecord) : ?>
        <div class="panel-heading"><strong>Create</strong> new category</div>
    <?php else: ?>
        <div class="panel-heading"><strong>Edit</strong> category</div>
    <?php endif; ?>
    <div class="panel-body">

        <?php $form = ActiveForm::begin(); ?>

        <div class="mb-3">
            <?= $form->field($category, 'title')->textInput(); ?>
        </div>

        <div class="mb-3">
            <?= $form->field($category, 'description')->textarea(); ?>
        </div>

        <div class="mb-3">
            <?= $form->field($category, 'sort_order')->textInput(); ?>
        </div>

        <?= Button::save()->submit() ?>

        <?php $form::end(); ?>
    </div>
</div>
