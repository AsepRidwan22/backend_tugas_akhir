<!DOCTYPE html>
<html>

    <head>
        <title>Verifikasi PIN</title>
        <style>
            body {
                background-color: #f5f5f5;
                font-family: Arial, sans-serif;
                font-size: 16px;
                line-height: 1.5;
                color: #333333;
            }

            h2 {
                font-size: 24px;
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 10px;
            }

            h3 {
                font-size: 36px;
                font-weight: bold;
                margin-top: 20px;
                margin-bottom: 10px;
                color: #009688;
            }

            p {
                margin-top: 10px;
                margin-bottom: 10px;
            }
        </style>
    </head>

    <body>
        <div style="background-color: #ffffff; padding: 20px;">
            <h2>Verifikasi PIN</h2>
            <p>Untuk menyelesaikan proses login Anda, silakan masukkan kode PIN berikut:</p>
            <h3>{{ $pin }}</h3>
            <p>Jangan berikan kode ini kepada siapapun, karena akan digunakan untuk memverifikasi akun Anda.</p>
        </div>
    </body>

</html>