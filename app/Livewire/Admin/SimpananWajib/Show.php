<?php

namespace App\Livewire\Admin\SimpananWajib;

use App\Models\Tagihan;
use App\Models\Simpanan;
use Livewire\Component;

class Show extends Component
{
    public $tagihan;
    public $simpanans;

    public $editModal = false;
    public $deleteModal = false;
    
    public $selectedSimpananId;
    public $editNominal;

    public function mount($tagihan_id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        $this->tagihan = Tagihan::findOrFail($tagihan_id);
        $this->loadSimpanans();
    }

    public function loadSimpanans()
    {
        $this->simpanans = Simpanan::with('user')->where('tagihan_id', $this->tagihan->id)->get();
    }

    public function openEditModal($id, $nominal)
    {
        $this->selectedSimpananId = $id;
        $this->editNominal = $nominal;
        $this->editModal = true;
    }

    public function updateNominal()
    {
        $this->validate([
            'editNominal' => 'required|numeric|min:0',
        ]);

        $simpanan = Simpanan::find($this->selectedSimpananId);
        if ($simpanan) {
            $simpanan->update(['nominal' => $this->editNominal]);
            session()->flash('message', 'Nominal berhasil diperbarui.');
        }

        $this->editModal = false;
        $this->loadSimpanans();
    }

    public function openDeleteModal($id)
    {
        $this->selectedSimpananId = $id;
        $this->deleteModal = true;
    }

    public function deleteSimpanan()
    {
        $simpanan = Simpanan::find($this->selectedSimpananId);
        if ($simpanan) {
            $simpanan->delete();
            session()->flash('message', 'Data pembayaran berhasil dihapus.');
        }

        $this->deleteModal = false;
        $this->loadSimpanans();
        
        if (Simpanan::where('tagihan_id', $this->tagihan->id)->count() === 0) {
            $this->tagihan->update(['status' => 'draft']);
        }
    }

    public function render()
    {
        return view('livewire.admin.simpanan-wajib.show')
            ->layout('layouts.app');
    }
}
