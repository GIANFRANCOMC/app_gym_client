<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\System\Organizations\{Company};

class CompanySectionService {

    public static function getSections($company_id, $forceRefresh = false) {

        $time = 30;

        $cacheKey = "active_sections_{$company_id}";

        if($forceRefresh || !Cache::has($cacheKey)) {

            Cache::put("last_$cacheKey", now(), now()->addMinutes($time));

            $sections = Company::getActiveSections($company_id);

            Cache::put($cacheKey, $sections, now()->addMinutes($time));

        }else{

            Cache::put("has_$cacheKey", now(), now()->addMinutes($time));

        }

        return Cache::get($cacheKey);

    }

}
