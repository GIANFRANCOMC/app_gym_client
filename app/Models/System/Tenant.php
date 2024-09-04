<?php

namespace App\Models\System;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\{HasDatabase, HasDomains};

class Tenant extends BaseTenant implements TenantWithDatabase {

    use HasDatabase, HasDomains;

    protected $hidden = [
        "tenancy_db_name"
    ];

    // Puede definir las columnas personalizadas (que no se almacenarán en la datacolumna JSON)
    // anulando el getCustomColumns()método en su Tenantmodelo
    public static function getCustomColumns(): array {

        return [
            "id",
            "name"
        ];

    }

    public static function getDataColumn(): string {

        return "data";

    }

}
