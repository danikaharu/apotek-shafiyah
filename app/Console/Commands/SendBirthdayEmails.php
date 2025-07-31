<?php

namespace App\Console\Commands;

use App\Mail\BirthdayReminderMail;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBirthdayEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email ulang tahun ke customer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('m-d');

        $customers = Customer::with('user')->whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$today])->get();

        foreach ($customers as $customer) {
            $email = optional($customer->user)->email;

            if (!$email) {
                \Log::warning("❌ Customer ID {$customer->id} tidak punya email.");
                continue;
            }

            \Log::info("📧 Kirim email ke: {$email}");

            Mail::to($email)->send(new BirthdayReminderMail($customer));
        }

        $this->info('Email ulang tahun berhasil dikirim.');
    }
}
