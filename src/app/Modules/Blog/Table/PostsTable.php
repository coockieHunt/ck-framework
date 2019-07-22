<?php


namespace app\Modules\Blog\Table;


use app\Modules\Blog\Entity\PostsEntity;
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

    public function FindBySlug(string $slug){
        $request = $this->PDO
            ->prepare('SELECT * FROM posts WHERE posts.slug = :slug');
        $request->bindValue(':slug', $slug, PDO::PARAM_STR);
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
}