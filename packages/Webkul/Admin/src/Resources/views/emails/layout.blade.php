<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ config('app.name') }} Email</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header a {
            text-decoration: none;
        }

        .content {
            color: #333;
            line-height: 1.6;
            font-size: 16px;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: center;
            color: #777;
        }

        .footer p {
            margin: 10px 0;
        }

        .footer a {
            color: #4C75A3;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Email Header -->
        <div class="header">
            <a href="{{ config('app.url') }}" style="font-size: 24px; color: #4C75A3; font-weight: 600;">
                {{ config('app.name') }}
            </a>
        </div>

        <!-- Email Content -->
        <div class="content">
            {{ $slot }}
        </div>

        <!-- Email Footer -->
        <div class="footer">
            <p>@lang('admin::app.emails.common.cheers', ['app_name' => config('app.name')])</p>
            <p>Se você não reconhece esta ação, por favor, <a href="{{ config('app.url') }}/support">entre em contato com o suporte</a>.</p>
        </div>
    </div>

</body>
</html>
