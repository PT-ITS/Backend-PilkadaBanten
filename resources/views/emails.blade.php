<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php
    $id = base64_encode($id);
    ?>
    <div class="container">
        <h1>Email Verification</h1>
        <p>Terima kasih telah mendaftar! Tekan tombol di bawah ini untuk mengaktifkan akun Anda:</p>
        <a href="{{ route('password', ['id' => $id]) }}">Aktivasi Akun</a>
    </div>
</body>

</html>
