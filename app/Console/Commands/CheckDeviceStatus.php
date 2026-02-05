<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\DeviceLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckDeviceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:check-status {--device-id= : Check specific device by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of all devices by pinging their IP addresses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting device status check...');

        $query = Device::whereNotNull('ip_address');
        
        // If specific device ID is provided
        if ($deviceId = $this->option('device-id')) {
            $query->where('id', $deviceId);
        }

        $devices = $query->get();
        $totalDevices = $devices->count();

        if ($totalDevices === 0) {
            $this->warn('No devices with IP addresses found.');
            return 0;
        }

        $this->info("Checking {$totalDevices} device(s)...");

        $bar = $this->output->createProgressBar($totalDevices);
        $bar->start();

        $onlineCount = 0;
        $offlineCount = 0;

        foreach ($devices as $device) {
            $previousStatus = $device->status;
            $isOnline = $this->pingDevice($device->ip_address);
            $newStatus = $isOnline ? 'online' : 'offline';
            
            // Update device status
            $device->update([
                'status' => $newStatus,
                'last_checked_at' => now(),
            ]);

            // Log status change if status changed
            if ($previousStatus !== $newStatus) {
                $message = $newStatus === 'online' 
                    ? "Perangkat kembali online" 
                    : "Perangkat mati/offline";
                
                DeviceLog::create([
                    'device_id' => $device->id,
                    'status' => $newStatus,
                    'previous_status' => $previousStatus,
                    'message' => $message,
                    'logged_at' => now(),
                ]);
            }

            if ($isOnline) {
                $onlineCount++;
            } else {
                $offlineCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Status check completed!");
        $this->info("Online: {$onlineCount} | Offline: {$offlineCount}");

        Log::info("Device status check completed", [
            'total' => $totalDevices,
            'online' => $onlineCount,
            'offline' => $offlineCount,
        ]);

        return 0;
    }

    /**
     * Ping a device IP address
     *
     * @param string $ip
     * @return bool
     */
    private function pingDevice(string $ip): bool
    {
        // Windows ping command
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $ping = exec("ping -n 1 -w 1000 {$ip}", $output, $result);
            return $result === 0;
        }
        
        // Linux/Unix ping command
        $ping = exec("ping -c 1 -W 1 {$ip} 2>&1", $output, $result);
        return $result === 0;
    }
}
