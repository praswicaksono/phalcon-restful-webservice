<?php

use Phinx\Migration\AbstractMigration;

class ApiKeys extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
     * public function change()
     * {
     * }
     */

    /**
     * Migrate Up.
     */
    public function up()
    {
        if (!$this->hasTable("api_keys")) {
            $apiKeys = $this->table(
                "api_keys",
                [
                    "id" => "api_key_id",
                    "primary_key" => [
                        "api_key_id"
                    ]
                ]
            );

            $apiKeys->addColumn("key", "string", ["length" => 64])
                ->addColumn("level", "integer", ["default" => 1])
                ->addColumn("ignore_limit", "boolean", ["default" => 0])
                ->addColumn("created_at", "datetime", ["null" => true])
                ->addColumn("updated_at", "datetime", ["null" => true])
                ->addIndex(["key"], ["unique" => true])
                ->create();
        }

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable("api_keys");
    }
}

//EOF
