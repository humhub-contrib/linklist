<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

use yii\db\Migration;

class m170811_163655_fix_category_stream_channel extends Migration
{
    public function safeUp()
    {
        $this->update('content', ['stream_channel' =>  new \yii\db\Expression('NULL')], ['object_model' => \humhub\modules\linklist\models\Category::class]);
    }

    public function safeDown()
    {
        echo "m170811_163655_fix_category_stream_channel cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170811_163655_fix_category_stream_channel cannot be reverted.\n";

        return false;
    }
    */
}
