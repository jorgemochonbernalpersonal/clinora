<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento para Teleconsulta Psicológica</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #1f2937;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        h2 {
            color: #374151;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
        }
        .info-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .warning-box {
            background-color: #fef3c7;
            border: 1px solid #fcd34d;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .security-box {
            background-color: #ecfdf5;
            border: 1px solid #86efac;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        ul {
            margin-left: 20px;
        }
        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .checkbox {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>CONSENTIMIENTO INFORMADO PARA TELECONSULTA PSICOLÓGICA</h1>

    <div class="section">
        <h2>1. IDENTIFICACIÓN DEL PROFESIONAL</h2>
        <p><strong>Nombre:</strong> {{ $professional->user->full_name ?? 'N/A' }}</p>
        <p><strong>Nº de Colegiado:</strong> {{ $professional->license_number ?? 'N/A' }}</p>
        <p><strong>Especialidad:</strong> Psicología Clínica</p>
    </div>

    <div class="section">
        <h2>2. NATURALEZA DE LA TELECONSULTA</h2>
        <p>
            La teleconsulta psicológica es una modalidad de atención psicológica que se realiza 
            mediante videollamada a través de una plataforma segura. Esta modalidad permite 
            recibir tratamiento psicológico desde cualquier ubicación, manteniendo los mismos 
            estándares de calidad y confidencialidad que la consulta presencial.
        </p>
        <div class="info-box">
            <p><strong>Plataforma utilizada:</strong> {{ $platform ?? 'Clinora' }}</p>
            <p><strong>Información de seguridad:</strong> {{ $security_info ?? 'Cifrado end-to-end' }}</p>
        </div>
    </div>

    <div class="section">
        <h2>3. REQUISITOS TÉCNICOS</h2>
        <p>Para participar en la teleconsulta, usted necesitará:</p>
        <ul>
            <li>Dispositivo con cámara y micrófono (ordenador, tablet o smartphone)</li>
            <li>Conexión a internet estable (mínimo 1 Mbps recomendado)</li>
            <li>Navegador web actualizado o aplicación móvil</li>
            <li>Espacio privado y tranquilo para la sesión</li>
        </ul>
    </div>

    <div class="section">
        <h2>4. VENTAJAS Y LIMITACIONES</h2>
        <p><strong>Ventajas:</strong></p>
        <ul>
            <li>Accesibilidad desde cualquier ubicación</li>
            <li>Ahorro de tiempo en desplazamientos</li>
            <li>Mayor flexibilidad horaria</li>
            <li>Continuidad del tratamiento en situaciones especiales</li>
        </ul>
        
        <p><strong>Limitaciones:</strong></p>
        <ul>
            <li>Dependencia de la calidad de la conexión a internet</li>
            <li>Posibles interrupciones técnicas</li>
            <li>Limitaciones en la observación de lenguaje no verbal</li>
            <li>No es adecuada para situaciones de crisis aguda</li>
        </ul>
    </div>

    <div class="section">
        <h2>5. CONFIDENCIALIDAD Y SEGURIDAD</h2>
        <div class="security-box">
            <p>
                La plataforma utilizada cumple con los estándares de seguridad y protección de datos:
            </p>
            <ul>
                <li>Cifrado end-to-end de las comunicaciones</li>
                <li>Servidores ubicados en la Unión Europea</li>
                <li>Cumplimiento con RGPD y LOPDGDD</li>
                <li>No se graban las sesiones sin consentimiento expreso</li>
            </ul>
        </div>
        
        <div class="warning-box">
            <p><strong>Importante:</strong></p>
            <ul>
                <li>Debe asegurarse de estar en un espacio privado durante la sesión</li>
                <li>No comparta el enlace de la videollamada con terceros</li>
                <li>Utilice auriculares si está en un espacio compartido</li>
                <li>Notifique inmediatamente cualquier problema técnico o de seguridad</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>6. GRABACIÓN DE SESIONES</h2>
        @if(isset($data['recording_consent']) && $data['recording_consent'])
        <div class="info-box">
            <p>
                <strong>Consentimiento para grabación:</strong> SÍ
            </p>
            <p>
                Las sesiones podrán ser grabadas únicamente con fines de supervisión profesional 
                o formación, siempre con su consentimiento previo y explícito para cada grabación.
            </p>
        </div>
        @else
        <p>
            <strong>Las sesiones NO serán grabadas</strong> sin su consentimiento expreso previo 
            para cada grabación específica.
        </p>
        @endif
    </div>

    <div class="section">
        <h2>7. SITUACIONES DE EMERGENCIA</h2>
        <div class="warning-box">
            <p>
                <strong>IMPORTANTE:</strong> La teleconsulta no es adecuada para situaciones de 
                emergencia o crisis aguda. En caso de:
            </p>
            <ul>
                <li>Ideación suicida o autolesiva</li>
                <li>Crisis psicológica aguda</li>
                <li>Riesgo inminente para usted o terceros</li>
            </ul>
            <p>
                Debe contactar inmediatamente con:
            </p>
            <ul>
                <li><strong>Emergencias:</strong> 112</li>
                <li><strong>Teléfono de la Esperanza:</strong> 717 003 717</li>
                <li><strong>Su centro de salud mental de referencia</strong></li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>8. DERECHO A CAMBIAR DE MODALIDAD</h2>
        <p>
            Usted tiene derecho a solicitar cambiar de teleconsulta a consulta presencial 
            en cualquier momento, siempre que sea posible según la disponibilidad del profesional.
        </p>
    </div>

    <div class="signature-section">
        <h2>DECLARACIÓN DE CONSENTIMIENTO</h2>
        
        <p>
            Yo, <strong>{{ $contactName ?? '_________________' }}</strong>
            @if(isset($contact) && $contact->dni)
            , con DNI <strong>{{ $contact->dni }}</strong>
            @else
            , con DNI <strong>_________________</strong>
            @endif
            :
        </p>

        <div class="checkbox">
            ☐ He leído y comprendido la información sobre teleconsulta
        </div>
        <div class="checkbox">
            ☐ Entiendo las ventajas y limitaciones de esta modalidad
        </div>
        <div class="checkbox">
            ☐ Acepto participar en sesiones de teleconsulta psicológica
        </div>
        <div class="checkbox">
            ☐ Me comprometo a utilizar un espacio privado y seguro
        </div>
        @if(isset($data['recording_consent']) && $data['recording_consent'])
        <div class="checkbox">
            ☐ Autorizo la grabación de sesiones con los fines indicados
        </div>
        @endif
        <div class="checkbox">
            ☐ Entiendo que debo contactar con servicios de emergencia en caso de crisis
        </div>

        <div style="margin-top: 30px;">
            <p><strong>Firma del paciente:</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <div style="margin-top: 40px; padding: 15px; background-color: #f3f4f6; border-radius: 5px; font-size: 0.9em; color: #6b7280;">
        <p><strong>Documento generado el:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Versión del documento:</strong> {{ $data['document_version'] ?? '1.0' }}</p>
        <p><strong>Plataforma:</strong> {{ $platform ?? 'Clinora' }}</p>
    </div>
</body>
</html>

