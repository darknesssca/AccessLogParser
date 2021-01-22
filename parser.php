<?php
spl_autoload_register(function ($className) {
    include 'src/' . $className . '.php';
});

$fileInfo = new FileInfo();

$accessLogParser = new AccessLogParser($argv[1], FileInfo::FORMAT);
$accessLogParser->parse([&$fileInfo, 'addLineInfo']);
echo $fileInfo->toJson();
