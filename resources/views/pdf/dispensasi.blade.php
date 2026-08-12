<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>{{ $data['judul'] }}</title></head>
<body>
    @include('surat.dispensasi-document', ['data' => $data, 'preview' => false])
</body>
</html>
