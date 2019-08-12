<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('posts')
            ->addColumn('id_category', 'integer', ['signed' => true, 'default' => true])
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('active', 'boolean', ['signed' => true, 'default' => true])
            ->addColumn('update_at', 'datetime')
            ->addColumn('create_at', 'datetime')
            ->create();
    }
}
