<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-auction-status';

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
        //function that will be checking the start and endtimes of auctions and updating their status if need be
        //once an auction is closed the function will also notify the winner and the seller!!
    }
}
