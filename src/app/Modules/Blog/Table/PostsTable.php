<?php


namespace app\Modules\Blog\Table;


use app\Modules\Blog\Entity\PostsEntity;
use DateTime;
use PDO;

class PostsTable
{
    /**
     * @var PDO
     */
    private $PDO;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function FindResultLimit(int $limit, int $offset) :array{
        $request = $this->PDO
            ->prepare('SELECT * FROM posts ORDER BY posts.create_at DESC LIMIT :limit, :offset');
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);

        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, PostsEntity::class);
        $posts = $request->fetchAll();

        return $posts;
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

    public function FindAll() :array{
        $request = $this->PDO
            ->prepare('SELECT * FROM posts');

        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, PostsEntity::class);
        $posts = $request->fetchAll();

        return $posts;
    }

    public function FindBySlug(string $slug){
        $request = $this->PDO
            ->prepare('SELECT * FROM posts WHERE posts.slug = :slug');
        $request->bindValue(':slug', $slug, PDO::PARAM_STR);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, PostsEntity::class);
        $post = $request->fetch();

        return $post;
    }

    public function FindById(int $id){
        $request = $this->PDO
            ->prepare('SELECT * FROM posts WHERE posts.id = :id');
        $request->bindValue(':id', $id, PDO::PARAM_STR);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, PostsEntity::class);
        $post = $request->fetch();

        return $post;
    }

    public function CountAll()
    {
        $posts = $this->PDO
            ->query('SELECT COUNT(*) FROM posts')
            ->fetch();
        return $posts;
    }

    public function UpdatePost(int $id, string $name, string $content, string $slug,bool $active){
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

    public function DeleteById($id)
    {
        $request = $this->PDO
            ->prepare('DELETE FROM posts WHERE posts.id = :id ');
        $request->bindValue(':id', $id, PDO::PARAM_STR);
        $request->execute();
    }
}