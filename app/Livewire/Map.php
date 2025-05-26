<?php
namespace App\Livewire;

use App\Models\Attendance;
use Livewire\Component;

class Map extends Component
{
    public function render()
    {
        $attendances = Attendance::get();
        return view('livewire.map', [
            'attendances' => $attendances,
        ]);
    }
}
