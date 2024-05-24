<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// inspireコマンドの定義
Artisan::command('inspire', function () {
    // Inspiringクラスから名言を表示
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
