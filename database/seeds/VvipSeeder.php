<?php

namespace Database\Seeders;

use App\Models\Vip;
use Illuminate\Database\Seeder;

class VvipSeeder extends Seeder
{
    protected $emails_non_credit_card_continues_6months = ["alinatb_Vm1@test.com", "alinatb_Vm2@test.com", "koema_v9@test.com", "koema_v10@test.com", "B7test@gami.com"];

    protected $emails_credit_card_continues_6months = ["alinatb_Vm3@test.com", "alinatb_Vm4@test.com", "koema_v7@test.com", "koema_v8@test.com", "B1test@gami.com", "B2test@gami.com", "B3test@gami.com", ];

    protected $emails_credit_card_had_continues_6months_current_not_vip = ["alinatb_Vm7@test.com", "alinatb_Vm8@test.com", "koema_v1@test.com", "koema_v2@test.com"];

    protected $emails_credit_card_had_below_6months_current_not_vip = ["koema_v6@test.com"];

    protected $emails_non_credit_card_accumulated_18months_with_spans = ["alinatb_Vm5@test.com", "alinatb_Vm6@test.com", "koema_v11@test.com", "koema_v12@test.com", "koema_v13@test.com", "B4test@gami.com", "B5test@gami.com", "B6test@gami.com"];

    protected $emails_non_credit_card_accumulated_18months_with_spans_not_vip = ["alinatb_Vm9@test.com", "alinatb_Vm10@test.com", "koema_v3@test.com", "koema_v4@test.com", "koema_v5@test.com", ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::whereIn('email', $this->emails_non_credit_card_continues_6months)->get();
        $users->each(function ($user) {
            Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'atm');
            $vip = $user->vip->first();
            if (!$vip) {
                echo "User {$user->id} has no vip\n";
                return;
            }
            $vip->created_at = \Carbon\Carbon::now()->subMonths(7);
            $vip->save();
        });

        $users = \App\Models\User::whereIn('email', $this->emails_credit_card_continues_6months)->get();
        $users->each(function ($user) {
            Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'cc_monthly_payment');
            $vip = $user->vip->first();
            if (!$vip) {
                echo "User {$user->id} has no vip\n";
                return;
            }
            $vip->created_at = \Carbon\Carbon::now()->subMonths(7);
            $vip->save();
        });

        $users = \App\Models\User::whereIn('email', $this->emails_credit_card_had_continues_6months_current_not_vip)->get();
        $users->each(function ($user) {
            Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'cc_monthly_payment');
            Vip::cancel($user->id, 0);
            $vip = $user->vip->first();
            $vip?->delete();
            foreach($user->vip_log as $log) {
                if ($log->created_at->isToday()) {
                    if(str_contains($log->member_name, 'upgrade')) {
                        $log->created_at->subMonths(8);
                        $log->save();
                    }
                    if(str_contains($log->member_name, 'cancel')) {
                        $log->created_at->subMonths(1);
                        $log->save();
                    }
                }
            }
        });

        $users = \App\Models\User::whereIn('email', $this->emails_credit_card_had_below_6months_current_not_vip)->get();
        $users->each(function ($user) {
            Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'cc_monthly_payment');
            Vip::cancel($user->id, 0);
            $vip = $user->vip->first();
            $vip?->delete();
            foreach($user->vip_log as $log) {
                if ($log->created_at->isToday()) {
                    if(str_contains($log->member_name, 'upgrade')) {
                        $log->created_at->subMonths(4);
                        $log->save();
                    }
                    if(str_contains($log->member_name, 'cancel')) {
                        $log->created_at->subMonths(1);
                        $log->save();
                    }
                }
            }
        });

        $users = \App\Models\User::whereIn('email', $this->emails_non_credit_card_accumulated_18months_with_spans)->get();
        $users->each(function ($user) {
            for($i = 1; $i <= 6; $i++) {
                Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'atm');
                Vip::cancel($user->id, 0);
                foreach($user->vip_log as $log) {
                    if ($log->created_at->isToday()) {
                        if(str_contains($log->member_name, 'upgrade')) {
                            $log->created_at->subMonths(27 - 3 * $i);
                            $log->save();
                        }
                        if(str_contains($log->member_name, 'cancel')) {
                            $log->created_at->subMonths(27 - 3 * $i - 4);
                            $log->save();
                        }
                    }
                }
            }
            Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'atm');
        });

        $users = \App\Models\User::whereIn('email', $this->emails_non_credit_card_accumulated_18months_with_spans_not_vip)->get();
        $users->each(function ($user) {
            for($i = 1; $i <= 6; $i++) {
                Vip::upgrade($user->id, "3137610", "TEST" . $user->id . time(), 666, '', 1, 0, 'atm');
                Vip::cancel($user->id, 0);
                foreach($user->vip_log as $log) {
                    if ($log->created_at->isToday()) {
                        if(str_contains($log->member_name, 'upgrade')) {
                            $log->created_at->subMonths(27 - 3 * $i);
                            $log->save();
                        }
                        if(str_contains($log->member_name, 'cancel')) {
                            $log->created_at->subMonths(27 - 3 * $i - 4);
                            $log->save();
                        }
                    }
                }
            }
        });
    }
}
