<?php

namespace Mindwingx\ServiceCallAdapter\Commands;

use Illuminate\Console\Command;
use Mindwingx\ServiceCallAdapter\handlers\ServiceGenerator;

class ServiceGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:new {name : service caller name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Service Call Adapter Generator Command';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $serviceName = $this->argument('name');
        $generator = new ServiceGenerator();
        $generator->generate($serviceName);

        is_null($generator->result)
            ? $this->info("$generator->serviceName Service Caller created!")
            : $this->error($generator->result);
    }
}
