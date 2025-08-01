<?php

namespace App\Console\Commands;

use App\Events\SubscriptionExpired;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Models\System\Customers\{Subscription};

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
    protected $description = "Cancela membresías que han expirado";

    /**
     * Execute the console command.
     */
    public function handle() {

        $now  = Carbon::now();
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

        Log::channel("subscriptions")->info("** Inicio del proceso: Evaluación de membresías");

        // Logic
        $subscriptions = Subscription::where("status", "active")
                                     ->where(function($query) use($now) {

                                        $query->where("end_date", "<=", $now);

                                        // $query->where("start_date", ">", $now)
                                              // ->orWhere("end_date", "<", $now);

                                      })
                                      ->get();

        $inactivatedCount = 0;

        foreach($subscriptions as $subscription) {

            $subscription->update([
                "motive"     => "Membresía expirada.",
                "status"     => "inactive",
                "updated_at" => $now,
                "updated_by" => null
            ]);

            event(new SubscriptionExpired($subscription));

            Log::channel("subscriptions")->info("Membresía ID {$subscription->id} expirada, inicio: {$subscription->start_date} - fin: {$subscription->end_date}");

            $inactivatedCount++;

        }

        Log::channel("subscriptions")->info("Total de membresías expiradas: {$inactivatedCount}");
        Log::channel("subscriptions")->info("------------------- Proceso finalizado -------------------");

    }

}
