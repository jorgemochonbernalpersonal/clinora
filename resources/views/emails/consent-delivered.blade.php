@extends('emails.layouts.base')

@section('content')
    <h1>Tu Consentimiento Informado</h1>
    
    <p>Estimado/a <strong>{{ $patientName }}</strong>,</p>
    
    <p>Adjunto encontrarás una copia de tu <strong>consentimiento informado</strong> firmado el {{ $signedDate }}.</p>
    
    {{-- Info Box --}}
    <div class="info-box" style="background-color: #EFF6FF; border-left: 4px solid #3B82F6; padding: 16px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0 0 12px; color: #1E40AF; font-weight: 600; font-size: 14px;">
            📋 Información del Documento
        </p>
        <p style="margin: 0 0 8px; color: #1E40AF; font-size: 14px;">
            <strong>Nº de Documento:</strong> {{ $documentNumber }}
        </p>
        <p style="margin: 0; color: #1E40AF; font-size: 14px;">
            <strong>Tipo:</strong> {{ $consentForm->consent_type_label }}
        </p>
    </div>
    
    <p><strong>Este documento es importante y debes conservarlo.</strong> Puedes:</p>
    
    <ul style="margin: 0 0 20px; padding-left: 24px; color: #334155; line-height: 1.8;">
        <li>Guardarlo en tu ordenador o dispositivo móvil</li>
        <li>Imprimirlo si lo deseas</li>
        <li>Consultarlo cuando lo necesites</li>
    </ul>
    
    {{-- Warning Box --}}
    <div class="alert-box" style="background-color: #FEF3C7; border-left: 4px solid #F59E0B; padding: 16px; border-radius: 8px; margin: 20px 0;">
        <p style="margin: 0; color: #92400E; font-size: 14px;">
            <strong>🔒 Confidencialidad:</strong> Este documento contiene información médica confidencial. 
            Manténlo en un lugar seguro y no lo compartas con personas no autorizadas.
        </p>
    </div>
    
    <p>Si tienes alguna duda sobre este documento, no dudes en contactar con tu profesional.</p>
    
    <p style="margin-top: 30px;">
        Atentamente,<br>
        <strong>{{ $professionalName }}</strong><br>
        <span style="color: #3B82F6;">Clinora</span>
    </p>
@endsection
