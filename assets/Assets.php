<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\linklist\assets;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@linklist/resources';

    /**
     * @inheritdoc
     */
    public $css = [
        'linklist.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'linklist.js',
    ];
}
