<?php

namespace BrosSquad\Linker\Api\Console;

use BrosSquad\DotEnv\EnvParser;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenAppKeyCommand extends Command
{
    protected static $defaultName = 'app:key';

    protected function configure()
    {
        $this->addOption('show', 's', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = sodium_bin2base64(random_bytes(SODIUM_CRYPTO_AUTH_KEYBYTES), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);

        $path = __DIR__.'/../../.env';

        $envParser = new EnvParser($path);

        try {
            $envParser->parse();
            $envs = $envParser->getEnvs();
            $envs['APP_KEY'] = $key;
            $data = '';
            foreach ($envs as $key => $value) {
                $data .= "{$key}={$value}".PHP_EOL;
            }
            file_put_contents($path, $data);
        } catch (Exception $e) {
            $output->writeln($e->getMessage());

            return 1;
        }

        if ($input->getOption('show')) {
            $output->writeln('Key: '.$key);
        }

        $output->writeln('Key has been written');

        return 0;
    }
}
