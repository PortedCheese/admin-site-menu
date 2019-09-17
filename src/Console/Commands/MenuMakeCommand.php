<?php

namespace PortedCheese\AdminSiteMenu\Console\Commands;

use App\Menu;
use App\MenuItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class MenuMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:menu-settings
                    {--replace-old : Refactor old menu items}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold menu models';

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = [
        'Menu.stub' => 'Menu.php',
        'MenuItem.stub' => 'MenuItem.php',
    ];

    protected $configName = "admin-site-menu";

    protected $configValues = [
        'useOwnAdminRoutes' => false,
        'useOwnSiteRoutes' => false,
    ];

    protected $dir = __DIR__;

    /**
     * The views that need to be exported.
     * @var array
     */
    protected $views = [
        'layouts/index.stub' => 'layouts/menu/index.blade.php',
        'layouts/link.stub' => 'layouts/menu/link.blade.php',
    ];

    protected $vueIncludes = [
        'admin' => [
            'change-menu-weight' => "MenuWeightComponent",
        ]
    ];

    protected $vueFolder = "admin-site-menu";

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
        if ($this->option('replace-old')) {
            $this->refactorOldMenus();
        }
        else {
            $this->createDirectories();

            $this->makeConfig();

            $this->exportModels();
            $this->makeDefaultMenus();
            $this->makeVueIncludes('admin');
        }
    }

    /**
     * Создать стандартные меню.
     */
    protected function makeDefaultMenus()
    {
        if (!Schema::hasTable('menus')) {
            $this->info("Table [menus] not found. Please run migrate command");
            return;
        }

        try {
            $menu = Menu::where('key', 'main')->firstOrFail();
        }
        catch (\Exception $e) {
            $menu = Menu::create([
                'title' => 'Основная навигация',
                'key' => 'main',
            ]);
            MenuItem::create([
                'title' => 'Главная',
                'menu_id' => $menu->id,
                'route' => 'home',
            ]);
        }

        try {
            $menu = Menu::where('key', 'admin')->firstOrFail();
        }
        catch (\Exception $e) {
            $menu = Menu::create([
                'title' => 'Навигация администратора',
                'key' => 'admin',
            ]);
            MenuItem::create([
                'title' => 'Меню сайта',
                'menu_id' => $menu->id,
                'route' => 'admin.menus.index',
            ]);
        }
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir($directory = resource_path('views/layouts/menu'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = resource_path('views/admin/menu'))) {
            mkdir($directory, 0755, true);
        }
    }

    protected function refactorOldMenus()
    {
        $items = MenuItem::query()
            ->get();

        foreach ($items as $item) {
            $needSave = false;
            if (strripos($item->class, '@') === 0) {
                $item->ico = str_replace("@", "", $item->class);
                $item->class = "";
                $needSave = true;
            }
            if (strripos($item->route, "@") === 0) {
                $text = str_replace("@", "", $item->route);
                $item->active = explode("|", $text);
                $item->route = "";
                $needSave = true;
            }
            if ($needSave) {
                $item->save();
            }
        }
    }
}
