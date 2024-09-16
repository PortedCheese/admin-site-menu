
## Versions

###v2.0.0-2.0.1: base-settings 5.0
- change admin views
- change layouts.link
- change sb-admin.link

    Обновление:

        php artisan vendor:publish --provider="PortedCheese\AdminSiteMenu\AdminSiteMenuServiceProvider" --tag=public --force

###v1.6.2: fix VendorName
###v1.6.1: change link: set active by url
###v1.6.0: base-settings 3.0
###v1.5.0: base-settings 2.0
###v1.4.0:
- Добавлен uuid
- Измененм экспорт
Обновление:


    php artisan migrate
        
###v1.3.8: Добавлены шаблоны меню для SBAdmin2
        
###v1.3.4:  Добавлена обратная совместимость с переопределенными меню.
Обновление:

         php artisan cache:clear
        
###v1.3.3: Изменено кэширование адресов.
Обновление:

    php artisan cache:clear
        
###v1.3.2:
- Добавлены права доступа на администрирование меню
- Новое поле в таблице gate

Обновление:

    php artisan make:menu-settings --policies
    php artisan migrate
    php artisan cache:clear
    
Добавить menu-management в Меню