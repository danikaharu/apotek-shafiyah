<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengingat Ulang Tahun</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px;">
    <div
        style="max-width: 600px; background-color: #ffffff; margin: auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h2 style="color: #2196F3;">📅 Pengingat Ulang Tahun</h2>
        <p style="font-size: 16px; color: #333;">
            Hai {{ $customer->name }},
        </p>
        <p style="font-size: 16px; color: #333;">
            Kami hanya ingin mengingatkan bahwa hari ini adalah hari ulang tahunmu. 🎉
        </p>
        <p style="font-size: 16px; color: #333;">
            Semoga hari spesialmu penuh dengan kebahagiaan dan kesehatan.
        </p>
        <hr style="margin: 30px 0;">
        <p style="font-size: 14px; color: #777;">Salam hangat,<br><strong>{{ config('app.name') }}</strong></p>
    </div>
</body>

</html>
