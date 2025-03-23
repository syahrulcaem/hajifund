<?php

use Illuminate\Console\Scheduling\Schedule;

app()->booted(function () {
    $schedule = app(Schedule::class);

    // Kirim reminder laporan tiap tanggal 1 jam 08:00
    $schedule->command('reminder:send-report')->monthlyOn(1, '08:00');
});
