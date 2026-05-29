<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\BukuKas;
use Livewire\Component;

class Approval extends Component
{
    public $users;
    public $selectedUser = null;
    public $simpananPokok = 10000;
    public $showModal = false;

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::where('status', 'pending')->get();
    }

    public function openModal($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->simpananPokok = 10000;
        $this->showModal = true;
    }

    public function approve()
    {
        $this->validate([
            'simpananPokok' => 'required|numeric|min:0',
        ]);

        if ($this->selectedUser) {
            $this->selectedUser->update(['status' => 'active']);

            if ($this->simpananPokok > 0) {
                Simpanan::create([
                    'user_id' => $this->selectedUser->id,
                    'tanggal' => now(),
                    'jenis' => 'pokok',
                    'nominal' => $this->simpananPokok,
                    'keterangan' => 'Simpanan Pokok Pendaftaran',
                ]);

                BukuKas::create([
                    'tanggal' => now(),
                    'keterangan' => 'Simpanan Pokok: ' . $this->selectedUser->name,
                    'jenis' => 'pemasukan',
                    'kategori' => 'simpanan',
                    'nominal' => $this->simpananPokok,
                ]);
            }

            session()->flash('message', 'Anggota berhasil disetujui.');
            $this->showModal = false;
            $this->selectedUser = null;
            $this->loadUsers();
        }
    }

    public function render()
    {
        return view('livewire.admin.approval')
            ->layout('layouts.app');
    }
}
