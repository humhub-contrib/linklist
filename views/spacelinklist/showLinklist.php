<?php 
/**
 * View to edit a link category.
 * 
 * @uses $container_type an identifier for the container.
 * @uses $container_guid the container may be a space or user object.
 * @uses $categories an array of categories.
 * @uses $links an array of arrays of links, indicated by the category id
 * @uses $editable true if the current user is allowed to edit the links.
 * 
 */
?>

<?php foreach($categories as $category) { ?>
<div id="category-entry_<?php echo $category->id?>" class="panel panel-default">
	<div class="panel-heading">
		<?php echo $category->title; ?>
		<?php if($editable) {
			$this->widget('application.widgets.ModalConfirmWidget', array(
		        'uniqueID' => 'modal_categorydelete_'.$category->id,
				'class' => 'pull-right" title="Delete Category" style="padding-left:5px;',
		        'linkOutput' => 'a',
		        'title' => Yii::t('LinklistModule.base', '<strong>Confirm</strong> category deleting'),
		        'message' => Yii::t('LinklistModule.base', 'Do you really want to delete this category? All connected links will be lost!'),
		        'buttonTrue' => Yii::t('LinklistModule.base', 'Delete'),
		        'buttonFalse' => Yii::t('LinklistModule.base', 'Cancel'),
		        'linkContent' => '<i class="fa fa-trash-o"></i> ',
		        'linkHref' => $this->createUrl("//linklist/spacelinklist/deleteCategory", array('category_id' => $category->id, 'sguid' => $sguid)),
		        'confirmJS' => 'function() { 
						$("#category-entry_'.$category->id.'").remove();
						$("#category-widget-entry_'.$category->id.'").remove(); 
						if($(".panel-linklist-widget").find(".media").length == 0) {
							$(".panel-linklist-widget").remove();
						}
					}'
		    ));
			echo CHtml::link('<i class="fa fa-pencil-square-o"></i>', array('//linklist/spacelinklist/editCategory', 'category_id' => $category->id, 'sguid' => $sguid), array('title'=>'Edit Category', 'class' => 'pull-right', 'style' => 'padding-left:5px;'));
			echo CHtml::link('<i class="fa fa-plus-square"></i>', array('//linklist/spacelinklist/editLink', 'link_id' => -1, 'category_id' => $category->id, 'sguid' => $sguid), array('title'=>'Add Link', 'class'=>'pull-right', 'style' => 'padding-left:5px;'));
		} ?>
	</div>
    <div class="panel-body">  
		<div class="media">
			<div class="media-body">	
				<p><?php echo $category->description; ?></p>
				<ul>
				<?php foreach($links[$category->id] as $link) { ?>
					<li id="link-entry_<?php echo $link->id;?>" style="padding-bottom:10px">
						<a href="<?php echo $link->href; ?>" title="<?php echo $link->description; ?>"><?php echo $link->title; ?></a>
						<?php if($editable) {
							$this->widget('application.widgets.ModalConfirmWidget', array(
						        'uniqueID' => 'modal_linkdelete_'.$link->id,
								'class' => 'pull-right" title="Delete Link" style="padding-left:5px;',
						        'linkOutput' => 'a',
						        'title' => Yii::t('LinklistModule.base', '<strong>Confirm</strong> link deleting'),
						        'message' => Yii::t('LinklistModule.base', 'Do you really want to delete this link?'),
						        'buttonTrue' => Yii::t('LinklistModule.base', 'Delete'),
						        'buttonFalse' => Yii::t('LinklistModule.base', 'Cancel'),
						        'linkContent' => '<i class="fa fa-trash-o"></i> ',
						        'linkHref' => $this->createUrl("//linklist/spacelinklist/deleteLink", array('category_id' => $category->id, 'link_id' => $link->id, 'sguid' => $sguid)),
						        'confirmJS' => 'function() { 
										$("#link-entry_'.$link->id.'").remove();
										$("#link-widget-entry_'.$link->id.'").remove(); 
										if($("#category-widget-entry_'.$category->id.'").find("li").length == 0) {
											$("#category-widget-entry_'.$category->id.'").remove();
										}
										if($(".panel-linklist-widget").find(".media").length == 0) {
											$(".panel-linklist-widget").remove();
										}
									}'
						    ));
							echo CHtml::link('<i class="fa fa-pencil-square-o"></i>', array('//linklist/spacelinklist/editLink', 'link_id' => $link->id, 'category_id' => $category->id, 'sguid' => $sguid), array('title'=>'Edit Link', 'class' => 'pull-right'));
						} ?>
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
    </div>
</div>
<?php } ?>
<?php if($editable) { ?>
<div><?php echo CHtml::link('Add Category', array('//linklist/spacelinklist/editCategory', 'category_id' => -1, 'sguid' => $sguid), array('class' => 'btn btn-primary'));?></div>
<?php } ?>