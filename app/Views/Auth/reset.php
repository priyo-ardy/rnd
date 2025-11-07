<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Schlemmer APQP</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #0056b3, #003d82);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .greeting strong {
            color: #0056b3;
        }

        .message {
            margin-bottom: 25px;
            font-size: 15px;
            color: #555;
        }

        .password-box {
            background-color: #f0f7ff;
            border: 2px dashed #0056b3;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }

        .password-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .new-password {
            font-size: 28px;
            font-weight: bold;
            color: #0056b3;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 5px;
            margin: 10px 0;
        }

        .warning {
            background-color: #fff8e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #856404;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 25px;
            border-top: 1px solid #e9ecef;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .company-info strong {
            color: #0056b3;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .company-details {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }

        .note {
            font-size: 12px;
            color: #999;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            background-color: #0056b3;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 15px 0;
        }

        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }

            .header {
                padding: 20px 15px;
            }

            .header h1 {
                font-size: 20px;
            }

            .new-password {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Reset Password</h1>
            <p>Schlemmer APQP Application</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Halo, <strong><?= ucwords($full_name) ?></strong></p>
            </div>

            <div class="message">
                <p>Kami mendapatkan permintaan untuk melakukan reset password pada akun Schlemmer APQP Application Anda. Berikut adalah password baru Anda:</p>
            </div>

            <!-- Password Box -->
            <div class="password-box">
                <div class="password-label">Password Baru Anda:</div>
                <div class="new-password"><?= $new_password ?></div>
                <div class="password-label">Harap simpan password ini dengan aman</div>
            </div>

            <!-- Warning -->
            <div class="warning">
                <p><strong>Penting:</strong> Jika Anda tidak merasa melakukan permintaan reset password, segera hubungi tim IT kami untuk dilakukan pengecekan keamanan akun.</p>
            </div>

            <div class="message">
                <p>Setelah login, kami sangat menyarankan untuk mengubah password ini melalui menu profil pengguna.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="company-info">
                <strong>Terima kasih,</strong>
                <div class="company-details">
                    <strong>System Administrator</strong><br>
                    PT. Schlemmer Automotive Indonesia<br>
                    Kawasan Industri Delta Silicon 3<br>
                    Jl. Johar, Blok F8 No.6<br>
                    Cikarang Pusat, Kab. Bekasi, Jawa Barat 17530<br>
                    Phone: 021-8991-3741<br>
                    Email: it@schlemmer.co.id
                </div>
            </div>

            <div class="note">
                <p><em>Note: Jangan membalas email ini, email ini dikirim secara otomatis oleh sistem.</em></p>
            </div>
        </div>
    </div>
</body>

</html>