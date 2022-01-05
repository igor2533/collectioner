<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;


#[AsCommand(
    name: 'Mercure',
    description: 'Add a short description for your command',
)]
class MercureCommand extends Command
{
    protected static $defaultName = 'mercure';

    public function __construct(HubInterface $hub)
    {
        parent::__construct();
//
//
//        $client = new CurlHttpClient();
//
//        $client->request('POST', 'http://localhost:3001/', [
//            // ...
//            'extra' => [
//                'curl' => [
//                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V6,
//                ],
//            ],
//        ]);


        $update = new Update('https://sheltered-river-18608.herokuapp.com/items/',
        json_encode(['message'=>'pppp']));
        $hub->publish($update);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);



        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
