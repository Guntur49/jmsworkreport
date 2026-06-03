<?php
// File untuk menambahkan kolom updated_at

$conn = new mysqli("127.0.0.1", "root", "", "jms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE rumah_sakit ADD COLUMN updated_at TIMESTAMP NULL";

if ($conn->query($sql) === TRUE) {
    echo "Kolom updated_at berhasil ditambahkan\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
?>
