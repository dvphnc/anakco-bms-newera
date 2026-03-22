<?php

namespace App\Exports;

use App\Models\Resident;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResidentExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return Resident::with(['purok', 'household'])
            ->when($this->filters['gender'] ?? null, fn($q, $v) => $q->where('gender', $v))
            ->when($this->filters['status'] ?? null, fn($q, $v) => $q->where('residency_status', $v))
            ->when($this->filters['purok_id'] ?? null, fn($q, $v) => $q->where('purok_id', $v))
            ->orderBy('last_name')->orderBy('first_name');
    }

    public function title(): string { return 'Residents'; }

    public function headings(): array
    {
        return [
            '#', 'Last Name', 'First Name', 'Middle Name', 'Suffix',
            'Gender', 'Birthdate', 'Age', 'Civil Status',
            'Purok', 'Address', 'Contact Number', 'Email',
            'Voter', 'Senior', 'PWD', 'Solo Parent', '4Ps',
            'Residency Status', 'Date Registered',
        ];
    }

    public function map($r): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $r->last_name,
            $r->first_name,
            $r->middle_name ?? '—',
            $r->suffix ?? '—',
            $r->gender,
            $r->birthdate?->format('M d, Y') ?? '—',
            $r->age ?? '—',
            $r->civil_status ?? '—',
            $r->purok->name ?? '—',
            $r->address,
            $r->contact_number ?? '—',
            $r->email_address ?? '—',
            $r->is_voter   ? 'Yes' : 'No',
            $r->is_senior  ? 'Yes' : 'No',
            $r->is_pwd     ? 'Yes' : 'No',
            $r->is_solo_parent ? 'Yes' : 'No',
            $r->is_4ps     ? 'Yes' : 'No',
            $r->residency_status,
            $r->created_at->format('M d, Y'),
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
            'A' => 5,  'B' => 18, 'C' => 18, 'D' => 16, 'E' => 8,
            'F' => 10, 'G' => 14, 'H' => 6,  'I' => 14, 'J' => 18,
            'K' => 30, 'L' => 16, 'M' => 24, 'N' => 8,  'O' => 8,
            'P' => 6,  'Q' => 12, 'R' => 6,  'S' => 16, 'T' => 14,
        ];
    }
}