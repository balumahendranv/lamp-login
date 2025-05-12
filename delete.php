<?php
include 'conn.php';
$id = $_GET['infoID'];
$sql = "Delete from info_tbl where md5(infoID) = '$id'";
if ($conn->query($sql) === true) {
    echo "<script>
alert('Record Deleted');
window.location.href='maintenance.php';
</script>";
} else {
    echo "Oppps something error ";
}
$conn->close();
