<?php

namespace App\Http\Controllers;

use App\Models\StudioLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogsExport; // Nanti kita buat

class DashboardController extends Controller
{
    const HARGA_STUDIO_1 = 35000;
    const HARGA_STUDIO_2 = 40000;

    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $startDate = $request->get('tanggal_mulai');
        $endDate = $request->get('tanggal_selesai');
        $now = Carbon::now();

        $logsQuery = StudioLog::query(); // Query dasar, tanpa order by atau get

        // Logika filter (sama seperti sebelumnya)
        if ($startDate && $endDate) {
            $logsQuery->whereBetween('tanggal', [$startDate, $endDate]);
            $currentFilter = 'custom';
            $period = Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate)->addDay());
        }
        // ... (sisa logika filter tidak berubah) ...
        elseif ($filter == 'year') {
            $logsQuery->whereYear('tanggal', $now->year);
            $currentFilter = 'year';
            $period = Carbon::parse($now->startOfYear())->daysUntil($now->endOfYear());
        } elseif ($filter == 'week') {
            $logsQuery->whereBetween('tanggal', [$now->startOfWeek(), $now->endOfWeek()]);
            $currentFilter = 'week';
            $period = Carbon::parse($now->startOfWeek())->daysUntil($now->endOfWeek());
        } else {
            $logsQuery->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year);
            $currentFilter = 'month';
            $period = Carbon::parse($now->startOfMonth())->daysUntil($now->endOfMonth());
        }

        // PENTING: Ambil SEMUA data untuk kalkulasi total dan chart
        $allLogsForPeriod = $logsQuery->get();

        // Kalkulasi total dari SEMUA data
        $totalPendapatan = $allLogsForPeriod->sum('total_pendapatan');
        $totalJamStudio1 = $allLogsForPeriod->where('studio_id', 1)->sum('jumlah_jam');
        $totalJamStudio2 = $allLogsForPeriod->where('studio_id', 2)->sum('jumlah_jam');

        // PENTING: Ambil data LAGI dengan PAGINASI untuk ditampilkan di tabel
        $logsForTable = $logsQuery->orderBy('tanggal', 'desc')->paginate(15); // <-- Menampilkan 15 data per halaman

        // Siapkan data untuk SEMUA Grafik
        $chartLabels = [];
        $pendapatanChartValues = [];
        $frekuensiChartValues1 = []; // Data untuk chart frekuensi studio 1
        $frekuensiChartValues2 = []; // Data untuk chart frekuensi studio 2

        $dataByDate = $allLogsForPeriod->groupBy(fn($date) => Carbon::parse($date->tanggal)->format('Y-m-d'));

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');

            if (isset($dataByDate[$formattedDate])) {
                $logsOnDate = $dataByDate[$formattedDate];
                // Data untuk chart pendapatan
                $pendapatanChartValues[] = $logsOnDate->sum('total_pendapatan');
                // Data untuk chart frekuensi (berdasarkan jumlah jam)
                $frekuensiChartValues1[] = $logsOnDate->where('studio_id', 1)->sum('jumlah_jam');
                $frekuensiChartValues2[] = $logsOnDate->where('studio_id', 2)->sum('jumlah_jam');
            } else {
                // Jika tidak ada data, semua nilainya 0
                $pendapatanChartValues[] = 0;
                $frekuensiChartValues1[] = 0;
                $frekuensiChartValues2[] = 0;
            }
        }

        return view('dashboard', [
            'logs' => $logsForTable, // <-- Kirim data yang sudah dipaginasi ke view
            'totalPendapatan' => $totalPendapatan,
            'totalJamStudio1' => $totalJamStudio1,
            'totalJamStudio2' => $totalJamStudio2,
            'chartLabels' => $chartLabels,
            'pendapatanChartValues' => $pendapatanChartValues, // <-- Ganti nama agar lebih jelas
            'frekuensiChartValues1' => $frekuensiChartValues1, // <-- Kirim data baru
            'frekuensiChartValues2' => $frekuensiChartValues2, // <-- Kirim data baru
            'currentFilter' => $currentFilter
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|in:1,2',
            'tanggal' => 'required|date',
            'jumlah_jam' => 'required|integer|min:1',
        ]);

        $hargaPerJam = ($request->studio_id == 1) ? self::HARGA_STUDIO_1 : self::HARGA_STUDIO_2;

        StudioLog::create([
            'studio_id' => $request->studio_id,
            'tanggal' => $request->tanggal,
            'jumlah_jam' => $request->jumlah_jam,
            'total_pendapatan' => $request->jumlah_jam * $hargaPerJam,
        ]);

        return redirect()->route('dashboard')->with('success', 'Log berhasil ditambahkan!');
    }

    public function export(Request $request)
    {
        // Ambil semua parameter filter dari request
        $filters = $request->all();
        $fileName = 'laporan-studio-' . date('Y-m-d-His') . '.xlsx';
        return Excel::download(new LogsExport($filters), $fileName);
    }
}