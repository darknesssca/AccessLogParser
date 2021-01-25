<?php


class AccessLogParser
{
    private $fileHandler;
    private $parsedFormat;
    private $isNeedFindCrawler = false;
    private $crawlerPattern;

    public function __construct(string $filePath, string $format)
    {
        if (strpos($format, 'crawler') !== false) {
            $this->isNeedFindCrawler = true;
            $this->crawlerPattern = Pattern::parseFormat('crawler');
            $format = str_replace('crawler', 'useragent', $format);
        }

        $this->parsedFormat = Pattern::parseFormat($format);

        $realPath = realpath($filePath);
        $this->fileHandler = new SplFileObject($realPath, 'r');
    }

    public function parse(callable $callback)
    {
        while (!$this->fileHandler->eof()) {
            $line = $this->fileHandler->fgets();

            if (!preg_match("/{$this->parsedFormat}/", $line, $matches)) {
                $this->fileHandler = null;
                throw new Exception('При разборе строки возникла ошибка');
            }

            if ($this->isNeedFindCrawler) {
                if (!preg_match("/{$this->crawlerPattern}/", $matches['useragent'], $crawler)) {
                    $matches['crawler'] = '';
                }

                if (!empty($crawler['crawler'])) {
                    $matches['crawler'] = Pattern::crawlers()[$crawler['crawler']];
                }
            }

            $callback($matches);
        }

        $this->fileHandler = null;
    }
}