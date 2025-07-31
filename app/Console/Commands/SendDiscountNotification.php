<?php

namespace App\Console\Commands;

use App\Mail\DiscountNotificationMail;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDiscountNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:discount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email promo diskon ke semua pelanggan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customers = Customer::with('user')->get();

        $messageContent = 'Dapatkan diskon 20% untuk pembelian produk kesehatan hingga 31 Agustus 2025!';

        foreach ($customers as $customer) {
            if ($customer->user && $customer->user->email) {
                Mail::to($customer->user->email)
                    ->send(new DiscountNotificationMail($customer, $messageContent));
            }
        }

        $this->info('Broadcast diskon berhasil dikirim ke semua pelanggan.');
    }
}
