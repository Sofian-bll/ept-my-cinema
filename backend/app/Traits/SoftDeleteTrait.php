<?php

namespace App\Traits;

trait SoftDeleteTrait
{
    public function softDelete(): bool
    {
        if ($this->id === null) {
            throw new \RuntimeException('Cannot delete: no ID set');
        }

        $database = self::getConnection();
        $table    = static::$table;


        $sql  = 'UPDATE ' . $table . ' SET active = 0 WHERE id = :id';
        $stmt = $database->prepare($sql);
        $stmt->execute([ 'id' => $this->id ]);

        return $stmt->rowCount() > 0;
    }

    public static function allActive(): array
    {
        $database = self::getConnection();
        $table    = static::$table;

        $sql  = 'SELECT * FROM ' . $table . ' WHERE active = 1';
        $stmt = $database->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

}