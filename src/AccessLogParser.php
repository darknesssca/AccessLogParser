<?php


class AccessLogParser
{
    private $file_handler;
    private $parsed_format;
    private $need_find_crawler = false;
    private $crawler_pattern = '';

    public function __construct(string $file_path, string $format)
    {
        if (strpos($format, 'crawler') !== false) {
            $this->need_find_crawler = true;
            $this->crawler_pattern = Pattern::parseFormat('crawler');
            $format = str_replace('crawler', 'useragent', $format);
        }

        $this->parsed_format = Pattern::parseFormat($format);

        $real_path = realpath($file_path);
        $this->file_handler = new SplFileObject($real_path, 'r');
    }

    public function parse(callable $callback)
    {
        while (!$this->file_handler->eof()) {
            $line = $this->file_handler->fgets();

            if (!preg_match("/{$this->parsed_format}/", $line, $matches)) {
                $this->file_handler = null;
                throw new Exception('При разборе строки возникла ошибка');
            }

            if ($this->need_find_crawler) {
                if (!preg_match("/{$this->crawler_pattern}/", $matches['useragent'], $crawler)) {
                    $matches['crawler'] = '';
                }

                if (!empty($crawler['crawler'])) {
                    $matches['crawler'] = Pattern::getCrawlers()[$crawler['crawler']];
                }
            }

            $callback($matches);
        }

        $this->file_handler = null;
    }
}