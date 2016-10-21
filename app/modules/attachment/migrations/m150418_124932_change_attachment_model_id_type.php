<?php

use yii\db\Schema;
use yii\db\Migration;

class m150418_124932_change_attachment_model_id_type extends Migration
{
    public function up()
    {
        $this->alterColumn('attachments', 'model_id', Schema::TYPE_BIGINT);
        $this->addColumn('attachments', 'user_id', Schema::TYPE_INTEGER . ' DEFAULT NULL');
    }

    public function down()
    {
        $this->alterColumn('attachments', 'model_id', Schema::TYPE_INTEGER);
        $this->dropColumn('attachments', 'user_id');
    }
}
