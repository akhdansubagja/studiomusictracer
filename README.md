# 🎵 Studio Musik Tracker

**Studio Musik Tracker** adalah aplikasi web sederhana yang dibangun menggunakan **Laravel 10** untuk mencatat, melacak, dan memvisualisasikan data pemesanan (booking) studio musik. Aplikasi ini dirancang untuk memberikan gambaran cepat mengenai pendapatan dan frekuensi sewa studio dalam rentang waktu yang fleksibel.

## ✨ Fitur Utama

-   **Dashboard Interaktif**: Tampilan utama yang merangkum semua informasi penting dalam satu halaman.
-   **Pencatatan Log Sederhana**: Input data pemakaian studio per hari dengan mudah, hanya dengan memilih studio, tanggal, dan jumlah jam.
-   **Visualisasi Data**: Dilengkapi dengan dua grafik interaktif untuk memantau:
    1.  **Grafik Pendapatan**: Melacak total pendapatan dari waktu ke waktu.
    2.  **Grafik Frekuensi Sewa**: Membandingkan total jam pemakaian antara Studio 1 dan Studio 2 dalam bentuk *multi-line chart*.
-   **Filter Fleksibel**: Saring data berdasarkan:
    -   Rentang tanggal kustom (dari tanggal A hingga tanggal B).
    -   Filter cepat: Minggu Ini, Bulan Ini, dan Tahun Ini.
-   **Ekspor ke Excel**: Unduh laporan data yang sudah difilter dalam format `.xlsx` dengan satu kali klik.
-   **Pagination**: Tabel rincian data dilengkapi dengan sistem halaman untuk navigasi yang mudah.
-   **Data Dummy**: Dilengkapi dengan *Seeder* dan *Factory* untuk mengisi database dengan data acak untuk keperluan pengembangan dan demonstrasi.

---

## 💻 Teknologi yang Digunakan

-   **Backend**: Laravel 10, PHP 8.2
-   **Frontend**: Blade, Bootstrap 5
-   **Database**: MySQL (atau sesuai konfigurasi Anda)
-   **Grafik**: Chart.js
-   **Lainnya**: Maatwebsite/excel (untuk fitur ekspor)

---

## 🚀 Instalasi dan Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

1.  **Clone repositori ini:**
    ```bash
    git clone [https://github.com/NAMA_USER_ANDA/NAMA_REPO_ANDA.git](https://github.com/NAMA_USER_ANDA/NAMA_REPO_ANDA.git)
    cd NAMA_REPO_ANDA
    ```

2.  **Install dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Salin file environment:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate kunci aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi database Anda di file `.env`:**
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6.  **Pastikan extension PHP aktif:**
    Pastikan `extension=gd` dan `extension=zip` sudah aktif (tidak ada tanda `;` di depannya) di dalam file `php.ini` Anda.

7.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate
    ```

8.  **(Opsional) Isi database dengan data dummy:**
    ```bash
    php artisan db:seed
    ```

9.  **Jalankan server pengembangan:**
    ```bash
    php artisan serve
    ```

Aplikasi sekarang dapat diakses di `http://127.0.0.1:8000`.
