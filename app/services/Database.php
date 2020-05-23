<?php

namespace app\services;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Database
{
    private $pdo;
    private $queryFactory;

    public function __construct(QueryFactory $queryFactory, PDO $pdo)
    {
        $this->queryFactory = $queryFactory;
        $this->pdo = $pdo;
    }

    public function all($table, $limit = null)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->limit($limit);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue(':id', $id);
        
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function whereAll($table, $row, $id, $limit = 4)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where("$row = :id")
            ->limit($limit)
            ->bindValue(':id', $id);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedFrom($table, $row, $id, $page = 1, $rows = 1)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where("$row = :id")
            ->bindValue(":id", $id)
            ->page($page)
            ->setPaging($rows);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCount($table, $row, $value)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where("$row = :$row")
            ->bindValue($row, $value);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        return count($sth->fetchAll(PDO::FETCH_ASSOC));
    }

    public function update($table, $id, $data)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue(':id', $id);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function create($table, $data)
    {
        $insert = $this->queryFactory->newInsert();
        $insert
            ->into($table)
            ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
        
        $name = $insert->getLastInsertIdName('id');
        $id = $this->pdo->lastInsertId($name);

        return $id;
    }

    public function delete($table, $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue(':id', $id);

        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());
    }
}