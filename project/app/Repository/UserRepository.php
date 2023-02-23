<?php

declare(strict_types=1);

namespace App\Repository;

use Nette\Database\Explorer;

//todo: implement all queries by doctrine query builder
class UserRepository
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function get(?int $id): \Nette\Database\Row
    {
        return $this->database->query('
            SELECT 
               user.username, 
               user.email,
               user.age,
               user.gender, 
               user.gender_search, 
               user.profile_text, 
               country.name as country, 
               city.name as city
    
            FROM users as user
            
            LEFT JOIN countries as country on country.id = user.country_id
            LEFT JOIN cities as city on city.id = user.city_id  
    
            WHERE user.id = ?;
        ', $id)->fetch();
    }

    public function getAll(int $limit, int $offset): array
    {
        return $this->database->query('
            SELECT 
               user.username, 
               user.email,
               user.age,
               user.gender, 
               user.gender_search, 
               user.profile_text, 
               country.name as country, 
               city.name as city
    
            FROM users as user
            
            LEFT JOIN countries as country on country.id = user.country_id
            LEFT JOIN cities as city on city.id = user.city_id

            LIMIT ?
			OFFSET ?',
            $limit, $offset,
        )->fetchAll();
    }

    public function getCount(): int
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM users');
    }
}