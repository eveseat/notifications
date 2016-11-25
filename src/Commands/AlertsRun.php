<?php

namespace Seat\Notifications\Commands;

use Illuminate\Console\Command;

class AlertsRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the Alerts for notifications';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach (config('notifications.alerts') as $category => $alerts) {

            $this->line('Processing Category: ' . $category);

            foreach ($alerts as $name => $classes) {

                $this->comment('Handling: ' . $classes['alert']);

                (new $classes['alert'])->handle();
            }

        }
    }
}
