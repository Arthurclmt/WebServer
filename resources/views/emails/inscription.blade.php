<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4f46e5; /* Un violet/bleu moderne */
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }
        .pseudo {
            color: #4f46e5;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">Pixel Perfect</h1>
        </div>

        <div class="content">
            <h2>Salut <span class="pseudo">{{ $user->pseudo }}</span> ! 👋</h2>
            <p>C'est un plaisir de t'accueillir parmi nous.</p>
            <p>Ton inscription sur <strong>Pixel Perfect</strong> a bien été validée. Tu fais désormais partie de la communauté !</p>
            
            <p>Tu peux dès maintenant explorer la plateforme et profiter de toutes les fonctionnalités.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Se connecter au Dashboard</a>
            </div>
            
            <p style="margin-top: 30px;">À très vite sur Pixel Perfect !<br><em>L'équipe Support</em></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Pixel Perfect. Tous droits réservés.</p>
            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>