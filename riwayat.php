<?php
$title = "Profil Service Rutin";
$brand = "AUTO CARE";
$kendala = [
    'judul' => 'Kendala',
    'deskripsi' => 'Muncul bunyi di bawah mobil, dan kontrol mobil terasa kurang nyaman saat handling. Getaran terasa pada kecepatan sedang.',
    'tanggal' => '2025-10-24',
    'intensitas' => 'Sedang'
];
$right_cards = [
    ['judul' => 'Riwayat Kendala', 'isi' => ['Kontrol mobil kurang nyaman', 'Kaki-kaki mobil bermasalah']],
    ['judul' => 'Penanganan', 'isi' => ['Service sedang pada kaki-kaki.', 'Penyesuaian handling dan balancing roda.']],
    ['judul' => 'Tambahan', 'isi' => ['Oli Shell Helix 1 Liter', 'Air radiator']]
];
function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function formatTgl($iso){
    if(!$iso) return '';
    $ts = strtotime($iso);
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return date('j',$ts).' '.$bulan[(int)date('n',$ts)].' '.date('Y',$ts);
}
?><!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo e($title); ?> - <?php echo e($brand); ?></title>
  <style>
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial;
      background:#ffffff;
      color:#111;
    }

    :root {
      --right-offset: 70px;
      --left-offset: 60px;
      --accent-red: #d32f2f;
    }

    header{
      height:64px;
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:0 28px;
      box-shadow:0 2px 8px rgba(0,0,0,0.06);
      position:relative;
      z-index:50;
    }
    header h1{font-size:1.1rem;margin:0}
    header a{text-decoration:none;color:#111;font-weight:500}

    .canvas{
      position:relative;
      padding:56px 20px 80px;
      min-height:calc(100vh - 64px);
      display:flex;
      justify-content:center;
      align-items:flex-start;
      overflow:hidden;
    }

    /* background logo dinaikkan agar terlihat penuh */
    .canvas::before{
      content:"";
      position:absolute;
      left:50%;
      top:-14%; /* dinaikkan lagi */
      transform:translateX(-50%);
      width:900px;
      height:900px;
      background-image:url('background.png');
      background-repeat:no-repeat;
      background-position:center;
      background-size:contain;
      opacity:0.07;
      pointer-events:none;
      z-index:1;
    }

    .wrap{
      width:100%;
      max-width:1100px;
      display:grid;
      grid-template-columns: 1fr 420px;
      gap:32px;
      position:relative;
      z-index:3;
      align-items:start;
    }

    .title-pill{
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      top:-18px;
      background:var(--accent-red);
      color:#fff;
      padding:12px 28px;
      border-radius:999px;
      box-shadow:0 10px 28px rgba(8,12,20,0.08);
      font-weight:700;
      z-index:6;
      letter-spacing:0.2px;
    }

    /* LEFT CARD */
    .left{ padding-top:6px; }
    .card-big{
      background:#fff;
      border-radius:12px;
      box-shadow:0 8px 26px rgba(8,12,20,0.06);
      border:1.5px solid rgba(0,0,0,0.15); /* outline lebih tegas */
      padding:26px;
      min-height:220px;
      width:100%;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      margin-top:var(--left-offset);
      transition:transform 0.2s ease;
    }
    .card-big:hover {
      transform:translateY(-3px);
    }
    .card-big h3{
      margin:0 0 12px 0;
      text-align:center;
      font-size:1.05rem;
    }
    .card-big p{
      margin:0;
      color:#333;
      line-height:1.6;
    }
    .card-footer{
      margin-top:18px;
      padding-top:12px;
      border-top:1px solid #f1f1f1;
      display:flex;
      justify-content:space-between;
      align-items:center;
      color:#444;
      font-size:0.95rem;
    }

    /* RIGHT CARDS */
    .right{
      display:flex;
      flex-direction:column;
      gap:20px;
      align-items:stretch;
      margin-top:var(--right-offset);
    }
    .small-card{
      background:#fff;
      border-radius:12px;
      padding:16px;
      box-shadow:0 6px 18px rgba(8,12,20,0.06);
      border:1.5px solid rgba(0,0,0,0.15); /* outline lebih jelas */
      width:100%;
      transition:transform 0.2s ease;
    }
    .small-card:hover {
      transform:translateY(-3px);
    }
    .small-card h4{
      margin:0 0 8px 0;
      font-size:0.98rem;
    }
    .small-card p{
      margin:0 0 6px 0;
      line-height:1.5;
      color:#333;
      font-size:0.95rem;
    }

    @media(max-width:880px){
      .wrap{grid-template-columns:1fr}
      .title-pill{position:relative;transform:none;margin:0 auto 16px}
      .left{padding-top:0}
      .card-big{margin-top:20px}
      .right{order:2;margin-top:20px}
    }
  </style>
</head>
<body>
  <header>
    <h1><?php echo e($brand); ?></h1>
    <a href="beranda.php">Beranda</a>
  </header>

  <div class="canvas">
    <div class="wrap">
      <div class="title-pill"><?php echo e($title); ?></div>

      <div class="left">
        <div class="card-big">
          <div>
            <h3><?php echo e($kendala['judul']); ?></h3>
            <p><?php echo e($kendala['deskripsi']); ?></p>
          </div>
          <div class="card-footer">
            <div><?php echo e(formatTgl($kendala['tanggal'])); ?></div>
            <div><strong>intensitas</strong> : <?php echo e($kendala['intensitas']); ?></div>
          </div>
        </div>
      </div>

      <div class="right">
        <?php foreach($right_cards as $card): ?>
          <div class="small-card">
            <h4><?php echo e($card['judul']); ?></h4>
            <?php foreach($card['isi'] as $line): ?>
              <p>› <?php echo e($line); ?></p>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</body>
</html>
