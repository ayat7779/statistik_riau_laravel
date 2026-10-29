<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApbdLampiran1;

class StatistikController extends Controller
{
    public function apbd(Request $request)
    {
        $available_years = ApbdLampiran1::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $selected_year = $request->get('tahun', '');
        if ($selected_year === '' && $available_years->isNotEmpty()) {
            $selected_year = $available_years->first();
        }

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
            'pageTitle' => 'Data APBD - Manajemen Statistik Riau',
            'result' => $result,
            'available_years' => $available_years,
            'selected_year' => $selected_year
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
            $bindings[] = $selected_year;
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
        $years = DB::table('lkpd_apbd_lampiran_1')->distinct()->orderBy('tahun_lkpd', 'asc')->pluck('tahun_lkpd');

        return view('kinerja_keuangan_detail', [
            'pageTitle' => 'Detail Kinerja Keuangan - Manajemen Statistik Riau',
            'available_years' => $years
        ]);
    }
}
