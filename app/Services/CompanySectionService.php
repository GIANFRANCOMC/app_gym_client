<?php

namespace App\Services;

use App\Models\System\Company;
use Illuminate\Support\Facades\Cache;

class CompanySectionService {

    public static function getActiveSections($company_id) {

        $time = 30;

        $cacheKey = "active_sections_{$company_id}";

        Cache::has($cacheKey) ? Cache::put("has_active_sections_{$company_id}", now(), now()->addMinutes($time)) :
                                Cache::put("last_active_sections_{$company_id}", now(), now()->addMinutes($time));

        return Cache::remember($cacheKey, now()->addMinutes($time), function() use($company_id) {

            return Company::getActiveSections($company_id);

        });

    }

}
