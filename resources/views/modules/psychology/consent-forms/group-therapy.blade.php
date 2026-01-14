<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado para Terapia de Grupo</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #1f2937; border-bottom: 3px solid #3b82f6; padding-bottom: 10px; }
        .section { margin-bottom: 25px; padding: 15px; background-color: #f9fafb; border-left: 4px solid #3b82f6; }
        .info-box { background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; border-radius: 5px; }
        .warning-box { background-color: #fef3c7; border: 1px solid #fcd34d; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>CONSENTIMIENTO INFORMADO PARA TERAPIA DE GRUPO</h1>

    <div class="section">
        <h2>1. NATURALEZA DE LA TERAPIA DE GRUPO</h2>
        <p>
            La terapia de grupo ofrece un espacio seguro donde varias personas, coordinadas por 
            uno o más profesionales, comparten experiencias y trabajan objetivos terapéuticos 
            desde la interacción grupal.
        </p>
    </div>

    @if(isset($data['group_theme']) && !empty($data['group_theme']))
    <div class="section">
        <h2>2. TEMÁTICA DEL GRUPO</h2>
        <div class="info-box">
            <p>{{ $data['group_theme'] }}</p>
        </div>
    </div>
    @endif

    <div class="section">
        <h2>3. CONFIDENCIALIDAD GRUPAL</h2>
        <div class="warning-box">
            <p>
                <strong>REGLA FUNDAMENTAL:</strong> Todo lo que se hable en el grupo debe permanecer 
                dentro del grupo. Cada miembro se compromete a no revelar la identidad ni las 
                vivencias de los demás participantes fuera de las sesiones.
            </p>
        </div>
    </div>

    <div class="section">
        <h2>4. NORMAS DEL GRUPO</h2>
        <ul>
            <li>Asistencia regular y puntualidad</li>
            <li>Respeto absoluto a todos los miembros</li>
            <li>No contacto fuera del grupo con fines terapéuticos sin conocimiento del profesional</li>
            <li>Libertad para participar al propio ritmo</li>
        </ul>
    </div>

    <div class="section">
        <h2>5. CONSENTIMIENTO</h2>
        <p>
            Yo, <strong>{{ $contactName ?? '_________________' }}</strong>, entiendo las normas de 
            la terapia de grupo y me comprometo especialmente a mantener la confidencialidad 
            de todo lo compartido por mis compañeros.
        </p>
        <div style="margin-top: 30px;">
            <p><strong>Firma del paciente:</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
