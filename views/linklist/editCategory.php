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
?>


<div class="panel panel-default">
    <div class="panel-heading"><strong>Create</strong> new Category</div>
    <div class="panel-body">
    	<?php if($isCreated) { ?>
    	<?php } else { ?>
    	<p>Edit Category</p>	
		<?php }
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'category-edit-form',
            'enableAjaxValidation' => false,
        ));
		//echo $form->errorSummary($category); ?>

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
	        <?php echo $form->numberField($category, 'sort_order', array('class' => 'form-control')); ?>
	        <?php echo $form->error($category, 'sort_order'); ?>
	    </div>
	    
        <?php echo CHtml::submitButton('Save', array('class' => 'btn btn-primary')); ?>
        
        <?php $this->endWidget(); ?>
    </div>
</div>