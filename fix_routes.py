import os

dir_path = 'app/Notifications'
for filename in os.listdir(dir_path):
    if not filename.endswith('Notification.php'): continue
    filepath = os.path.join(dir_path, filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    content = content.replace("route('member.simpanan')", "route('member.simpanan.index')")
    content = content.replace("route('admin.anggota')", "route('admin.anggota.index')")
    
    with open(filepath, 'w') as f:
        f.write(content)
print("Routes fixed")
