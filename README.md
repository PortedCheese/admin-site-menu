# Admin site menu

Есть роут `admin.menus.index`, на нем создается и редактируется меню.
Можно выгрузить структуру меню в yaml формате и загрузить.

Если расширить класс MenuItem, можно в него дописать методы для вывода подменю которое будет динамичным. Единственный момент, поскольку получение меню пишется в кэш, что бы сократить кол-во запросов, то и динамичное меню будет в кэше. Нужно при обновлении динамики сбрасывать кэш у меню.

## Установка
`composer require portedcheese/admin-site-menu`

`php artisan migrate`

Выгружается компонент для изменения веса меню

    `php artisan vendor:publish --provider="PortedCheese\AdminSiteMenu\AdminSiteMenuServiceProvider" --tag=public --force`

Создать шаблоны в layouts и модели.

    `php artisan make:menu-settings
        {--all : Run full command}
        {--models : Create models}
        {--controllers : Create controllers}
        {--vue : Add vue to file} 
        {--replace-old : Пересобрать старое меню}`
