<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AppSetting;

class ManajemenKontak extends Component
{
    public $contact_phone;
    public $contact_email;
    public $contact_whatsapp;
    public $contact_facebook;
    public $contact_instagram;
    public $contact_youtube;
    public $contact_tiktok;
    public $contact_address;

    public function mount()
    {
        $this->contact_phone = AppSetting::get('contact_phone', '');
        $this->contact_email = AppSetting::get('contact_email', '');
        $this->contact_whatsapp = AppSetting::get('contact_whatsapp', '');
        $this->contact_facebook = AppSetting::get('contact_facebook', '');
        $this->contact_instagram = AppSetting::get('contact_instagram', '');
        $this->contact_youtube = AppSetting::get('contact_youtube', '');
        $this->contact_tiktok = AppSetting::get('contact_tiktok', '');
        $this->contact_address = AppSetting::get('contact_address', '');
    }

    public function save()
    {
        AppSetting::set('contact_phone', $this->contact_phone);
        AppSetting::set('contact_email', $this->contact_email);
        AppSetting::set('contact_whatsapp', $this->contact_whatsapp);
        AppSetting::set('contact_facebook', $this->contact_facebook);
        AppSetting::set('contact_instagram', $this->contact_instagram);
        AppSetting::set('contact_youtube', $this->contact_youtube);
        AppSetting::set('contact_tiktok', $this->contact_tiktok);
        AppSetting::set('contact_address', $this->contact_address);

        session()->flash('message', 'Informasi Kontak dan Media Sosial berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.manajemen-kontak');
    }
}
