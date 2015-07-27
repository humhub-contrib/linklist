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

use humhub\compat\CActiveForm;
use yii\helpers\Html;
?>


<div class="panel panel-default">
    <?php if ($category->isNewRecord) : ?>
        <div class="panel-heading"><strong>Create</strong> new category</div>
    <?php else: ?>
        <div class="panel-heading"><strong>Edit</strong> category</div>
    <?php endif; ?>
    <div class="panel-body">

        <?php
        $form = CActiveForm::begin();
        $form->errorSummary($category);
        ?>

        <div class="form-group">
            <?php echo $form->labelEx($category, 'title'); ?>
            <?php echo $form->textField($category, 'title', array('class' => 'form-control')); ?>
            <?php echo $form->error($category, 'title'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($category, 'description'); ?>
            <?php echo $form->textArea($category, 'description', array('class' => 'form-control', 'rows' => 3)); ?>
            <?php echo $form->error($category, 'description'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($category, 'sort_order'); ?>
            <?php echo $form->textField($category, 'sort_order', array('class' => 'form-control')); ?>
            <?php echo $form->error($category, 'sort_order'); ?>
        </div>

        <?php echo Html::submitButton('Save', array('class' => 'btn btn-primary')); ?>

        <?php CActiveForm::end(); ?>
    </div>
</div>