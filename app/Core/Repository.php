<?php

namespace App\Core;

abstract class Repository
{
    protected \PDO $db;

    public function __construct()
    {
        // For simplicity, we can inject or fetch the PDO connection directly via the Model base 
        // or a DI container. Assuming a static accessor for now:
        $this->db = Model::getDB();
    }
}
