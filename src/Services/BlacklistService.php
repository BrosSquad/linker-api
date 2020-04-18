<?php

namespace BrosSquad\Linker\Api\Services;

use InvalidArgumentException;

class BlacklistService
{
    protected string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function check(string $url)
    {
        $handler = fopen($this->file, 'r');

        while (false !== ($line = fgets($handler))) {
            if (preg_match('#'.$line.'#', $url)) {
                fclose($handler);

                throw new InvalidArgumentException('Url is not allowed');
            }
        }

        fclose($handler);
    }

    public function add(string $url)
    {
        $handler = fopen($this->file, 'a+');

        // Lock for threading
        fwrite($handler, $url.PHP_EOL);
        fclose($handler);
    }

    public function delete(string $url)
    {
        $randomName = __DIR__.'/../../'.bin2hex(random_bytes(10));

        touch($randomName);

        $tempHandler = fopen($randomName, 'rw+');

        $handler = fopen($this->file, 'r+');

        $i = 0;
        while (!feof($handler)) {
            echo ++$i.PHP_EOL;
            $line = fgets($handler);
            echo $line;
            if (trim($line) !== trim($url)) {
                echo $line;
                fwrite($tempHandler, $line.PHP_EOL);
            }
        }

        fclose($tempHandler);
        fclose($handler);

        echo false === copy($randomName, $this->file);

        unlink($randomName);
    }
}
