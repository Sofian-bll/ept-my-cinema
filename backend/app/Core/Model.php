<?php

namespace App\Core;


abstract class Model implements \JsonSerializable
{

    protected static string $table;
    protected ?int $id = null;
    protected static array $ignored = [];

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

        $objProps = get_object_vars($this);
        unset($objProps['id'], $objProps['table'], $objProps['created_at'], $objProps['updated_at']);

        foreach (static::$ignored as $prop) {
            unset($objProps[$prop]);
        }

        if ($this->id === null) {
            // Insert

            $columns  = array_keys($objProps);
            $tableCol = implode(',', $columns);
            $tableVal = ':' . implode(', :', $columns);

            $sql = "INSERT INTO {$table} ({$tableCol}) VALUES ({$tableVal})";
        } else {
            // Update

            $kv = [];
            foreach ($objProps as $key => $prop) {
                $kv[] = $key . ' = :' . $key;
            }

            $sql            = "UPDATE {$table} SET " . implode(', ', $kv) . ' WHERE id = :id';
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
        $stmt->execute([ 'id' => $this->id ]);

        return $stmt->rowCount() > 0;
    }

    public function jsonSerialize(): array
    {
        $data = get_object_vars($this);
        unset($data['table'], $data['ignored']);

        foreach (static::$ignored as $prop) {
            unset($data[$prop]);
        }

        return $data;
    }

}