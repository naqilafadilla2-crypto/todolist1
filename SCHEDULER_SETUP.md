# Setup Scheduler untuk Auto Ping Device

## Cara Menjalankan Scheduler

### Windows (Task Scheduler)

1. Buka Task Scheduler (taskschd.msc)
2. Create Basic Task
3. Name: Laravel Scheduler
4. Trigger: Daily atau When computer starts
5. Action: Start a program
6. Program/script: `php`
7. Add arguments: `C:\laragon\www\Todolist\artisan schedule:run`
8. Start in: `C:\laragon\www\Todolist`
9. Set to run every minute:
   - Edit task → Triggers → Edit
   - Repeat task every: 1 minute
   - Duration: Indefinitely

### Linux/Unix (Cron)

Tambahkan ke crontab:
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Atau edit crontab:
```bash
crontab -e
```

Tambahkan baris:
```
* * * * * cd /path/to/Todolist && php artisan schedule:run >> /dev/null 2>&1
```

## Manual Testing

Untuk test command secara manual:
```bash
php artisan devices:check-status
```

Untuk test device tertentu:
```bash
php artisan devices:check-status --device-id=1
```

## Fitur

- ✅ Auto ping setiap 1 menit untuk semua perangkat yang memiliki IP
- ✅ Update status (online/offline) otomatis
- ✅ Update timestamp `last_checked_at`
- ✅ Logging hasil pengecekan
- ✅ Support Windows dan Linux ping command
- ✅ Auto-refresh di Livewire component setiap 30 detik
- ✅ Manual trigger via button di UI
