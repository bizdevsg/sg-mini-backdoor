# Retensi Undo CRUD

Undo tersedia selama 10 detik melalui toast. Snapshot database dan backup file tetap dipertahankan selama 24 jam agar proses pemulihan dapat diaudit dan dibersihkan secara aman.

Laravel menjadwalkan cleanup setiap hari pukul 03:15 melalui command:

```text
php artisan crud-undo:prune
```

Server production harus menjalankan scheduler Laravel setiap menit. Contoh cron Linux:

```cron
* * * * * cd /path/to/sg-admin && php artisan schedule:run >> /dev/null 2>&1
```

Command cleanup menghapus permanen baris `crud_undo_actions` dan backup file privat yang `retention_until`-nya sudah lewat. `withoutOverlapping()` mencegah dua proses cleanup berjalan bersamaan.
