<?php
// Inisialisasi variabel
$nama = "";
$umur = "";
$kategori = "";
$sudahSubmit = false;

// Proses form jika menggunakan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars(trim($_POST["nama"]));
    $umur = (int)$_POST["umur"];
    $sudahSubmit = true;

    // Percabangan if-elseif-else untuk menentukan kategori usia
    if ($umur < 13) {
        $kategori = "Anak-anak";
    } elseif ($umur >= 13 && $umur <= 17) {
        $kategori = "Remaja";
    } elseif ($umur >= 18 && $umur <= 59) {
        $kategori = "Dewasa";
    } else {
        $kategori = "Lansia";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kategori Usia Mahasiswa</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;700&display=swap');

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg:        #0f1117;
            --surface:   #1a1d27;
            --border:    #2a2d3a;
            --accent:    #6c63ff;
            --accent-soft: rgba(108, 99, 255, 0.15);
            --text:      #e8e9f0;
            --muted:     #7a7d8e;
            --success:   #22d3a5;
            --radius:    14px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* Subtle grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.35;
            pointer-events: none;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px 36px;
            width: 100%;
            max-width: 440px;
            position: relative;
            box-shadow: 0 24px 60px rgba(0,0,0,0.5);
        }

        /* Accent glow on top edge */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 20%; right: 20%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            border-radius: 999px;
        }

        .badge {
            display: inline-block;
            background: var(--accent-soft);
            color: var(--accent);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 16px;
            border: 1px solid rgba(108,99,255,0.3);
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 8px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            padding: 12px 16px;
            outline: none;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        input::placeholder {
            color: var(--muted);
            opacity: 0.6;
        }

        button[type="submit"] {
            width: 100%;
            background: var(--accent);
            color: #fff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 600;
            padding: 13px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 8px;
            letter-spacing: 0.02em;
            transition: opacity 0.2s, transform 0.1s;
        }

        button[type="submit"]:hover  { opacity: 0.9; }
        button[type="submit"]:active { transform: scale(0.98); }

        /* Result box */
        .result {
            margin-top: 28px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 22px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .result-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
        }

        .result-row:last-child { border-bottom: none; }

        .result-key { color: var(--muted); }
        .result-val { font-weight: 600; }

        .kategori-pill {
            background: rgba(34, 211, 165, 0.15);
            color: var(--success);
            border: 1px solid rgba(34, 211, 165, 0.3);
            border-radius: 999px;
            padding: 3px 14px;
            font-size: 13px;
            font-weight: 700;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }

        .legend {
            font-size: 12px;
            color: var(--muted);
        }

        .legend-title {
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 11px;
        }

        .legend-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .legend-range { opacity: 0.7; }
    </style>
</head>
<body>
    <div class="card">
        <div class="badge">&#128203; Program PHP</div>
        <h1>Cek Kategori Usia Mahasiswa</h1>
        <p class="subtitle">Masukkan nama dan umur untuk mengetahui kategori usia.</p>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Contoh: Budi Santoso"
                    value="<?php echo htmlspecialchars($nama); ?>"
                    required
                >
            </div>
            <div class="form-group">
                <label for="umur">Umur (tahun)</label>
                <input
                    type="number"
                    id="umur"
                    name="umur"
                    placeholder="Contoh: 20"
                    value="<?php echo htmlspecialchars($umur); ?>"
                    min="0"
                    max="150"
                    required
                >
            </div>
            <button type="submit">Cek Kategori &rarr;</button>
        </form>

        <?php if ($sudahSubmit): ?>
        <div class="result">
            <div class="result-label">&#9989; Hasil</div>
            <div class="result-row">
                <span class="result-key">Nama</span>
                <span class="result-val"><?php echo $nama; ?></span>
            </div>
            <div class="result-row">
                <span class="result-key">Umur</span>
                <span class="result-val"><?php echo $umur; ?> tahun</span>
            </div>
            <div class="result-row">
                <span class="result-key">Kategori</span>
                <span class="kategori-pill">
                    <?php echo $kategori; ?>
                </span>
            </div>
        </div>
        <?php
            // Output wajib menggunakan echo
            echo "<p style='margin-top:14px; font-size:13px; color:var(--muted); text-align:center;'>
                    &ldquo;<strong style='color:var(--text);'>{$nama}</strong>&rdquo; dengan umur
                    <strong style='color:var(--text);'>{$umur} tahun</strong>
                    termasuk kategori <strong style='color:var(--success);'>{$kategori}</strong>.
                  </p>";
        ?>
        <?php endif; ?>

        <hr class="divider">

        <div class="legend">
            <div class="legend-title">Tabel Kategori</div>
            <div class="legend-item">
                <span>&lt; 13 tahun</span>
                <span class="legend-range">Anak-anak</span>
            </div>
            <div class="legend-item">
                <span>13 – 17 tahun</span>
                <span class="legend-range">Remaja</span>
            </div>
            <div class="legend-item">
                <span>18 – 59 tahun</span>
                <span class="legend-range">Dewasa</span>
            </div>
            <div class="legend-item">
                <span>&ge; 60 tahun</span>
                <span class="legend-range">Lansia</span>
            </div>
        </div>
    </div>
</body>
</html>