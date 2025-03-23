<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class SendReportReminder extends Command
{
    protected $signature = 'reminder:send-report';
    protected $description = 'Kirim notifikasi ke entrepreneur yang belum mengunggah laporan bulan ini';

    public function handle()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Cari entrepreneur yang belum mengunggah laporan bulan ini
        $entrepreneurs = User::where('role', 'ENTREPRENEUR')
            ->whereDoesntHave('reports', function ($query) use ($currentMonth, $currentYear) {
                $query->where('month', $currentMonth)->where('year', $currentYear);
            })
            ->get();

        foreach ($entrepreneurs as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Reminder Laporan Bulanan',
                'message' => 'Jangan lupa untuk mengunggah laporan keuangan bulan ini.',
                'status' => 'UNREAD',
            ]);
        }

        $this->info('Reminder laporan berhasil dikirim.');
    }
}
