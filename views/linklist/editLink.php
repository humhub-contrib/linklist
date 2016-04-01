<?php
/**
 * View to edit a link category.
 * 
 * @uses $link the link object.
 * @uses $isCreated true if the link is first created, false if an existing link is edited
 * 
 * @author Sebastian Stumpf
 * 
 */
humhub\modules\linklist\Assets::register($this);

use humhub\compat\CActiveForm;
use yii\helpers\Html;
?>


<div class="panel panel-default">
    <?php if ($link->isNewRecord) : ?>
        <div class="panel-heading"><?php echo Yii::t('LinklistModule.base', '<strong>Create</strong> new link'); ?></div>
    <?php else: ?>
        <div class="panel-heading"><?php echo Yii::t('LinklistModule.base', '<strong>Edit</strong> link'); ?></div>
    <?php endif; ?>

    <div class="panel-body">
        <?php
        $form = CActiveForm::begin();
        //echo $form->errorSummary($link); 
        ?>

        <div class="form-group">
            <?php echo $form->labelEx($link, Yii::t('LinklistModule.base', 'title')); ?>
            <?php echo $form->textField($link, 'title', array('class' => 'form-control')); ?>
            <?php echo $form->error($link, 'title'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($link, Yii::t('LinklistModule.base', 'description')); ?>
            <?php echo $form->textArea($link, 'description', array('class' => 'form-control', 'rows' => '2')); ?>
            <?php echo $form->error($link, 'description'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($link, 'href'); ?>
            <?php echo $form->textField($link, 'href', array('class' => 'form-control')); ?>
            <?php echo $form->error($link, 'href'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($link, Yii::t('LinklistModule.base', 'sort_order')); ?>
            <?php echo $form->textField($link, 'sort_order', array('class' => 'form-control')); ?>
            <?php echo $form->error($link, 'sort_order'); ?>
        </div>

        <?php echo Html::submitButton(Yii::t('LinklistModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>

        <?php CActiveForm::end(); ?>
    </div>
</div>