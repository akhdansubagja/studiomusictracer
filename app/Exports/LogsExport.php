<?php
namespace App\Exports;

use App\Models\StudioLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class LogsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filter;

    public function __construct(string $filter) { $this->filter = $filter; }

    public function query()
    {
        $query = StudioLog::query();
        $now = Carbon::now();

        if ($this->filter == 'year') {
            $query->whereYear('tanggal', $now->year);
        } elseif ($this->filter == 'week') {
            $query->whereBetween('tanggal', [$now->startOfWeek(), $now->endOfWeek()]);
        } else {
            $query->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year);
        }
        return $query->orderBy('tanggal', 'asc');
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama Studio', 'Jumlah Jam', 'Total Pendapatan'];
    }

    public function map($log): array
    {
        return [
            Carbon::parse($log->tanggal)->format('d-m-Y'),
            'Studio ' . $log->studio_id,
            $log->jumlah_jam,
            $log->total_pendapatan,
        ];
    }
}