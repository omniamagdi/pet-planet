<?php

namespace App\Database\Connection;


class Connection
{
    private string $dbName = 'pet_planet';
    private string $hostName = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private int $DBport = 3307;
    protected \mysqli $connect;

    public function __construct()
    {
        $this->connect = new \mysqli(
            $this->hostName,
            $this->username,
            $this->password,
            $this->dbName,
            $this->DBport
        );
    }
}

new Connection;
