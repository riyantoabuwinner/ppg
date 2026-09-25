<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Lapor Diri PPG - {{ $user->username }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h3 { margin: 5px 0 0; font-size: 14px; font-weight: normal; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content-table th, .content-table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .content-table th { background-color: #f4f4f4; width: 35%; }
        .footer { text-align: right; margin-top: 50px; }
        .qr-placeholder { width: 80px; height: 80px; border: 1px dashed #000; display: inline-block; text-align: center; line-height: 80px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h3>UNIVERSITAS ISLAM NEGERI SIBER SYEKH NURJATI CIREBON</h3>
        <p style="margin:5px 0 0; font-size:10px;">Jl. Perjuangan By Pass Sunyaragi, Cirebon</p>
    </div>

    <h3 style="text-align:center; text-decoration:underline;">TANDA BUKTI LAPOR DIRI PPG</h3>
    
    <p>Menerangkan bahwa mahasiswa di bawah ini telah melakukan Lapor Diri secara daring:</p>

    <table class="content-table">
        <tr>
            <th>NIM / Nomor Tes</th>
            <td>{{ $user->username }}</td>
        </tr>
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $profile->nama }}</td>
        </tr>
        <tr>
            <th>NIK (Nomor Induk Kependudukan)</th>
            <td>{{ $profile->nik }}</td>
        </tr>
        <tr>
            <th>Program Studi Asal (S1)</th>
            <td>{{ optional($profile->education)->asal_program_studi ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jalur Pendaftaran / Gelombang</th>
            <td>{{ $profile->jalur_pendaftaran ?? '-' }} / {{ $profile->gelombang ?? '-' }}</td>
        </tr>
        <tr>
            <th>Waktu Lapor Diri (Sistem)</th>
            <td>{{ $profile->updated_at->format('d F Y H:i:s') }}</td>
        </tr>
        <tr>
            <th>Status Dokumen</th>
            <td>
                @if($user->status_lapor_diri === 'verified')
                    <b>Terverifikasi</b> (Valid)
                @else
                    <b>Menunggu Verifikasi</b> Admin
                @endif
            </td>
        </tr>
    </table>

    <p style="font-size:11px; font-style:italic;">
        * Dokumen ini dicetak otomatis oleh sistem dan sah sebagai bukti lapor diri sementara hingga proses verifikasi dokumen fisik/digital selesai dilakukan oleh Panitia PPG UIN Siber Syekh Nurjati Cirebon.
    </p>

    <div class="footer">
        <p>Cirebon, {{ date('d F Y') }}</p>
        <div class="qr-placeholder">
            [ QR CODE ]
        </div>
        <p style="margin-top:5px; font-size:10px;">{{ $user->username }}</p>
    </div>
</body>
</html>
