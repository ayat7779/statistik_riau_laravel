<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApbdLampiran1;

class StatistikController extends Controller
{
    public function apbd(Request $request)
    {
        $apbd_years = ApbdLampiran1::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $lkpd_years = DB::table('lkpd_apbd_lampiran_1')->distinct()->orderBy('tahun_lkpd', 'desc')->pluck('tahun_lkpd');

        $selected_year = (int)$request->get('tahun', ($apbd_years->isNotEmpty() ? $apbd_years->first() : date('Y')));

        // --- 1. APBD KPI Metrics for $selected_year ---
        $apbd_kpi = DB::selectOne("
            SELECT
                SUM(CASE WHEN LEFT(b.no_urut,2) = '4.' THEN a.jumlah_anggaran ELSE 0 END) as pendapatan_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,2) = '4.' THEN a.jumlah_perubahan ELSE 0 END) as pendapatan_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '4.1.' THEN a.jumlah_anggaran ELSE 0 END) as pad_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '4.1.' THEN a.jumlah_perubahan ELSE 0 END) as pad_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '4.2.' THEN a.jumlah_anggaran ELSE 0 END) as transfer_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '4.2.' THEN a.jumlah_perubahan ELSE 0 END) as transfer_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,2) = '5.' THEN a.jumlah_anggaran ELSE 0 END) as belanja_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,2) = '5.' THEN a.jumlah_perubahan ELSE 0 END) as belanja_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '5.1.' THEN a.jumlah_anggaran ELSE 0 END) as belanja_operasi_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '5.1.' THEN a.jumlah_perubahan ELSE 0 END) as belanja_operasi_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '5.2.' THEN a.jumlah_anggaran ELSE 0 END) as belanja_modal_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '5.2.' THEN a.jumlah_perubahan ELSE 0 END) as belanja_modal_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '6.1.' THEN a.jumlah_anggaran ELSE 0 END) as pembiayaan_masuk_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '6.1.' THEN a.jumlah_perubahan ELSE 0 END) as pembiayaan_masuk_perubahan,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '6.2.' THEN a.jumlah_anggaran ELSE 0 END) as pembiayaan_keluar_anggaran,
                SUM(CASE WHEN LEFT(b.no_urut,4) = '6.2.' THEN a.jumlah_perubahan ELSE 0 END) as pembiayaan_keluar_perubahan
            FROM apbd_lampiran_1 a
            JOIN kode_urut b ON a.id_kode_urut = b.id
            WHERE a.tahun = ?
        ", [$selected_year]);

        // --- 2. LKPD KPI Metrics for $selected_year ---
        $lkpd_kpi = DB::selectOne("
            SELECT
                SUM(CASE WHEN LEFT(kc.kode_catatan,2) = '1.' THEN la.jumlah_anggaran ELSE 0 END) as pendapatan_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,2) = '1.' THEN la.jumlah_realisasi ELSE 0 END) as pendapatan_realisasi,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '1.1.' THEN la.jumlah_anggaran ELSE 0 END) as pad_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '1.1.' THEN la.jumlah_realisasi ELSE 0 END) as pad_realisasi,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '1.2.' THEN la.jumlah_anggaran ELSE 0 END) as transfer_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '1.2.' THEN la.jumlah_realisasi ELSE 0 END) as transfer_realisasi,
                SUM(CASE WHEN LEFT(kc.kode_catatan,2) IN ('2.','3.') THEN la.jumlah_anggaran ELSE 0 END) as belanja_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,2) IN ('2.','3.') THEN la.jumlah_realisasi ELSE 0 END) as belanja_realisasi,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '2.1.' THEN la.jumlah_anggaran ELSE 0 END) as belanja_operasi_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '2.1.' THEN la.jumlah_realisasi ELSE 0 END) as belanja_operasi_realisasi,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '2.2.' THEN la.jumlah_anggaran ELSE 0 END) as belanja_modal_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '2.2.' THEN la.jumlah_realisasi ELSE 0 END) as belanja_modal_realisasi,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '4.1.' THEN la.jumlah_anggaran ELSE 0 END) as pembiayaan_masuk_anggaran,
                SUM(CASE WHEN LEFT(kc.kode_catatan,4) = '4.1.' THEN la.jumlah_realisasi ELSE 0 END) as pembiayaan_masuk_realisasi
            FROM lkpd_apbd_lampiran_1 la
            JOIN kode_catatan kc ON la.id_kode_catatan = kc.id
            WHERE la.tahun_lkpd = ?
        ", [$selected_year]);

        // --- 3. Financial Ratios (Kinerja Keuangan) for $selected_year ---
        $ratios = DB::selectOne("
            SELECT
                ROUND(((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,2)= '1.') THEN la.jumlah_realisasi ELSE 0 END),0))*100)::numeric, 2) AS desentralisasi,
                ROUND((((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.2.') THEN la.jumlah_realisasi ELSE 0 END)+SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.3.') THEN la.jumlah_realisasi ELSE 0 END))/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,2)= '1.') THEN la.jumlah_realisasi ELSE 0 END),0))*100)::numeric, 2) AS ketergantungan,
                ROUND(((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.2.') THEN la.jumlah_realisasi ELSE 0 END)+SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.3.') THEN la.jumlah_realisasi ELSE 0 END)),0))*100)::numeric, 2) AS kemandirian,
                ROUND(((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_anggaran ELSE 0 END),0))*100)::numeric, 2) AS efektivitas_pad
            FROM lkpd_apbd_lampiran_1 la
            JOIN kode_catatan kc ON la.id_kode_catatan=kc.id
            WHERE la.tahun_lkpd = ?
        ", [$selected_year]);

        // --- 4. Multi-year Trends for Charts ---
        $trend_apbd = DB::select("
            SELECT
                a.tahun,
                ROUND(SUM(CASE WHEN LEFT(b.no_urut,2) = '4.' THEN a.jumlah_anggaran ELSE 0 END) / 1000000000000.0, 2) as pendapatan_anggaran,
                ROUND(SUM(CASE WHEN LEFT(b.no_urut,2) = '4.' THEN a.jumlah_perubahan ELSE 0 END) / 1000000000000.0, 2) as pendapatan_perubahan,
                ROUND(SUM(CASE WHEN LEFT(b.no_urut,2) = '5.' THEN a.jumlah_anggaran ELSE 0 END) / 1000000000000.0, 2) as belanja_anggaran,
                ROUND(SUM(CASE WHEN LEFT(b.no_urut,2) = '5.' THEN a.jumlah_perubahan ELSE 0 END) / 1000000000000.0, 2) as belanja_perubahan
            FROM apbd_lampiran_1 a
            JOIN kode_urut b ON a.id_kode_urut = b.id
            GROUP BY a.tahun
            ORDER BY a.tahun ASC
        ");

        $trend_lkpd = DB::select("
            SELECT
                la.tahun_lkpd as tahun,
                ROUND(SUM(CASE WHEN LEFT(kc.kode_catatan,2) = '1.' THEN la.jumlah_anggaran ELSE 0 END) / 1000000000000.0, 2) as pendapatan_anggaran,
                ROUND(SUM(CASE WHEN LEFT(kc.kode_catatan,2) = '1.' THEN la.jumlah_realisasi ELSE 0 END) / 1000000000000.0, 2) as pendapatan_realisasi,
                ROUND(SUM(CASE WHEN LEFT(kc.kode_catatan,2) IN ('2.','3.') THEN la.jumlah_anggaran ELSE 0 END) / 1000000000000.0, 2) as belanja_anggaran,
                ROUND(SUM(CASE WHEN LEFT(kc.kode_catatan,2) IN ('2.','3.') THEN la.jumlah_realisasi ELSE 0 END) / 1000000000000.0, 2) as belanja_realisasi
            FROM lkpd_apbd_lampiran_1 la
            JOIN kode_catatan kc ON la.id_kode_catatan = kc.id
            GROUP BY la.tahun_lkpd
            ORDER BY la.tahun_lkpd ASC
        ");

        // Available years union for filter
        $available_years = $apbd_years->concat($lkpd_years)->unique()->sortDesc()->values();

        $sql = "
        SELECT tahun, kode, b.uraian AS uraian, anggaran, perubahan,
            COALESCE(ROUND((perubahan - anggaran)::numeric, 2), 0) AS bertambah_berkurang,
            COALESCE(ROUND((((perubahan - anggaran) / NULLIF(anggaran, 0)) * 100)::numeric, 2), 0) AS persentase,
            c.akronim AS jenis_apbd,
            b.id_kode_level, nama_level
        FROM (
            -- AKUN
            SELECT tahun, LEFT(no_urut,2) AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_perubahan) AS perubahan, id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, kode, id_jenis_apbd
            UNION ALL
            -- KELOMPOK
            SELECT tahun, LEFT(no_urut,4) AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_perubahan) AS perubahan, id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, kode, id_jenis_apbd
            UNION ALL
            -- JENIS
            SELECT tahun, no_urut AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_perubahan) AS perubahan, id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, no_urut, id_jenis_apbd
            UNION ALL
            -- jumlah pendapatan
            SELECT tahun, '4.3333' AS kode,
            SUM(CASE WHEN (LEFT(b.no_urut,2)= '4.') THEN a.jumlah_anggaran ELSE 0 END) AS anggaran,
            SUM(CASE WHEN (LEFT(b.no_urut,2)= '4.') THEN a.jumlah_perubahan ELSE 0 END) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
            UNION ALL
            -- jumlah belanja
            SELECT tahun, '5.4444' AS kode,
            SUM(CASE WHEN (LEFT(b.no_urut,2)= '5.') THEN a.jumlah_anggaran ELSE 0 END) AS anggaran,
            SUM(CASE WHEN (LEFT(b.no_urut,2)= '5.') THEN a.jumlah_perubahan ELSE 0 END) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
            UNION ALL
            -- surplus defisit
            SELECT tahun, '5.5555' AS kode,
            SUM(CASE WHEN (LEFT(b.no_urut,2)= '4.') THEN a.jumlah_anggaran ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,2)= '5.') THEN a.jumlah_anggaran ELSE 0 END)AS anggaran,
            SUM(CASE WHEN (LEFT(b.no_urut,2)= '4.') THEN a.jumlah_perubahan ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,2)= '5.') THEN a.jumlah_perubahan ELSE 0 END) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
            UNION ALL
            -- jumlah penerimaan pembiayaan
            SELECT tahun, '6.1111' AS kode,
            SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.1.') THEN a.jumlah_anggaran ELSE 0 END) AS anggaran,
            SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.1.') THEN a.jumlah_perubahan ELSE 0 END) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
            UNION ALL
            -- pembiayaan pengeluaran pembiayaan
            SELECT tahun, '6.2222' AS kode,
            SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.2.') THEN a.jumlah_anggaran ELSE 0 END)AS anggaran,
            SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.2.') THEN a.jumlah_perubahan ELSE 0 END) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
            UNION ALL
            -- pembiayaan neto
            SELECT tahun, '6.2.9' AS kode,
            SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.1.') THEN a.jumlah_anggaran ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.2.') THEN a.jumlah_anggaran ELSE 0 END)AS anggaran,
            SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.1.') THEN a.jumlah_perubahan ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.2.') THEN a.jumlah_perubahan ELSE 0 END) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
            UNION ALL
            -- SILPA
            SELECT tahun, '6.3.' AS kode,
            (SUM(CASE WHEN (LEFT(b.no_urut,2)= '4.') THEN a.jumlah_anggaran ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,2)= '5.') THEN a.jumlah_anggaran ELSE 0 END))+
            (SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.1.') THEN a.jumlah_anggaran ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.2.') THEN a.jumlah_anggaran ELSE 0 END)) AS anggaran,
            (SUM(CASE WHEN (LEFT(b.no_urut,2)= '4.') THEN a.jumlah_perubahan ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,2)= '5.') THEN a.jumlah_perubahan ELSE 0 END))+
            (SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.1.') THEN a.jumlah_perubahan ELSE 0 END)
            -SUM(CASE WHEN (LEFT(b.no_urut,4)= '6.2.') THEN a.jumlah_perubahan ELSE 0 END)) AS perubahan,
            id_jenis_apbd
            FROM apbd_lampiran_1 AS a
            INNER JOIN kode_urut AS b ON a.id_kode_urut=b.id
            INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd=c.id
            GROUP BY tahun, id_jenis_apbd
        ) AS a
        INNER JOIN kode_urut AS b ON a.kode = b.no_urut
        INNER JOIN jenis_apbd AS c ON a.id_jenis_apbd = c.id
        LEFT JOIN kode_level AS d ON b.id_kode_level = d.id
        WHERE b.id_kode_level IN (1, 2, 3)
        ";

        $bindings = [];
        if ($selected_year !== '') {
            $sql .= " AND tahun = ?";
            $bindings[] = $selected_year;
        }
        $sql .= " ORDER BY tahun DESC, kode ASC";

        $result = DB::select($sql, $bindings);

        return view('apbd', [
            'pageTitle' => 'Dashboard Analitik APBD & LKPD - Manajemen Statistik Riau',
            'result' => $result,
            'available_years' => $available_years,
            'selected_year' => $selected_year,
            'apbd_kpi' => $apbd_kpi,
            'lkpd_kpi' => $lkpd_kpi,
            'ratios' => $ratios,
            'trend_apbd' => $trend_apbd,
            'trend_lkpd' => $trend_lkpd,
        ]);
    }

    public function lkpdApbd(Request $request)
    {
        $years = DB::table('lkpd_apbd_lampiran_1')->distinct()->orderBy('tahun_lkpd', 'desc')->pluck('tahun_lkpd');

        $selected_year = $request->get('tahun_lkpd', '');
        if ($selected_year === '' && $years->isNotEmpty()) {
            $selected_year = $years->first();
        }

        $sql = "
        SELECT a.tahun_lkpd AS tahun, a.kode, kc.uraian, a.anggaran, a.realisasi,
        CASE WHEN a.anggaran = 0 THEN 0 ELSE (a.anggaran-a.realisasi) END AS sisa_anggaran,
        CASE WHEN a.anggaran = 0 THEN 0 ELSE (a.realisasi/NULLIF(a.anggaran,0))*100 END AS persentase,
        kl.nama_level, kc.id_kode_level
        FROM (
            SELECT tahun_lkpd, LEFT(kode_catatan,2) AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_realisasi) AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id
            GROUP BY tahun_lkpd, LEFT(kode_catatan,2)
            UNION ALL
            SELECT tahun_lkpd, LEFT(kode_catatan,4) AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_realisasi) AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id
            GROUP BY tahun_lkpd, LEFT(kode_catatan,4)
            UNION ALL
            SELECT tahun_lkpd, kode_catatan AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_realisasi) AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id
            GROUP BY tahun_lkpd, kode_catatan
        ) a
        INNER JOIN kode_catatan kc ON a.kode = kc.kode_catatan
        LEFT JOIN kode_level kl ON kc.id_kode_level = kl.id
        ";

        $bindings = [];
        if ($selected_year !== '') {
            $sql .= " WHERE a.tahun_lkpd = ?";
            $bindings[] = $selected_year;
        }
        $sql .= " ORDER BY a.tahun_lkpd DESC, a.kode ASC";

        $result = DB::select($sql, $bindings);

        return view('lkpd_apbd', [
            'pageTitle' => 'Lampiran 1 - LKPD',
            'result' => $result,
            'available_years' => $years,
            'selected_year' => $selected_year
        ]);
    }

    public function kinerjaKeuangan(Request $request)
    {
        $years = DB::table('lkpd_apbd_lampiran_1')->distinct()->orderBy('tahun_lkpd', 'desc')->pluck('tahun_lkpd');

        $selected_year = $request->get('tahun', '');
        if ($selected_year === '' && $years->isNotEmpty()) {
            $selected_year = $years->first();
        }
        $selected_kinerja = $request->get('kinerja', '');

        $available_kinerja = [
            'Derajat Desentralisasi',
            'Ketergantungan Keuangan',
            'Kemandirian Keuangan',
            'Efektifitas PAD'
        ];

        $sql = "
        SELECT a.tahun_lkpd AS tahun, a.kode AS kinerja, a.anggaran AS anggaran, a.realisasi AS realisasi
        FROM (
            SELECT tahun_lkpd, LEFT(kode_catatan,2) AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_realisasi) AS realisasi
                FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd, LEFT(kode_catatan,2)
            UNION ALL
            SELECT tahun_lkpd, LEFT(kode_catatan,4) AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_realisasi) AS realisasi
                FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd, LEFT(kode_catatan,4)
            UNION ALL
            SELECT tahun_lkpd, kode_catatan AS kode, SUM(jumlah_anggaran) AS anggaran, SUM(jumlah_realisasi) AS realisasi
                FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd, kode_catatan
            UNION ALL
            SELECT tahun_lkpd, 'Derajat Desentralisasi' AS kode,
            (SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_anggaran ELSE 0 END)/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,2)= '1.') THEN la.jumlah_anggaran ELSE 0 END),0))*100 AS anggaran,
            (SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,2)= '1.') THEN la.jumlah_realisasi ELSE 0 END),0))*100 AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd
            UNION ALL
            SELECT tahun_lkpd, 'Ketergantungan Keuangan' AS kode,
            ((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.2.') THEN la.jumlah_anggaran ELSE 0 END)+SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.3.') THEN la.jumlah_anggaran ELSE 0 END))/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,2)= '1.') THEN la.jumlah_anggaran ELSE 0 END),0))*100 AS anggaran,
            ((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.2.') THEN la.jumlah_realisasi ELSE 0 END)+SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.3.') THEN la.jumlah_realisasi ELSE 0 END))/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,2)= '1.') THEN la.jumlah_realisasi ELSE 0 END),0))*100 AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd
            UNION ALL
            SELECT tahun_lkpd, 'Kemandirian Keuangan' AS kode,
            (SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_anggaran ELSE 0 END)/NULLIF((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.2.') THEN la.jumlah_anggaran ELSE 0 END)+SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.3.') THEN la.jumlah_anggaran ELSE 0 END)),0))*100 AS anggaran,
            (SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF((SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.2.') THEN la.jumlah_realisasi ELSE 0 END)+SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.3.') THEN la.jumlah_realisasi ELSE 0 END)),0))*100 AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd
            UNION ALL
            SELECT tahun_lkpd, 'Efektifitas PAD' AS kode,
            (SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_anggaran ELSE 0 END),0))*100 AS anggaran,
            (SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_realisasi ELSE 0 END)/NULLIF(SUM(CASE WHEN (LEFT(kc.kode_catatan,4)= '1.1.') THEN la.jumlah_anggaran ELSE 0 END),0))*100 AS realisasi
            FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id GROUP BY tahun_lkpd
        ) AS a
        LEFT JOIN kode_catatan AS kc ON a.kode = kc.kode_catatan
        WHERE 1=1
        ";

        $bindings = [];
        if ($selected_kinerja !== '') {
            $sql .= " AND a.kode = ?";
            $bindings[] = $selected_kinerja;
        } else {
            $placeholders = implode(',', array_fill(0, count($available_kinerja), '?'));
            $sql .= " AND a.kode IN ($placeholders)";
            foreach ($available_kinerja as $k) {
                $bindings[] = $k;
            }
        }

        if ($selected_year !== '') {
            $sql .= " AND a.tahun_lkpd = ?";
            $bindings[] = (int) $selected_year;
        }

        $sql .= " ORDER BY a.tahun_lkpd DESC, a.kode ASC";

        $result = DB::select($sql, $bindings);
        $years = DB::table('lkpd_apbd_lampiran_1')->distinct()->orderBy('tahun_lkpd', 'desc')->pluck('tahun_lkpd');

        return view('kinerja_keuangan', [
            'pageTitle' => 'KINERJA KEUANGAN PROVINSI RIAU',
            'result' => $result,
            'available_years' => $years,
            'available_kinerja' => $available_kinerja,
            'selected_year' => $selected_year,
            'selected_kinerja' => $selected_kinerja
        ]);
    }

    public function kinerjaKeuanganDetail(Request $request)
    {
        $tahun_options = DB::table('lkpd_apbd_lampiran_1')
            ->distinct()
            ->orderBy('tahun_lkpd', 'asc')
            ->pluck('tahun_lkpd')
            ->toArray();

        $selected_indicator_key = $request->get('indikator', '');
        $selected_start_year = (int) $request->get('start_year', (!empty($tahun_options) ? min($tahun_options) : date('Y') - 5));
        $selected_end_year = (int) $request->get('end_year', (!empty($tahun_options) ? max($tahun_options) : date('Y')));
        $selected_value_type = $request->get('value_type', 'persentase');
        $selected_data_type = $request->get('data_type', 'real');

        if ($selected_start_year > $selected_end_year) {
            [$selected_start_year, $selected_end_year] = [$selected_end_year, $selected_start_year];
        }

        $indicators = [
            "desentralisasi" => [
                "title" => "Derajat Desentralisasi",
                "nominal_data_keys" => [
                    "pad_agr" => "PAD (Anggaran)",
                    "totalpendapatan_agr" => "Total Pendapatan (Anggaran)",
                    "pad_real" => "PAD (Realisasi)",
                    "totalpendapatan_real" => "Total Pendapatan (Realisasi)"
                ],
                "percentage_data_keys" => [
                    "persentase_a" => "Anggaran (%)",
                    "persentase_real" => "Realisasi (%)"
                ],
                "y_axis_label_nominal" => "Nilai (Triliun Rupiah)",
                "y_axis_label_percentage" => "Persentase (%)"
            ],
            "ketergantungan" => [
                "title" => "Ketergantungan Keuangan",
                "nominal_data_keys" => [
                    "transfer_agr" => "Transfer (Anggaran)",
                    "totalpendapatan_agr" => "Total Pendapatan (Anggaran)",
                    "transfer_real" => "Transfer (Realisasi)",
                    "totalpendapatan_real" => "Total Pendapatan (Realisasi)"
                ],
                "percentage_data_keys" => [
                    "persentase_a" => "Anggaran (%)",
                    "persentase_real" => "Realisasi (%)"
                ],
                "y_axis_label_nominal" => "Nilai (Triliun Rupiah)",
                "y_axis_label_percentage" => "Persentase (%)"
            ],
            "kemandirian" => [
                "title" => "Kemandirian Keuangan",
                "nominal_data_keys" => [
                    "pad_agr" => "PAD (Anggaran)",
                    "transfer_agr" => "Transfer (Anggaran)",
                    "pad_real" => "PAD (Realisasi)",
                    "transfer_real" => "Transfer (Realisasi)"
                ],
                "percentage_data_keys" => [
                    "persentase_a" => "Anggaran (%)",
                    "persentase_real" => "Realisasi (%)"
                ],
                "y_axis_label_nominal" => "Nilai (Triliun Rupiah)",
                "y_axis_label_percentage" => "Persentase (%)"
            ],
            "efektivitas" => [
                "title" => "Efektivitas PAD",
                "nominal_data_keys" => [
                    "pad_real" => "Realisasi PAD",
                    "pad_agr" => "Target PAD"
                ],
                "percentage_data_keys" => [
                    "persentase_efektif" => "Efektivitas PAD (%)"
                ],
                "y_axis_label_nominal" => "Nilai (Triliun Rupiah)",
                "y_axis_label_percentage" => "Persentase (%)"
            ]
        ];

        $report_title = "Pilih Indikator Keuangan Daerah";
        $chart_labels = [];
        $chart_datasets = [];
        $y_axis_label_for_chart = "";

        if ($selected_indicator_key && array_key_exists($selected_indicator_key, $indicators)) {
            $current_indicator = $indicators[$selected_indicator_key];
            $report_title = $current_indicator['title'];

            $select_raw_fields_array = [];
            $select_final_fields_array = ["tahun"];
            $temp_chart_keys = [];

            if ($selected_value_type === 'nominal') {
                foreach ($current_indicator['nominal_data_keys'] as $key => $label) {
                    $field_expression = "";
                    if ($key === 'pad_agr') {
                        $field_expression = "SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.1.' THEN la.jumlah_anggaran ELSE 0 END)";
                    } elseif ($key === 'pad_real') {
                        $field_expression = "SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.1.' THEN la.jumlah_realisasi ELSE 0 END)";
                    } elseif ($key === 'totalpendapatan_agr') {
                        $field_expression = "SUM(CASE WHEN LEFT(kc.kode_catatan,2)= '1.' THEN la.jumlah_anggaran ELSE 0 END)";
                    } elseif ($key === 'totalpendapatan_real') {
                        $field_expression = "SUM(CASE WHEN LEFT(kc.kode_catatan,2)= '1.' THEN la.jumlah_realisasi ELSE 0 END)";
                    } elseif ($key === 'transfer_agr') {
                        $field_expression = "(SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.2.' THEN la.jumlah_anggaran ELSE 0 END) + SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.3.' THEN la.jumlah_anggaran ELSE 0 END))";
                    } elseif ($key === 'transfer_real') {
                        $field_expression = "(SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.2.' THEN la.jumlah_realisasi ELSE 0 END) + SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.3.' THEN la.jumlah_realisasi ELSE 0 END))";
                    }
                    $select_raw_fields_array[] = "{$field_expression} AS {$key}";
                }
                $y_axis_label_for_chart = $current_indicator['y_axis_label_nominal'];

                if ($selected_data_type === 'agr') {
                    foreach ($current_indicator['nominal_data_keys'] as $key => $label) {
                        if (strpos($key, '_agr') !== false) {
                            $temp_chart_keys[$key . '_triliun'] = $label;
                            $select_final_fields_array[] = "{$key}/1000000000000.0 AS {$key}_triliun";
                        }
                    }
                } elseif ($selected_data_type === 'real') {
                    foreach ($current_indicator['nominal_data_keys'] as $key => $label) {
                        if (strpos($key, '_real') !== false) {
                            $temp_chart_keys[$key . '_triliun'] = $label;
                            $select_final_fields_array[] = "{$key}/1000000000000.0 AS {$key}_triliun";
                        }
                    }
                } else {
                    foreach ($current_indicator['nominal_data_keys'] as $key => $label) {
                        $temp_chart_keys[$key . '_triliun'] = $label;
                        $select_final_fields_array[] = "{$key}/1000000000000.0 AS {$key}_triliun";
                    }
                }
            } else {
                if ($selected_indicator_key === 'efektivitas') {
                    $select_raw_fields_array[] = "SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.1.' THEN la.jumlah_realisasi ELSE 0 END) AS pad_real";
                    $select_raw_fields_array[] = "SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.1.' THEN la.jumlah_anggaran ELSE 0 END) AS pad_agr";
                    $select_final_fields_array[] = "(pad_real / NULLIF(pad_agr, 0)) * 100 AS persentase_efektif";
                } else {
                    $select_raw_fields_array[] = "SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.1.' THEN la.jumlah_anggaran ELSE 0 END) AS pad_agr";
                    $select_raw_fields_array[] = "SUM(CASE WHEN LEFT(kc.kode_catatan,2)= '1.' THEN la.jumlah_anggaran ELSE 0 END) AS totalpendapatan_agr";
                    $select_raw_fields_array[] = "SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.1.' THEN la.jumlah_realisasi ELSE 0 END) AS pad_real";
                    $select_raw_fields_array[] = "SUM(CASE WHEN LEFT(kc.kode_catatan,2)= '1.' THEN la.jumlah_realisasi ELSE 0 END) AS totalpendapatan_real";
                    $select_raw_fields_array[] = "(SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.2.' THEN la.jumlah_anggaran ELSE 0 END) + SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.3.' THEN la.jumlah_anggaran ELSE 0 END)) AS transfer_agr";
                    $select_raw_fields_array[] = "(SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.2.' THEN la.jumlah_realisasi ELSE 0 END) + SUM(CASE WHEN LEFT(kc.kode_catatan,4)= '1.3.' THEN la.jumlah_realisasi ELSE 0 END)) AS transfer_real";

                    if ($selected_indicator_key === 'desentralisasi') {
                        $select_final_fields_array[] = "(pad_agr / NULLIF(totalpendapatan_agr, 0)) * 100 AS persentase_a";
                        $select_final_fields_array[] = "(pad_real / NULLIF(totalpendapatan_real, 0)) * 100 AS persentase_real";
                    } elseif ($selected_indicator_key === 'ketergantungan') {
                        $select_final_fields_array[] = "(transfer_agr / NULLIF(totalpendapatan_agr, 0)) * 100 AS persentase_a";
                        $select_final_fields_array[] = "(transfer_real / NULLIF(totalpendapatan_real, 0)) * 100 AS persentase_real";
                    } elseif ($selected_indicator_key === 'kemandirian') {
                        $select_final_fields_array[] = "(pad_agr / NULLIF(transfer_agr, 0)) * 100 AS persentase_a";
                        $select_final_fields_array[] = "(pad_real / NULLIF(transfer_real, 0)) * 100 AS persentase_real";
                    }
                }
                $y_axis_label_for_chart = $current_indicator['y_axis_label_percentage'];

                if ($selected_data_type === 'agr') {
                    if (isset($current_indicator['percentage_data_keys']['persentase_a'])) {
                        $temp_chart_keys['persentase_a'] = $current_indicator['percentage_data_keys']['persentase_a'];
                    } elseif (isset($current_indicator['percentage_data_keys']['persentase_efektif'])) {
                        $temp_chart_keys['persentase_efektif'] = $current_indicator['percentage_data_keys']['persentase_efektif'];
                    }
                } elseif ($selected_data_type === 'real') {
                    if (isset($current_indicator['percentage_data_keys']['persentase_real'])) {
                        $temp_chart_keys['persentase_real'] = $current_indicator['percentage_data_keys']['persentase_real'];
                    } elseif (isset($current_indicator['percentage_data_keys']['persentase_efektif'])) {
                        $temp_chart_keys['persentase_efektif'] = $current_indicator['percentage_data_keys']['persentase_efektif'];
                    }
                } else {
                    foreach ($current_indicator['percentage_data_keys'] as $key => $label) {
                        $temp_chart_keys[$key] = $label;
                    }
                }
            }

            $select_raw_fields_string = implode(", ", $select_raw_fields_array);
            $select_final_fields_string = implode(", ", $select_final_fields_array);

            $sql_query = "SELECT {$select_final_fields_string} FROM (
                SELECT tahun_lkpd AS tahun, {$select_raw_fields_string}
                FROM lkpd_apbd_lampiran_1 la INNER JOIN kode_catatan kc ON la.id_kode_catatan=kc.id
                WHERE tahun_lkpd BETWEEN ? AND ?
                GROUP BY tahun_lkpd
            ) AS a ORDER BY tahun ASC";

            $rows = DB::select($sql_query, [$selected_start_year, $selected_end_year]);

            if (!empty($rows)) {
                $chart_data_temp = [];
                foreach ($temp_chart_keys as $key => $label) {
                    $chart_data_temp[$key] = [];
                }

                foreach ($rows as $row) {
                    $row = (array) $row;
                    $chart_labels[] = (string) $row['tahun'];

                    foreach ($temp_chart_keys as $key => $label) {
                        if (isset($row[$key]) && $row[$key] !== null) {
                            $chart_data_temp[$key][] = round((float) $row[$key], 2);
                        } else {
                            $chart_data_temp[$key][] = null;
                        }
                    }
                }

                $colors = [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1',
                    '#20c997',
                    '#fd7e14',
                    '#e83e8c',
                    '#17a2b8',
                    '#6c757d'
                ];
                $color_index = 0;
                foreach ($temp_chart_keys as $key => $label) {
                    $hex = $colors[$color_index % count($colors)];
                    sscanf($hex, '#%02x%02x%02x', $r, $g, $b);
                    $chart_datasets[] = [
                        'label' => $label,
                        'data' => $chart_data_temp[$key],
                        'borderColor' => $hex,
                        'backgroundColor' => "rgba($r, $g, $b, 0.1)",
                        'fill' => false,
                        'tension' => 0.1,
                        'borderWidth' => 2,
                        'pointRadius' => 4,
                        'pointBackgroundColor' => $hex,
                        'pointBorderColor' => '#fff',
                        'pointHoverRadius' => 6,
                    ];
                    $color_index++;
                }
            }
        }

        return view('kinerja_keuangan_detail', [
            'pageTitle' => 'Detail Kinerja Keuangan - Manajemen Statistik Riau',
            'indicators' => $indicators,
            'tahun_options' => $tahun_options,
            'selected_indicator_key' => $selected_indicator_key,
            'selected_start_year' => $selected_start_year,
            'selected_end_year' => $selected_end_year,
            'selected_value_type' => $selected_value_type,
            'selected_data_type' => $selected_data_type,
            'report_title' => $report_title,
            'chart_labels' => $chart_labels,
            'chart_datasets' => $chart_datasets,
            'y_axis_label_for_chart' => $y_axis_label_for_chart
        ]);
    }
}
