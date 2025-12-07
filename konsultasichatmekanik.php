<?php
// chat.php
$nama_mekanik = "Nama Mekanik";
$status = "online";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AUTO CARE</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  html, body {
    width: 100%;
    height: 100%;
    font-family: "Poppins", sans-serif;
    background-color: #f2f2f2;
  }

  .chat-wrapper {
    width: 100%;
    height: 100%;
    background: #fff;
    display: flex;
    flex-direction: column;
  }

  /* HEADER ATAS */
  .top-bar {
    flex-shrink: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 20px;
    background: #fff;
    border-bottom: 1px solid #ccc;
  }

  .top-bar h1 {
    font-size: 22px;
    font-weight: 700;
    color: #000;
  }

  .profile {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* AREA CHAT */
  .chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 15px;
    background: #fdfdfd;
  }

  .chat-header {
    border: 1.5px solid #000000ff;
    border-radius: 6px;
    padding: 10px;
    display: flex;
    align-items: center;
    background: #fff;
    position: relative;
  }

  .back-btn {
    position: absolute;
    left: 10px;
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #333;
  }

  .mechanic-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #c33;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    margin-left: 35px;
  }

  .mechanic-details {
    margin-left: 10px;
  }

  .mechanic-details h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 2px;
  }

  .mechanic-details p {
    font-size: 13px;
    color: #3a3a3aff;
  }

  .chat-box {
    flex: 1;
    margin-top: 10px;
    background: #fff;
    border: 1.5px solid #000000ff;
    border-radius: 6px;
    overflow-y: auto;
    padding: 10px;
  }

  .chat-message {
    margin: 8px 0;
    display: flex;
  }

  .chat-message.user {
    justify-content: flex-end;
  }

  .chat-message p, .chat-message img, .chat-message a {
    padding: 10px 14px;
    border-radius: 16px;
    max-width: 70%;
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
  }

  .chat-message.user p, .chat-message.user a {
    background: #c33;
    color: #fff;
    border-bottom-right-radius: 0;
  }

  .chat-message.mekanik p {
    background: #e0e0e0;
    color: #000;
    border-bottom-left-radius: 0;
  }

  .chat-message img {
    max-width: 150px;
    border-radius: 10px;
  }

  /* INPUT PESAN */
  .input-area {
    border: 1.5px solid #000000ff;
    border-radius: 6px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    background: #f2f2f2;
    padding: 8px 10px;
  }

  .input-area input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
    padding: 6px;
    color: #333;
  }

  .input-icons {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .input-icons button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    color: #333;
  }

  .send-btn {
    font-size: 20px;
    margin-left: 3px;
  }

  .link-icon::before {
    content: "🔗";
  }

  .camera-icon::before {
    content: "📷";
  }

  .send-icon::before {
    content: "▶";
  }
</style>
</head>
<body>
  <div class="chat-wrapper">
    <!-- HEADER ATAS -->
    <div class="top-bar">
      <h1>AUTO CARE</h1>
      <div class="profile">
        <span>Beranda</span>
        <div class="mechanic-avatar">👤</div>
      </div>
    </div>

    <!-- AREA CHAT -->
    <div class="chat-area">
      <div class="chat-header">
        <button class="back-btn" onclick="history.back()">←</button>
        <div class="mechanic-avatar">👤</div>
        <div class="mechanic-details">
          <h3><?php echo $nama_mekanik; ?></h3>
          <p><?php echo $status; ?></p>
        </div>
      </div>

      <div class="chat-box" id="chatBox"></div>

      <div class="input-area">
        <input type="text" id="messageInput" placeholder="ketik pesan...">
        <div class="input-icons">
          <input type="file" id="fileInput" accept="*/*" hidden>
          <input type="file" id="imageInput" accept="image/*" hidden>
          <button class="link-icon" title="tautan" id="linkBtn"></button>
          <button class="camera-icon" title="kamera" id="cameraBtn"></button>
          <button class="send-btn send-icon" id="sendBtn" title="kirim"></button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const sendBtn = document.getElementById("sendBtn");
    const messageInput = document.getElementById("messageInput");
    const chatBox = document.getElementById("chatBox");
    const fileInput = document.getElementById("fileInput");
    const imageInput = document.getElementById("imageInput");
    const linkBtn = document.getElementById("linkBtn");
    const cameraBtn = document.getElementById("cameraBtn");

    function tambahPesan(html, kelas = "user") {
      const msg = document.createElement("div");
      msg.classList.add("chat-message", kelas);
      msg.innerHTML = html;
      chatBox.appendChild(msg);
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    function kirimPesan() {
      const text = messageInput.value.trim();
      if (text === "") return;
      tambahPesan(`<p>${text}</p>`);
      messageInput.value = "";

      setTimeout(() => {
        tambahPesan(`<p>Baik, kami menerima pesan: "${text}"</p>`, "mekanik");
      }, 800);
    }

    sendBtn.addEventListener("click", kirimPesan);
    messageInput.addEventListener("keydown", e => {
      if (e.key === "Enter") {
        e.preventDefault();
        kirimPesan();
      }
    });

    // Kirim file (tautan)
    linkBtn.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", e => {
      const file = e.target.files[0];
      if (file) {
        tambahPesan(`<a href="#" download="${file.name}">📎 ${file.name}</a>`);
      }
    });

    // Kirim foto
    cameraBtn.addEventListener("click", () => imageInput.click());
    imageInput.addEventListener("change", e => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
          tambahPesan(`<img src="${ev.target.result}" alt="foto">`);
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
