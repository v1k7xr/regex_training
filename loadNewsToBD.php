<?php

$fileNames = [
    0 => 'example1.txt',
    1 => 'example2.txt',
];

$pdo = new PDO("pgsql:dbname=linksdb;host=localhost", "newsman", "qwerty"); 

$stmt = $pdo->prepare('INSERT INTO news (news_text) VALUES (?)');

foreach ($fileNames as $fileName) {
    $text = file_get_contents($fileName);
    $stmt->execute([$text]);
}