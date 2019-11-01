<?php

namespace PortedCheese\AdminSiteMenu\Console\Commands;

use App\Menu;
use App\MenuItem;
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
                    {--all : Run full command}
                    {--models : Create models}
                    {--controllers : Create controllers}
                    {--vue : Add vue to file}
                    {--replace-old : Refactor old menu items}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold menu models';

    protected $packageName = "AdminSiteMenu";

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = ['Menu', 'MenuItem',];

    protected $controllers = [
        "Admin" => ["MenuController"]
    ];

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
            'admin-menu-list' => "MenuListComponent",
            'admin-menu-item' => "MenuItemComponent",
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
        $this->makeDefaultMenus();

        if ($this->option('replace-old')) {
            $this->refactorOldMenus();
        }
        else {
            $all = $this->option("all");

            if ($this->option("models") || $all) {
                $this->exportModels();
            }

            if ($this->option("controllers") || $all) {
                $this->exportControllers("Admin");
            }

            if ($this->option("vue") || $all) {
                $this->makeVueIncludes('admin');
            }
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
            Menu::where('key', 'main')->firstOrFail();
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
            Menu::where('key', 'admin')->firstOrFail();
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
     * Пересобрать старые меню.
     */
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
