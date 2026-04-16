<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2.5rem;
        }
        .table-wrapper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 16px;
            border-bottom: 1px solid #dee2e6;
            color: #212529;
        }
        tbody tr:hover {
            background: #f8f9fa;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .code {
            font-family: 'Monaco', 'Courier New', monospace;
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: #d63384;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #6c757d;
        }
        .empty-state p {
            font-size: 1.1rem;
        }
        .timestamp {
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Les 10 Derniers OTP</h1>
        
        <div class="table-wrapper">
            @if ($otps->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Code OTP</th>
                            <th>Identifiant</th>
                            <th>Type</th>
                            <th>Utilisé</th>
                            <th>Vérifié</th>
                            <th>Tentatives</th>
                            <th>Expire à</th>
                            <th>Créé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($otps as $otp)
                            <tr>
                                <td>
                                    <span class="code">{{ $otp->otp_code }}</span>
                                </td>
                                <td>{{ $otp->identifier }}</td>
                                <td>
                                    @switch($otp->type)
                                        @case('registration')
                                            <span class="badge badge-info">Inscription</span>
                                            @break
                                        @case('login')
                                            <span class="badge badge-info">Connexion</span>
                                            @break
                                        @case('password_reset')
                                            <span class="badge badge-warning">Réinitialisation</span>
                                            @break
                                        @case('phone_verification')
                                            <span class="badge badge-info">Vérification Téléphone</span>
                                            @break
                                        @default
                                            <span class="badge badge-info">{{ $otp->type }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if ($otp->is_used)
                                        <span class="badge badge-success">Oui</span>
                                    @else
                                        <span class="badge badge-danger">Non</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($otp->verified_at)
                                        <span class="badge badge-success">Oui</span>
                                        <div class="timestamp">{{ $otp->verified_at->format('d/m/Y H:i:s') }}</div>
                                    @else
                                        <span class="badge badge-danger">Non</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="code">{{ $otp->attempts }}</span>
                                </td>
                                <td class="timestamp">{{ $otp->expires_at->format('d/m/Y H:i:s') }}</td>
                                <td class="timestamp">{{ $otp->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <p>Aucun OTP trouvé</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
