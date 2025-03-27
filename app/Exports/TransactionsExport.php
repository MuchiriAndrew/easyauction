<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

// class TransactionsExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         return Transaction::all();
//     }
// }


class TransactionsExport implements FromCollection
{
    protected $transactions;

    public function __construct(Collection $transactions = null)
    {
        $this->transactions = $transactions ?? Transaction::all();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactions;
    }
}
