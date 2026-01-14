<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado para Terapia de Pareja</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #1f2937; border-bottom: 3px solid #3b82f6; padding-bottom: 10px; }
        .section { margin-bottom: 25px; padding: 15px; background-color: #f9fafb; border-left: 4px solid #3b82f6; }
        .info-box { background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 5px; }
        .warning-box { background-color: #fef3c7; border: 1px solid #fcd34d; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>CONSENTIMIENTO INFORMADO PARA TERAPIA DE PAREJA</h1>

    <div class="section">
        <h2>1. NATURALEZA DE LA TERAPIA DE PAREJA</h2>
        <p>
            La terapia de pareja es un proceso colaborativo diseñado para ayudar a ambos miembros 
            a resolver conflictos y mejorar su relación. El paciente es "la relación" en sí misma, 
            y el profesional trabaja con ambos miembros simultáneamente.
        </p>
    </div>

    <div class="section">
        <h2>2. MIEMBROS DE LA TERAPIA</h2>
        <p><strong>Paciente 1:</strong> {{ $contactName ?? '_________________' }}</p>
        <p><strong>Paciente 2:</strong> {{ $data['partner_name'] ?? '_________________' }}</p>
    </div>

    <div class="section">
        <h2>3. POLÍTICA DE CONFIDENCIALIDAD Y "NO SECRETOS"</h2>
        <div class="warning-box">
            <p>
                En terapia de pareja, el profesional mantiene una política de <strong>"no secretos"</strong>. 
                Cualquier información compartida individualmente que sea relevante para la relación 
                puede ser llevada a la sesión conjunta si el profesional lo considera terapéuticamente necesario.
            </p>
        </div>
    </div>

    <div class="section">
        <h2>4. OBJETIVOS</h2>
        <p>
            Mejorar la comunicación, resolver conflictos específicos, fortalecer el vínculo afectivo 
            o, si fuera el caso, mediar en una separación lo más saludable posible.
        </p>
    </div>

    <div class="section">
        <h2>5. CONSENTIMIENTO</h2>
        <p>
            Ambos miembros aceptamos las condiciones de la terapia de pareja y consufeelmente 
            participar en el proceso.
        </p>
        <div style="margin-top: 30px;">
            <p><strong>Firma de (1):</strong> _________________ &nbsp;&nbsp; <strong>Firma de (2):</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
