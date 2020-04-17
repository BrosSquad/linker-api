<?php

use Phinx\Migration\AbstractMigration;

class CreateKeysMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('keys');
        $table->addColumn('key', 'string', ['limit' => 90, 'null' => true])
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex('name', ['unique' => true])
            ->create()
        ;
    }
}
