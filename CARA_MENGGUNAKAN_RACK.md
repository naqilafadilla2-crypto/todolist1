# Cara Menggunakan Rack Management

## 1. Setup Awal

### A. Buat Rack
1. Login sebagai admin
2. Klik menu "Rack Management" di sidebar
3. Klik tombol "+ Tambah Rack"
4. Isi form:
   - Nama Rack (contoh: "Rack Server 1")
   - Deskripsi (opsional)
   - Total Unit (default 42U untuk rack standar 19")
5. Klik "Simpan"

### B. Tambah Perangkat
1. Klik tombol "+ Tambah Perangkat"
2. Isi form:
   - **Nama Perangkat** (wajib): Contoh "Server Web 1"
   - **IP Address** (opsional tapi disarankan): Contoh "192.168.1.100"
   - **Gambar** (opsional): Upload foto perangkat
   - **Deskripsi** (opsional)
   - **Tinggi (U)**: Berapa unit rack yang digunakan (1-10)
3. Klik "Simpan"

## 2. Drag & Drop Perangkat ke Rack

1. Perangkat yang belum di-assign akan muncul di kolom "Perangkat Tersedia"
2. **Drag** perangkat dari kolom tersebut
3. **Drop** ke unit rack yang diinginkan (lihat nomor unit di sisi kiri)
4. Perangkat akan otomatis terpasang di posisi tersebut

### Memindahkan Perangkat
- Drag perangkat dari unit rack ke unit lain untuk memindahkan
- Drag ke kolom "Perangkat Tersedia" untuk melepas dari rack

## 3. Monitoring Status Perangkat

### Auto Ping (Otomatis)
Status perangkat akan otomatis dicek setiap **1 menit** jika scheduler aktif.

**Setup Scheduler:**
- **Windows**: Jalankan `start-scheduler.bat` atau setup Task Scheduler
- **Linux**: Setup cron job (lihat SCHEDULER_SETUP.md)

### Manual Ping
1. Klik tombol **"🔄 Cek Semua Status"** untuk cek semua perangkat sekaligus
2. Atau klik **"Cek Status"** pada perangkat tertentu

### Status Indicator
- 🟢 **Hijau** = Online (perangkat merespon ping)
- 🔴 **Merah** = Offline (perangkat tidak merespon ping)

## 4. Edit/Hapus Perangkat

### Edit
1. Klik tombol **"Edit"** pada perangkat
2. Ubah data yang diperlukan
3. Klik **"Simpan"**

### Hapus
1. Klik tombol **"Hapus"** pada perangkat
2. Konfirmasi penghapusan

## 5. Test Command

### Test Ping Manual
Jalankan di terminal:
```bash
# Cek semua perangkat
php artisan devices:check-status

# Cek perangkat tertentu (ganti 1 dengan ID perangkat)
php artisan devices:check-status --device-id=1
```

Atau gunakan file batch:
```bash
test-device-ping.bat
```

## 6. Troubleshooting

### Livewire tidak berfungsi?
- Pastikan `@livewireStyles` dan `@livewireScripts` sudah ada di layout
- Clear cache: `php artisan view:clear`

### Ping tidak bekerja?
- Pastikan IP address sudah diisi
- Pastikan perangkat bisa di-ping dari server (test dengan `ping 192.168.1.100`)
- Windows: Pastikan firewall tidak memblokir ping
- Linux: Pastikan user memiliki permission untuk ping

### Scheduler tidak jalan?
- Windows: Jalankan `start-scheduler.bat` atau setup Task Scheduler
- Linux: Pastikan cron job sudah di-setup dengan benar
- Test manual: `php artisan schedule:run`

### Drag & Drop tidak bekerja?
- Pastikan browser support HTML5 drag & drop
- Refresh halaman (Ctrl+F5)
- Clear browser cache

## 7. Fitur Auto-Refresh

UI akan otomatis refresh setiap **30 detik** untuk menampilkan status terbaru tanpa perlu refresh manual.

## 8. Tips

1. **Gunakan IP yang valid** untuk monitoring otomatis
2. **Atur tinggi perangkat (U)** sesuai ukuran fisik untuk visualisasi yang akurat
3. **Upload gambar perangkat** untuk memudahkan identifikasi
4. **Gunakan deskripsi** untuk mencatat informasi penting (serial number, spesifikasi, dll)
