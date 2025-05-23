<?php
namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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

    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Transaction ID',
            'Auction Name',
            'Car Make & Model',
            'User Name',
            'Amount',
            'Payment Status',
            'Transaction Date',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param Transaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        return [
            $transaction->id,
            optional($transaction->auction)->name, // Fetch auction name
            optional($transaction->car)->make . ' ' . optional($transaction->car)->model, // Fetch car make and model
            optional($transaction->user)->name, // Fetch user name
            number_format($transaction->amount, 2), // Format amount
            $transaction->payment_status,
            $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d') : null, // Format date
        ];
    }

    /**
     * Apply styles to the worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Apply a grey background to the header row
        $sheet->getStyle('1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'D3D3D3'], // Grey background
            ],
            'font' => [
                'bold' => true, // Make the text bold
            ],
        ]);

        return [];
    }

    /**
     * Set column widths for the exported file.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Transaction ID
            'B' => 25, // Auction Name
            'C' => 30, // Car Make & Model
            'D' => 20, // User Name
            'E' => 15, // Amount
            'F' => 20, // Payment Status
            'G' => 20, // Transaction Date
        ];
    }
}