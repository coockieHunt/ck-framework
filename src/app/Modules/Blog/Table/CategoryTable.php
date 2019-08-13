<?php


namespace app\Modules\Blog\Table;


use app\Modules\Blog\Entity\CategoryEntity;
use ck_framework\Database\Table;
use PDO;

class CategoryTable extends Table
{
    protected $tableName = "category";
    protected $entity = CategoryEntity::class;

    /**
     * find between range categories
     * @param int $limit (limit post ex : 1)
     * @param int $offset (offset post  ex: 10)
     * @return array
     */
    public function FindResultLimit(int $limit, int $offset) :array{
        return $this->FindByLimit($limit, $offset, 'create_at DESC');;
    }

    /**
     * find category by slug
     * @param string $slug (slug post ex : this-slug)
     * @return mixed
     */
    public function FindBySlug(string $slug){
        return $this->FindByValue('slug', $slug, PDO::PARAM_STR);
    }

    public function NewCategory($name, $slug)
    {
        $request = $this->PDO
            ->prepare('INSERT INTO category (name, slug, create_at, update_at) VALUES (:name , :slug, NOW(), NOW())');
        $request->bindValue(':name', $name, PDO::PARAM_STR);
        $request->bindValue(':slug', $slug, PDO::PARAM_STR);
        $request->execute();
    }

    public function UpdateCategory(int $id, string $name, string $slug){
        $request = $this->PDO
            ->prepare('UPDATE category SET name = :name, slug = :slug, update_at = NOW() WHERE category.id = :id');
        $request->bindValue(':name', $name, PDO::PARAM_STR);
        $request->bindValue(':slug', $slug, PDO::PARAM_STR);
        $request->bindValue(':id', $id, PDO::PARAM_STR);
        $request->execute();
    }

    /**
     * find category by slug
     * @param int $id (id post ex : 1)
     * @return mixed
     */
    public function FindById(int $id){
        return $this->FindByValue('id', $id, PDO::PARAM_STR);
    }

    public function FindResultLimitFilter(string $name, string $slug, int $limit, int $offset) :array{
        $filter = ['name' => $name , 'slug' => $slug];
        $filterParse = '';
        $i = 0;
        foreach ($filter as $key => $value){
            if ($value != ''){
                if ($i == 0){
                    $filterParse = $filterParse . 'WHERE '.$key.' LIKE "%'.$value.'%" ';
                }else{
                    $filterParse = $filterParse . 'AND '.$key.' LIKE "%'.$value.'%" ';
                }
                $i ++;
            }
        }

        $query = 'SELECT * FROM category '. $filterParse .' LIMIT :limit, :offset';

        $request = $this->PDO
            ->prepare($query);
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);

        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, CategoryEntity::class);
        $posts = $request->fetchAll();
        return $posts;
    }

    public function CountFilter(string $name, string $slug)
    {
        $filter = ['name' => $name , 'slug' => $slug];
        $filterParse = '';
        $i = 0;
        foreach ($filter as $key => $value){
            if ($value != ''){
                if ($i == 0){
                    $filterParse = $filterParse . 'WHERE '.$key.' LIKE "%'.$value.'%" ';
                }else{
                    $filterParse = $filterParse . 'AND '.$key.' LIKE "%'.$value.'%" ';
                }
                $i ++;
            }
        }

        $query = 'SELECT COUNT(*) FROM category '. $filterParse;
        $posts = $this->PDO
            ->query($query)
            ->fetch();
        return $posts;
    }
}