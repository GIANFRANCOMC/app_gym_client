<?php

namespace App\Listeners;

use App\Events\SubscriptionExpired;
use App\Helpers\System\Utilities;
use App\Models\System\Email;
use App\Models\System\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSubscriptionEmail {

    /**
     * Create the event listener.
     */
    public function __construct() {

        //

    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionExpired $event) {

        $subscription = $event->subscription;
        $branch       = $subscription->branch;
        $customer     = $subscription->customer;
        $ownerApp     = Utilities::getOwnerApp();

        $company = $subscription->company;
        $socialsMedia = $company->socialsMedia;

        $touchpoints = collect();

        $whatsapp = $socialsMedia->where("type", "whatsapp");

        if(count($whatsapp) === 1) {

            $touchpoints->push($whatsapp->first());

        }

        Email::create([
            "company_id"  => $subscription->company_id,
            "to"          => $customer->email ?? "sin-correo@example.com",
            "subject"     => "Tu membresía ha expirado",
            "body"        => view("mails.subscriptions.expired.default", compact("subscription", "branch", "company", "customer", "ownerApp", "touchpoints"))->render(),
            "extras_json" => null,
            "type"        => "SubscriptionExpired",
            "model_id"    => $subscription->id,
            "model_type"  => Subscription::class,
            "status"      => "pending",
            "created_at"  => now(),
        ]);

    }

}
