<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .info-row {
            margin-bottom: 15px;
            padding: 10px;
            background-color: white;
            border-radius: 3px;
        }
        .label {
            font-weight: bold;
            color: #0d6efd;
        }
        .message-box {
            background-color: white;
            padding: 20px;
            border-left: 4px solid #0d6efd;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nouveau message de contact</h1>
    </div>
    
    <div class="content">
        <div class="info-row">
            <span class="label">Nom complet :</span><br>
            {{ $contactData['first_name'] }} {{ $contactData['last_name'] }}
        </div>
        
        <div class="info-row">
            <span class="label">Email :</span><br>
            <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
        </div>
        
        @if(!empty($contactData['phone']))
        <div class="info-row">
            <span class="label">Téléphone :</span><br>
            {{ $contactData['phone'] }}
        </div>
        @endif
        
        <div class="info-row">
            <span class="label">Sujet :</span><br>
            {{ $contactData['subject_label'] }}
        </div>
        
        <div class="message-box">
            <span class="label">Message :</span><br><br>
            {!! nl2br(e($contactData['message'])) !!}
        </div>
    </div>
    
    <div class="footer">
        <p>Ce message a été envoyé depuis le formulaire de contact</p>
        <p>Date : {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>