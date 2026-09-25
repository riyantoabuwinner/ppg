<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\AppSetting;

class IdentitasAplikasi extends Component
{
    use WithFileUploads;

    public $app_name;
    public $app_subtitle;
    public $logo_light_upload  = null;
    public $logo_dark_upload   = null;
    public $favicon_upload     = null;

    public function mount()
    {
        $this->app_name    = AppSetting::get('app_name', 'Portal Lapor Diri PPG');
        $this->app_subtitle = AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon');
    }

    public function save()
    {
        $this->validate([
            'app_name'           => 'required|min:3',
            'logo_light_upload'  => 'nullable|image|max:1024',
            'logo_dark_upload'   => 'nullable|image|max:1024',
            'favicon_upload'     => 'nullable|image|max:512',
        ]);

        AppSetting::set('app_name', $this->app_name);
        AppSetting::set('app_subtitle', $this->app_subtitle);

        if ($this->logo_light_upload) {
            $path = $this->logo_light_upload->storeAs('identity', 'logo-light.' . $this->logo_light_upload->getClientOriginalExtension(), 'public');
            AppSetting::set('app_logo_light', $path);
        }

        if ($this->logo_dark_upload) {
            $path = $this->logo_dark_upload->storeAs('identity', 'logo-dark.' . $this->logo_dark_upload->getClientOriginalExtension(), 'public');
            AppSetting::set('app_logo_dark', $path);
        }

        if ($this->favicon_upload) {
            $path = $this->favicon_upload->storeAs('identity', 'favicon.' . $this->favicon_upload->getClientOriginalExtension(), 'public');
            AppSetting::set('app_favicon', $path);
        }

        session()->flash('message', 'Identitas aplikasi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.identitas-aplikasi', [
            'logo_light' => AppSetting::get('app_logo_light'),
            'logo_dark'  => AppSetting::get('app_logo_dark'),
            'favicon'    => AppSetting::get('app_favicon'),
        ]);
    }
}
