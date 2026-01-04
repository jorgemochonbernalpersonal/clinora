@extends('emails.layouts.base')

@section('content')
    <h1>¡Te damos la bienvenida a Clinora!</h1>
    
    <p>Hola {{ $name }},</p>
    
    <p>Gracias por unirte a Clinora. Estamos emocionados de ayudarte a gestionar tu práctica profesional de manera más eficiente.</p>
    
    <p>Para comenzar a utilizar todas las funciones de tu cuenta, necesitamos que confirmes tu dirección de correo electrónico.</p>
    
    <div style="text-align: center;">
        <a href="{{ $url }}" class="button">Verificar mi cuenta</a>
    </div>
    
    <p>Este enlace de seguridad expirará en 24 horas por motivos de protección de tu cuenta.</p>
    
    <div class="alert-box">
        <p><strong>¿No has creado esta cuenta?</strong> Si no te has registrado en Clinora, puedes ignorar este mensaje con total seguridad. No es necesario realizar ninguna acción adicional.</p>
    </div>
    
    <p>Si tienes alguna duda o necesitas asistencia técnica, no dudes en responder a este correo o contactar con nuestro equipo de soporte.</p>
    
    <p>Atentamente,<br><strong>El equipo de Clinora</strong></p>
@endsection
