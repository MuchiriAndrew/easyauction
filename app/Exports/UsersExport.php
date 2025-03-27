<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * Fetch all users for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }

    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'User ID',
            'Name',
            'Email',
            'Phone Number',
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param User $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone_number, // Assuming the phone number field is `phone_number`
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
            'A' => 10, // User ID
            'B' => 25, // Name
            'C' => 30, // Email
            'D' => 20, // Phone Number
        ];
    }
}