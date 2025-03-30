<?php

namespace App\Console\Commands;

use App\Models\System\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredSubscriptions extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "subscriptions:cancel-expired";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cancela suscripciones que han expirado";

    /**
     * Execute the console command.
     */
    public function handle() {

        $now = Carbon::now();
        $date = $now->format("Y-m-d");
        $year = $now->format("Y");

        $logPath = storage_path("logs/subscriptions/{$year}/{$date}.log");

        if(!file_exists(dirname($logPath))) {

            mkdir(dirname($logPath), 0777, true);

        }

        $logPath = storage_path("logs/subscriptions/{$year}/{$date}.log");

        config(["logging.channels.subscriptions" => [
            "driver" => "single",
            "path" => $logPath,
            "level" => "info",
        ]]);

        Log::channel("subscriptions")->info("** Inicio del proceso: Evaluación de suscripciones");

        // Logic
        $now = Carbon::now();

        $subscriptions = Subscription::where("status", "active")
                                     ->where(function($query) use($now) {

                                        $query->where("start_date", ">", $now)
                                              ->orWhere("end_date", "<", $now);

                                      })
                                      ->get();

        $inactivatedCount = 0;

        foreach($subscriptions as $subscription) {

            $subscription->update([
                "motive"     => "Suscripción expirada.",
                "status"     => "inactive",
                "updated_at" => $now,
                "updated_by" => null
            ]);

            Log::channel("subscriptions")->info("Suscripción ID {$subscription->id} expirada, inicio: {$subscription->start_date} - fin: {$subscription->end_date}");

            $inactivatedCount++;

        }

        Log::channel("subscriptions")->info("Total de suscripciones expiradas: {$inactivatedCount}");
        Log::channel("subscriptions")->info("------------------- Proceso finalizado -------------------");

    }

}
