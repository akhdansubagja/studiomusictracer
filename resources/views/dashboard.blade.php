<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Studio Musik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">🎵 Dashboard Studio Musik</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Pendapatan</div>
                    <div class="card-body">
                        <h4 class="card-title">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Jam Studio 1</div>
                    <div class="card-body">
                        <h4 class="card-title">{{ $totalJamStudio1 }} Jam</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Jam Studio 2</div>
                    <div class="card-body">
                        <h4 class="card-title">{{ $totalJamStudio2 }} Jam</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Tambah Catatan Pemakaian</strong></div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('log.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="studio_id" class="form-label">Pilih Studio</label>
                            <select class="form-select" id="studio_id" name="studio_id" required>
                                <option value="1">Studio 1 (Rp 35rb/jam)</option>
                                <option value="2">Studio 2 (Rp 40rb/jam)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="jumlah_jam" class="form-label">Jumlah Jam</label>
                            <input type="number" class="form-control" id="jumlah_jam" name="jumlah_jam" min="1"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Laporan Pendapatan</strong>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center me-3">
                            <input type="date" name="tanggal_mulai" class="form-control form-control-sm me-2"
                                value="{{ request('tanggal_mulai') }}">
                            <span class="me-2">-</span>
                            <input type="date" name="tanggal_selesai" class="form-control form-control-sm me-2"
                                value="{{ request('tanggal_selesai') }}">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        </form>

                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('dashboard', ['filter' => 'week']) }}"
                                class="btn btn-outline-secondary {{ $currentFilter == 'week' ? 'active' : '' }}">Minggu
                                Ini</a>
                            <a href="{{ route('dashboard', ['filter' => 'month']) }}"
                                class="btn btn-outline-secondary {{ $currentFilter == 'month' ? 'active' : '' }}">Bulan
                                Ini</a>
                            <a href="{{ route('dashboard', ['filter' => 'year']) }}"
                                class="btn btn-outline-secondary {{ $currentFilter == 'year' ? 'active' : '' }}">Tahun
                                Ini</a>
                        </div>

                        <a href="{{ route('log.export', request()->all()) }}" class="btn btn-sm btn-success ms-3">Export
                            Excel</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="pendapatanChart" class="mb-4"></canvas>
                <hr class="my-4">
                <h5 class="mt-4">Grafik Frekuensi Sewa (Total Jam)</h5>
                <canvas id="frekuensiChart"></canvas>
                <hr>
                <h5 class="mt-4">Rincian Catatan</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Studio</th>
                                <th>Jumlah Jam</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</td>
                                    <td>Studio {{ $log->studio_id }}</td>
                                    <td>{{ $log->jumlah_jam }} Jam</td>
                                    <td>Rp {{ number_format($log->total_pendapatan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('pendapatanChart');
        new Chart(ctx, {
            type: 'line', // Ganti jadi line chart agar lebih menarik
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Pendapatan',
                    data: @json($pendapatanChartValues),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.2 // membuat garis sedikit melengkung
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
        const ctx2 = document.getElementById('frekuensiChart');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Studio 1 (Jam)',
                        data: @json($frekuensiChartValues1),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        tension: 0.2
                    },
                    {
                        label: 'Studio 2 (Jam)',
                        data: @json($frekuensiChartValues2),
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        tension: 0.2
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>