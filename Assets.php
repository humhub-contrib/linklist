<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\linklist;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{

    public $css = [
        'linklist.css',
    ];
    public $js = [
        'linklist.js',
    ];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }

}
