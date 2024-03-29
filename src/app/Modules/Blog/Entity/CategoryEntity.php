<?php


namespace app\Modules\Blog\Entity;


use DateTime;

class CategoryEntity
{
    public $id;

    public $name;

    public $slug;

    public $create_at;

    public $update_at;

    public function __construct()
    {
        if ($this->create_at) {
            $this->create_at = new DateTime($this->create_at);
        }
        if ($this->update_at) {
            $this->update_at = new DateTime($this->update_at);

        }
    }
}