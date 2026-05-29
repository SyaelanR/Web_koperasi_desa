<?php

namespace App\Livewire\Admin\Anggota;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public function updateStatus($userId, $status)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        $user = User::findOrFail($userId);
        $user->update(['status' => $status]);
        
        session()->flash('message', "Status keanggotaan {$user->name} berhasil diubah menjadi {$status}.");
    }

    public function render()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $anggota = User::where('role', 'member')->orderBy('created_at', 'desc')->get();
        return view('livewire.admin.anggota.index', compact('anggota'))
            ->layout('layouts.app');
    }
}
