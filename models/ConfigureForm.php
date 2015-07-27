<?php

namespace humhub\modules\linklist\models;

use Yii;

/**
 * LinklistConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.linklist.forms
 * @author Sebastian Stumpf
 */
class ConfigureForm extends \yii\base\Model
{

    public $enableDeadLinkValidation;
    public $enableWidget;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // why do i nee a rule if if i dont haveobne, the value is not saved at all!
            array('enableDeadLinkValidation', 'boolean', 'falseValue' => 0, 'trueValue' => 1),
            array('enableWidget', 'boolean', 'falseValue' => 0, 'trueValue' => 1),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'enableDeadLinkValidation' => Yii::t('LinklistModule.base', 'Extend link validation by a connection test.'),
            'enableWidget' => Yii::t('LinklistModule.base', 'Show the links as a widget on the right.'),
        );
    }

}
