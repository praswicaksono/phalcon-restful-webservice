<?php

use Phinx\Migration\AbstractMigration;

class ApiLogs extends AbstractMigration
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
        if (!$this->hasTable("api_logs")) {
            $apiLogs = $this->table(
                "api_logs",
                [
                    "id" => "api_logs_id",
                    "primary_key" => [
                        "api_logs_id"
                    ]
                ]
            );

            $apiLogs->addColumn("api_key_id", "integer")
                ->addColumn("route", "string", ["length" => 128])
                ->addColumn("method", "string", ["length" => 64])
                ->addColumn("param", "text", ["null" => true])
                ->addColumn("ip_address", "string", ["length" => 64])
                ->addColumn("created_at", "datetime", ["null" => true])
                ->addColumn("updated_at", "datetime", ["null" => true])
                ->addForeignKey("api_key_id", "api_keys", "api_key_id", ["delete" => "CASCADE"])
                ->addIndex(["created_at", "updated_at"])
                ->create();
        }

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable("api_logs");
    }
}