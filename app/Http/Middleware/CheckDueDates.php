<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Angsuran;
use Carbon\Carbon;
use App\Notifications\CicilanJatuhTempoNotification;
use Illuminate\Support\Facades\Cache;

class CheckDueDates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'member') {
            $user = auth()->user();
            $cacheKey = 'due_dates_checked_' . $user->id . '_' . now()->format('Y-m-d');
            
            // Only check once per day per user
            if (!Cache::has($cacheKey)) {
                $angsurans = Angsuran::whereHas('pinjaman', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('status', 'belum lunas')->get();

                foreach ($angsurans as $angsuran) {
                    $dueDate = Carbon::parse($angsuran->tanggal_jatuh_tempo)->startOfDay();
                    $today = now()->startOfDay();
                    
                    if ($today->lte($dueDate)) {
                        $daysDiff = $today->diffInDays($dueDate);
                        
                        if (in_array($daysDiff, [1, 3, 7])) {
                            // Cek apakah sudah pernah dinotifikasi untuk H-x ini
                            $notifType = 'App\Notifications\CicilanJatuhTempoNotification';
                            
                            $alreadyNotified = $user->notifications()
                                ->where('type', $notifType)
                                ->where('created_at', '>=', now()->startOfDay())
                                ->get()
                                ->filter(function ($notif) use ($angsuran, $daysDiff) {
                                    return isset($notif->data['message']) 
                                        && str_contains($notif->data['message'], "ke-{$angsuran->bulan_ke}")
                                        && str_contains($notif->data['message'], "dalam {$daysDiff} hari");
                                })->isNotEmpty();

                            if (!$alreadyNotified) {
                                $user->notify(new CicilanJatuhTempoNotification($angsuran, $daysDiff));
                            }
                        }
                    }
                }
                
                Cache::put($cacheKey, true, now()->addDay());
            }
        }

        return $next($request);
    }
}
