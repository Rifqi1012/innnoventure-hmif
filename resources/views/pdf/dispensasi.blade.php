<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $data['judul'] }}</title>
    <style>
        @page {
            margin-top: 38mm;
            margin-right: 18mm;
            margin-bottom: 28mm;
            margin-left: 18mm;
        }
    </style>
</head>
<body>
    @include('surat.dispensasi-document', ['data' => $data, 'preview' => false])
</body>
</html>
