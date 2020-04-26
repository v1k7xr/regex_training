<?php

function changeLink($text) {
    $pattern = '/"http:\/\/asozd\.duma\.gov\.ru\/main\.nsf\/\(Spravka\)\?OpenAgent&RN=([0-9]+-[0-9]&[0-9]+)"/m';
    $changeText = preg_replace_callback(
        $pattern,
        function($matches) {
            $newLinkPattern = "\"http://sozd.parlament.gov.ru/bill/";
            return $newLinkPattern . explode("&", $matches[1])[0] . '"';
        },
        $text
    );

    return $changeText;
}


$pdo = new PDO("pgsql:dbname=linksdb;host=localhost", "newsman", "qwerty"); 

$stmt = $pdo->prepare("SELECT * FROM news");
$stmt->execute();

$arrayToSave = [];

while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
    $newsText = $row['news_text'];
    $changedText = changeLink($newsText);
    $arrayToSave[] = [
        'news_id' => $row['news_id'],
        'news_text' => $changedText,
    ];
}



$stmt = $pdo->prepare('UPDATE news SET news_text = ? WHERE news_id = ?');

foreach ($arrayToSave as $item) {
    $stmt->execute([$item['news_text'], $item['news_id']]);
}

