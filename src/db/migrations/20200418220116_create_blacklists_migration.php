<?php

use Phinx\Migration\AbstractMigration;

class CreateBlacklistsMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('blacklists');

        $table->addColumn('url', 'string', ['limit' => 300])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex('url', ['unique' => true])
            ->create()
        ;
    }
}
