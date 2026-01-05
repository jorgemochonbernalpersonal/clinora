<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consentimiento Informado - {{ $consentForm->consent_title }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm 2cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.4;
            color: #1f2937;
            font-size: 10pt;
            position: relative;
        }
        
        /* Header compacto y elegante */
        .pdf-header {
            position: fixed;
            top: -1.5cm;
            left: 0;
            right: 0;
            height: 1.5cm;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pdf-header img {
            max-height: 35px;
            max-width: 120px;
            filter: brightness(0) invert(1);
        }
        .pdf-header .brand {
            font-size: 16pt;
            font-weight: bold;
            color: white;
            letter-spacing: 1px;
        }
        .pdf-header .doc-number {
            font-size: 8pt;
            color: #e0e7ff;
            text-align: right;
        }
        
        /* Footer compacto */
        .pdf-footer {
            position: fixed;
            bottom: -1.5cm;
            left: 0;
            right: 0;
            height: 1.2cm;
            background-color: #f8fafc;
            border-top: 2px solid #e2e8f0;
            padding: 6px 20px;
            font-size: 7pt;
            color: #64748b;
        }
        .pdf-footer .page-number:after {
            content: "Pág. " counter(page);
        }
        .pdf-footer .footer-left {
            float: left;
        }
        .pdf-footer .footer-right {
            float: right;
        }
        
        /* Marca de agua para documentos revocados */
        @if($consentForm->isRevoked())
        body::before {
            content: "REVOCADO";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100pt;
            font-weight: bold;
            color: rgba(220, 38, 38, 0.12);
            z-index: 1000;
            pointer-events: none;
        }
        @endif
        
        /* Tipografía mejorada */
        h1 {
            color: #1e293b;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 8px;
            margin: 0 0 15px 0;
            font-size: 16pt;
            text-align: center;
            font-weight: bold;
        }
        h2 {
            color: #334155;
            margin: 12px 0 8px 0;
            font-size: 12pt;
            border-left: 3px solid #2563eb;
            padding-left: 8px;
            font-weight: bold;
        }
        h3 {
            color: #475569;
            margin: 10px 0 6px 0;
            font-size: 10.5pt;
            font-weight: bold;
        }
        
        /* Boxes con diseño moderno */
        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-left: 3px solid #3b82f6;
            padding: 8px 12px;
            margin: 8px 0;
            border-radius: 2px;
        }
        .warning-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 3px solid #f59e0b;
            padding: 8px 12px;
            margin: 8px 0;
            border-radius: 2px;
        }
        .success-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 3px solid #10b981;
            padding: 8px 12px;
            margin: 8px 0;
            border-radius: 2px;
        }
        
        /* Listas compactas */
        ul, ol {
            margin: 5px 0 5px 18px;
            padding: 0;
        }
        li {
            margin-bottom: 2px;
            line-height: 1.3;
        }
        
        /* Sección de firma moderna */
        .signature-box {
            border: 2px solid #10b981;
            background: linear-gradient(to bottom, #f0fdf4 0%, #ffffff 100%);
            padding: 12px;
            margin-top: 15px;
            border-radius: 4px;
            page-break-inside: avoid;
        }
        .signature-box h2 {
            margin: 0 0 10px 0;
            border: none;
            padding: 0;
            color: #065f46;
            font-size: 11pt;
        }
        .signature-img {
            border: 1px solid #cbd5e1;
            padding: 4px;
            background-color: white;
            max-width: 250px;
            max-height: 80px;
            border-radius: 2px;
        }
        
        /* Metadata compacta */
        .metadata {
            margin-top: 15px;
            padding: 8px;
            background-color: #f8fafc;
            font-size: 7.5pt;
            color: #64748b;
            border-top: 1px solid #cbd5e1;
            border-radius: 2px;
        }
        
        /* Header info compacto */
        .header-info {
            text-align: center;
            margin-bottom: 12px;
            padding: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 3px;
        }
        .header-info strong {
            color: #1e293b;
            font-size: 10pt;
        }
        .header-info small {
            color: #64748b;
            font-size: 7.5pt;
        }
        
        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        table td {
            padding: 4px;
            vertical-align: top;
        }
        
        /* Elementos generales */
        strong {
            color: #1e293b;
            font-weight: bold;
        }
        .text-small {
            font-size: 8pt;
        }
        .text-muted {
            color: #64748b;
        }
        p {
            margin: 6px 0;
        }
        
        /* Aviso legal compacto */
        .legal-notice {
            margin-top: 12px;
            padding: 8px;
            background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);
            border-left: 3px solid #eab308;
            font-size: 7pt;
            color: #713f12;
            border-radius: 2px;
        }
    </style>
</head>
<body>
    {{-- Header fijo con diseño moderno --}}
    <div class="pdf-header">
        <div>
            @php
                $logoPath = public_path('images/logo.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Clinora">
            @else
                <span class="brand">CLINORA</span>
            @endif
        </div>
        <div class="doc-number">
            <strong>DOC:</strong> CL-{{ date('Y') }}-{{ str_pad($consentForm->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    {{-- Footer compacto --}}
    <div class="pdf-footer">
        <span class="footer-left">Clinora © {{ date('Y') }}</span>
        <span class="page-number"></span>
        <span class="footer-right">{{ $consentForm->consent_type_label }}</span>
    </div>

    {{-- Header info compacto --}}
    <div class="header-info">
        <strong>CONSENTIMIENTO INFORMADO</strong><br>
        <small>
            Generado: {{ now()->format('d/m/Y H:i') }} • 
            Versión {{ $consentForm->document_version ?? '1.0' }} • 
            Doc. CL-{{ date('Y') }}-{{ str_pad($consentForm->id, 6, '0', STR_PAD_LEFT) }}
        </small>
    </div>

    {{-- Contenido del consentimiento --}}
    {!! $consentBodyContent !!}

    {{-- Sección de firma moderna y compacta --}}
    @if($consentForm->isSigned())
    <div class="signature-box">
        <h2>✓ FIRMADO DIGITALMENTE</h2>
        
        <table>
            <tr>
                <td style="width: 48%;">
                    <strong style="font-size: 9pt;">Paciente</strong><br>
                    <span style="font-size: 9.5pt;">{{ $consentForm->contact->full_name }}</span>
                    @if($consentForm->contact->dni)
                    <br><span class="text-small text-muted">DNI: {{ $consentForm->contact->dni }}</span>
                    @endif
                    
                    <div style="margin-top: 8px;">
                        @if($consentForm->patient_signature_data)
                        <img src="{{ $consentForm->patient_signature_data }}" 
                             alt="Firma" 
                             class="signature-img">
                        @endif
                    </div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%;">
                    <strong style="font-size: 9pt;">Detalles de Firma</strong><br>
                    <span class="text-small">
                        <strong>Fecha:</strong> {{ $consentForm->signed_at->format('d/m/Y H:i:s') }}<br>
                        <strong>IP:</strong> {{ $consentForm->patient_ip_address ?? 'N/A' }}<br>
                        <strong>Dispositivo:</strong> {{ Str::limit($consentForm->patient_device_info ?? 'N/A', 45) }}
                    </span>
                </td>
            </tr>
        </table>

        {{-- Firma del tutor (compacto) --}}
        @if($consentForm->isForMinor() && $consentForm->legal_guardian_name)
        <hr style="margin: 10px 0; border: none; border-top: 1px solid #cbd5e1;">
        <table>
            <tr>
                <td style="width: 48%;">
                    <strong style="font-size: 9pt;">Tutor Legal</strong><br>
                    <span style="font-size: 9.5pt;">{{ $consentForm->legal_guardian_name }}</span><br>
                    <span class="text-small text-muted">
                        {{ $consentForm->legal_guardian_relationship }}
                        @if($consentForm->legal_guardian_id_document)
                        • DNI: {{ $consentForm->legal_guardian_id_document }}
                        @endif
                    </span>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%;">
                    @if($consentForm->guardian_signature_data)
                    <img src="{{ $consentForm->guardian_signature_data }}" 
                         alt="Firma tutor" 
                         class="signature-img">
                    @endif
                </td>
            </tr>
        </table>
        @endif
    </div>
    @else
    <div class="warning-box">
        <strong>⚠ PENDIENTE DE FIRMA</strong><br>
        <span class="text-small">Este documento no ha sido firmado por el paciente.</span>
    </div>
    @endif

    {{-- Info de entrega (compacto) --}}
    @if($consentForm->isDelivered())
    <div class="success-box">
        <strong>✓ Entregado</strong> • {{ $consentForm->delivered_at->format('d/m/Y H:i') }}
    </div>
    @endif

    {{-- Metadata compacta --}}
    <div class="metadata">
        <strong>Info:</strong> 
        ID {{ $consentForm->id }} • 
        Doc. CL-{{ date('Y') }}-{{ str_pad($consentForm->id, 6, '0', STR_PAD_LEFT) }} • 
        {{ $consentForm->consent_type_label }} • 
        Creado {{ $consentForm->created_at->format('d/m/Y H:i') }}
        @if($consentForm->isRevoked())
        • <strong style="color: #dc2626;">REVOCADO {{ $consentForm->revoked_at->format('d/m/Y') }}</strong>
        @if($consentForm->revocation_reason) ({{ $consentForm->revocation_reason }})@endif
        @endif
        <br>
        <em>Documento generado electrónicamente por Clinora con validez legal (RGPD, LOPDGDD).</em>
    </div>

    {{-- Aviso legal compacto --}}
    <div class="legal-notice">
        <strong>Aviso Legal:</strong> Información confidencial protegida por LOPDGDD 3/2018 y RGPD UE 2016/679. 
        Uso restringido a partes autorizadas. Prohibida distribución no autorizada.
    </div>
</body>
</html>
