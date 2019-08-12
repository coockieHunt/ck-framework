<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsCategoriesTable extends AbstractMigration
{
    public function change()
    {
        $this->table('category')
            ->addColumn('title', 'string')
            ->addColumn('name', 'string')
            ->addColumn('create_at', 'datetime')
            ->create();
    }
}
