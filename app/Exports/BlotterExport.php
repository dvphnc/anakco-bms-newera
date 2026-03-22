<?php

namespace App\Exports;

use App\Models\BlotterCase;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BlotterExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return BlotterCase::with(['filedBy'])
            ->when($this->filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($this->filters['incident_type'] ?? null, fn($q, $v) => $q->where('incident_type', $v))
            ->orderBy('incident_date', 'desc');
    }

    public function title(): string { return 'Blotter Cases'; }

    public function headings(): array
    {
        return [
            '#', 'Case Number', 'Incident Type', 'Incident Date',
            'Location', 'Complainant', 'Respondent',
            'Status', 'Filed By', 'Date Filed', 'Settled On',
        ];
    }

    public function map($b): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $b->case_number,
            $b->incident_type,
            $b->incident_date ? \Carbon\Carbon::parse($b->incident_date)->format('M d, Y') : '—',
            $b->incident_location ?? '—',
            $b->complainant_name ?? '—',
            $b->respondent_name ?? '—',
            $b->status,
            $b->filedBy->name ?? '—',
            $b->created_at->format('M d, Y'),
            $b->settled_at?->format('M d, Y') ?? '—',
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
            'A' => 5, 'B' => 18, 'C' => 20, 'D' => 14,
            'E' => 28, 'F' => 22, 'G' => 22, 'H' => 24,
            'I' => 18, 'J' => 14, 'K' => 14,
        ];
    }
}