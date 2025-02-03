const mysql = require("mysql");

const connection = mysql.createConnection({
    host: process.env.DB_HOST || "sql309.ezyro.com",
    user: process.env.DB_USER || "ezyro_37516611",
    password: process.env.DB_PASS || "ppsm1919",
    database: process.env.DB_NAME || "ezyro_37516611_login"
});

connection.connect((err) => {
    if (err) {
        console.error("Koneksi Database Gagal:", err);
        return;
    }
    console.log("Terhubung ke Database!");
});

module.exports = connection;
