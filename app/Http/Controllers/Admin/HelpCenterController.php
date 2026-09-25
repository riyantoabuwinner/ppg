<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpSession;
use App\Services\HelpCenterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Endpoint polling untuk mengecek panggilan live bantuan yang sedang masuk ('calling').
     */
    public function incomingCheck(Request $request, HelpCenterService $service): JsonResponse
    {
        $callingSessions = HelpSession::with('user.profile')
            ->calling()
            ->where('created_at', '>=', now()->subMinutes(30))
            ->oldest()
            ->get();

        $formatted = $callingSessions->map(function (HelpSession $session) {
            $waitSeconds = max(0, now()->diffInSeconds($session->created_at));
            $callerName = $session->user->name ?? 'Pengguna Anonim';
            $callerRole = $session->user->role === 'mahasiswa' ? 'Mahasiswa PPG' : ucfirst($session->user->role ?? 'User');

            return [
                'id'             => $session->id,
                'session_code'   => $session->session_code,
                'caller_name'    => $callerName,
                'caller_role'    => $callerRole,
                'topic'          => $session->topic,
                'wait_seconds'   => $waitSeconds,
                'wait_time_text' => $waitSeconds < 60 ? "{$waitSeconds} detik lalu" : floor($waitSeconds / 60) . ' mnt lalu',
                'created_at'     => $session->created_at?->toISOString(),
                'answer_url'     => route('admin.helpdesk', [
                    'session_id'  => $session->id,
                    'auto_answer' => 1,
                ]),
            ];
        });

        return response()->json([
            'status'      => 'success',
            'has_calling' => $formatted->isNotEmpty(),
            'count'       => $formatted->count(),
            'sessions'    => $formatted,
            'timestamp'   => now()->toISOString(),
        ]);
    }
}
