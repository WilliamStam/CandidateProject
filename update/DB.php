<?php

namespace update;

class DB {
    protected $pdo;
    protected $config;

    function __construct($config) {
        $this->config = $config;
    }

    function connect() {
        $host = $this->config['host'];
        $db = $this->config['database'];
        $user = $this->config['username'];
        $pass = $this->config['password'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => true,
        ];
        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function close() {
        $this->pdo = null;
    }

    function select(string $sql, array $params = array()): array {
        $this->connect();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->close();
        return $rows;
    }

    function exec(string $sql, array $params = array()) {
        $this->connect();

        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $return = $stmt->execute($params);
            $result = false;
            do {
                // $data = $stmt->fetchAll();
                // var_dump($stmt);
            } while ($stmt->nextRowset());

            if ($this->pdo->inTransaction()) {
                $this->pdo->commit();
            }


        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        $this->close();
        return $return;
    }


}