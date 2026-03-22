<?php

namespace App\Exports;

use App\Models\Business;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BusinessExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return Business::with(['issuedBy'])
            ->when($this->filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($this->filters['business_type'] ?? null, fn($q, $v) => $q->where('business_type', $v))
            ->orderBy('business_name');
    }

    public function title(): string { return 'Business Permits'; }

    public function headings(): array
    {
        return [
            '#', 'Permit No.', 'Business Name', 'Business Type',
            'Owner', 'Contact', 'Address',
            'Permit Date', 'Expiry Date', 'Status',
        ];
    }

    public function map($b): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $b->permit_number,
            $b->business_name,
            $b->business_type,
            $b->owner_name,
            $b->owner_contact ?? '—',
            $b->business_address,
            $b->permit_date ? \Carbon\Carbon::parse($b->permit_date)->format('M d, Y') : '—',
            $b->expiry_date ? \Carbon\Carbon::parse($b->expiry_date)->format('M d, Y') : '—',
            $b->status,
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
            'A' => 5, 'B' => 18, 'C' => 26, 'D' => 22,
            'E' => 22, 'F' => 16, 'G' => 32, 'H' => 14,
            'I' => 14, 'J' => 14,
        ];
    }
}