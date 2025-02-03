<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ubah variabel koneksi sesuai dengan kredensial yang benar
$servername = "sql309.ezyro.com";
$username = "ezyro_37516611";
$password = "ppsm1919";
$dbname = "ezyro_37516611_login";

// Fungsi untuk membuat username acak
function generateRandomUsername($length = 10) {
    $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi ke database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST['username'] ?? '';
    $captcha_input = $_POST['captcha'] ?? '';

    // Validasi inputan username
    if (empty($username_input)) {
        $message = "Username tidak boleh kosong.";
    } else {
        // Mencari username di database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika username ditemukan
            $message = "Login berhasil! Selamat datang kembali, <b>$username_input</b>.";
        } else {
            // Jika username tidak ditemukan, periksa captcha untuk registrasi
            if ($captcha_input !== "") {
                if ($captcha_input === "@alwywoii15") {
                    $stmt = $conn->prepare("INSERT INTO users (username) VALUES (?)");
                    $stmt->bind_param("s", $username_input);

                    if ($stmt->execute()) {
                        $message = "Registrasi berhasil! Username: <b>$username_input</b>";
                    } else {
                        $message = "Terjadi kesalahan saat registrasi: " . $conn->error;
                    }
                } else {
                    $message = "Captcha salah. Silakan coba lagi.";
                }
            } else {
                $message = "Username tidak ditemukan. Silakan daftar dengan memasukkan captcha.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
</head>
<body>
    <h2>Login/Register</h2>

    <?php if (!empty($message)): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo 'user_' . generateRandomUsername(); ?>" required><br><br>

        <label for="captcha">Captcha (Hanya untuk Registrasi):</label>
        <input type="text" id="captcha" name="captcha" placeholder="captcha hanya admin yang tau!"><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
