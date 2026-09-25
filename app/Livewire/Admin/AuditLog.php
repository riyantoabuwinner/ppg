<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class AuditLog extends Component
{
    public $search = '';

    public function render()
    {
        $logs = Activity::with('causer')
            ->when($this->search, fn($q) => $q->where('description', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.audit-log', compact('logs'));
    }
}
