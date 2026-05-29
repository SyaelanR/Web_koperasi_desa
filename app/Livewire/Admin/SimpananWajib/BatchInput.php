<?php

namespace App\Livewire\Admin\SimpananWajib;

use App\Models\User;
use App\Models\Tagihan;
use App\Models\Simpanan;
use App\Models\BukuKas;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class BatchInput extends Component
{
    public $tagihan;
    public $rows = [];
    public $availableUsers = [];

    public function mount($tagihan_id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $this->tagihan = Tagihan::findOrFail($tagihan_id);

        $paidUserIds = Simpanan::where('tagihan_id', $this->tagihan->id)->pluck('user_id')->toArray();
        $this->availableUsers = User::where('role', 'member')->where('status', 'active')->get();

        $unpaidUsers = $this->availableUsers->whereNotIn('id', $paidUserIds);

        if ($unpaidUsers->isEmpty()) {
            session()->flash('message', 'Semua anggota koperasi sudah membayar tagihan bulan ini.');
            return redirect()->route('admin.simpanan-wajib.show', $this->tagihan->id);
        }

        // Populate default rows for all active unpaid members
        foreach ($unpaidUsers as $user) {
            $this->rows[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'nominal' => $this->tagihan->nominal_default,
            ];
        }
    }

    public function addRow()
    {
        $this->rows[] = [
            'user_id' => '',
            'user_name' => '',
            'nominal' => $this->tagihan->nominal_default,
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function saveAll()
    {
        // Validasi sederhana
        $this->validate([
            'rows.*.user_id' => 'required',
            'rows.*.nominal' => 'required|numeric|min:0',
        ], [
            'rows.*.user_id.required' => 'Anggota harus dipilih.',
            'rows.*.nominal.required' => 'Nominal harus diisi.',
            'rows.*.nominal.numeric' => 'Nominal harus berupa angka.',
        ]);

        DB::transaction(function () {
            foreach ($this->rows as $row) {
                if ($row['nominal'] > 0) {
                    $user = User::find($row['user_id']);
                    
                    $simpanan = Simpanan::create([
                        'user_id' => $row['user_id'],
                        'tagihan_id' => $this->tagihan->id,
                        'tanggal' => now(),
                        'jenis' => 'wajib',
                        'nominal' => $row['nominal'],
                        'keterangan' => 'Simpanan Wajib ' . $this->tagihan->periode,
                    ]);

                    BukuKas::create([
                        'tanggal' => now(),
                        'keterangan' => 'Simpanan Wajib (' . $this->tagihan->periode . ') - ' . ($user ? $user->name : 'Anonim'),
                        'jenis' => 'pemasukan',
                        'kategori' => 'simpanan',
                        'nominal' => $row['nominal'],
                    ]);
                    
                    if ($user) {
                        $user->notify(new \App\Notifications\PembayaranSimpananWajibNotification($simpanan));
                    }
                }
            }

            $this->tagihan->update(['status' => 'completed']);
        });

        session()->flash('message', 'Semua pembayaran simpanan wajib berhasil disimpan!');
        return redirect()->route('admin.simpanan-wajib.index');
    }

    public function getAvailableUsersForDropdownProperty()
    {
        $selectedIds = collect($this->rows)->pluck('user_id')->filter()->toArray();
        return collect($this->availableUsers)->whereNotIn('id', $selectedIds);
    }

    public function render()
    {
        return view('livewire.admin.simpanan-wajib.batch-input')
            ->layout('layouts.app');
    }
}
