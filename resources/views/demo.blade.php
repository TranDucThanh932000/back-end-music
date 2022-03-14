<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Drive Demo</title>
</head>
<body>
    <form action="/upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="thing" onchange="onChangeFile(this.value)">
        <br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>

<script>
function onChangeFile(val){
    console.log(val)
}

</script>