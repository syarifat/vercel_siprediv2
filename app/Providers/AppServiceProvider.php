<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ensure there's a selected tahun_ajaran in session (except when running in console)
        if (!app()->runningInConsole()) {
            try {
                if (!session()->has('tahun_ajaran_id')) {
                    $active = \App\Models\TahunAjaran::where('aktif', true)->first();
                    if ($active) {
                        session(['tahun_ajaran_id' => $active->id]);
                    }
                }

                $current = null;
                if (session()->has('tahun_ajaran_id')) {
                    $current = \App\Models\TahunAjaran::find(session('tahun_ajaran_id'));
                }

                // Share current selection with all views as $currentTahunAjaran
                View::share('currentTahunAjaran', $current);
            } catch (\Exception $e) {
                // avoid breaking CLI or early bootstrap if session not available
            }
        }
    }
}
