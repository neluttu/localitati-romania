<!DOCTYPE html>
<html lang="ro">

    <head>
        <meta charset="UTF-8">
        <title>Confirmă emailul</title>
    </head>

    <body style="font-family: Arial, sans-serif; background:#f7f7f7; padding:40px;">
        <div style="max-width:600px;margin:auto;background:#fff;padding:30px;border-radius:10px;">

            <h2 style="">Salut, {{ $user->profile->first_name }}</h2>

            <p style="font-size:16px;">
                Apasă pe butonul de mai jos pentru a confirma adresa ta de email:
            </p>

            <div style="margin:40px 0;">
                <a href="{{ $url }}"
                    style="background:#2563eb;color:white;padding:14px 24px;border-radius:8px;text-decoration:none;font-size:18px;">
                    Confirmă Emailul
                </a>
            </div>

            <p style="font-size:14px;color:#666;">
                Dacă nu ai cerut crearea contului, poți ignora acest email.
            </p>

        </div>
    </body>

</html>
