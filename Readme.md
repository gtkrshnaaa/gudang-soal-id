# Deskripsi Gudang Soal ID :
Gudang Soal ID adalah sebuah platform artikel yang dirancang khusus untuk memfasilitasi Admin Website dalam membuat, mengelola, dan mempublikasikan konten artikel berupa soal pelajaran sekolah.
Konsep Gudang Soal ID :

# Tujuan Aplikasi:
Aplikasi ini bertujuan untuk memberikan pengalaman yang optimal bagi author (penulis) dalam mengelola konten artikel, serta memberikan informasi dan inspirasi kepada pengunjung terkait dengan soal pelajaran di sekolah.

# Fitur Utama : 
- Autentikasi :
    - Menjamin keamanan dengan memerlukan login bagi pengguna (admin dan author) sebelum mengakses fitur-fitur admin panel dan halaman dashboard author.

- Admin Panel :
    - Mengelola Author: Menambah, mengedit, dan menghapus author yang berkontribusi dalam menulis artikel.
    - Mengelola artikel: Menambah, mengedit, dan menghapus postingan artikel.
    - Mengelola Mata Pelajaran : Menambah, mengedit, dan menghapus kategori untuk mengatur konten artikel agar terorganisir dengan baik.

- Dashboard Author :
    - Melihat Postingan: Melihat daftar postingan yang telah ditulis.
    - Membuat Postingan Baru: Membuat postingan baru untuk dipublikasikan.
    - Mengedit Postingan: Mengedit postingan yang telah dibuat sebelumnya.
    - Menghapus Postingan: Menghapus postingan yang tidak diperlukan lagi.

- Pengelolaan Konten artikel :
    - Admin dan author memiliki kemampuan untuk membuat, mengedit, dan menghapus postingan artikel, memastikan kelancaran dan relevansi konten yang disajikan kepada pengunjung.

- Tampilan Publik :
    - Menampilkan konten artikel kepada pengunjung melalui halaman publik, termasuk daftar artikel, detail artikel, dan mata pelajaran untuk memudahkan navigasi dan pencarian.

- Atribut Artikel :
    - Slug
    - Konten (soal dan jawaban)
    - Mata Pelajaran
    - Jumlah view
    - Tanggal posting
    - Penulis 


# struktur folder

|-- gudang-soal-id/
    |-- assets/
    |   |-- css/
    |   |   |-- Style.css
    |   |-- js/
    |       |-- Script.js
    |
    |
    |-- database/
    |   |-- Db.sql
    |
    |-- includes/
    |   |-- DbConfig.php
    |   |-- DbConnect.php
    |   |-- Functions.php
    |
    |-- dashboard/
    |   |-- admin/
    |   |   |-- Index.php
    |   |   |-- ManageAuthors.php
    |   |   |-- ManageArticles.php
    |   |   |-- ManageSubjects.php
    |   |
    |   |-- author/
    |       |-- Index.php
    |       |-- CreateArticle.php
    |       |-- EditArticle.php
    |       |-- DeleteArticle.php
    |
    |-- public/
    |   |-- Index.php
    |   |-- Article.php
    |
    |-- login/
    |   |-- admin/
    |   |   |-- AdminLogin.php
    |   |   |-- AdminAuthenticate.php
    |   |   |-- AdminLogout.php
    |   |
    |   |-- author/
    |       |-- AuthorLogin.php
    |       |-- AuthorAuthenticate.php
    |       |-- AuthorLogout.php

...