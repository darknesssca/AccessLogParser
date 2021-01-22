<?php
spl_autoload_register(function ($class_name) {
    include 'src/' . $class_name . '.php';
});

$file_info = new FileInfo();

$access_log_parser = new AccessLogParser($argv[1], FileInfo::FORMAT);
$access_log_parser->parse([&$file_info, 'addLineInfo']);
echo $file_info->toJson();
