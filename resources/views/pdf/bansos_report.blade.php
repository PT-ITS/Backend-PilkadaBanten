<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bansos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Bansos Berdasarkan Kabupaten/Kota</h1>
    <table>
        <thead>
            <tr>
                <th>Kabupaten/Kota</th>
                <th>Jumlah Warga Diinput</th>
                <th>Jumlah Sudah Terima Bansos</th>
                <th>Jumlah Belum Terima Bansos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kabupatenData as $kabupaten)
                <tr>
                    <td>{{ $kabupaten->name }}</td>
                    <td>{{ $kabupaten->wargas->sum('total_warga') }}</td>
                    <td>{{ $kabupaten->wargas->sum('sudah_terima') }}</td>
                    <td>{{ $kabupaten->wargas->sum('belum_terima') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                <td>{{ $totalWarga }}</td>
                <td>{{ $totalSudahTerima }}</td>
                <td>{{ $totalBelumTerima }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
