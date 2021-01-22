<?php


class FileInfo
{
    const SUCCESS_HTTP_CODE = 200;
    const FORMAT = 'url status traffic crawler';

    private $fileInfo = [
        'views' => 0,
        'urls' => 0,
        'traffic' => 0,
        'crawlers' => [],
        'statusCodes' => [],
    ];

    private $urls = [];

    public function addLineInfo(array $matches)
    {
        $status = (int)$matches['status'];
        $crawler = $matches['crawler'];

        $this->fileInfo['views'] += 1;

        if ($status === self::SUCCESS_HTTP_CODE) {
            $this->fileInfo['traffic'] += (int) $matches['traffic'];
        }

        if (!empty($this->fileInfo['statusCodes'][$status])) {
            $this->fileInfo['statusCodes'][$status] += 1;
        } else {
            $this->fileInfo['statusCodes'][$status] = 1;
        }

        if ($this->checkUrl($matches['url'])) {
            $this->fileInfo['urls'] += 1;
        }

        if (!empty($crawler)) {
            if (!empty($this->fileInfo['crawlers'][$crawler])) {
                $this->fileInfo['crawlers'][$crawler] += 1;
            } else {
                $this->fileInfo['crawlers'][$crawler] = 1;
            }
        }
    }

    private function checkUrl(string $url): bool
    {
        $isUnique = !in_array($url, $this->urls);

        if ($isUnique) {
            $this->urls[] = $url;
        }

        return $isUnique;
    }

    public function toJson(): string
    {
        return json_encode($this->fileInfo);
    }
}