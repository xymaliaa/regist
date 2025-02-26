const express = require('express');
const mysql = require('mysql');
const app = express();
const port = 3000;

// Middleware untuk meng-parse body POST
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Koneksi ke database
const db = mysql.createConnection({
    host: 'sql309.ezyro.com',
    user: 'ezyro_37516611',
    password: 'ppsm1919',
    database: 'ezyro_37516611_login'
});

db.connect((err) => {
    if (err) {
        console.error('Connection failed: ' + err.stack);
        return;
    }
    console.log('Connected to database.');
});

// Endpoint untuk registrasi dan login
app.post('/login', (req, res) => {
    const username_input = req.body.username;
    const captcha_input = req.body.captcha;

    if (!username_input) {
        return res.status(400).json({ message: "Username tidak boleh kosong." });
    }

    // Cek apakah username sudah ada
    db.query("SELECT * FROM users WHERE username = ?", [username_input], (err, result) => {
        if (err) {
            return res.status(500).json({ message: "Terjadi kesalahan pada server." });
        }

        if (result.length > 0) {
            return res.json({ message: `Login berhasil! Selamat datang kembali, ${username_input}.` });
        } else {
            if (captcha_input !== "") {
                if (captcha_input === "@alwywoii15") {
                    // Registrasi baru
                    db.query("INSERT INTO users (username) VALUES (?)", [username_input], (err) => {
                        if (err) {
                            return res.status(500).json({ message: "Terjadi kesalahan saat registrasi." });
                        }
                        return res.json({ message: `Registrasi berhasil! Username: ${username_input}` });
                    });
                } else {
                    return res.status(400).json({ message: "Captcha salah. Silakan coba lagi." });
                }
            } else {
                return res.status(400).json({ message: "Username tidak ditemukan. Silakan daftar dengan memasukkan captcha." });
            }
        }
    });
});

// Jalankan server
app.listen(port, () => {
    console.log(`Server berjalan di http://localhost:${port}`);
});
