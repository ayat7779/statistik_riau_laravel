import re

with open('stat_provinsi_riau.sql', 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Remove MySQL specific comments and commands
content = re.sub(r'/\*.*?\*/;', '', content, flags=re.DOTALL)
content = re.sub(r'SET .*?;', '', content)
content = re.sub(r'START TRANSACTION;', 'BEGIN;', content)
content = re.sub(r'COMMIT;', 'COMMIT;', content)

# Replace backticks with double quotes
content = content.replace('`', '"')

# Replace MySQL datatypes with PostgreSQL equivalents
content = re.sub(r'int\(\d+\) NOT NULL AUTO_INCREMENT', 'INTEGER NOT NULL', content, flags=re.IGNORECASE)
content = re.sub(r'int\(\d+\) DEFAULT NULL', 'INTEGER DEFAULT NULL', content, flags=re.IGNORECASE)
content = re.sub(r'int\(\d+\)', 'INTEGER', content, flags=re.IGNORECASE)
content = re.sub(r'datetime', 'TIMESTAMP', content, flags=re.IGNORECASE)
content = re.sub(r'longtext', 'TEXT', content, flags=re.IGNORECASE)
content = re.sub(r'mediumtext', 'TEXT', content, flags=re.IGNORECASE)
content = re.sub(r'text', 'TEXT', content, flags=re.IGNORECASE)
content = re.sub(r'varchar\((\d+)\)', r'VARCHAR(\1)', content, flags=re.IGNORECASE)

# Remove ENGINE=InnoDB ...
content = re.sub(r'ENGINE=InnoDB[^\;]*;', ';', content, flags=re.IGNORECASE)

# Remove MySQL ALTER TABLE ... MODIFY ... AUTO_INCREMENT statements
content = re.sub(r'ALTER TABLE\s+"[^"]+"\s+MODIFY\s+[^;]+;', '', content, flags=re.IGNORECASE)

# Add DROP TABLE IF EXISTS before CREATE TABLE
def add_drop(match):
    table_name = match.group(1)
    return f'DROP TABLE IF EXISTS "{table_name}" CASCADE;\nCREATE TABLE "{table_name}"'

content = re.sub(r'CREATE TABLE\s+"([^"]+)"', add_drop, content)

# Remove view creation parts (from -- Struktur untuk view onwards)
if '-- Struktur untuk view' in content:
    parts = content.split('-- Struktur untuk view')
    content = parts[0] + "\nCOMMIT;\n"

# Fix escaped quotes
content = content.replace(r"\'", "''")

with open('stat_provinsi_riau_postgres.sql', 'w', encoding='utf-8') as f:
    f.write(content)

print("Conversion complete with DROP TABLE statements.")
