<form action="/admin/statistic/upload" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv">
    <button type="submit">Upload</button>
</form>
</form>
