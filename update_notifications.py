import os
import re

notifications = {
    'NewRegistrationNotification.php': {
        'props': 'public $user;',
        'construct': '$this->user = $user;',
        'array': "return ['title' => 'Pendaftaran Baru', 'message' => $this->user->name . ' mendaftar sebagai anggota baru.', 'url' => route('admin.anggota')];"
    },
    'NewPinjamanNotification.php': {
        'props': 'public $pinjaman; public $userName;',
        'construct': '$this->pinjaman = $pinjaman; $this->userName = $userName;',
        'array': "return ['title' => 'Pengajuan Pinjaman Baru', 'message' => $this->userName . ' mengajukan pinjaman sebesar Rp ' . number_format($this->pinjaman->nominal_pinjam, 0, ',', '.'), 'url' => route('admin.pinjaman.show', $this->pinjaman->id)];"
    },
    'TagihanSimpananWajibNotification.php': {
        'props': 'public $tagihan;',
        'construct': '$this->tagihan = $tagihan;',
        'array': "return ['title' => 'Tagihan Simpanan Wajib', 'message' => 'Tagihan bulan ' . $this->tagihan->periode . ' telah terbit sebesar Rp ' . number_format($this->tagihan->nominal_default, 0, ',', '.'), 'url' => route('member.simpanan')];"
    },
    'PinjamanDiprosesNotification.php': {
        'props': 'public $pinjaman;',
        'construct': '$this->pinjaman = $pinjaman;',
        'array': "return ['title' => 'Pinjaman Diproses', 'message' => 'Pengajuan pinjaman Anda sebesar Rp ' . number_format($this->pinjaman->nominal_pinjam, 0, ',', '.') . ' sedang diproses admin.', 'url' => route('member.pinjaman.progress')];"
    },
    'PinjamanDiterimaNotification.php': {
        'props': 'public $pinjaman;',
        'construct': '$this->pinjaman = $pinjaman;',
        'array': "return ['title' => 'Pinjaman Disetujui', 'message' => 'Selamat! Pengajuan pinjaman Anda telah disetujui.', 'url' => route('member.pinjaman.progress')];"
    },
    'PinjamanLunasNotification.php': {
        'props': 'public $pinjaman;',
        'construct': '$this->pinjaman = $pinjaman;',
        'array': "return ['title' => 'Pinjaman Lunas!', 'message' => 'Selamat, seluruh tagihan pinjaman Anda telah lunas.', 'url' => route('member.pinjaman.progress')];"
    },
    'CicilanJatuhTempoNotification.php': {
        'props': 'public $angsuran; public $days;',
        'construct': '$this->angsuran = $angsuran; $this->days = $days;',
        'array': "return ['title' => 'Peringatan Jatuh Tempo', 'message' => 'Cicilan ke-' . $this->angsuran->angsuran_ke . ' Anda akan jatuh tempo dalam ' . $this->days . ' hari.', 'url' => route('member.pinjaman.progress')];"
    },
    'PembayaranCicilanNotification.php': {
        'props': 'public $angsuran;',
        'construct': '$this->angsuran = $angsuran;',
        'array': "return ['title' => 'Pembayaran Cicilan Berhasil', 'message' => 'Terima kasih, pembayaran cicilan ke-' . $this->angsuran->angsuran_ke . ' telah kami terima.', 'url' => route('member.pinjaman.progress')];"
    },
    'PembayaranSimpananWajibNotification.php': {
        'props': 'public $simpanan;',
        'construct': '$this->simpanan = $simpanan;',
        'array': "return ['title' => 'Setoran Simpanan Wajib Diterima', 'message' => 'Setoran wajib Anda sebesar Rp ' . number_format($this->simpanan->nominal, 0, ',', '.') . ' telah dicatat.', 'url' => route('member.simpanan')];"
    },
    'PembayaranSimpananLainnyaNotification.php': {
        'props': 'public $simpanan;',
        'construct': '$this->simpanan = $simpanan;',
        'array': "return ['title' => 'Setoran ' . ucfirst($this->simpanan->jenis) . ' Diterima', 'message' => 'Setoran Anda sebesar Rp ' . number_format($this->simpanan->nominal, 0, ',', '.') . ' telah dicatat.', 'url' => route('member.simpanan')];"
    }
}

dir_path = 'app/Notifications'

for filename, config in notifications.items():
    filepath = os.path.join(dir_path, filename)
    if not os.path.exists(filepath): continue
    
    with open(filepath, 'r') as f:
        content = f.read()

    # Change mail to database
    content = content.replace("['mail']", "['database']")

    # Insert props
    content = re.sub(r'(use Queueable;\s*)', r'\1\n    ' + config['props'] + '\n', content)
    
    # Replace construct
    vars_matches = re.findall(r'\$(\w+)', config['props'])
    args = ', '.join(['$' + v for v in vars_matches])
    new_construct = f"public function __construct({args})\n    {{\n        {config['construct']}\n    }}"
    content = re.sub(r'public function __construct\(\)\s*\{[^}]*\}', new_construct, content)
    
    # Replace toArray return
    content = re.sub(r'return\s+\[\s*//\s*\];', config['array'], content)
    
    with open(filepath, 'w') as f:
        f.write(content)

print("Notifications updated safely!")
