<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:fetch-stock-data')
    ->everyMinute()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/stock_data.log'));
