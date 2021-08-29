<?php

declare(strict_types=1);

namespace Saltibarsciai\CommissionTask\Services;

use Exception;

class InputManager
{
    private string $filename;
    private $file;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @throws Exception
     */
    public function openFile()
    {
        $params = getopt('', [$this->filename . ':']);

        if (empty($params[$this->filename])) {
            throw new Exception('Parameter ' . $this->filename . ' is required');
        }

        $filePath = $params[$this->filename];

        if (!file_exists($filePath)) {
            throw new Exception("File doesn't exist");
        }

        $file = fopen($filePath, 'r');
        if ($file === false) {
            throw new Exception('Could not open the file');
        }
        $this->file = $file;
        return $this->file;
    }

    public function closeFile()
    {
        fclose($this->file);
    }
}