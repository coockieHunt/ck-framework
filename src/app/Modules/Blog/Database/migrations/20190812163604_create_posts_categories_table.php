<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsCategoriesTable extends AbstractMigration
{
    public function change()
    {
        $this->table('category')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('update_at', 'datetime')
            ->addColumn('create_at', 'datetime')
            ->create();
    }
}
