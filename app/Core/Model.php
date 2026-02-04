<?php

namespace App\Core;

abstract class Model
{
    protected static string $table;
    protected ?int $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    protected static function getConnection(): \PDO
    {
        return new Database()->connect();
    }

    public static function all(): array
    {
        $database = self::getConnection();
        $table    = static::$table;


        $sql  = 'SELECT * FROM ' . $table;
        $stmt = $database->query($sql);

        $data = $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);

        return $data;
    }

    public static function find(int $id): ?static
    {
        $database = self::getConnection();
        $table    = static::$table;


        $sql  = 'SELECT * FROM ' . $table . ' WHERE id = :id';
        $stmt = $database->prepare($sql);
        $stmt->execute([ ':id' => $id ]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function save(): void
    {
        $database = self::getConnection();
        $table    = static::$table;

        if ($this->id === null) {
            // Insert
            $objProps = get_object_vars($this);
            unset($objProps['id'], $objProps['table']);

            $columns  = array_keys($objProps);
            $tableCol = implode(',', $columns);
            $tableVal = ':' . implode(', :', $columns);

            $sql = "INSERT INTO {$table} ({$tableCol}) VALUES ({$tableVal})";
        } else {
            // Update
            $objProps = get_object_vars($this);
            unset($objProps['id'], $objProps['table']);

            $lastProps = end($objProps);
            $kv        = '';

            foreach ($objProps as $key => $prop) {
                if ($prop === $lastProps) {
                    $kv .= $key . ' = :' . $key;
                } else {
                    $kv .= $key . ' = :' . $key . ', ';
                }
            }

            $sql            = "UPDATE {$table} SET {$kv} WHERE id = :id";
            $objProps['id'] = $this->id;
        }
        $stmt = $database->prepare($sql);
        $stmt->execute($objProps);
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            throw new \RuntimeException('Cannot delete: no ID set');
        }

        $database = self::getConnection();
        $table    = static::$table;


        $sql  = 'DELETE FROM ' . $table . ' WHERE id = :id';
        $stmt = $database->prepare($sql);
        try {
            $stmt->execute([ 'id' => $this->id ]);
        } catch (\Exception | \PDOException $e) {
            echo dump($e->getMessage());
        }

        return $stmt->rowCount() > 0;
    }
}