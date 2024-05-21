<?php

namespace App\Livewire;

use Livewire\Component;

class AdminDash extends Component
{
    public function render()
    {
        return view('livewire.admin-dash')->extends('layouts/master')->section('content');;
    }
}
