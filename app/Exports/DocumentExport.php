<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DocumentExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return Document::with(['resident', 'issuedBy'])
            ->when($this->filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($this->filters['document_type'] ?? null, fn($q, $v) => $q->where('document_type', $v))
            ->orderBy('created_at', 'desc');
    }

    public function title(): string { return 'Documents'; }

    public function headings(): array
    {
        return [
            '#', 'Doc Number', 'Document Type', 'Resident',
            'Purpose', 'Status', 'Fee Paid', 'OR Number',
            'Issued By', 'Date Requested', 'Date Released',
        ];
    }

    public function map($d): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $d->doc_number,
            $d->document_type,
            $d->resident->full_name ?? '—',
            $d->purpose ?? '—',
            $d->status,
            $d->fee_paid > 0 ? '₱' . number_format($d->fee_paid, 2) : 'Free',
            $d->or_number ?? '—',
            $d->issuedBy->name ?? '—',
            $d->created_at->format('M d, Y'),
            $d->released_at?->format('M d, Y') ?? '—',
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
            'A' => 5, 'B' => 18, 'C' => 24, 'D' => 24,
            'E' => 28, 'F' => 14, 'G' => 12, 'H' => 16,
            'I' => 18, 'J' => 14, 'K' => 14,
        ];
    }
}