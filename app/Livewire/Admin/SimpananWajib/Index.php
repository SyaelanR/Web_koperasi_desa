<?php

namespace App\Livewire\Admin\SimpananWajib;

use App\Models\Tagihan;
use Livewire\Component;

class Index extends Component
{
    public $tagihans;
    public $showModal = false;
    public $periode;
    public $nominal_default = 12000;

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $this->loadTagihans();
    }

    public function loadTagihans()
    {
        $this->tagihans = Tagihan::orderBy('created_at', 'desc')->get();
    }

    public function createTagihan()
    {
        $this->validate([
            'periode' => 'required|string|max:255',
            'nominal_default' => 'required|numeric|min:0',
        ]);

        $tagihan = Tagihan::create([
            'periode' => $this->periode,
            'nominal_default' => $this->nominal_default,
            'status' => 'draft',
        ]);
        
        // Notify all active members
        $members = \App\Models\User::where('role', 'member')->where('status', 'active')->get();
        foreach ($members as $member) {
            $member->notify(new \App\Notifications\TagihanSimpananWajibNotification($tagihan));
        }

        session()->flash('message', 'Tagihan bulan '.$this->periode.' berhasil dibuat.');
        $this->showModal = false;
        $this->reset(['periode', 'nominal_default']);
        $this->nominal_default = 12000;
        $this->loadTagihans();
    }

    public function render()
    {
        return view('livewire.admin.simpanan-wajib.index')
            ->layout('layouts.app');
    }
}
