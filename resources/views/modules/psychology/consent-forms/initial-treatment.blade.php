<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado para Tratamiento Psicológico</title>
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
    <h1>CONSENTIMIENTO INFORMADO PARA TRATAMIENTO PSICOLÓGICO</h1>

    <div class="section">
        <h2>1. IDENTIFICACIÓN DEL PROFESIONAL</h2>
        <p><strong>Nombre:</strong> {{ $professional->user->name ?? 'N/A' }}</p>
        <p><strong>Nº de Colegiado:</strong> {{ $professional->license_number ?? 'N/A' }}</p>
        <p><strong>Especialidad:</strong> Psicología Clínica</p>
        @if($professional->address_city)
        <p><strong>Centro:</strong> {{ $professional->address_city }}</p>
        @endif
    </div>

    <div class="section">
        <h2>2. NATURALEZA DEL TRATAMIENTO</h2>
        <p>
            El tratamiento psicológico consiste en un proceso de ayuda basado en la interacción 
            entre el/la psicólogo/a y el/la paciente, mediante el uso de técnicas psicológicas 
            científicamente validadas. Este proceso tiene como objetivo mejorar el bienestar 
            psicológico, emocional y social del paciente.
        </p>
    </div>

    <div class="section">
        <h2>3. OBJETIVOS DEL TRATAMIENTO</h2>
        <p>
            Los objetivos del tratamiento serán acordados conjuntamente entre el profesional 
            y el paciente, y podrán incluir:
        </p>
        <ul>
            <li>Mejora del bienestar psicológico y emocional</li>
            <li>Desarrollo de estrategias de afrontamiento</li>
            <li>Mejora de las relaciones interpersonales</li>
            <li>Reducción de síntomas psicológicos</li>
            <li>Promoción del crecimiento personal</li>
        </ul>
        @if(isset($data['treatment_goals']) && !empty($data['treatment_goals']))
        <div class="info-box">
            <strong>Objetivos específicos acordados:</strong>
            <p>{{ $data['treatment_goals'] }}</p>
        </div>
        @endif
    </div>

    <div class="section">
        <h2>4. METODOLOGÍA</h2>
        <p>
            Se utilizarán técnicas basadas en la evidencia científica, que pueden incluir:
        </p>
        <ul>
            <li>Entrevistas clínicas estructuradas y semiestructuradas</li>
            <li>Evaluación psicológica mediante test y cuestionarios estandarizados</li>
            <li>Técnicas cognitivo-conductuales</li>
            <li>Intervenciones basadas en mindfulness y aceptación</li>
            <li>Psicoeducación</li>
            <li>Otras técnicas según las necesidades del caso</li>
        </ul>
        @if(isset($data['treatment_techniques']) && is_array($data['treatment_techniques']))
        <div class="info-box">
            <strong>Técnicas específicas a utilizar:</strong>
            <ul>
                @foreach($data['treatment_techniques'] as $technique)
                <li>{{ $technique }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="section">
        <h2>5. DURACIÓN Y FRECUENCIA</h2>
        <ul>
            @if(isset($data['treatment_duration']))
            <li><strong>Duración aproximada:</strong> {{ $data['treatment_duration'] }}</li>
            @else
            <li><strong>Duración aproximada:</strong> A determinar según evolución</li>
            @endif
            
            @if(isset($data['session_frequency']))
            <li><strong>Frecuencia de sesiones:</strong> {{ $data['session_frequency'] }}</li>
            @else
            <li><strong>Frecuencia de sesiones:</strong> A acordar</li>
            @endif
            
            @if(isset($data['session_duration']))
            <li><strong>Duración de cada sesión:</strong> {{ $data['session_duration'] }} minutos</li>
            @else
            <li><strong>Duración de cada sesión:</strong> 50-60 minutos</li>
            @endif
        </ul>
    </div>

    <div class="section">
        <h2>6. CONFIDENCIALIDAD</h2>
        <p>
            Toda la información compartida en las sesiones es <strong>estrictamente confidencial</strong> 
            y está protegida por el secreto profesional recogido en el Código Deontológico 
            del Psicólogo y la legislación vigente.
        </p>
        
        <div class="warning-box">
            <h3>Excepciones a la confidencialidad:</h3>
            <ol>
                <li><strong>Riesgo grave e inminente</strong> para el paciente o terceros</li>
                <li><strong>Orden judicial</strong> que requiera revelación de información</li>
                <li><strong>Maltrato de menores o personas vulnerables</strong> (obligación legal de notificación)</li>
                <li><strong>Consentimiento expreso</strong> del paciente para compartir información con otros profesionales</li>
            </ol>
        </div>
    </div>

    <div class="section">
        <h2>6.1. PROTOCOLO DE RIESGO VITAL</h2>
        
        <div class="warning-box">
            <h3 style="margin-top: 0;">⚠️ Importante: Excepciones a la Confidencialidad por Riesgo Vital</h3>
            <p>
                Comprendo que si durante el tratamiento el/la psicólogo/a detecta un 
                <strong>riesgo grave e inminente de suicidio, autolesión o daño a terceros</strong>, 
                tiene la obligación deontológica y legal de:
            </p>
            <ul>
                <li>Romper la confidencialidad si es necesario para proteger mi vida o la de terceros</li>
                <li>Contactar con servicios de emergencia (112) si la situación lo requiere</li>
                <li>Informar a familiares o personas de contacto designadas</li>
                <li>Activar el protocolo de derivación urgente a psiquiatría o servicios de urgencias</li>
                <li>Documentar todas las acciones tomadas para mi protección</li>
            </ul>
            
            <p style="margin-top: 15px;">
                <strong>Persona de contacto de emergencia:</strong>
            </p>
            @if(isset($data['emergency_contact_name']) && !empty($data['emergency_contact_name']))
            <p style="margin-left: 20px;">
                <strong>Nombre:</strong> {{ $data['emergency_contact_name'] }}<br>
                <strong>Teléfono:</strong> {{ $data['emergency_contact_phone'] ?? 'No proporcionado' }}<br>
                <strong>Relación:</strong> {{ $data['emergency_contact_relationship'] ?? 'No especificada' }}
            </p>
            @else
            <p style="margin-left: 20px;">
                <strong>Nombre:</strong> _______________________________________<br>
                <strong>Teléfono:</strong> _______________________________________<br>
                <strong>Relación:</strong> _______________________________________
            </p>
            @endif
            
            <p style="margin-top: 10px;">
                <em>Autorizo expresamente al profesional a contactar con esta persona en caso de emergencia 
                o riesgo vital detectado durante el tratamiento.</em>
            </p>
        </div>
    </div>

    <div class="section">
        <h2>7. PROTECCIÓN DE DATOS (RGPD)</h2>
        <p>
            Sus datos personales serán tratados conforme al Reglamento (UE) 2016/679 (RGPD) 
            y la Ley Orgánica de Protección de Datos y Garantía de Derechos Digitales (LOPDGDD).
        </p>
        <ul>
            <li><strong>Responsable:</strong> {{ $professional->user->name ?? 'N/A' }}</li>
            <li><strong>Finalidad:</strong> Prestación de servicios psicológicos</li>
            <li><strong>Conservación:</strong> Durante el tratamiento y 5 años posteriores (obligación legal)</li>
            <li><strong>Derechos:</strong> Acceso, rectificación, supresión, limitación, portabilidad, oposición</li>
        </ul>
        <p>
            Puede ejercer sus derechos contactando con el profesional o presentando una 
            reclamación ante la Agencia Española de Protección de Datos (AEPD).
        </p>
    </div>

    <div class="section">
        <h2>8. COSTES Y CANCELACIONES</h2>
        @if(isset($data['session_price']) && $data['session_price'] > 0)
        <p><strong>Coste por sesión:</strong> {{ number_format($data['session_price'], 2) }}€</p>
        <p><strong>Método de pago:</strong> {{ $data['payment_method'] ?? 'Efectivo/Transferencia' }}</p>
        @else
        <div class="warning-box">
            <strong>⚠ IMPORTANTE - Código Deontológico del COP (art. 48):</strong><br>
            El coste por sesión debe acordarse con el profesional antes de iniciar el tratamiento.
            Los honorarios profesionales se fijarán de mutuo acuerdo y serán comunicados claramente
            al paciente antes de la primera sesión.
        </div>
        <p><strong>Coste por sesión:</strong> _______________ € (a acordar)</p>
        @endif
        
        <p>
            <strong>Política de cancelación:</strong> Las cancelaciones con menos de 24 horas 
            de antelación podrán ser cobradas al 50% del coste de la sesión, salvo casos de 
            fuerza mayor justificados.
        </p>
    </div>

    <div class="section">
        <h2>9. RELACIONES PROFESIONALES</h2>
        <p>
            La relación será estrictamente profesional. No se permitirán:
        </p>
        <ul>
            <li>Relaciones duales (amistad, negocios, etc.)</li>
            <li>Contacto por redes sociales personales</li>
            <li>Regalos de valor significativo</li>
            <li>Cualquier relación que pueda comprometer la objetividad terapéutica</li>
        </ul>
    </div>

    <div class="section">
        <h2>10. DERECHOS DEL PACIENTE</h2>
        <p>Usted tiene derecho a:</p>
        <ul>
            <li>Recibir información clara sobre su tratamiento</li>
            <li>Participar en las decisiones terapéuticas</li>
            <li>Solicitar una segunda opinión</li>
            <li>Finalizar el tratamiento en cualquier momento</li>
            <li>Presentar quejas ante el Colegio Oficial de Psicólogos</li>
            <li>Acceder a su historial clínico</li>
        </ul>
    </div>

    <div class="section">
        <h2>11. LÍMITES DE LA INTERVENCIÓN PSICOLÓGICA</h2>
        
        <div class="info-box">
            <h3 style="margin-top: 0;">Derivación a Psiquiatría</h3>
            <p>
                El/la psicólogo/a puede recomendar una derivación a <strong>psiquiatría</strong> 
                si detecta que el caso requiere tratamiento farmacológico, está fuera del 
                ámbito de competencia de la psicología clínica, o si el paciente presenta:
            </p>
            <ul>
                <li>Síntomas psicóticos que requieren medicación</li>
                <li>Trastornos del estado de ánimo graves que no responden a psicoterapia</li>
                <li>Crisis agudas que requieren intervención farmacológica inmediata</li>
                <li>Otras condiciones médicas que requieren evaluación psiquiátrica</li>
            </ul>
            <p>
                Comprendo que <strong>la psicología NO incluye prescripción de medicamentos</strong> 
                y que, en caso necesario, seré derivado/a a un profesional médico especializado.
            </p>
        </div>
    </div>

    <div class="signature-section">
        <h2>DECLARACIÓN DE CAPACIDAD</h2>
        
        <p>
            Yo, <strong>{{ $contactName ?? '_________________' }}</strong>
            @if(isset($contact) && $contact->dni)
            , con DNI <strong>{{ $contact->dni }}</strong>
            @else
            , con DNI <strong>_________________</strong>
            @endif
            , <strong>DECLARO QUE</strong>:
        </p>

        <div class="checkbox">
            ☐ Me encuentro en pleno uso de mis facultades mentales para comprender este documento
        </div>
        <div class="checkbox">
            ☐ Comprendo la naturaleza del tratamiento psicológico y sus implicaciones
        </div>
        <div class="checkbox">
            ☐ No me encuentro bajo coacción, presión externa o influencia indebida
        </div>
        <div class="checkbox">
            ☐ He tenido tiempo suficiente para reflexionar antes de firmar este documento
        </div>
        <div class="checkbox">
            ☐ He sido informado/a de mi derecho a rechazar o retirar el consentimiento en cualquier momento
        </div>

        <h2 style="margin-top: 30px;">DECLARACIÓN DE CONSENTIMIENTO</h2>
        
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
            ☐ He leído y comprendido la información anterior
        </div>
        <div class="checkbox">
            ☐ He tenido oportunidad de hacer preguntas
        </div>
        <div class="checkbox">
            ☐ Consiento voluntariamente recibir tratamiento psicológico
        </div>
        <div class="checkbox">
            ☐ Autorizo el tratamiento de mis datos según lo indicado en la sección 7
        </div>

        <div style="margin-top: 30px;">
            <p><strong>Firma del paciente:</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>

        @if(isset($data['is_minor']) && $data['is_minor'])
        <div style="margin-top: 30px; padding: 15px; background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 5px;">
            <h3>CONSENTIMIENTO DEL TUTOR LEGAL</h3>
            <p>
                Yo, <strong>{{ $data['legal_guardian_name'] ?? '_________________' }}</strong>,
                como tutor legal de <strong>{{ $contactName ?? '_________________' }}</strong>,
                consiento el tratamiento psicológico del menor.
            </p>
            <p><strong>Firma del tutor:</strong> _________________</p>
            <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
        </div>
        @endif
    </div>

    <div style="margin-top: 40px; padding: 15px; background-color: #f3f4f6; border-radius: 5px; font-size: 0.9em; color: #6b7280;">
        <p><strong>Documento generado el:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Versión del documento:</strong> {{ $data['document_version'] ?? '1.0' }}</p>
    </div>
</body>
</html>

