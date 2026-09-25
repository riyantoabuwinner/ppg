<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\StudentProfile;
use App\Models\StudentEducation;
use App\Models\StudentFamily;
use Illuminate\Support\Facades\Auth;

class FormLaporDiri extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    
    // Data Step 1 (Pribadi)
    public $nim, $nama, $nik, $tempat_lahir_id, $tanggal_lahir, $jenis_kelamin, $agama, $kewarganegaraan, $nisn, $npwp;
    
    // Data Step 2 (Domisili)
    public $alamat, $rt, $rw, $dusun, $kelurahan, $kecamatan, $kode_pos, $jenis_tinggal, $alat_transportasi, $no_hp, $email;

    // Data Step 3 (Pendidikan)
    public $asal_perguruan_tinggi, $asal_program_studi, $smta_kode, $jurusan_smta, $tahun_lulus_smta;

    // Data Step 4 (Keluarga)
    public $nama_ayah, $nik_ayah, $pekerjaan_ayah, $penghasilan_ayah;
    public $nama_ibu, $nik_ibu, $pekerjaan_ibu, $penghasilan_ibu;

    // Data Step 5 (Ekonomi)
    public $jenis_pembiayaan, $sumber_dana, $terima_kps;

    // Data Step 6 (Dokumen)
    public $file_ktp, $file_ijazah, $file_transkrip, $file_pakta_integritas, $file_surat_sehat, $link_rpl;


    public function mount()
    {
        $user = Auth::user();
        $this->nim = $user->username;
        $this->nama = $user->name;
        $this->email = $user->email;
        
        $profile = StudentProfile::where('user_id', $user->id)->first();
        if ($profile) {
            $this->nik = $profile->nik;
            // ... load other existing data ...
            
            if ($user->status_lapor_diri === 'submitted' || $user->status_lapor_diri === 'verified') {
                $this->currentStep = 7; // Ready / Locked state
            }
        }
    }

    public function nextStep()
    {
        $user = Auth::user();
        if ($user->status_lapor_diri === 'draft' && !\App\Models\Period::isAnyOpen()) {
            session()->flash('error', 'Periode lapor diri saat ini sedang ditutup atau belum dibuka.');
            return;
        }

        if ($this->currentStep < 6) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submitForm()
    {
        $user = Auth::user();

        // Validasi periode buka/tutup
        $activePeriod = \App\Models\Period::currentOpen();
        if (!$activePeriod) {
            session()->flash('error', 'Maaf, periode lapor diri saat ini sedang ditutup atau belum dibuka. Formulir tidak dapat dikirim.');
            return;
        }
        
        // Simpan Profil
        $profile = StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'periode_id' => $activePeriod->id,
                'gelombang' => $activePeriod->nama,
                'nim' => $this->nim,
                'nama' => $this->nama,
                'nik' => $this->nik,
                'tempat_lahir_id' => $this->tempat_lahir_id,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'agama' => $this->agama,
                'kewarganegaraan' => $this->kewarganegaraan,
                'nisn' => $this->nisn,
                'npwp' => $this->npwp,
                'alamat' => $this->alamat,
                'rt' => $this->rt,
                'rw' => $this->rw,
                'dusun' => $this->dusun,
                'kelurahan' => $this->kelurahan,
                'kecamatan' => $this->kecamatan,
                'kode_pos' => $this->kode_pos,
                'jenis_tinggal' => $this->jenis_tinggal,
                'alat_transportasi' => $this->alat_transportasi,
                'no_hp' => $this->no_hp,
                'email' => $this->email,
                'jenis_pembiayaan' => $this->jenis_pembiayaan,
                'sumber_dana' => $this->sumber_dana,
                'terima_kps' => $this->terima_kps,
            ]
        );

        // Simpan tautan RPL ke profile (jika diperlukan, atau ke student_documents)
        // Kita bisa menyimpan Link RPL sebagai dokumen bertipe link_rpl
        if ($this->link_rpl) {
            \App\Models\StudentDocument::updateOrCreate(
                ['user_id' => $user->id, 'jenis_dokumen' => 'link_rpl'],
                ['file_path' => $this->link_rpl, 'status' => 'pending']
            );
        }

        // Simpan dokumen fisik
        $docs = [
            'ktp' => $this->file_ktp,
            'ijazah' => $this->file_ijazah,
            'transkrip' => $this->file_transkrip,
            'pakta_integritas' => $this->file_pakta_integritas,
            'surat_sehat' => $this->file_surat_sehat,
        ];

        foreach ($docs as $jenis => $file) {
            if ($file) {
                $path = $file->store('dokumen/' . $user->username, 'public');
                \App\Models\StudentDocument::updateOrCreate(
                    ['user_id' => $user->id, 'jenis_dokumen' => $jenis],
                    ['file_path' => $path, 'status' => 'pending']
                );
            }
        }

        // Update status user
        $user->status_lapor_diri = 'submitted';
        $user->save();
        
        $this->currentStep = 7;
        session()->flash('message', 'Lapor Diri Berhasil Disimpan!');
    }

    public function render()
    {
        return view('livewire.mahasiswa.form-lapor-diri', [
            'activePeriod' => \App\Models\Period::currentOpen(),
            'isOpen'       => \App\Models\Period::isAnyOpen(),
        ]);
    }
}
