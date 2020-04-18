<?php


namespace BrosSquad\Linker\Api\Console;


use BrosSquad\Linker\Api\Models\Key;
use BrosSquad\Linker\Api\Services\KeyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenKeyCommand extends Command
{
    /**
     * @var \BrosSquad\Linker\Api\Services\KeyService
     */
    protected KeyService $keyService;

    protected static $defaultName = 'key:generate';

    public function __construct(KeyService $keyService)
    {
        parent::__construct();
        $this->keyService = $keyService;
    }


    protected function configure()
    {
        $this->setDescription('Generate new key for authentication')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if(Key::query()->where('name', '=', $name)->exists()) {
            $output->writeln("Key with name {$name} already exists!");
            return 1;
        }

        ['apiKey' => $apiKey]  = $this->keyService->create($name);

        $output->writeln($apiKey);

        return 0;
    }
}
