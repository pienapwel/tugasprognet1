<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Data dari form
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $semester = $_POST['semester'];
    $kode_matkul = $_POST['kode_matkul'];
    $nama_matkul = $_POST['nama_matkul'];
    $sks = $_POST['sks'];
    $nilai_angka = $_POST['nilai_angka'];

    $data_matkul = [];

    // Koneksi ke database
    $host = "localhost";
    $dbname = "mahasiswa_db";
    $username = "root";
    $password = "Bunga_2005";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi ke database gagal: " . $e->getMessage());
    }

    // Proses data mata kuliah
    for ($i = 0; $i < count($kode_matkul); $i++) {
        $nilai_huruf = '';

        if ($nilai_angka[$i] >= 80 && $nilai_angka[$i] <= 100) {
            $nilai_huruf = 'A';
        } elseif ($nilai_angka[$i] >= 70 && $nilai_angka[$i] < 80) {
            $nilai_huruf = 'B+';
        } elseif ($nilai_angka[$i] >= 60 && $nilai_angka[$i] < 70) {
            $nilai_huruf = 'B';
        } elseif ($nilai_angka[$i] >= 50 && $nilai_angka[$i] < 60) {
            $nilai_huruf = 'C+';
        } elseif ($nilai_angka[$i] >= 40 && $nilai_angka[$i] < 50) {
            $nilai_huruf = 'C';
        } elseif ($nilai_angka[$i] >= 30 && $nilai_angka[$i] < 40) {
            $nilai_huruf = 'D';
        } else {
            $nilai_huruf = 'E';
        }

        $data_matkul[] = [
            'kode_matkul' => $kode_matkul[$i],
            'nama_matkul' => $nama_matkul[$i],
            'sks' => $sks[$i],
            'nilai_angka' => $nilai_angka[$i],
            'nilai_huruf' => $nilai_huruf
        ];
    }

    // Simpan data mahasiswa
    $stmt = $pdo->prepare("INSERT INTO mahasiswa (nama, nim, semester) VALUES (:nama, :nim, :semester)");
    $stmt->execute([
        ':nama' => $nama,
        ':nim' => $nim,
        ':semester' => $semester
    ]);
    $mahasiswa_id = $pdo->lastInsertId();

    // Simpan data mata kuliah
    $stmt = $pdo->prepare("INSERT INTO mata_kuliah (mahasiswa_id, kode_matkul, nama_matkul, sks, nilai_angka, nilai_huruf) 
                           VALUES (:mahasiswa_id, :kode_matkul, :nama_matkul, :sks, :nilai_angka, :nilai_huruf)");
    foreach ($data_matkul as $matkul) {
        $stmt->execute([
            ':mahasiswa_id' => $mahasiswa_id,
            ':kode_matkul' => $matkul['kode_matkul'],
            ':nama_matkul' => $matkul['nama_matkul'],
            ':sks' => $matkul['sks'],
            ':nilai_angka' => $matkul['nilai_angka'],
            ':nilai_huruf' => $matkul['nilai_huruf']
        ]);
    }

    echo "<h1>Data Berhasil Disimpan!</h1>";
} else {
    // Form tetap sama
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form Data Mahasiswa</title>
    </head>
    <body>
        <h1>Input Data Mahasiswa</h1>
        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required><br><br>

            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" required><br><br>

            <label for="semester">Semester:</label>
            <input type="text" id="semester" name="semester" required><br><br>

            <h3>Data Mata Kuliah</h3>

            <div id="mata_kuliah_fields">
                <label for="kode_matkul">Kode Mata Kuliah:</label>
                <input type="text" id="kode_matkul" name="kode_matkul[]" required><br><br>

                <label for="nama_matkul">Nama Mata Kuliah:</label>
                <input type="text" id="nama_matkul" name="nama_matkul[]" required><br><br>

                <label for="sks">SKS:</label>
                <input type="number" id="sks" name="sks[]" required><br><br>

                <label for="nilai_angka">Nilai Angka (0-100):</label>
                <input type="number" id="nilai_angka" name="nilai_angka[]" required><br><br>
            </div>

            <button type="submit">Submit</button>
        </form>
    </body>
    </html>';
}
?>
