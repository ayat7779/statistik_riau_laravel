<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Manajemen Statistik Riau' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('templates/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8fafc;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        h1 {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        h1::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 28px;
            background: #2563eb;
            border-radius: 3px;
        }
        .main-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
        }
        .menu-section h3 {
            font-size: 15px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .menu-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .menu-section ul li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: #ffffff;
            color: #334155;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .menu-section ul li a:hover {
            background: #2563eb;
            color: #ffffff;
            transform: translateX(4px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        th {
            background: #1e293b;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }
        tr:hover {
            background: #f8fafc;
        }
        form select, form input {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        form select:focus, form input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .message-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            border-left: 4px solid #22c55e;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manajemen Data Statistik Riau</h1>

        <nav class="main-menu">
            <div class="menu-section">
                <h3><i class="fas fa-cog"></i> Pengaturan & Referensi</h3>
                <ul>
                    <li><a href="{{ route('kode-urut.index') }}"><i class="fas fa-list-ol"></i> Kode Urut PMDN77 APBD</a></li>
                    <li><a href="{{ route('kode-urut-13.index') }}"><i class="fas fa-list-ol"></i> Kode Urut PMDN13 APBD</a></li>
                    <li><a href="{{ route('kode-catatan.index') }}"><i class="fas fa-sticky-note"></i> Kode Catatan LKPD APBD</a></li>
                    <li><a href="{{ route('jenis-apbd.index') }}"><i class="fas fa-tags"></i> Jenis APBD</a></li>
                    <li><a href="{{ route('level-kode.index') }}"><i class="fas fa-layer-group"></i> Level Kode</a></li>
                </ul>
            </div>

            <div class="menu-section">
                <h3><i class="fas fa-paperclip"></i> Lampiran Dokumen</h3>
                <ul>
                    <li><a href="{{ route('apbd-lampiran-1.index') }}"><i class="fas fa-file-invoice-dollar"></i> Lampiran 1 - APBD</a></li>
                    <li><a href="{{ route('input-apbd.index') }}"><i class="fas fa-edit"></i> Input Data APBD</a></li>
                    <li><a href="{{ route('lkpd-apbd') }}"><i class="fas fa-file-alt"></i> Lampiran 1 - LKPD</a></li>
                    <li><a href="{{ route('input-lkpd.index') }}"><i class="fas fa-edit"></i> Input Data LKPD</a></li>
                </ul>
            </div>

            <div class="menu-section">
                <h3><i class="fas fa-database"></i> Data Utama</h3>
                <ul>
                    <li><a href="{{ url('/') }}"><i class="fas fa-chart-bar"></i> Data APBD</a></li>
                    <li><a href="{{ url('/lkpd-apbd') }}"><i class="fas fa-chart-line"></i> Data LKPD APBD</a></li>
                    <li><a href="{{ url('/kinerja-keuangan') }}"><i class="fas fa-chart-line"></i> KINERJA KEUANGAN</a></li>
                    <li><a href="{{ url('/kinerja-keuangan-detail') }}"><i class="fas fa-chart-line"></i> DETAIL KINERJA KEUANGAN</a></li>
                </ul>
            </div>
        </nav>

        <div class="content">
            @yield('content')
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} Pemerintah Provinsi Riau - Aplikasi Manajemen Data Statistik</p>
        </footer>
    </div>
</body>
</html>
