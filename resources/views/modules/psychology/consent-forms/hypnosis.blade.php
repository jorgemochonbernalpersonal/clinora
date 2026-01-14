<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado para Hipnosis Clínica</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #1f2937; border-bottom: 3px solid #3b82f6; padding-bottom: 10px; }
        .section { margin-bottom: 25px; padding: 15px; background-color: #f9fafb; border-left: 4px solid #3b82f6; }
        .info-box { background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>CONSENTIMIENTO INFORMADO PARA HIPNOSIS CLÍNICA</h1>

    <div class="section">
        <h2>1. ¿QUÉ ES LA HIPNOSIS CLÍNICA?</h2>
        <p>
            La hipnosis clínica es una técnica terapéutica que utiliza estados de atención focalizada 
            para facilitar cambios positivos en pensamientos, sentimientos o comportamientos. 
            No es un estado de sueño, sino un estado de relajación profunda y concentración.
        </p>
    </div>

    @if(isset($data['hypnosis_objective']) && !empty($data['hypnosis_objective']))
    <div class="section">
        <h2>2. OBJETIVO DE LA INTERVENCIÓN</h2>
        <div class="info-box">
            <p><strong>Objetivo específico:</strong> {{ $data['hypnosis_objective'] }}</p>
        </div>
    </div>
    @endif

    <div class="section">
        <h2>3. MITOS Y REALIDADES</h2>
        <p>
            Durante la hipnosis, usted permanecerá consciente y mantendrá el control en todo momento. 
            No se le puede obligar a hacer nada en contra de su voluntad o valores. El proceso se 
            basa en la colaboración entre usted y el profesional.
        </p>
    </div>

    <div class="section">
        <h2>4. CONSENTIMIENTO</h2>
        <p>
            Yo, <strong>{{ $contactName ?? '_________________' }}</strong>, entiendo la naturaleza 
            de la hipnosis clínica y consiento voluntariamente participar en este tratamiento.
        </p>
        <div style="margin-top: 30px;">
            <p><strong>Firma del paciente:</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
