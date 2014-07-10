
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('LinklistModule.base', 'Linklist Module Configuration'); ?></div>
    <div class="panel-body">


        <p><?php echo Yii::t('LinklistModule.base', 'You may enable the extended validation of links.'); ?></p>
        <br/>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'linklist-configure-form',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
        	<?php echo $form->checkBox($model, 'enableDeadLinkValidation'); ?>&nbsp;&nbsp;
            <?php echo $form->labelEx($model, 'enableDeadLinkValidation'); ?>
            <?php echo $form->error($model, 'enableDeadLinkValidation'); ?>
        </div>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('LinklistModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo $this->createUrl('//space/admin/modules', array(
        		'sguid' => Yii::app()->getController()->getSpace()->guid,
        ))?>"><?php echo Yii::t('AdminModule.base', 'Back to modules'); ?></a>
        <?php $this->endWidget(); ?>
    </div>
</div>