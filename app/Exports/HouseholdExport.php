<?php

namespace App\Exports;

use App\Models\Household;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class HouseholdExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    public function query()
    {
        return Household::with(['purok', 'residents'])->orderBy('household_number');
    }

    public function title(): string { return 'Households'; }

    public function headings(): array
    {
        return [
            '#', 'Household No.', 'Household Head', 'Purok',
            'Address', 'Family Size', 'Voter Household',
            'Members Count', 'Date Registered',
        ];
    }

    public function map($h): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $h->household_number,
            $h->household_head ?? '—',
            $h->purok->name ?? '—',
            $h->address,
            $h->family_size ?? '—',
            $h->is_voter_household ? 'Yes' : 'No',
            $h->residents->count(),
            $h->created_at->format('M d, Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0D2144']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 16, 'C' => 22, 'D' => 20,
            'E' => 35, 'F' => 12, 'G' => 16, 'H' => 14, 'I' => 14,
        ];
    }
}