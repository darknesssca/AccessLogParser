<?php


class FileInfo
{
    const SUCCESS_HTTP_CODE = 200;
    const FORMAT = 'url status traffic crawler';

    private $file_info = [
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

        $this->file_info['views'] += 1;

        if ($status === self::SUCCESS_HTTP_CODE) {
            $this->file_info['traffic'] += (int) $matches['traffic'];
        }

        if (!empty($this->file_info['statusCodes'][$status])) {
            $this->file_info['statusCodes'][$status] += 1;
        } else {
            $this->file_info['statusCodes'][$status] = 1;
        }

        if ($this->checkUrl($matches['url'])) {
            $this->file_info['urls'] += 1;
        }

        if (!empty($crawler)) {
            if (!empty($this->file_info['crawlers'][$crawler])) {
                $this->file_info['crawlers'][$crawler] += 1;
            } else {
                $this->file_info['crawlers'][$crawler] = 1;
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
        return json_encode($this->file_info);
    }
}