<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $semester = $_POST['semester'];

    $kode_matkul = $_POST['kode_matkul'];
    $nama_matkul = $_POST['nama_matkul'];
    $sks = $_POST['sks'];
    $nilai_angka = $_POST['nilai_angka'];

    $data_matkul = [];

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


    echo "<h1>Data Mahasiswa</h1>";
    echo "Nama: " . htmlspecialchars($nama) . "<br>";
    echo "NIM: " . htmlspecialchars($nim) . "<br>";
    echo "Semester: " . htmlspecialchars($semester) . "<br>";
    
    echo "<h2>Data Mata Kuliah</h2>";
    echo "<table border='1'>
            <tr>
                <th>Kode Mata Kuliah</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Nilai Angka</th>
                <th>Nilai Huruf</th>
            </tr>";

    foreach ($data_matkul as $matkul) {
        echo "<tr>
                <td>{$matkul['kode_matkul']}</td>
                <td>{$matkul['nama_matkul']}</td>
                <td>{$matkul['sks']}</td>
                <td>{$matkul['nilai_angka']}</td>
                <td>{$matkul['nilai_huruf']}</td>
              </tr>";
    }

    echo "</table>";
} else {
   
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
