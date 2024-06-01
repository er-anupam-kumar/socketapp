<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CameraDataTable extends Component
{
    public function render()
    {
        return view('livewire.camera-data-table', [
            'data' => DB::table('camera_data')->orderBy('id','DESC')->get(),
        ]);
    }
}
