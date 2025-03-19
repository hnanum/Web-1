<?php
// Inisialisasi variabel untuk menyimpan nilai input dan error
$nama = $email = $nomor = $sewa = $item = $alamat = "";
$namaErr = $emailErr = $nomorErr = $alamatErr = $tanggalErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validasi Nama
    $nama = $_POST["nama"];
    if (empty($nama)) {
        $namaErr = "Nama wajib diisi";
    }

    // Validasi Email
    $email = $_POST["email"];
    if (empty($email)) {
        $emailErr = "Email wajib diisi";
    }

    // Validasi Nomor Telepon
    $nomor = $_POST["nomor"];
    if (empty($nomor)) {
        $nomorErr = "Nomor Telepon wajib diisi";
    } elseif (!ctype_digit($nomor)) {
        $nomorErr = "Nomor Telepon harus berupa angka";
    }

    // Validasi Alamat
    $alamat = $_POST["alamat"];
    if (empty($alamat)) {
        $alamatErr = "Alamat wajib diisi";
    }

    // Validasi dan Hitung Lama Sewa
    $tanggal_mulai = $_POST["tanggal_mulai"];
    $tanggal_selesai = $_POST["tanggal_selesai"];

    if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
        $tanggalErr = "Tanggal wajib diisi";
    } 
    elseif ($tanggal_mulai > $tanggal_selesai) {
        $tanggalErr = "❌ Tanggal mulai tidak boleh lebih besar dari tanggal selesai!";
    } 
    else {
        // Hitung lama sewa dalam hari
        $date1 = new DateTime($tanggal_mulai);
        $date2 = new DateTime($tanggal_selesai);
        $interval = $date1->diff($date2);
        $lamaSewa = $interval->days + 1; // +1 agar hari pertama juga dihitung
    }

    // Menyimpan pilihan sewa tanpa validasi khusus
    $sewa = $_POST["sewa"];
    $item = $_POST["item"];

     // Bersihkan formulir jika tidak ada error
     if (empty($namaErr) && empty($emailErr) && empty($nomorErr) && empty($alamatErr) && empty($tanggalErr)) {
        $nama = $email = $nomor = $alamat = $tanggal_mulai = $tanggal_selesai = "";
    }
   
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penyewaan Baby Island</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Form Penyewaan </h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>">
                <span class="error"><?php echo $namaErr ? "* $namaErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr ? "* $emailErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="nomor">Nomor Telepon:</label>
                <input type="text" id="nomor" name="nomor" value="<?php echo $nomor; ?>">
                <span class="error"><?php echo $nomorErr ? "* $nomorErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="sewa">Kategori Penyewaan:</label>
                <select id="sewa" name="sewa">
                    <option value="Transportasi & Keamanan" <?php echo ($sewa == "Transportasi & Keamanan") ? "selected" : ""; ?>>Transportasi & Keamanan</option>
                    <option value="Mainan & edukasi" <?php echo ($sewa == "Mainan & edukasi") ? "selected" : ""; ?>>Mainan & edukasi</option>
                    <option value="Tidur & kenyamanan" <?php echo ($sewa == "Tidur & kenyamanan") ? "selected" : ""; ?>>Tidur & kenyamanan
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="item">Pilih Penyewaan:</label>
                <select id="item" name="item">
                    <option value="Stroller" <?php echo ($item == "Stroller") ? "selected" : ""; ?>>Stroller</option>
                    <option value="Buku A B C" <?php echo ($item == "Buku A B C") ? "selected" : ""; ?>>Buku A B C</option>
                    <option value="Bantal & guling" <?php echo ($item == "Bantal & guling") ? "selected" : ""; ?>>Bantal & guling
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Sewa:</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo $tanggal_mulai; ?>" required>

                <label for="tanggal_selesai">Tanggal Pengembalian:</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo $tanggal_selesai; ?>" required>

                <span class="error"><?php echo $tanggalErr ? "* $tanggalErr" : ""; ?></span>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Pengiriman:</label>
                <textarea id="alamat" name="alamat"><?php echo $alamat; ?></textarea>
                <span class="error"><?php echo $alamatErr ? "* $alamatErr" : ""; ?></span>
            </div>

            <div class="button-container">
                <button type="submit">Sewa sekarang!</button>
            </div>
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$namaErr && !$emailErr && !$nomorErr && !$alamatErr) { ?>
    <div class="container">
        <h3>Data Pembelian:</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="20%">Nama</th>
                        <th width="20%">Email</th>
                        <th width="15%">Nomor Telepon</th>
                        <th width="15%">Kategori</th>
                        <th widht="15%">Item Penyewaan</th>
                        <th width="30%">Alamat Pengiriman</th>
                        <th width="20%">Tanggal Sewa</th>
                        <th width="20%">Tanggal Pengembalian</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td><?php echo $_POST["nama"]; ?></td>
                            <td><?php echo $_POST["email"]; ?></td>
                            <td><?php echo $_POST["nomor"]; ?></td>
                            <td><?php echo $_POST["sewa"]; ?></td>
                            <td><?php echo $_POST["item"]; ?></td>
                            <td><?php echo $_POST["alamat"]; ?></td>
                            <td><?php echo $_POST["tanggal_mulai"]; ?></td>
                            <td><?php echo $_POST["tanggal_selesai"]; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</body>

</html>