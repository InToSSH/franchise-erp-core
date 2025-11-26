<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $abilities = [
            ['name' => 'documents.categories.view', 'title' => 'Zobrazit kategorie dokumentů'],
            ['name' => 'documents.categories.edit', 'title' => 'Upravit kategorie dokumentů'],
            ['name' => 'documents.documents.view', 'title' => 'Zobrazit dokumenty'],
            ['name' => 'documents.documents.edit', 'title' => 'Upravit dokumenty'],
        ];

        foreach ($abilities as $ability) {
            Bouncer::ability()->create($ability);
        }

        Bouncer::allow('admin')->to(array_column($abilities, 'name'));
        Bouncer::allow('manager')->to([
            'documents.categories.view',
            'documents.documents.view',
        ]);
        Bouncer::allow('staff')->to([
            'documents.documents.view',
            'documents.categories.view'
        ]);
    }

    public function down(): void
    {
        $abilityNames = [
            'documents.categories.view',
            'documents.categories.edit',
            'documents.documents.view',
            'documents.documents.edit',
        ];

        Bouncer::disallow('admin')->to($abilityNames);
        Bouncer::disallow('manager')->to($abilityNames);
        Bouncer::disallow('staff')->to($abilityNames);

        foreach ($abilityNames as $abilityName) {
            Bouncer::ability()->where('name', $abilityName)->delete();
        }
    }
};
