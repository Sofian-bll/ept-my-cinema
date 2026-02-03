<?php

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/routes.php';

use Symfony\Component\VarDumper\VarDumper;

$objProps = [ 'table' => 'books', 'id' => null, 'title' => 'Dune', 'author' => 'Herbert' ];
unset($objProps['id'], $objProps['table']);
//
//$table = 'movies';
//
//$sql = 'INSERT INTO ' . $table . ' (:tableCol) VALUES (:tableVal)';
//// (col1, col2) (val1 ,val2)
//
//$tableCol = '';
//$tableVal  = '';
//
//$columns  = array_keys((array)$objProps);
//$tableCol .= implode(',', $columns);
//$tableVal .= implode(",", $objProps);
//
//dump($sql);
//dump($tableCol);
//dump($tableVal);
/**
 * Concat Value & Title
 */
$table     = 'movies';
$id        = '32';
$columns   = array_keys((array)$objProps);
$tableCol  = implode(',', $columns);
$tableVal  = ':' . implode(', :', $columns);
$lastProps = end($objProps);
$kv        = "";

foreach ($objProps as $key => $prop) {
    if ($prop === $lastProps) {
        $kv .= $key . ' = :' . $key;
    } else {
        $kv .= $key . ' = :' . $key . ', ';
    }
}
$sql = "UPDATE {$table} SET ({$tableCol}) WHERE id = {$id}";

dump($sql);
dump($kv);
dump($objProps);
//$sql = 'UPDATE books SET title = :title, author = :author WHERE id = :id';