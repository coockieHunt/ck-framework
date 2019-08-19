<?php


namespace ck_framework\Database;

use app\Modules\Blog\Entity\PostsEntity;
use ck_framework\Utils\SnippetUtils;
use PDO;

class Table
{
    /**
     * @var PDO
     */
    protected $PDO;

    /**
     * name table name
     * @var string
     */
    protected $tableName;

    /**
     * entity in table
     * @var string null
     */
    protected $entity;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    /**
     * find all element
     * @return array
     */
    public function FindAll() :array{
        $request = $this->PDO
            ->prepare('SELECT * FROM ' . $this->tableName);
        if ($this->entity){$request->setFetchMode(PDO::FETCH_CLASS, $this->entity);}
        $request->execute();
        $response = $request->fetchAll();

        return $response;
    }

    /**
     * count all element table
     * @return mixed
     */
    public function CountAll()
    {
        $response = $this->PDO
            ->query('SELECT COUNT(*) FROM ' . $this->tableName)
            ->fetch();
        return $response[0];
    }

    /**
     * Deleter entry by id
     * @param $id
     */
    public function DeleteById(int $id):void
    {
        $request = $this->PDO
            ->prepare('DELETE FROM '.$this->tableName.' WHERE '.$this->tableName.'.id = :id ');
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        $request->execute();
    }

    /**
     * Find entry by value
     * @param string $where
     * @param $value
     * @param $data_type
     * @return mixed
     */
    public function FindByValue(string $where, $value, $data_type){
        $BuildWERE = $this->tableName . '.'. $where . ' = :' . $where;
        $request = $this->PDO
            ->prepare('SELECT * FROM '.$this->tableName.' WHERE ' . $BuildWERE);
        $request->bindValue(':' . $where, $value, $data_type);
        $request->execute();
        if ($this->entity){$request->setFetchMode(PDO::FETCH_CLASS, $this->entity);}
        $response = $request->fetch();

        return $response;
    }

    /**
     * find entry between two values
     * @param int $limit
     * @param int $offset
     * @param string $order
     * @return array
     */
    public function FindByLimit(int $limit, int $offset, string $order) :array{
        $BuildOrder = ' ORDER BY '. $this->tableName . '.' . $order;
        $request = $this->PDO
            ->prepare('SELECT * FROM '. $this->tableName . $BuildOrder .' LIMIT :limit, :offset');
        $request->bindValue(':limit', $limit, PDO::PARAM_INT);
        $request->bindValue(':offset', $offset, PDO::PARAM_INT);

        $request->execute();
        if ($this->entity){$request->setFetchMode(PDO::FETCH_CLASS, $this->entity);}
        $response = $request->fetchAll();

        return $response;
    }

    /**
     * get Entity for child class
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}