<?php

namespace App\Livewire\Admin\SimpananSukarela;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\BukuKas;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class BatchInput extends Component
{
    public $tanggal;
    public $rows = [];
    public $availableUsers = [];

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $this->tanggal = now()->format('Y-m-d');
        $this->availableUsers = User::where('role', 'member')->where('status', 'active')->get();

        foreach ($this->availableUsers as $user) {
            $this->rows[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'nominal' => 0, // Default 0 for sukarela
            ];
        }
    }

    public function addRow()
    {
        $this->rows[] = [
            'user_id' => '',
            'user_name' => '',
            'nominal' => 0,
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function saveAll()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'rows.*.user_id' => 'required',
            'rows.*.nominal' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            foreach ($this->rows as $row) {
                if ($row['nominal'] > 0) {
                    $user = User::find($row['user_id']);
                    
                    $simpanan = Simpanan::create([
                        'user_id' => $row['user_id'],
                        'tanggal' => $this->tanggal,
                        'jenis' => 'sukarela',
                        'nominal' => $row['nominal'],
                        'keterangan' => 'Simpanan Sukarela',
                    ]);

                    BukuKas::create([
                        'tanggal' => $this->tanggal,
                        'keterangan' => 'Simpanan Sukarela - ' . ($user ? $user->name : 'Anonim'),
                        'jenis' => 'pemasukan',
                        'kategori' => 'simpanan',
                        'nominal' => $row['nominal'],
                    ]);
                    
                    if ($user) {
                        $user->notify(new \App\Notifications\PembayaranSimpananLainnyaNotification($simpanan));
                    }
                }
            }
        });

        session()->flash('message', 'Pemasukan Simpanan Sukarela berhasil disimpan!');
        return redirect()->route('admin.simpanan-sukarela.input');
    }

    public function getAvailableUsersForDropdownProperty()
    {
        $selectedIds = collect($this->rows)->pluck('user_id')->filter()->toArray();
        return collect($this->availableUsers)->whereNotIn('id', $selectedIds);
    }

    public function render()
    {
        return view('livewire.admin.simpanan-sukarela.batch-input')
            ->layout('layouts.app');
    }
}
