import os
import re

filepath = 'app/Notifications/PinjamanLunasNotification.php'
with open(filepath, 'r') as f:
    content = f.read()

content = re.sub(r'(use Queueable;\s*)', r'\1\n    public $pinjaman;\n', content)
content = re.sub(r'public function __construct\(\)\s*\{[^}]*\}', 'public function __construct($pinjaman)\n    {\n        $this->pinjaman = $pinjaman;\n    }', content)
content = re.sub(r'return\s+\[\s*//\s*\];', "return ['title' => 'Pinjaman Lunas!', 'message' => 'Selamat, seluruh tagihan pinjaman Anda telah lunas.', 'url' => route('member.pinjaman.progress')];", content)

with open(filepath, 'w') as f:
    f.write(content)
