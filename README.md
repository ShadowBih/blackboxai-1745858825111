# Website E-Commerce Penjualan Aluminium

## Deskripsi
Website ini adalah platform e-commerce sederhana untuk penjualan produk aluminium seperti jendela, pintu, dan lemari. Dibangun menggunakan PHP dan MySQL.

## Fitur
- Registrasi dan login pengguna (password disimpan tanpa hash sesuai permintaan).
- Halaman katalog produk dengan gambar, deskripsi, harga, dan stok.
- Halaman detail produk.
- Sistem pembayaran via transfer bank dengan pencatatan transaksi.
- Halaman admin untuk melihat data transaksi.
- Manajemen sesi pengguna.
- Desain sederhana dan responsif.

## Struktur Database
- Tabel `users`: id, nama, telepon, alamat, email, password.
- Tabel `products`: id, nama_produk, deskripsi, harga, stok, gambar.
- Tabel `transactions`: id_transaksi, nomor_transaksi, tanggal_transaksi, product_id, jumlah, harga_total, kontak_pembeli.

## Cara Menjalankan
1. Buat database MySQL dan import struktur tabel sesuai kebutuhan.
2. Sesuaikan konfigurasi database di `db_config.php`.
3. Tempatkan gambar produk di folder `images/`.
4. Jalankan server PHP dan akses `index.php`.

## Catatan
- Password disimpan dalam bentuk asli (plain text) sesuai permintaan, yang tidak direkomendasikan untuk keamanan.
- Sistem pembayaran hanya mencatat transaksi dan memberikan instruksi transfer bank, tidak terintegrasi dengan gateway pembayaran.
