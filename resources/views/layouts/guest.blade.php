<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Noteku')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500&family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:    '#23A9BD',
                        'primary-dk': '#1b8a9a',
                        secondary:  '#0D4E59',
                        tertiary:   '#DA8642',
                        bg:         '#07111a',
                        'bg-2':     '#0c1c28',
                        'bg-3':     '#112030',
                        'bg-card':  '#0f1e2b',
                        border:     '#163040',
                        'border-2': '#1e3f53',
                        tx:         '#e2eaf0',
                        'tx-2':     '#6b8fa3',
                        'tx-3':     '#3a5a6e',
                    },
                    fontFamily: {
                        display: ['Hanken Grotesk', 'sans-serif'],
                        body:    ['Inter', 'sans-serif'],
                        label:   ['Geist', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    @livewireStyles
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #23A9BD;
            --primary-dk: #1b8a9a;
            --secondary:  #0D4E59;
            --bg:         #07111a;
            --bg-2:       #0c1c28;
            --bg-3:       #112030;
            --bg-card:    #0f1e2b;
            --border:     #163040;
            --border-2:   #1e3f53;
            --text:       #e2eaf0;
            --text-2:     #6b8fa3;
            --text-3:     #3a5a6e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(13,78,89,0.45) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 80% 80%, rgba(35,169,189,0.06) 0%, transparent 60%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            width: 100%;
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow:
                0 32px 80px rgba(0,0,0,0.6),
                0 0 0 1px rgba(35,169,189,0.06),
                inset 0 1px 0 rgba(35,169,189,0.08);
        }

        .auth-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .auth-logo-icon {
            width: 56px;
            height: 56px;
            background: var(--bg-3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-2);
            box-shadow: 0 0 28px rgba(35,169,189,0.2), inset 0 1px 0 rgba(35,169,189,0.1);
        }
        .auth-logo-icon svg { width: 26px; height: 26px; stroke: var(--primary); }

        .auth-logo-name {
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .auth-logo-sub {
            font-size: 13px;
            color: var(--text-2);
            margin-top: -4px;
        }

        .auth-page-footer {
            width: 100%;
            max-width: 960px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 28px;
            font-size: 12px;
            color: var(--text-3);
        }
        .auth-page-footer a { color: var(--text-3); text-decoration: none; transition: color 0.15s; }
        .auth-page-footer a:hover { color: var(--text-2); }
        .auth-page-footer-links { display: flex; gap: 20px; }

        @media (max-width: 480px) {
            .auth-card { padding: 32px 24px; border-radius: 16px; }
            .auth-page-footer { flex-direction: column; gap: 10px; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <line x1="10" y1="9" x2="8" y2="9"/>
                    </svg>
                </div>
                <div class="auth-logo-name">Noteku</div>
                @yield('logo-sub')
            </div>

            @yield('content')
        </div>
    </div>

    <footer class="auth-page-footer">
        <span>© 2024 Noteku. Designed for deep work.</span>
        <div class="auth-page-footer-links">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Support</a>
            <a href="#">Twitter</a>
        </div>
    </footer>

    @livewireScripts
</body>
</html>