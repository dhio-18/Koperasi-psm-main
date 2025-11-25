<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f7; font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="padding: 25px 0;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 600px; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:#4f46e5; padding: 25px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:26px;">Koperasi PSM</h1>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding: 30px 40px; color:#333333;">

                            <p style="font-size: 18px; font-weight: 600; margin-top:0;">
                                Halo {{ $name }},
                            </p>

                            <p style="font-size: 16px; line-height: 1.6; margin: 20px 0;">
                                Kami menerima permintaan untuk mengatur ulang password akun Anda.
                                Silakan klik tombol di bawah ini untuk melanjutkan proses reset password.
                            </p>

                            <!-- BUTTON -->
                            <table cellpadding="0" cellspacing="0" border="0" role="presentation" style="margin: 30px auto; text-align:center;">
                                <tr>
                                    <td>
                                        <a href="{{ $url }}"
                                           style="background-color:#4f46e5;
                                                  padding:14px 28px;
                                                  color:#ffffff;
                                                  font-size:16px;
                                                  font-weight:bold;
                                                  border-radius:6px;
                                                  text-decoration:none;
                                                  display:inline-block;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 15px; color:#555555; line-height:1.6;">
                                Jika Anda tidak merasa meminta reset password, Anda dapat mengabaikan email ini.
                                Link ini hanya berlaku selama <strong>60 menit</strong>.
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f4f4f7; padding: 20px 40px; text-align:center; color:#777777; font-size:13px;">
                            <p style="margin:0;">© {{ date('Y') }} Koperasi PSM.<br> Semua Hak Dilindungi.</p>
                            <p style="margin:8px 0 0; font-size:12px;">Email ini dikirim secara otomatis, mohon tidak membalas pesan ini.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
