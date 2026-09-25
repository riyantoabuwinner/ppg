<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function cetakBukti()
    {
        $user = Auth::user();
        
        if ($user->role !== 'mahasiswa' || ($user->status_lapor_diri !== 'submitted' && $user->status_lapor_diri !== 'verified')) {
            abort(403, 'Akses ditolak atau data belum difinalisasi.');
        }

        $profile = StudentProfile::with(['education', 'families'])->where('user_id', $user->id)->first();
        
        if (!$profile) {
            abort(404, 'Data Profil Tidak Ditemukan');
        }

        $pdf = Pdf::loadView('pdf.bukti-lapor', compact('user', 'profile'));
        
        return $pdf->stream('Bukti-Lapor-Diri-PPG-'.$user->username.'.pdf');
    }
}
