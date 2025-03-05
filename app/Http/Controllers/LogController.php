<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return view('logs.index', ['logs' => 'Log file not found.']);
        }

        $logs = File::get($logPath);
        $logs = nl2br(e($logs)); // Mengamankan output HTML

        return view('logs.index', compact('logs'));
    }
}
