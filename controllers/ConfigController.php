<?php
/**
 * Defines the configure actions.
 *
 * @package humhub.modules.linklist.controllers
 * @author Sebastian Stumpf
 */
class ConfigController extends Controller {

    public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Configuration Action for Super Admins
     */
    public function actionConfig() {

        Yii::import('linklist.forms.*');

        $form = new LinklistConfigureForm();

//         uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'linklist-configure-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (isset($_POST['LinklistConfigureForm'])) {
            $_POST['LinklistConfigureForm'] = Yii::app()->input->stripClean($_POST['LinklistConfigureForm']);
            $form->attributes = $_POST['LinklistConfigureForm'];

            if ($form->validate()) {
                $form->enableDeadLinkValidation = HSetting::Set('enableDeadLinkValidation', $form->enableDeadLinkValidation, 'linklist');
                $this->redirect(Yii::app()->createUrl('linklist/config/config'));
            }
        } else {
            $form->enableDeadLinkValidation = HSetting::Get('enableDeadLinkValidation', 'linklist');
            if($form->enableDeadLinkValidation == '') {
            	$form->enableDeadLinkValidation = 0;
            }
        }

        $this->render('config', array('model' => $form));
    }
}

?>
