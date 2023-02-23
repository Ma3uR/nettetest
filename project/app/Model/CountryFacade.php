<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

final class CountryFacade
{
    use Nette\SmartObject;

    private const
        TableName = 'countries',
        ColumnId = 'id',
        ColumnName = 'name',
        ColumnStatus = 'status';

    private Nette\Database\Explorer $database;

    public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
    {
        $this->database = $database;
    }

    public function getCounties(): array {
        $items = [];
        foreach ($this->database->table(self::TableName)->fetchAssoc('id') as $id => $item) {
            $items[$id] = $item['name'];
        }

        return $items;
    }
}