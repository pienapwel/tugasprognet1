<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mahasiswa_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$mahasiswa_id = $_GET['id'] ?? 0;
$kode_matkul = $_GET['matkul'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_nama_matkul = $_POST['nama_matkul'] ?? '';
    $new_sks = $_POST['sks'] ?? 0;
    $new_nilai_angka = $_POST['nilai_angka'] ?? 0;

    $new_nilai_huruf = 'E';
    if ($new_nilai_angka >= 80) $new_nilai_huruf = 'A';
    elseif ($new_nilai_angka >= 70) $new_nilai_huruf = 'B+';
    elseif ($new_nilai_angka >= 60) $new_nilai_huruf = 'B';
    elseif ($new_nilai_angka >= 50) $new_nilai_huruf = 'C+';
    elseif ($new_nilai_angka >= 40) $new_nilai_huruf = 'C';
    elseif ($new_nilai_angka >= 30) $new_nilai_huruf = 'D+';
    elseif ($new_nilai_angka >= 20) $new_nilai_huruf = 'D';

    $sql_update = "UPDATE mata_kuliah SET nama_matkul=?, sks=?, nilai_angka=?, nilai_huruf=? WHERE mahasiswa_id=? AND kode_matkul=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sidsis", $new_nama_matkul, $new_sks, $new_nilai_angka, $new_nilai_huruf, $mahasiswa_id, $kode_matkul);
    $stmt_update->execute();

    header("Location: process.php?id=$mahasiswa_id");
    exit;
}

$sql = "SELECT * FROM mata_kuliah WHERE mahasiswa_id = ? AND kode_matkul = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $mahasiswa_id, $kode_matkul);
$stmt->execute();
$result = $stmt->get_result();
$matkul = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>Edit Mata Kuliah</title>
    /* CSS Style */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

form div {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

input[type="text"],
input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="number"]:focus {
    border-color: #007BFF;
    outline: none;
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

button[type="submit"]:focus {
    outline: none;
}
</head>
<body>
    <h1>Edit Mata Kuliah</h1>
    <form method="POST" action="">
        <div>
            <label>Kode Mata Kuliah: </label>
            <input type="text" name="kode_matkul" value="<?php echo htmlspecialchars($matkul['kode_matkul']); ?>" readonly>
        </div>
        <div>
            <label>Nama Mata Kuliah: </label>
            <input type="text" name="nama_matkul" value="<?php echo htmlspecialchars($matkul['nama_matkul']); ?>" required>
        </div>
        <div>
            <label>SKS: </label>
            <input type="number" name="sks" value="<?php echo htmlspecialchars($matkul['sks']); ?>" required>
        </div>
        <div>
            <label>Nilai Angka (0-100): </label>
            <input type="number" name="nilai_angka" value="<?php echo htmlspecialchars($matkul['nilai_angka']); ?>" required>
        </div>
        <div>
            <button type="submit">Simpan Perubahan</button>
        </div>
    </form>
</body>
</html>

<?php
$conn->close();
?>
