<?php

class LinklistController extends Controller
{
	public $subLayout = "";
	
	/**
	 * Add mix-ins to this model
	 *
	 * @return type
	 */
	public function behaviors() {
		return array(
				'SpaceControllerBehavior' => array(
						'class' => 'application.modules_core.space.SpaceControllerBehavior',
				),
				'ProfileControllerBehavior' => array(
						'class' => 'application.modules_core.user.ProfileControllerBehavior',
				),
		);
	}
	
	public function actionShowUserLinks()
	{
 		$this->subLayout = "application.modules_core.user.views.profile._layout";
// FRAGE: Wieso geht hier renderPartial nicht??? 		
// 		$this->renderPartial('showUserLinks', array('blub'=>'blub'), false);
		$this->render('showUserLinks', array('user' => $this->getUser()));
	}
	
	public function actionShowSpaceLinks()
	{
		$this->subLayout = "application.modules_core.space.views.space._layout";
		$this->render('showSpaceLinks', array('space' => $this->getSpace()));
	}
}

?>
