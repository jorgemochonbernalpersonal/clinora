<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado para EMDR</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #1f2937; border-bottom: 3px solid #3b82f6; padding-bottom: 10px; }
        .section { margin-bottom: 25px; padding: 15px; background-color: #f9fafb; border-left: 4px solid #3b82f6; }
        .info-box { background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>CONSENTIMIENTO INFORMADO PARA TERAPIA EMDR</h1>

    <div class="section">
        <h2>1. ¿QUÉ ES EL EMDR?</h2>
        <p>
            EMDR (Desensibilización y Reprocesamiento por los Movimientos Oculares) es un abordaje psicoterapéutico 
            especialmente indicado para el tratamiento de dificultades emocionales causadas por experiencias 
            traumáticas o eventos estresantes del pasado.
        </p>
    </div>

    <div class="section">
        <h2>2. EL PROCESO</h2>
        <p>
            El tratamiento utiliza la estimulación bilateral (movimientos oculares, sonidos o golpeteos) 
            para ayudar al cerebro a procesar recuerdos traumáticos. Durante el proceso, es posible que 
            experimente emociones intensas o sensaciones físicas asociadas a los recuerdos.
        </p>
    </div>

    @if(isset($data['target_trauma']) && !empty($data['target_trauma']))
    <div class="section">
        <h2>3. OBJETIVO DE LA INTERVENCIÓN</h2>
        <div class="info-box">
            <p><strong>Evento/s objetivo:</strong> {{ $data['target_trauma'] }}</p>
        </div>
    </div>
    @endif

    <div class="section">
        <h2>4. RIESGOS Y BENEFICIOS</h2>
        <p>
            Al procesar información, pueden aparecer recuerdos perturbadores. No obstante, el objetivo 
            es reducir significativamente el malestar asociado a dichos recuerdos. Usted tiene el control 
            en todo momento y puede pedir detener la sesión cuando lo desee.
        </p>
    </div>

    <div class="section">
        <h2>5. CONSENTIMIENTO</h2>
        <p>
            Yo, <strong>{{ $contactName ?? '_________________' }}</strong>, entiendo la naturaleza 
            de la terapia EMDR y consiento voluntariamente participar en este tratamiento.
        </p>
        <div style="margin-top: 30px;">
            <p><strong>Firma del paciente:</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
