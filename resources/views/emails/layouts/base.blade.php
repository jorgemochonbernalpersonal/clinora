<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Clinora' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #334155;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background-color: #0F172A;
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            max-width: 160px;
            height: auto;
        }
        .email-body {
            padding: 40px 30px;
            background-color: #ffffff;
        }
        .email-footer {
            background-color: #F8FAFC;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #E2E8F0;
        }
        h1 {
            color: #0F172A;
            font-size: 24px;
            margin: 0 0 20px 0;
            font-weight: 700;
        }
        p {
            color: #334155;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 16px 0;
        }
        .button {
            display: inline-block;
            padding: 16px 32px;
            background-color: #0F172A;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 25px 0;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(15, 23, 42, 0.1);
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #1E293B;
        }
        .stats-container {
            display: flex;
            gap: 15px;
            margin: 25px 0;
        }
        .stat-card {
            flex: 1;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border-left: 4px solid #06B6D4;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #06B6D4;
            margin: 0;
        }
        .stat-label {
            font-size: 14px;
            color: #0E7490;
            margin: 5px 0 0 0;
        }
        .alert-box {
            background-color: #FFFBEB;
            border-left: 4px solid #F59E0B;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert-box p {
            margin: 0;
            color: #92400e;
        }
        .success-box {
            background-color: #F0FDF4;
            border-left: 4px solid #10B981;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .success-box p {
            margin: 0;
            color: #065F46;
        }
        .footer-links {
            margin: 20px 0;
        }
        .footer-links a {
            color: #64748B;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        .footer-links a:hover {
            color: #06B6D4;
        }
        .footer-text {
            color: #94A3B8;
            font-size: 12px;
            margin: 10px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .stats-container {
                flex-direction: column;
            }
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <div class="email-wrapper">
                    <!-- Header with Logo -->
                    <div class="email-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Clinora" class="logo">
                    </div>
                    
                    <!-- Email Body -->
                    <div class="email-body">
                        @yield('content')
                    </div>
                    
                    <!-- Footer -->
                    <div class="email-footer">
                        <div class="footer-links">
                            <a href="{{ route('home') }}">Inicio</a>
                            <a href="{{ route('faqs') }}">Ayuda</a>
                            <a href="{{ route('legal.terms') }}">Términos</a>
                            <a href="{{ route('legal.privacy') }}">Privacidad</a>
                        </div>
                        
                        <p class="footer-text">
                            Clinora - Software de Gestión para Profesionales de la Salud<br>
                            📧 <a href="mailto:info@clinora.es" style="color: #0EA5E9; text-decoration: none;">info@clinora.es</a>
                        </p>
                        
                        @auth
                        <p class="footer-text">
                            <a href="{{ profession_route('profile.settings') }}" style="color: #64748b; text-decoration: underline;">
                                Gestionar preferencias de email
                            </a>
                        </p>
                        @endauth
                        
                        <p class="footer-text">
                            © {{ date('Y') }} Clinora. Todos los derechos reservados.
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
