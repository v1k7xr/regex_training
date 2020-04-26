<?php

$textdata = '<div class="content-text">
<p>
</p><p>16 мая 2012 года в первом чтении принят <a href="http://ria.ru/defense_safety/20120306/585890377.html" target="_blank">правительственный законопроект "</a><a name="news_linker" href="http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=31990-6&2" class="external">О государственном оборонном заказе</a>"<b>. </b></p>

<p>В связи с этим председатель Комитета по обороне <b><a name="news_linker" href="/structure/deputies/131370/" id="deputy_131370" class="deputy-popup">Владимир Комоедов</a></b> отметил: </p>

<p>«Этот законопроект архиважный для обороноспособности нашего государства, его концепция заключается в создании правовых основ решения проблем в сфере формирования, размещения и исполнения гособоронзаказа. Законопроектом структурированы и детализированы правовые нормы, регламентирующие процесс формирования, утверждения и исполнения государственного оборонного заказа». </p>

<p>Кроме того, он подчеркнул, что в законопроекте определено понятие «основные показатели государственного оборонного заказа». </p>

<p>«Федеральный закон от 1995 года №213-ФЗ о гособоронзаказе, уже давно устарел и требует модернизации. Несмотря на положительные стороны, в законопроекте есть над чем работать. С момента внесения законопроекта в Комитет по обороне поступило большое количество замечаний и предложений, которые будут учтены при подготовке законопроекта ко второму чтению. Впереди у нас сложная и ответственная работа», - отметил В. Комоедов. </p>
<div class="signature-news">
<div class="signature-news-date">16 мая 2012 года</div>
<div class="signature-news-text">   </div>
</div>
</div>';


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

