<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $invoice->invoice_number }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Factura {{ $invoice->invoice_number }}</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; border: 1px solid #e5e7eb;">
        <p style="font-size: 16px; margin-bottom: 20px;">
            Hola <strong>{{ $contact->first_name }}</strong>,
        </p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Te enviamos la factura por los servicios de psicología clínica recibidos.
        </p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Número de factura:</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: bold;">{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Fecha de emisión:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ $invoice->issue_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Total:</td>
                    <td style="padding: 8px 0; text-align: right; font-size: 18px; font-weight: bold; color: #1e40af;">
                        {{ number_format($invoice->total, 2) }} €
                    </td>
                </tr>
            </table>
        </div>
        
        @if($invoice->appointment)
        <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
            <strong>Sesión:</strong> {{ $invoice->appointment->start_time->format('d/m/Y H:i') }} - {{ $invoice->appointment->type->label() }}
        </p>
        @endif
        
        <p style="font-size: 16px; margin-top: 30px;">
            Puedes encontrar el PDF de la factura adjunto a este email.
        </p>
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            Si tienes alguna pregunta sobre esta factura, no dudes en contactarnos.
        </p>
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 10px;">
            Saludos,<br>
            <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
            {{ $professional->license_number ? 'Nº Colegiado: ' . $professional->license_number : '' }}
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>Esta factura cumple con la normativa VeriFactu (AEAT)</p>
        <p>{{ config('app.name') }} - Sistema de gestión clínica</p>
    </div>
</body>
</html>
