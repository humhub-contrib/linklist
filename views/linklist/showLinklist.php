<?php 
/**
 * View to edit a link category.
 * 
 * @uses $container_type an identifier for the container.
 * @uses $container_guid the container may be a space or user object.
 * @uses $categories an array of categories.
 * @uses $links an array of arrays of links, indicated by the category id
 * @uses $isAdmin true if the current user is allowed to edit the links.
 * 
 */
?>

<div id="linklist-empty-txt" <?php if(empty($categories)) { echo 'style="visibility:visible; display:block"'; } ?>><?php echo Yii::t('LinklistModule.base', 'There have been no links or categories added to this space yet.') ?></div>

<?php foreach($categories as $category) { ?>
<div id="linklist-category_<?php echo $category->id?>" class="panel panel-default panel-linklist-category">
	<div class="panel-heading">
		<div class="heading">
			<?php echo $category->title; ?>
			<?php if(Yii::app()->getController()->accessLevel != 0) { ?>
			<div class="linklist-edit-controls linklist-editable">
				<?php if(Yii::app()->getController()->accessLevel == 2) {		 
				// admins may edit and delete categories
				$this->widget('application.widgets.ModalConfirmWidget', array(
			        'uniqueID' => 'modal_categorydelete_'.$category->id,
			        'linkOutput' => 'a',
					'class' => 'deleteButton" title="'.Yii::t('LinklistModule.base', 'Delete category'),
			        'title' => Yii::t('LinklistModule.base', '<strong>Confirm</strong> category deleting'),
			        'message' => Yii::t('LinklistModule.base', 'Do you really want to delete this category? All connected links will be lost!'),
			        'buttonTrue' => Yii::t('LinklistModule.base', 'Delete'),
			        'buttonFalse' => Yii::t('LinklistModule.base', 'Cancel'),
			        'linkContent' => '<i class="fa fa-trash-o"></i> ',
			        'linkHref' => $this->createUrl("//linklist/linklist/deleteCategory", array('category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid)),
			        'confirmJS' => 'function() { 
							$("#linklist-category_'.$category->id.'").remove();
							$("#linklist-widget-category_'.$category->id.'").remove(); 
							if($(".panel-linklist-widget").find(".media").length == 0) {
								$(".panel-linklist-widget").remove();
							}
						}'
			    ));
				echo CHtml::link('<i class="fa fa-pencil-square-o"></i>', array('//linklist/linklist/editCategory', 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title'=>'Edit Category'));
				}
				// all users may add a link to an existing category
				echo CHtml::link('<i class="fa fa-plus-square-o"></i>', array('//linklist/linklist/editLink', 'link_id' => -1, 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title'=>'Add Link')); ?>
			</div>
			<?php } ?>
		</div>
	</div>
    <div class="panel-body">  
		<div class="media">
			<?php if(!($category->description == NULL || $category->description == "")) { ?>
				<div class="media-heading"><?php echo $category->description; ?></div>
			<?php } ?>
			<div class="media-body">	
				<ul>
				<?php foreach($links[$category->id] as $link) { ?>
					<li id="linklist-link_<?php echo $link->id;?>">
						<a href="<?php echo $link->href; ?>" title="<?php echo $link->description; ?>"><?php echo $link->title; ?></a>
						
						<div class="linklist-interaction-controls">	
						<?php $this->widget('application.modules_core.comment.widgets.CommentLinkWidget', array('object' => $link, 'mode' => 'popup')); ?> &middot;
                        <?php $this->widget('application.modules_core.like.widgets.LikeLinkWidget', array('object' => $link)); ?>
						</div>	
                       	<?php // all admins and users that created the link may edit or delete it ?>                         
						<?php if(Yii::app()->getController()->accessLevel == 2 || Yii::app()->getController()->accessLevel == 1 && $link->content->created_by == Yii::app()->user->id) { ?>
							<div class="linklist-edit-controls linklist-editable">		 
							<?php $this->widget('application.widgets.ModalConfirmWidget', array(
						        'uniqueID' => 'modal_linkdelete_'.$link->id,
						        'linkOutput' => 'a',
								'class' => 'deleteButton" title="'.Yii::t('LinklistModule.base', 'Delete link'),
						        'title' => Yii::t('LinklistModule.base', '<strong>Confirm</strong> link deleting'),
						        'message' => Yii::t('LinklistModule.base', 'Do you really want to delete this link?'),
						        'buttonTrue' => Yii::t('LinklistModule.base', 'Delete'),
						        'buttonFalse' => Yii::t('LinklistModule.base', 'Cancel'),
						        'linkContent' => '<i class="fa fa-trash-o"></i> ',
						        'linkHref' => $this->createUrl("//linklist/linklist/deleteLink", array('category_id' => $category->id, 'link_id' => $link->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid)),
						        'confirmJS' => 'function() { 
										$("#linklist-link_'.$link->id.'").remove();
										$("#linklist-widget-link_'.$link->id.'").remove(); 
										if($("#linklist-widget-category_'.$category->id.'").find("li").length == 0) {
											$("#linklist-widget-category_'.$category->id.'").remove();
										}
										if($(".panel-linklist-widget").find(".media").length == 0) {
											$(".panel-linklist-widget").remove();
										}
									}'
						    ));
							echo CHtml::link('<i class="fa fa-pencil-square-o"></i>', array('//linklist/linklist/editLink', 'link_id' => $link->id, 'category_id' => $category->id, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('title'=>'Edit Link')); ?>
						</div>
						<?php } ?>
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
    </div>
</div>
<?php } ?>
<?php if(Yii::app()->getController()->accessLevel != 0) { ?>
<?php if(!empty($categories)) { ?>
<div class="toggle-view-mode"><a href="#" class="btn btn-primary"><?php echo Yii::t('LinklistModule.base', 'Toggle view mode') ?></a></div>
<?php } ?>
<?php if(Yii::app()->getController()->accessLevel == 2) { ?>
<div class="linklist-add-category linklist-editable"><?php echo CHtml::link('Add Category', array('//linklist/linklist/editCategory', 'category_id' => -1, Yii::app()->getController()->guidParamName => Yii::app()->getController()->contentContainer->guid), array('class' => 'btn btn-primary'));?></div>
<?php } ?>
<?php } ?>
