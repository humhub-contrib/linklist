<?php

/**
 * Linklist configuration view.
 *
 * @uses $form the form with the formular fields.
 *
 * @author Sebastian Stumpf
 *
 */
use humhub\compat\CActiveForm;
use yii\helpers\Html;

humhub\modules\linklist\Assets::register($this);
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('LinklistModule.base', 'Linklist Module Configuration'); ?></div>
    <div class="panel-body">

        <p><?php echo Yii::t('LinklistModule.base', 'You can enable the extended validation of links for a space or user.'); ?></p><br />

        <?php
        $form = CActiveForm::begin();
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'enableDeadLinkValidation'); ?> <?php echo $model->getAttributeLabel('enableDeadLinkValidation'); ?>
                </label>
            </div>
        </div>
        <?php echo $form->error($model, 'enableDeadLinkValidation'); ?>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'enableWidget'); ?> <?php echo $model->getAttributeLabel('enableWidget'); ?>
                </label>
            </div>
        </div>
        <?php echo $form->error($model, 'enableWidget'); ?>

        <hr>
        <?php echo Html::submitButton(Yii::t('LinklistModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <?php CActiveForm::end(); ?>
    </div>
</div>