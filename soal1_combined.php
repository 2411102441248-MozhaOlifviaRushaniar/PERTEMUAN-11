<?php
// soal1_combined.php
// Form + proses menghitung saldo akhir setelah N bulan
function formatIDR($n) {
    return "Rp " . number_format($n, 0, ',', '.');
}

$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil input, bersihkan & konversi ke integer
    $saldo = isset($_POST['saldo']) ? (int)$_POST['saldo'] : 0;
    $bulan = isset($_POST['bulan']) ? (int)$_POST['bulan'] : 0;

    if ($saldo <= 0 || $bulan <= 0) {
        $result = "Masukkan saldo awal dan jumlah bulan yang valid (lebih dari 0).";
    } else {
        // simulasikan tiap bulan
        $saldoAwal = $saldo;
        $log = [];
        for ($i = 1; $i <= $bulan; $i++) {
            // tentukan persentase bunga berdasarkan saldo saat ini
            if ($saldo < 1100000) {
                $bunga = 0.03 * $saldo;
            } else {
                $bunga = 0.04 * $saldo;
            }

            // tambahkan bunga, kurangi biaya admin Rp 9.000
            $saldo = $saldo + $bunga - 9000;

            // catat untuk debug / tampilan (opsional)
            $log[] = "Bulan $i: +bunga ". formatIDR(round($bunga)) .", -admin Rp 9.000 -> saldo: ". formatIDR(round($saldo));
        }

        $result = "<strong>Saldo awal:</strong> " . formatIDR($saldoAwal) . "<br>";
        $result .= "<strong>Saldo akhir setelah $bulan bulan:</strong> " . formatIDR(round($saldo)) . "<br><br>";
        // tampilkan ringkasan setiap bulan (jika ingin lengkap)
        $result .= "<details><summary>Rincian per bulan (klik untuk buka)</summary><pre>" . implode("\n", $log) . "</pre></details>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Soal 1 - Saldo Tabungan (Form + Proses)</title>
</head>
<body>
    <h3>Form Soal 1 — Hitung Saldo Akhir</h3>

    <form method="post" action="">
        Saldo Awal: <input type="number" name="saldo" value="<?php echo isset($_POST['saldo']) ? (int)$_POST['saldo'] : ''; ?>" required><br><br>
        Lama menabung (bulan): <input type="number" name="bulan" value="<?php echo isset($_POST['bulan']) ? (int)$_POST['bulan'] : ''; ?>" required><br><br>
        <button type="submit">Hitung</button>
    </form>

    <hr>

    <div>
        <?php
        if ($result !== '') {
            echo $result;
        } else {
            echo "<em>Isi form di atas lalu klik Hitung untuk melihat saldo akhir.</em>";
        }
        ?>
    </div>
</body>
</html>
