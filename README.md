# Admin site menu

Есть роут `admin.menus.index`, на нем создается и редактируется меню.
Можно выгрузить структуру меню в yaml формате и загрузить.

Если расширить класс MenuItem, можно в него дописать методы для вывода подменю которое будет динамичным. Единственный момент, поскольку получение меню пишется в кэш, что бы сократить кол-во запросов, то и динамичное меню будет в кэше. Нужно при обновлении динамики сбрасывать кэш у меню.

## Установка
`composer require portedcheese/admin-site-menu`

`php artisan migrate`

Выгружается компонент для изменения веса меню

    php artisan vendor:publish --provider="PortedCheese\AdminSiteMenu\AdminSiteMenuServiceProvider" --tag=public --force

Создать шаблоны в layouts и модели.

    php artisan make:menu-settings
                            {--all : Run full command}
                            {--models : Create models}
                            {--controllers : Create controllers}
                            {--policies : Export and create rules}
                            {--only-default : Create default rules}
                            {--vue : Add vue to file}
                            {--replace-old : Refactor old menu items}

### Versions

    v1.3.8:
        - Добавлены шаблоны меню для SBAdmin2
        
    v1.3.4:
        - Добавлена обратная совместимость с переопределенными меню.
    Обновление:
        - php artisan cache:clear
        
    v1.3.3:
        - Изменено кэширование адресов.
    Обновление:
        - php artisan cache:clear
        
    v1.3.2:
        - Добавлены права доступа на администрирование меню
        - Новое поле в таблице gate
    Обновление:
        - php artisan make:menu-settings --policies
        - php artisan migrate
        - php artisan cache:clear
        - Добавить menu-management в Меню