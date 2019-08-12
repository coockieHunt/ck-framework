<?php


namespace app\Modules\Blog\Table;


use app\Modules\Blog\Entity\PostsEntity;
use ck_framework\Database\Table;
use PDO;

class PostsTable extends Table
{
    protected $tableName = "posts";
    protected $entity = PostsEntity::class;


    /**
     * find post by slug
     * @param string $slug (slug post ex : this-slug)
     * @return mixed
     */
    public function FindBySlug(string $slug){
        return $this->FindByValue('slug', $slug, PDO::PARAM_STR);
    }

    /**
     * find post by slug
     * @param int $id (id post ex : 1)
     * @return mixed
     */
    public function FindById(int $id){
        return $this->FindByValue('id', $id, PDO::PARAM_STR);
    }

    /**
     * find between range post
     * @param int $limit (limit post ex : 1)
     * @param int $offset (offset post  ex: 10)
     * @return array
     */
    public function FindResultLimit(int $limit, int $offset) :array{
        return $this->FindByLimit($limit, $offset, 'create_at DESC');;
    }


    public function UpdatePost(int $id, string $name, string $content, string $slug, bool $active){
        $request = $this->PDO
            ->prepare('UPDATE posts SET name = :name, content = :content, slug = :slug , active = :active, update_at = NOW() WHERE posts.id = :id');
        $request->bindValue(':name', $name, PDO::PARAM_STR);
        $request->bindValue(':content', $content, PDO::PARAM_STR);
        $request->bindValue(':slug', $slug, PDO::PARAM_STR);
        $request->bindValue(':id', $id, PDO::PARAM_STR);
        $request->bindValue(':active', $active, PDO::PARAM_BOOL);
        $request->execute();
    }

    public function NewPost($name, $content, $slug, $active)
    {
        $request = $this->PDO
            ->prepare('INSERT INTO posts (name, content, slug, active,create_at, update_at) VALUES (:name , :content, :slug, :active, NOW(), NOW())');
        $request->bindValue(':name', $name, PDO::PARAM_STR);
        $request->bindValue(':content', $content, PDO::PARAM_STR);
        $request->bindValue(':slug', $slug, PDO::PARAM_STR);
        $request->bindValue(':active', $active, PDO::PARAM_BOOL);
        $request->execute();
    }

    public function FindResultLimitFilter(string $name, string $slug, string $content, int $limit, int $offset) :array{
        $filter = ['name' => $name , 'slug' => $slug , 'content' => $content ];
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

        $query = 'SELECT * FROM posts '. $filterParse .' LIMIT :limit, :offset';

        $request = $this->PDO
            ->prepare($query);
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);

        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, PostsEntity::class);
        $posts = $request->fetchAll();
        return $posts;
    }

    public function CountFilter(string $name, string $slug, string $content)
    {
        $filter = ['name' => $name , 'slug' => $slug , 'content' => $content ];
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

        $query = 'SELECT COUNT(*) FROM posts '. $filterParse;
        $posts = $this->PDO
            ->query($query)
            ->fetch();
        return $posts;
    }
}