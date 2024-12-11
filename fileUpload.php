<?php
error_reporting(0)
    ?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File upload</title>
</head>

<body>
    <form action="#" method="POST" enctype="multipart/form-data">
        <input type="file" name="uploadfile">
        <input type="submit" name="upload" value="upload file">
    </form>
</body>

</html>

<?php

$fileName = $_FILES["uploadfile"]["name"];
$tempName = $_FILES["uploadfile"]["tmp_name"];
$folder = "upload/" . $fileName;
move_uploaded_file($tempName, $folder);
if ($folder) {

    echo "<img src='$folder' height='100' width='100' >";
}
?>