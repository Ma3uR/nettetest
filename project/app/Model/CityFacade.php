<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;

final class CityFacade
{
    use Nette\SmartObject;

    private const
        TableName = 'cities',
        ColumnId = 'id',
        ColumnCountryId = 'country_id',
        ColumnName = 'name',
        ColumnStatus = 'status';

    private Nette\Database\Explorer $database;


    public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
    {
        $this->database = $database;
    }

    public function getCities(int $countryId): array {
        $cities = $this->database->table(self::TableName)
            ->where(self::ColumnCountryId, $countryId)
            ->fetchAssoc('id');

        $items = [];
        foreach ($cities as $id => $item) {
            $items[$id] = $item['name'];
        }

        return $items;
    }
}