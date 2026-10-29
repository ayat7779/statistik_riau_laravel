<?php

namespace App\Http\Controllers;

use App\Models\ApbdLampiran1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApbdLampiran1Controller extends Controller
{
    public function index(Request $request)
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

        $items = DB::select($sql, $bindings);

        return view('apbd_lampiran_1.index', [
            'pageTitle' => 'Lampiran I - APBD',
            'items' => $items,
            'available_years' => $available_years,
            'selected_year' => $selected_year
        ]);
    }
}
