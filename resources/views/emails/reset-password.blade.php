<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <style>
            /* styling complet custom */
        </style>
    </head>

    <body>
        <p>Salut {{ $email }},</p>
        <p>Ai cerut resetarea parolei.</p>

        <p>
            <a href="{{ $url }}"
                style="background:#4f46e5;color:#fff;padding:12px 20px;border-radius:6px;text-decoration:none;">
                Resetare parolă
            </a>
        </p>

        <p>Dacă nu ai făcut această cerere, ignoră acest email.</p>

        <p>Mulțumim,<br>{{ config('app.name') }}</p>
    </body>

</html>
