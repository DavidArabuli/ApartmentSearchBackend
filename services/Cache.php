<?php

class Cache
{
    private string $cacheDir;
    private int $cacheTime;

    public function __construct(string $cacheDir = '../cache/', int $cacheTime = 600)
    {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        $this->cacheTime = $cacheTime;
    }
    public function get(string $key)
    {
        $path = $this->cacheDir . md5($key) . '.json';

        if (file_exists($path) && (time() - filemtime($path)) < $this->cacheTime) {
            return json_decode(file_get_contents($path), true);
        }
        return null;
    }
    public function put(string $key, array $data)
    {
        $path = $this->cacheDir . md5($key) . '.json';
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
