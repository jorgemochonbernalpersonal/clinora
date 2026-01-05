<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimiento Informado Revocado</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 20px;">
                <!-- Container -->
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 30px; text-align: center; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 8px 8px 0 0;">
                            @php
                                $logoPath = public_path('images/logo.png');
                            @endphp
                            @if(file_exists($logoPath))
                                <img src="{{ $message->embed($logoPath) }}" alt="Clinora" style="max-height: 60px; max-width: 180px;">
                            @else
                                <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: bold;">Clinora</h1>
                            @endif
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 20px; color: #dc2626; font-size: 24px; font-weight: 600;">
                                ⚠️ Consentimiento Informado Revocado
                            </h2>

                            <p style="margin: 0 0 16px; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Estimado/a <strong>{{ $patientName }}</strong>,
                            </p>

                            <p style="margin: 0 0 16px; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Te informamos que tu <strong>consentimiento informado</strong> ha sido <strong style="color: #dc2626;">REVOCADO</strong> el {{ $revokedDate }}.
                            </p>

                            <!-- Warning Box -->
                            <table role="presentation" style="width: 100%; margin: 24px 0; background-color: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <p style="margin: 0 0 12px; color: #991b1b; font-weight: 600; font-size: 14px;">
                                            📋 Información del Documento Revocado
                                        </p>
                                        <p style="margin: 0 0 8px; color: #991b1b; font-size: 14px; line-height: 1.5;">
                                            <strong>Nº de Documento:</strong> {{ $documentNumber }}
                                        </p>
                                        <p style="margin: 0 0 8px; color: #991b1b; font-size: 14px; line-height: 1.5;">
                                            <strong>Tipo:</strong> {{ $consentForm->consent_type_label }}
                                        </p>
                                        <p style="margin: 0; color: #991b1b; font-size: 14px; line-height: 1.5;">
                                            <strong>Fecha de revocación:</strong> {{ $revokedDate }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            @if($reason)
                            <p style="margin: 0 0 16px; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                <strong>Motivo de la revocación:</strong><br>
                                <em style="color: #6b7280;">{{ $reason }}</em>
                            </p>
                            @endif

                            <p style="margin: 0 0 16px; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                <strong>¿Qué significa esto?</strong>
                            </p>

                            <ul style="margin: 0 0 24px; padding-left: 24px; color: #4b5563; font-size: 16px; line-height: 1.8;">
                                <li>El consentimiento informado ya <strong>no tiene validez legal</strong></li>
                                <li>Tu derecho a revocar el consentimiento ha sido ejercido correctamente</li>
                                <li>Si deseas continuar el tratamiento, será necesario firmar un nuevo consentimiento</li>
                            </ul>

                            <!-- Info Box -->
                            <table role="presentation" style="width: 100%; margin: 24px 0; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <p style="margin: 0 0 8px; color: #1e40af; font-weight: 600; font-size: 14px;">
                                            ℹ️ Tus Derechos
                                        </p>
                                        <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.5;">
                                            De acuerdo con la legislación vigente (RGPD, LOPDGDD, Ley 41/2002), 
                                            tienes derecho a revocar tu consentimiento en cualquier momento sin 
                                            que esto afecte a la licitud del tratamiento basado en el consentimiento 
                                            previo a su retirada.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Si tienes alguna duda o deseas más información sobre esta revocación, 
                                no dudes en contactar con tu profesional.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f9fafb; border-radius: 0 0 8px 8px; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 12px; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Atentamente,<br>
                                <strong style="color: #1f2937;">{{ $professionalName }}</strong><br>
                                <span style="color: #3b82f6;">Clinora</span>
                            </p>

                            <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">

                            <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.5; text-align: center;">
                                Este correo electrónico ha sido generado automáticamente por Clinora.<br>
                                Por favor, no respondas a este mensaje. Si necesitas ayuda, contacta directamente con tu profesional.
                            </p>

                            <p style="margin: 16px 0 0; color: #9ca3af; font-size: 12px; line-height: 1.5; text-align: center;">
                                © {{ date('Y') }} Clinora. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
