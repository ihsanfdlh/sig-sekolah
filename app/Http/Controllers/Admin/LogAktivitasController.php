<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::latest('created_at')
            ->latest('id')
            ->paginate(15);

        return view('admin.log-aktivitas.index', compact('logs'));
    }
}
