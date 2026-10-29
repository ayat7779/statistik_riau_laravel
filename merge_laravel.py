import os
import shutil

src = 'laravel_temp'
dst = '.'

for item in os.listdir(src):
    s_path = os.path.join(src, item)
    d_path = os.path.join(dst, item)
    if os.path.isdir(s_path):
        if os.path.exists(d_path):
            # If directory exists (like config), we can copy files inside or merge
            if item == 'config':
                # Don't overwrite existing config completely or merge
                for subitem in os.listdir(s_path):
                    sub_s = os.path.join(s_path, subitem)
                    sub_d = os.path.join(d_path, subitem)
                    if not os.path.exists(sub_d):
                        shutil.copy2(sub_s, sub_d) if os.path.isfile(sub_s) else shutil.copytree(sub_s, sub_d)
            else:
                shutil.rmtree(d_path)
                shutil.copytree(s_path, d_path)
        else:
            shutil.copytree(s_path, d_path)
    else:
        shutil.copy2(s_path, d_path)

# Remove laravel_temp directory
shutil.rmtree(src)
print("Laravel files merged into root directory successfully.")
