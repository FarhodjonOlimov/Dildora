<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Modal CRUD (Qo‘shish / Yangilash)</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    table {
      border-collapse: collapse;
      width: 60%;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #333;
      padding: 8px 12px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    .form-group {
      margin-bottom: 10px;
    }
    .buttons button {
      padding: 10px 20px;
      margin-right: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .buttons button:hover {
      background-color: #45a049;
    }
    input[type="text"], input[type="tel"] {
      padding: 5px;
      width: 200px;
    }
    #editModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 10;
    }
    #modalContent {
      background: white;
      padding: 20px;
      margin: 100px auto;
      width: 300px;
      border-radius: 10px;
      position: relative;
    }
  </style>
</head>
<body>
  <h1>Ma'lumotlar</h1>

  <div class="form-group">
    <label>Ism:</label>
    <input type="text" id="ismInput" />
  </div>
  <div class="form-group">
    <label>Familiya:</label>
    <input type="text" id="familiyaInput" />
  </div>
  <div class="form-group">
    <label>Telefon:</label>
    <input type="tel" id="telefonInput" oninput="telefonniTekshir(this)" />
  </div>
  <div class="buttons">
    <button onclick="qoshish()">Qo‘shish</button>
    <button onclick="yangilashModal()">Yangilash</button>
  </div>

  <table id="malumotlar">
    <thead>
      <tr>
        <th>Ism</th>
        <th>Familiya</th>
        <th>Telefon</th>
      </tr>
    </thead>
    <tbody id="tbody"></tbody>
  </table>

  <!-- Modal oynasi -->
  <div id="editModal">
    <div id="modalContent">
      <h3>Tahrirlash</h3>
      <label>Ism:</label><br>
      <input type="text" id="editIsm"><br><br>
      <label>Familiya:</label><br>
      <input type="text" id="editFamiliya"><br><br>
      <label>Telefon:</label><br>
      <input type="tel" id="editTelefon" oninput="telefonniTekshir(this)"><br><br>
      <button onclick="saqlashTahrir()">Saqlash</button>
      <button onclick="yopishModal()">Bekor qilish</button>
    </div>
  </div>

  <script>
    function telefonniTekshir(input) {
      let qiymat = input.value.replace(/[^+\d]/g, '');
      if (qiymat.indexOf('+') > 0) {
        qiymat = qiymat.replace(/\+/g, '');
        qiymat = '+' + qiymat;
      } else if (qiymat.indexOf('+') === -1) {
        qiymat = '+' + qiymat;
      }
      input.value = qiymat;
    }

    function qoshish() {
      const ism = document.getElementById('ismInput').value.trim();
      const familiya = document.getElementById('familiyaInput').value.trim();
      const telefon = document.getElementById('telefonInput').value.trim();

      if (!ism || !familiya || !telefon) {
        alert("Iltimos, barcha maydonlarni to‘ldiring.");
        return;
      }

      let foydalanuvchilar = JSON.parse(localStorage.getItem('foydalanuvchilar')) || [];
      if (foydalanuvchilar.length >= 1) {
        alert("Faqat bitta foydalanuvchi ro‘yxatdan o‘ta oladi.");
        return;
      }

      foydalanuvchilar.push({ ism, familiya, telefon });
      localStorage.setItem('foydalanuvchilar', JSON.stringify(foydalanuvchilar));
      alert("Ma'lumot qo‘shildi!");
      tozalash();
      korish();
    }

    function korish() {
      const foydalanuvchilar = JSON.parse(localStorage.getItem('foydalanuvchilar')) || [];
      const tbody = document.getElementById('tbody');
      tbody.innerHTML = "";

      foydalanuvchilar.forEach((user) => {
        tbody.innerHTML += `
          <tr>
            <td>${user.ism}</td>
            <td>${user.familiya}</td>
            <td>${user.telefon}</td>
          </tr>`;
      });
    }

    function yangilashModal() {
      const foydalanuvchilar = JSON.parse(localStorage.getItem('foydalanuvchilar')) || [];
      if (foydalanuvchilar.length === 0) {
        alert("Hozircha ma'lumot mavjud emas.");
        return;
      }

      const user = foydalanuvchilar[0];
      document.getElementById('editIsm').value = user.ism;
      document.getElementById('editFamiliya').value = user.familiya;
      document.getElementById('editTelefon').value = user.telefon;

      document.getElementById('editModal').style.display = 'block';
    }

    function yopishModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    function saqlashTahrir() {
      const ism = document.getElementById('editIsm').value.trim();
      const familiya = document.getElementById('editFamiliya').value.trim();
      const telefon = document.getElementById('editTelefon').value.trim();

      if (!ism || !familiya || !telefon) {
        alert("Iltimos, barcha maydonlarni to‘ldiring.");
        return;
      }

      let foydalanuvchilar = [{ ism, familiya, telefon }];
      localStorage.setItem('foydalanuvchilar', JSON.stringify(foydalanuvchilar));
      alert("Ma'lumot yangilandi!");
      yopishModal();
      korish();
    }

    function tozalash() {
      document.getElementById('ismInput').value = "";
      document.getElementById('familiyaInput').value = "";
      document.getElementById('telefonInput').value = "";
    }

    window.onload = korish;
  </script>
</body>
</html>
