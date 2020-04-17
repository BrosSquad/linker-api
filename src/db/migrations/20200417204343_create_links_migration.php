<?php

use Phinx\Migration\AbstractMigration;

class CreateLinksMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('links')
            ->addColumn('url', 'string', ['limit' => 400])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex('url', ['unique' => true])
            ->create()
        ;
    }
}
