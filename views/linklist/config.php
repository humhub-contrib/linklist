<?php 
/**
 * Linklist configuration view.
 * 
 * @uses $form the form with the formular fields.
 * 
 * @author Sebastian Stumpf
 * 
 */
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('LinklistModule.base', 'Linklist Module Configuration'); ?></div>
    <div class="panel-body">

        <p><?php echo Yii::t('LinklistModule.base', 'You can enable the extended validation of links for a space or user.'); ?></p>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'linklist-configure-form',
            'enableAjaxValidation' => false,
        ));
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

        <hr>
        <?php echo CHtml::submitButton(Yii::t('LinklistModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Yii::app()->getController()->modulesUrl?>"><?php echo Yii::t('AdminModule.base', 'Back to modules'); ?></a>
        <?php $this->endWidget(); ?>
    </div>
</div>