<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class AdditionalBlogPostsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Teleconsulta en psicología: Guía legal y práctica 2026',
                'slug' => 'teleconsulta-psicologia-guia-legal-practica',
                'excerpt' => 'Todo lo que necesitas saber sobre teleconsulta en psicología: aspectos legales, herramientas, mejores prácticas y cumplimiento normativo en España.',
                'content' => '<h2>¿Qué es la teleconsulta en psicología?</h2>
<p>La teleconsulta, también conocida como telepsicología o terapia online, es la prestación de servicios psicológicos a través de medios tecnológicos como videollamadas, teléfono o mensajería.</p>

<h2>Marco legal en España</h2>

<h3>1. Normativa aplicable</h3>
<p>La teleconsulta en psicología está regulada por:</p>
<ul>
    <li><strong>Ley 41/2002</strong> de autonomía del paciente</li>
    <li><strong>RGPD</strong> y LOPD para protección de datos</li>
    <li><strong>Código Deontológico</strong> del Colegio Oficial de Psicólogos</li>
    <li><strong>Ley 34/2002</strong> de servicios de la sociedad de la información</li>
</ul>

<h3>2. Consentimiento informado</h3>
<p>Es obligatorio obtener consentimiento específico para teleconsulta que incluya:</p>
<ul>
    <li>Información sobre la modalidad online</li>
    <li>Limitaciones técnicas</li>
    <li>Medidas de seguridad aplicadas</li>
    <li>Procedimiento en caso de emergencia</li>
</ul>

<h2>Requisitos técnicos</h2>

<h3>1. Plataforma segura</h3>
<p>La plataforma de videollamada debe cumplir:</p>
<ul>
    <li>Encriptación end-to-end</li>
    <li>Servidores en la UE</li>
    <li>Cumplimiento RGPD</li>
    <li>Grabación solo con consentimiento</li>
</ul>

<h3>2. Identificación del paciente</h3>
<p>Es necesario verificar la identidad del paciente mediante:</p>
<ul>
    <li>Documento de identidad (primera sesión)</li>
    <li>Datos de contacto verificados</li>
    <li>Confirmación de ubicación</li>
</ul>

<h2>Mejores prácticas</h2>

<h3>1. Preparación del espacio</h3>
<ul>
    <li>Entorno privado y silencioso</li>
    <li>Buena iluminación</li>
    <li>Conexión a internet estable</li>
    <li>Cámara y micrófono de calidad</li>
</ul>

<h3>2. Durante la sesión</h3>
<ul>
    <li>Verificar que el paciente está solo</li>
    <li>Tener plan B si falla la conexión</li>
    <li>Documentar igual que sesión presencial</li>
    <li>Mantener contacto visual</li>
</ul>

<h3>3. Gestión de crisis</h3>
<p>Tener protocolo para:</p>
<ul>
    <li>Emergencias psiquiátricas</li>
    <li>Riesgo de suicidio</li>
    <li>Contacto con servicios de emergencia</li>
    <li>Datos de contacto de emergencia del paciente</li>
</ul>

<h2>Ventajas de la teleconsulta</h2>
<ul>
    <li>Accesibilidad para pacientes con movilidad reducida</li>
    <li>Ahorro de tiempo y desplazamientos</li>
    <li>Mayor flexibilidad horaria</li>
    <li>Continuidad en situaciones excepcionales</li>
</ul>

<h2>Limitaciones</h2>
<ul>
    <li>Menor percepción del lenguaje no verbal</li>
    <li>Dependencia de la tecnología</li>
    <li>No apta para todos los casos (psicosis activa, riesgo grave)</li>
    <li>Posibles problemas de privacidad del paciente</li>
</ul>

<h2>Facturación y seguros</h2>
<p>La teleconsulta se factura igual que la presencial. Muchas aseguradoras ya cubren sesiones online. Es importante:</p>
<ul>
    <li>Emitir factura con los mismos datos</li>
    <li>Especificar "sesión online" si lo requiere el seguro</li>
    <li>Mantener mismos precios que presencial</li>
</ul>

<h2>Conclusión</h2>
<p>La teleconsulta es una modalidad válida y efectiva cuando se implementa correctamente. Un software especializado que cumpla con todas las normativas facilita enormemente la gestión de sesiones online.</p>',
                'meta_description' => 'Guía completa sobre teleconsulta en psicología: marco legal, requisitos técnicos, mejores prácticas y cumplimiento RGPD en España.',
                'meta_keywords' => 'teleconsulta psicología, telepsicología, terapia online, RGPD teleconsulta',
                'published_at' => now()->subDays(15),
                'is_published' => true,
            ],
            [
                'title' => 'Gestión de pacientes: Mejores prácticas para psicólogos',
                'slug' => 'gestion-pacientes-mejores-practicas-psicologos',
                'excerpt' => 'Optimiza la gestión de tu consulta de psicología con estas mejores prácticas: organización, documentación, seguimiento y herramientas digitales.',
                'content' => '<h2>Introducción</h2>
<p>Una buena gestión de pacientes es fundamental para ofrecer un servicio de calidad y mantener una consulta organizada y eficiente.</p>

<h2>Organización de la agenda</h2>

<h3>1. Planificación semanal</h3>
<ul>
    <li>Bloques de tiempo específicos para cada tipo de sesión</li>
    <li>Tiempo buffer entre sesiones (10-15 minutos)</li>
    <li>Días específicos para evaluaciones iniciales</li>
    <li>Tiempo administrativo bloqueado</li>
</ul>

<h3>2. Gestión de citas</h3>
<ul>
    <li>Sistema de recordatorios automáticos (24-48h antes)</li>
    <li>Política clara de cancelaciones</li>
    <li>Lista de espera organizada</li>
    <li>Flexibilidad para urgencias</li>
</ul>

<h2>Documentación clínica</h2>

<h3>1. Historia clínica</h3>
<p>Debe incluir:</p>
<ul>
    <li>Datos de identificación completos</li>
    <li>Motivo de consulta</li>
    <li>Antecedentes personales y familiares</li>
    <li>Evaluación inicial</li>
    <li>Plan de tratamiento</li>
</ul>

<h3>2. Notas de sesión</h3>
<p>Recomendaciones:</p>
<ul>
    <li>Usar formato estructurado (SOAP o DAP)</li>
    <li>Documentar inmediatamente después de la sesión</li>
    <li>Ser objetivo y profesional</li>
    <li>Incluir observaciones relevantes</li>
    <li>Actualizar plan de tratamiento</li>
</ul>

<h3>3. Evaluaciones y tests</h3>
<ul>
    <li>Guardar resultados de forma segura</li>
    <li>Fecha de aplicación</li>
    <li>Interpretación profesional</li>
    <li>Comparativas si hay reevaluaciones</li>
</ul>

<h2>Comunicación con pacientes</h2>

<h3>1. Canales apropiados</h3>
<ul>
    <li>Email para cuestiones administrativas</li>
    <li>Teléfono para urgencias</li>
    <li>Portal del paciente para documentos</li>
    <li>Evitar WhatsApp para temas clínicos</li>
</ul>

<h3>2. Límites profesionales</h3>
<ul>
    <li>Horario de disponibilidad claro</li>
    <li>Política de respuesta a mensajes</li>
    <li>Procedimiento para emergencias</li>
    <li>Separación vida personal/profesional</li>
</ul>

<h2>Seguimiento y evaluación</h2>

<h3>1. Indicadores de progreso</h3>
<ul>
    <li>Objetivos terapéuticos medibles</li>
    <li>Escalas de evaluación periódicas</li>
    <li>Feedback del paciente</li>
    <li>Revisión de plan de tratamiento</li>
</ul>

<h3>2. Alta terapéutica</h3>
<p>Criterios para el alta:</p>
<ul>
    <li>Objetivos alcanzados</li>
    <li>Estabilidad sintomática</li>
    <li>Herramientas de afrontamiento adquiridas</li>
    <li>Plan de prevención de recaídas</li>
</ul>

<h2>Gestión financiera</h2>

<h3>1. Facturación</h3>
<ul>
    <li>Emitir factura inmediatamente</li>
    <li>Sistema de pago claro</li>
    <li>Seguimiento de impagos</li>
    <li>Política de bonos o packs</li>
</ul>

<h3>2. Control de ingresos</h3>
<ul>
    <li>Registro de todas las sesiones</li>
    <li>Conciliación bancaria mensual</li>
    <li>Previsión de ingresos</li>
    <li>Gestión de seguros</li>
</ul>

<h2>Herramientas digitales</h2>

<h3>Beneficios de un software de gestión:</h3>
<ul>
    <li>Centralización de información</li>
    <li>Recordatorios automáticos</li>
    <li>Facturación integrada</li>
    <li>Cumplimiento RGPD automático</li>
    <li>Acceso desde cualquier dispositivo</li>
    <li>Backups automáticos</li>
</ul>

<h2>Conclusión</h2>
<p>Una gestión eficiente de pacientes no solo mejora la calidad del servicio, sino que reduce el estrés administrativo y permite dedicar más tiempo a la labor terapéutica.</p>',
                'meta_description' => 'Mejores prácticas para gestión de pacientes en psicología: organización, documentación, comunicación y herramientas digitales.',
                'meta_keywords' => 'gestión pacientes psicología, organización consulta, documentación clínica',
                'published_at' => now()->subDays(12),
                'is_published' => true,
            ],
            [
                'title' => 'Facturación para psicólogos: Guía completa 2026',
                'slug' => 'facturacion-psicologos-guia-completa',
                'excerpt' => 'Todo sobre facturación para psicólogos: obligaciones fiscales, tipos de factura, IVA, IRPF, autónomos vs sociedad y software de facturación.',
                'content' => '<h2>Obligaciones fiscales del psicólogo</h2>

<h3>1. Alta como autónomo</h3>
<p>Para ejercer como psicólogo necesitas:</p>
<ul>
    <li>Alta en Hacienda (modelo 036/037)</li>
    <li>Alta en Seguridad Social (RETA)</li>
    <li>Epígrafe IAE: 841 - Servicios médicos y sanitarios</li>
    <li>Colegiación obligatoria</li>
</ul>

<h3>2. Régimen fiscal</h3>
<p>Opciones:</p>
<ul>
    <li><strong>Estimación directa simplificada:</strong> Más común para psicólogos</li>
    <li><strong>Estimación directa normal:</strong> Si facturas > 600.000€</li>
    <li><strong>Módulos:</strong> No aplicable a servicios profesionales</li>
</ul>

<h2>La factura del psicólogo</h2>

<h3>Datos obligatorios</h3>
<ul>
    <li>Número de factura (secuencial)</li>
    <li>Fecha de emisión</li>
    <li>Datos del profesional (nombre, NIF, dirección)</li>
    <li>Datos del cliente</li>
    <li>Descripción del servicio</li>
    <li>Base imponible</li>
    <li>IVA (si aplica)</li>
    <li>Retención IRPF (si aplica)</li>
    <li>Total a pagar</li>
</ul>

<h3>Ejemplo de factura</h3>
<pre>
FACTURA Nº: 2026/001
Fecha: 07/01/2026

PROFESIONAL:
María García López
NIF: 12345678A
C/ Ejemplo, 123 - Madrid
Nº Colegiado: M-12345

CLIENTE:
Juan Pérez Martínez
NIF: 87654321B

CONCEPTO: Sesión de psicología clínica
Base imponible: 60,00 €
IVA exento (Art. 20.Uno.3º Ley IVA)
Retención IRPF (15%): -9,00 €
TOTAL: 51,00 €
</pre>

<h2>IVA en servicios psicológicos</h2>

<h3>Exención de IVA</h3>
<p>Los servicios de psicología están <strong>exentos de IVA</strong> según el artículo 20.Uno.3º de la Ley del IVA cuando:</p>
<ul>
    <li>El profesional está colegiado</li>
    <li>Se trata de servicios de salud</li>
    <li>Tienen finalidad terapéutica</li>
</ul>

<h3>Excepciones (SÍ llevan IVA)</h3>
<ul>
    <li>Informes periciales</li>
    <li>Formación o cursos</li>
    <li>Asesoramiento empresarial</li>
    <li>Coaching (si no es terapéutico)</li>
</ul>

<h2>Retención de IRPF</h2>

<h3>¿Cuándo aplicar retención?</h3>
<p>Debes aplicar retención del <strong>15%</strong> cuando el cliente es:</p>
<ul>
    <li>Empresa o autónomo</li>
    <li>Administración pública</li>
    <li>Seguro médico</li>
</ul>

<h3>¿Cuándo NO aplicar retención?</h3>
<ul>
    <li>Pacientes particulares</li>
    <li>Primeros 3 años de actividad (retención 7%)</li>
</ul>

<h2>Declaraciones fiscales</h2>

<h3>Trimestrales</h3>
<ul>
    <li><strong>Modelo 130:</strong> Pago fraccionado IRPF (si no tienes retenciones)</li>
    <li><strong>Modelo 303:</strong> IVA (solo si facturas servicios con IVA)</li>
</ul>

<h3>Anuales</h3>
<ul>
    <li><strong>Modelo 100:</strong> Declaración de la Renta</li>
    <li><strong>Modelo 190:</strong> Resumen de retenciones practicadas</li>
    <li><strong>Modelo 347:</strong> Operaciones con terceros > 3.005€</li>
</ul>

<h2>Gastos deducibles</h2>

<h3>Gastos comunes</h3>
<ul>
    <li>Alquiler del local (100% si es exclusivo)</li>
    <li>Suministros (luz, agua, internet)</li>
    <li>Material de consulta</li>
    <li>Software de gestión</li>
    <li>Cuota colegial</li>
    <li>Seguros profesionales</li>
    <li>Formación continua</li>
    <li>Publicidad y marketing</li>
</ul>

<h3>Gastos parcialmente deducibles</h3>
<ul>
    <li>Teléfono móvil (% uso profesional)</li>
    <li>Vehículo (si se usa para visitas)</li>
    <li>Comidas con pacientes (no deducible)</li>
</ul>

<h2>Autónomo vs Sociedad</h2>

<h3>Autónomo</h3>
<p><strong>Ventajas:</strong></p>
<ul>
    <li>Trámites más sencillos</li>
    <li>Menor coste administrativo</li>
    <li>Gestión más simple</li>
</ul>
<p><strong>Desventajas:</strong></p>
<ul>
    <li>Responsabilidad ilimitada</li>
    <li>Cuota autónomos fija</li>
    <li>IRPF progresivo (hasta 47%)</li>
</ul>

<h3>Sociedad (SL)</h3>
<p><strong>Ventajas:</strong></p>
<ul>
    <li>Responsabilidad limitada</li>
    <li>Impuesto sociedades (25% fijo)</li>
    <li>Optimización fiscal</li>
</ul>
<p><strong>Desventajas:</strong></p>
<ul>
    <li>Mayor coste de constitución</li>
    <li>Más obligaciones contables</li>
    <li>Necesitas gestoría</li>
</ul>

<h2>Software de facturación</h2>

<h3>Beneficios</h3>
<ul>
    <li>Numeración automática</li>
    <li>Cálculo automático de retenciones</li>
    <li>Envío por email</li>
    <li>Control de impagos</li>
    <li>Informes fiscales</li>
    <li>Integración con contabilidad</li>
</ul>

<h2>Consejos prácticos</h2>
<ol>
    <li>Factura inmediatamente después de cada sesión</li>
    <li>Guarda todas las facturas (mínimo 4 años)</li>
    <li>Separa cuentas personal y profesional</li>
    <li>Revisa con gestor antes de declaraciones</li>
    <li>Provisiona para impuestos (30-35% ingresos)</li>
    <li>Digitaliza todo (facturas, gastos, contratos)</li>
</ol>

<h2>Conclusión</h2>
<p>Una buena gestión de la facturación no solo cumple con la ley, sino que te da control sobre tu negocio y evita sorpresas fiscales. Un software especializado simplifica enormemente este proceso.</p>',
                'meta_description' => 'Guía completa de facturación para psicólogos: IVA, IRPF, retenciones, declaraciones fiscales y software de facturación.',
                'meta_keywords' => 'facturación psicólogos, IVA psicología, IRPF autónomos, factura psicólogo',
                'published_at' => now()->subDays(8),
                'is_published' => true,
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }
    }
}
