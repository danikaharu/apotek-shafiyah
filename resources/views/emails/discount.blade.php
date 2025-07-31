<!DOCTYPE html>
<html>

<head>
    <title>Promo Apotek</title>
</head>

<body style="font-family: sans-serif; background: #f8f8f8; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #d32f2f;">🎉 Promo Menarik dari {{ config('app.name') }}!</h2>
        <p>Halo {{ $customer->name }},</p>
        <p>Kami sedang mengadakan <strong>diskon spesial</strong> untuk beberapa produk tertentu.</p>
        <p>Jangan lewatkan kesempatan ini. Kunjungi apotek kami sekarang!</p>
        <p style="color: gray;">*Syarat & ketentuan berlaku</p>
        <hr>
        <p>Salam sehat,<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>
