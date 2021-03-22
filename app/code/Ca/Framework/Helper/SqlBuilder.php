<?php

namespace Ca\Framework\Helper;

class SqlBuilder
{
    private $host;
    private $databaseName;
    private $databaseUser;
    private $pass;
    private $charset = 'utf8mb4';
    private $pdo;
    private $table;
    private $sql = '';

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->databaseName = DB_NAME;
        $this->databaseUser = DB_USER;
        $this->pass = DB_PASS;

        $dsn = "mysql:host=$this->host;dbname=$this->databaseName;charset=$this->charset";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new \PDO($dsn, $this->databaseUser, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function select($fields = '*')
    {
        $this->sql .= 'SELECT ' . $fields . ' ';
        return $this;
    }

    public function from($table)
    {
        $this->sql .= 'FROM ' . $table . ' ';
        return $this;
    }

    public function where($field, $value)
    {
        $this->sql .= 'WHERE ' . $field . ' = "' . $value . '"';
        return $this;
    }

    public function update($table)
    {
        $this->sql .= 'UPDATE ' . $table . ' ';
        return $this;
    }

    public function set($array)
    {
        $this->sql .= 'SET ';
        foreach ($array as $field => $value) {
            $this->sql .= $field . ' = "' . ($value) . '", ';
        }
        $this->sql = rtrim($this->sql, ', ');

        return $this;
    }

    public function insert($table)
    {
        $this->sql .= 'INSERT INTO ' . $table . ' ';
        return $this;
    }

    public function values($array)
    {
        $valueLine = '';
        $this->sql .= '(';
        foreach ($array as $field => $value) {
            $this->sql .= $field . ', ';
            $valueLine .= '"' . strip_tags($value) . '", ';
        }
        $this->sql = rtrim($this->sql, ', ');
        $this->sql .= ') ';
        $this->sql .= 'VALUES (' . rtrim($valueLine, ', ') . ')';
        return $this;
    }

    public function getOne()
    {
        $stmt = $this->pdo->query($this->sql);
        while ($row = $stmt->fetch()) {
            return $row;
        }
        return false;
    }

    public function get()
    {
        $stmt = $this->pdo->query($this->sql);
        $rez = [];
        while ($row = $stmt->fetch()) {
            $rez[] = $row;
        }
        return $rez;
    }

    public function exec()
    {
        echo $this->sql;
        $stmt = $this->pdo->query($this->sql);
    }

    public function delete()
    {
        $this->sql .= 'DELETE ';
        return $this;
    }
}

