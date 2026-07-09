<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($subject) ? htmlspecialchars($subject) : 'Marigold Signature' ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0a0a0a;
            color: #ffffff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0a0a0a;
            padding-bottom: 60px;
        }
        .main {
            background-color: #111111;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border: 1px solid #222222;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 40px;
        }
        .header {
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid #222222;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 24px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header h1 span {
            color: #c8a96e; /* Gold accent */
        }
        .content {
            padding: 40px 30px;
            color: #cccccc;
            line-height: 1.6;
            font-size: 15px;
        }
        .content h2 {
            color: #ffffff;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background-color: #c8a96e;
            color: #111111 !important;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #666666;
            border-top: 1px solid #222222;
        }
        .footer a {
            color: #c8a96e;
            text-decoration: none;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .data-table th, .data-table td {
            padding: 12px;
            border-bottom: 1px solid #333333;
            text-align: left;
        }
        .data-table th {
            color: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .summary-box {
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #333333;
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <!-- Header -->
            <tr>
                <td class="header">
                    <h1>MARIGOLD<span>.</span></h1>
                </td>
            </tr>
            
            <!-- Body Content -->
            <tr>
                <td class="content">
                    <?= $content ?? '' ?>
                </td>
            </tr>
            
            <!-- Footer -->
            <tr>
                <td class="footer">
                    <p>Marigold Signature Ltd.<br>14 Adeola Odeku St, Victoria Island, Lagos</p>
                    <p>If you have any questions, reply to this email or contact <a href="mailto:support@marigoldsignature.com">support@marigoldsignature.com</a></p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
