# Setup dan Jalankan Aplikasi Buku Tamu di Laravel Fillament



## Prasyarat

- PHP 8.0 atau lebih tinggi
- Laravel 8.x atau lebih tinggi
- Composer (untuk mengelola dependensi)
- Git (untuk mengkloning repositori)

## Langkah-langkah Mengkloning dan Menjalankan Filament

### 1. Clone Repositori Proyek

```bash
git remote add origin git@github.com:rustam76/buku-tamu-fillament.git

```
### 2. Masuk Ke Folder

```bash
cd buku-tamu-fillament

```

### 3. Instal Dependensi
Filament menggunakan Composer untuk manajemen dependensi Laravel. Setelah masuk ke dalam direktori proyek, jalankan perintah berikut untuk menginstal semua dependensi yang diperlukan:

```bash
composer install

```

lalu install

```bash
npm install && npm run build
```




### 4. Setel File .env
Salin file .env.example menjadi .env untuk mengonfigurasi file environment:
```bash
cp .env.example .env
```

Kemudian, atur konfigurasi database dan pengaturan lainnya di file .env

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_buku_tamu_fillament
DB_USERNAME=root
DB_PASSWORD=
```


### 5. Generate Kunci Aplikasi
Laravel membutuhkan kunci aplikasi yang digunakan untuk enkripsi dan keamanan. Jalankan perintah berikut untuk menghasilkan kunci aplikasi:

```bash
php artisan key:generate
```

### 6. Migrasi Database
Filament memerlukan beberapa tabel di database untuk menyimpan informasi terkait pengguna dan pengaturan. Jalankan migrasi untuk membuat tabel-tabel tersebut:

```bash
php artisan migrate
```

### 7. Buat User Admin
Jika kamu belum memiliki user admin, kamu dapat membuatnya menggunakan perintah artisan. Misalnya, untuk membuat user admin secara manual:
```bash
php artisan make:user
```

### 8. Jalankan Server Laravel
Setelah pengaturan selesai, jalankan server Laravel menggunakan perintah:

```bash
php artisan serve
```
Yey, Sekarang Filament dapat diakses di browser dengan alamat http://127.0.0.1:8000/
