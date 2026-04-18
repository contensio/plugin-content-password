<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Protected</title>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f9f7f4;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
        .card{background:#fff;border:1px solid #e5e2dd;border-radius:1.25rem;padding:2.5rem;max-width:400px;width:100%;text-align:center}
        .icon{width:3.5rem;height:3.5rem;background:#f3f4f6;border-radius:.875rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}
        h1{font-size:1.25rem;font-weight:800;color:#1a1a1a;margin-bottom:.5rem}
        p.sub{font-size:.875rem;color:#6b7280;margin-bottom:1.5rem}
        .error{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:.5rem;padding:.625rem 1rem;font-size:.8125rem;margin-bottom:1rem;text-align:left}
        input[type=password]{width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.625rem .875rem;font-size:.9375rem;margin-bottom:.875rem;outline:none}
        input[type=password]:focus{border-color:#e05b2b;box-shadow:0 0 0 3px rgba(224,91,43,.15)}
        button{width:100%;background:#e05b2b;color:#fff;border:none;border-radius:.5rem;padding:.75rem;font-size:.9375rem;font-weight:600;cursor:pointer}
        button:hover{background:#c94d21}
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg width="24" height="24" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1>Password Protected</h1>
        <p class="sub">This content is protected. Enter the password to continue.</p>

        @if($error)
        <div class="error">{{ $error }}</div>
        @endif

        <form method="POST" action="">
            @csrf
            <input type="password" name="content_password" placeholder="Enter password" autofocus required>
            <button type="submit">Unlock</button>
        </form>
    </div>
</body>
</html>
