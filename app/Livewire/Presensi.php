<?php
namespace App\Livewire;

use App\Models\Schedule;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Livewire\Component;

class Presensi extends Component
{
    public function render()
    {
        $schedule = Schedule::where('user_id', FacadesAuth::user()->id)->first();
        return view('livewire.presensi', [
            'schedule' => $schedule,
        ]);
    }
}
