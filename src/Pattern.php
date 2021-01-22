<?php


class Pattern
{
    private static $patterns = [
        'url' => '".+ (?P<url>.+) .+"',
        'status' => '(?P<status>\d{3}|-)',
        'traffic' => '(?P<traffic>\d+|-)',
        'crawler' => '',
        'useragent' => '.+ "(?P<useragent>[^"]+)"$'
    ];

    private static $crawlers = [
        'Googlebot' => 'Google',
        'msnbot' => 'Bing',
        'Baiduspider' => 'Baidu',
        'YandexBot' => 'Yandex',
    ];

    public static function parseFormat(string $format): string
    {
        foreach (self::$patterns as $pattern => $replace) {
            if ($pattern == 'crawler') {
                $replace = self::getCrawlerPattern();
            }
            $format = preg_replace("/{$pattern}/", $replace, $format);
        }

        return $format;
    }

    public static function getCrawlers(): array
    {
        return self::$crawlers;
    }

    private static function getCrawlerPattern(): string
    {
        $crawlersStr = implode('|', array_keys(self::$crawlers));
        return '(?P<crawler>' . $crawlersStr . ')';
    }
}