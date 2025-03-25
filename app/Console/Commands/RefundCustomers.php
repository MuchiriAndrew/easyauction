<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefundCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //function will check all tranactions with a payment status of PENDING REFUND and refund the customers using a b2c api
    }
}
