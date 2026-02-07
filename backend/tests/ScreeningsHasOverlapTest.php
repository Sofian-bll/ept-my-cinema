<?php

require_once __DIR__ . '/../vendor/autoload.php';

//use App\Models\Movies;
//
//public static function hasOverlap(int $roomId, string $startTime, int $movieId): bool
//
//{
//    $movie = Movies::find($movieId);
//    $movieDuration = $movie->getDuration();
//
//    $endTime = $startTime + $movieDuration;
//}

$room_id    = 1;
$movies_id  = 1;

$movieDuration = 142;

$startDateTime = new DateTime('2026-02-10 15:00:00');
$endDateTime = new DateTime('2026-02-10 15:00:00')->modify("+$movieDuration minutes");

dump($startDateTime);
dump($endDateTime);




