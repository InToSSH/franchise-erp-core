<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        $abilities = [
            ['name' => 'admin.users.edit', 'title' => 'Upravit uživatele'],
            ['name' => 'admin.users.view', 'title' => 'Zobrazit uživatele'],
            ['name' => 'admin.branches.edit', 'title' => 'Upravit pobočky'],
            ['name' => 'admin.branches.view', 'title' => 'Zobrazit pobočky'],
            ['name' => 'catalog.products.edit', 'title' => 'Upravit produkty'],
            ['name' => 'catalog.products.view', 'title' => 'Zobrazit produkty'],
            ['name' => 'catalog.categories.edit', 'title' => 'Upravit kategorie'],
            ['name' => 'catalog.categories.view', 'title' => 'Zobrazit kategorie'],
            ['name' => 'catalog.suppliers.edit', 'title' => 'Upravit dodavatele'],
            ['name' => 'catalog.suppliers.view', 'title' => 'Zobrazit dodavatele'],
            ['name' => 'sales.orders.edit', 'title' => 'Upravit objednávky'],
            ['name' => 'sales.orders.approve', 'title' => 'Schválit objednávky'],
            ['name' => 'sales.orders.view', 'title' => 'Zobrazit objednávky'],
            ['name' => 'sales.orders.view-all', 'title' => 'Zobrazit objednávky'],
        ];

        $roles = [
            [
                'name' => 'admin',
                'title' => 'Administrátor',
                'abilities' => array_column($abilities, 'name')
            ],
            [
                'name' => 'manager',
                'title' => 'Manažer',
                'abilities' => [
                    'admin.users.view',
                    'admin.branches.view',
                    'admin.branches.edit',
                    'catalog.products.view',
                    'catalog.products.edit',
                    'catalog.categories.view',
                    'catalog.categories.edit',
                    'catalog.suppliers.view',
                    'sales.orders.view',
                    'sales.orders.edit',
                    'sales.orders.approve',
                ]
            ],
            [
                'name' => 'staff',
                'title' => 'Zaměstnanec',
                'abilities' => [
                    'catalog.products.view',
                    'catalog.categories.view',
                    'catalog.suppliers.view',
                    'sales.orders.view',
                    'sales.orders.edit',
                ]
            ],
        ];

        foreach ($abilities as $ability) {
            Bouncer::ability()->create($ability);
        }

        foreach ($roles as $roleData) {
            $role = Bouncer::role()->create([
                'name' => $roleData['name'],
                'title' => $roleData['title'],
            ]);
            Bouncer::allow($role)->to($roleData['abilities']);
        }
    }
};
