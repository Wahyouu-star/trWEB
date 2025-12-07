<?php
$title = "Pengingat Servis Rutin";
$brand = "AUTO CARE";
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($title); ?> - <?php echo htmlspecialchars($brand); ?></title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
      background:#fff;color:#111;min-height:100vh;position:relative;overflow-x:hidden;
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

    header h1{
        font-size:1.1rem;
        margin:0;
    }

    header a{
        text-decoration:none;
        color:#111;
        font-weight:500;
    }

    body::before{
      content:"";position:absolute;top:-6%;left:50%;transform:translateX(-50%);
      width:900px;height:900px;background:url('background.png') no-repeat center/contain;
      opacity:0.07;z-index:0;
    }

    main.content{ text-align:center; position:relative; z-index:2; padding-top:80px; padding-bottom:100px; }

    .title-pill{
      display:inline-block;background:#d32f2f;color:#fff;padding:14px 36px;border-radius:999px;
      font-weight:700;font-size:1.3rem;letter-spacing:.3px;box-shadow:0 6px 18px rgba(0,0,0,0.15);
    }

    .btn-tambah{
      position:fixed;
      right:70px;
      top:50%;
      transform:translateY(-50%);
      background:#d32f2f;color:#fff;border:none;border-radius:999px;
      padding:12px 26px;font-size:1.05rem;font-weight:600;cursor:pointer;
      box-shadow:0 5px 16px rgba(211,47,47,0.35);
      display:flex;align-items:center;gap:8px;
      transition:all .15s ease;
      z-index:50;
    }
    .btn-tambah:hover{ transform:translateY(-50%) scale(1.03); box-shadow:0 7px 22px rgba(211,47,47,0.45); }

    #list{margin:36px auto 0;max-width:960px;display:flex;flex-direction:column;gap:14px;padding:0 20px;z-index:2;position:relative}

    .svc-card{
      background:#fff;border-radius:12px;padding:16px;
      box-shadow:0 10px 30px rgba(0,0,0,0.06);border:1px solid rgba(0,0,0,0.06);
      display:block; 
    }
    .svc-left { margin-bottom:12px; }
    .svc-left .title{ font-weight:700; font-size:1rem; margin-bottom:6px; }
    .svc-left .meta{ color:#666; font-size:0.95rem; line-height:1.5; }

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
      gap:12px;
      flex-wrap:wrap;
    }

    .card-footer .actions { display:flex; gap:8px; align-items:center; }
    .btn-ghost{ background:#fff;border:1px solid #ccc;padding:8px 12px;border-radius:8px;cursor:pointer; }
    .btn-primary{ background:#d32f2f;color:#fff;border:0;padding:8px 12px;border-radius:8px;cursor:pointer; }
    .btn-done{ background:#2ea44f;color:#fff;border:0;padding:8px 12px;border-radius:8px;cursor:pointer; }

    .svc-card.done{ opacity:0.55; text-decoration:line-through; }

    /* modal custom */
    .modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,0.35); display:none; z-index:1000; align-items:center; justify-content:center; padding:18px; }
    .modal-box{ background:#fff; border-radius:12px; width:100%; max-width:520px; padding:18px; box-shadow:0 14px 40px rgba(0,0,0,0.25); }
    .modal-box h4{ text-align:center; margin-bottom:12px; }

    .form-group{ margin-bottom:10px; text-align:left; }
    .form-group label{ display:block; font-size:0.9rem; margin-bottom:6px; color:#333; }
    .form-group input, .form-group select{ width:100%; padding:8px 10px; border-radius:8px; border:1px solid #ddd; font-size:0.95rem; }

    .form-row{ display:flex; gap:10px; }
    .form-row .col{ flex:1; }

    /* success box */
    .success-box{ position:fixed; left:50%; top:30%; transform:translateX(-50%); background:#fff; padding:18px; border-radius:12px; box-shadow:0 12px 40px rgba(0,0,0,0.2); width:320px; text-align:center; display:none; z-index:1100; border:1px solid rgba(0,0,0,0.06); }
    .success-box .circle{ width:64px;height:64px;border-radius:50%; background:#d32f2f; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#fff; font-weight:700; }

    @media(max-width:800px){
      .btn-tambah{ right:20px; padding:10px 18px; font-size:1rem; }
      .title-pill{ font-size:1.15rem; padding:12px 28px; }
    }
  </style>
</head>
<body>
  <header>
    <h1><?php echo htmlspecialchars($brand); ?></h1>
    <a href="beranda.php">Beranda</a>
  </header>

  <main class="content">
    <div class="title-pill"><?php echo htmlspecialchars($title); ?></div>
    <button class="btn-tambah" id="btnTambah">Tambah <strong style="font-size:18px">+</strong></button>

    <div id="list"></div>
  </main>

  <!-- Modal -->
  <div class="modal-backdrop" id="modalBackdrop">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <h4 id="modalTitle">Tambah Jadwal Servis</h4>
      <form id="formServis">
        <div class="form-group"><label for="jenis">Jenis Servis</label><input id="jenis" name="jenis" required></div>
        <div class="form-group"><label for="jarak">Jarak Tempuh (km)</label><input id="jarak" name="jarak" type="number" required></div>
        <div class="form-group"><label for="durasi">Durasi</label><input id="durasi" name="durasi" required></div>
        <div class="form-row">
          <div class="col form-group"><label for="waktu1">Waktu 1</label><input id="waktu1" name="waktu1" type="time" required></div>
          <div class="col form-group"><label for="waktu2">Waktu 2 (opsional)</label><input id="waktu2" name="waktu2" type="time"></div>
        </div>
        <div class="form-group"><label for="interval">Interval</label>
          <select id="interval" name="interval">
            <option>3 bulan / 5000 km</option>
            <option>6 bulan / 10000 km</option>
            <option>12 bulan / 20000 km</option>
            <option>Manual</option>
          </select>
        </div>

        <div style="display:flex;justify-content:center;gap:10px;margin-top:8px">
          <button type="button" id="btnCancel" class="btn-ghost">Batal</button>
          <button type="submit" class="btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- success dialog -->
  <div class="success-box" id="successBox" role="status" aria-live="polite">
    <div class="circle">✔</div>
    <h4 style="margin-top:10px">Berhasil</h4>
    <p id="successText" style="color:#555;margin:8px 0 0 0">Anda berhasil menambahkan jadwal servis</p>
    <div style="margin-top:12px"><button onclick="closeSuccess()" style="padding:8px 20px;border-radius:8px;border:0;background:#d32f2f;color:#fff;cursor:pointer">OK</button></div>
  </div>

  <script>
    const btnTambah = document.getElementById('btnTambah');
    const modal = document.getElementById('modalBackdrop');
    const form = document.getElementById('formServis');
    const list = document.getElementById('list');
    const successBox = document.getElementById('successBox');
    const successText = document.getElementById('successText');
    const btnCancel = document.getElementById('btnCancel');

    // helper html escape
    function escapeHtml(s){ return String(s||'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#39;'); }

    function showModal(){ modal.style.display='flex'; setTimeout(()=>document.getElementById('jenis').focus(),80); }
    function hideModal(){ modal.style.display='none'; form.reset(); }
    function showSuccess(msg){ successText.textContent = msg; successBox.style.display='block'; }
    function closeSuccess(){ successBox.style.display='none'; }

    btnTambah.addEventListener('click', showModal);
    btnCancel.addEventListener('click', hideModal);
    modal.addEventListener('click', (e)=>{ if(e.target===modal) hideModal(); });

    function createCardElem(data, waktu){
      const card = document.createElement('div');
      card.className = 'svc-card';
      // vertical layout: left section (content) then footer
      card.innerHTML = `
        <div class="svc-left">
          <div class="title">${escapeHtml(data.jenis)} <small style="color:#666">(${escapeHtml(data.durasi)})</small></div>
          <p class="card-desc card-big"><span class="meta">Jarak: ${escapeHtml(data.jarak)} km • Interval: ${escapeHtml(data.interval)}</span></p>
          <p class="card-desc card-big"><span class="meta">Waktu pengingat: <strong>${escapeHtml(waktu)}</strong></span></p>
        </div>
        <div class="card-footer">
          <div class="info">Dibuat: ${new Date().toLocaleString()}</div>
          <div class="actions">
            <button class="btn-ghost btn-edit" type="button">Edit</button>
            <button class="btn-ghost btn-delete" type="button" style="border-color:#d32f2f;color:#d32f2f">Hapus</button>
            <button class="btn-done" type="button">Selesai</button>
          </div>
        </div>
      `;

      // wire actions
      card.querySelector('.btn-delete').addEventListener('click', ()=> card.remove());
      card.querySelector('.btn-done').addEventListener('click', ()=>{
        card.classList.add('done');
        showSuccess('Servis ditandai selesai');
      });
      card.querySelector('.btn-edit').addEventListener('click', ()=>{
        // prefill modal with card values and remove card
        document.getElementById('jenis').value = data.jenis;
        document.getElementById('jarak').value = data.jarak;
        document.getElementById('durasi').value = data.durasi;
        document.getElementById('waktu1').value = waktu;
        document.getElementById('waktu2').value = '';
        document.getElementById('interval').value = data.interval;
        card.remove();
        showModal();
      });

      return card;
    }

    form.addEventListener('submit', function(e){
      e.preventDefault();
      const jenis = document.getElementById('jenis').value.trim();
      const jarak = document.getElementById('jarak').value.trim();
      const durasi = document.getElementById('durasi').value.trim();
      const waktu1 = document.getElementById('waktu1').value;
      const waktu2 = document.getElementById('waktu2').value;
      const interval = document.getElementById('interval').value;
      if(!jenis || !jarak || !durasi || !waktu1){ alert('Lengkapi form (Jenis, Jarak, Durasi, Waktu 1).'); return; }

      const data = { jenis, jarak, durasi, interval };
      list.appendChild(createCardElem(data, waktu1));
      if(waktu2) list.appendChild(createCardElem(data, waktu2));
      hideModal();
      showSuccess('Anda berhasil menambahkan jadwal servis');
    });
  </script>
</body>
</html>
