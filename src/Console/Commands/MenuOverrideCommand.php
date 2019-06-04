<?php

namespace PortedCheese\AdminSiteMenu\Console\Commands;

use Illuminate\Console\Command;
use PortedCheese\BaseSettings\Console\Commands\BaseOverrideCommand;

class MenuOverrideCommand extends BaseOverrideCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'override:admin-menu
                    {--admin : Scaffold admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change admin menu default logic';

    protected $controllers = [
        "Admin" => ["MenuController"],
    ];

    protected $packageName = "AdminSiteMenu";

    protected $dir = __DIR__;

    /**
     * Create a new command instance.
     *
     * @return void
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
        if ($this->option('admin')) {
            $this->createControllers("Admin");
            $this->expandSiteRoutes('admin');
        }
    }
}
