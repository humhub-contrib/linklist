<?php
/**
 * LinklistConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.linklist.forms
 * @author Sebastian Stumpf
 */
class LinklistConfigureForm extends CFormModel {

    public $enableDeadLinkValidation;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
        		array('enableDeadLinkValidation', 'required'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'enableDeadLinkValidation' => Yii::t('LinklistModule.base', 'If enabled, entering links that cannot be connected to, will not be accepted.'),
        );
    }

}