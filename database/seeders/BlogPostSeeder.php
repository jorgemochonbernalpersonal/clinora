<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Cómo elegir software de gestión para tu consulta de psicología',
                'slug' => 'como-elegir-software-gestion-psicologia',
                'excerpt' => 'Descubre los factores clave que debes considerar al elegir un software de gestión para tu consulta de psicología: seguridad, usabilidad, cumplimiento normativo y más.',
                'content' => '<h2>Introducción</h2>
<p>Elegir el software adecuado para gestionar tu consulta de psicología es una decisión crucial que afectará tu día a día profesional. Un buen sistema puede ahorrarte tiempo, mejorar la organización de tu consulta y ayudarte a cumplir con las normativas de protección de datos.</p>

<h2>Factores clave a considerar</h2>

<h3>1. Seguridad y Cumplimiento RGPD</h3>
<p>La protección de datos de tus pacientes debe ser tu prioridad número uno. Asegúrate de que el software:</p>
<ul>
    <li>Cumple con el RGPD y la LOPD</li>
    <li>Ofrece encriptación end-to-end</li>
    <li>Realiza backups automáticos</li>
    <li>Tiene auditoría de accesos</li>
</ul>

<h3>2. Facilidad de uso</h3>
<p>El software debe ser intuitivo y no requerir formación técnica extensa. Busca:</p>
<ul>
    <li>Interfaz limpia y moderna</li>
    <li>Navegación intuitiva</li>
    <li>Documentación clara</li>
    <li>Soporte técnico accesible</li>
</ul>

<h3>3. Funcionalidades específicas</h3>
<p>Para psicólogos, es esencial contar con:</p>
<ul>
    <li>Notas clínicas tipo SOAP o DAP</li>
    <li>Gestión de citas con recordatorios automáticos</li>
    <li>Portal del paciente</li>
    <li>Teleconsulta integrada</li>
    <li>Facturación automática</li>
</ul>

<h3>4. Precio transparente</h3>
<p>Evita sorpresas en tu factura mensual. El software ideal debe:</p>
<ul>
    <li>Tener precios claros sin costes ocultos</li>
    <li>Ofrecer un plan gratuito o periodo de prueba</li>
    <li>Escalar según tus necesidades</li>
</ul>

<h2>Conclusión</h2>
<p>La mejor opción es un software diseñado específicamente para profesionales de la salud mental, que combine seguridad, facilidad de uso y un modelo de precios justo.</p>',
                'meta_description' => 'Guía completa para elegir el mejor software de gestión para tu consulta de psicología. Seguridad, RGPD, usabilidad y precios.',
                'meta_keywords' => 'software psicólogos, gestión clínica, RGPD, software consulta',
                'published_at' => now()->subDays(10),
                'is_published' => true,
            ],
            [
                'title' => 'RGPD en clínicas de salud: Guía completa para psicólogos',
                'slug' => 'rgpd-clinicas-salud-guia-completa',
                'excerpt' => 'Todo lo que necesitas saber sobre el cumplimiento del RGPD en tu consulta de psicología: consentimientos, derechos del paciente, y medidas de seguridad esenciales.',
                'content' => '<h2>¿Qué es el RGPD?</h2>
<p>El Reglamento General de Protección de Datos (RGPD) es la normativa europea que regula el tratamiento de datos personales. Para los profesionales de la salud, el cumplimiento es especialmente crítico debido a la naturaleza sensible de los datos que manejamos.</p>

<h2>Obligaciones principales</h2>

<h3>1. Base legal del tratamiento</h3>
<p>En el ámbito sanitario, la base legal principal es el <strong>consentimiento informado</strong> del paciente. Este debe ser:</p>
<ul>
    <li>Libre y específico</li>
    <li>Informado y por escrito</li>
    <li>Revocable en cualquier momento</li>
</ul>

<h3>2. Información al paciente</h3>
<p>Debes informar claramente sobre:</p>
<ul>
    <li>Qué datos recopilas</li>
    <li>Para qué los utilizas</li>
    <li>Cuánto tiempo los conservas</li>
    <li>Con quién los compartes (si aplica)</li>
    <li>Los derechos que tiene el paciente</li>
</ul>

<h3>3. Derechos de los pacientes</h3>
<p>Los pacientes tienen derecho a:</p>
<ul>
    <li><strong>Acceso</strong>: Consultar sus datos</li>
    <li><strong>Rectificación</strong>: Corregir datos inexactos</li>
    <li><strong>Supresión</strong>: "Derecho al olvido" (con limitaciones en salud)</li>
    <li><strong>Portabilidad</strong>: Obtener copia de sus datos</li>
    <li><strong>Oposición</strong>: Oponerse al tratamiento</li>
</ul>

<h3>4. Medidas de seguridad</h3>
<p>Implementa estas medidas técnicas y organizativas:</p>
<ul>
    <li>Encriptación de datos</li>
    <li>Control de accesos (contraseñas robustas, 2FA)</li>
    <li>Backups regulares</li>
    <li>Auditoría de accesos</li>
    <li>Formación del personal (si tienes equipo)</li>
</ul>

<h2>Conservación de datos clínicos</h2>
<p>La legislación sanitaria establece que las historias clínicas deben conservarse <strong>al menos 5 años</strong> desde la última asistencia. Después, puedes conservarlas en formato anonimizado o destruirlas de forma segura.</p>

<h2>Conclusión</h2>
<p>El cumplimiento del RGPD no solo es una obligación legal, sino una forma de demostrar profesionalidad y generar confianza en tus pacientes. Un software de gestión que cumpla automáticamente con estas normativas puede ahorrarte tiempo y preocupaciones.</p>',
                'meta_description' => 'Guía completa sobre RGPD para psicólogos: consentimientos, derechos del paciente, medidas de seguridad y conservación de datos clínicos.',
                'meta_keywords' => 'RGPD psicólogos, protección datos salud, LOPD, consentimiento informado',
                'published_at' => now()->subDays(5),
                'is_published' => true,
            ],
            [
                'title' => 'Notas SOAP vs DAP: ¿Cuál usar en tu consulta de psicología?',
                'slug' => 'notas-soap-vs-dap-psicologia',
                'excerpt' => 'Comparativa completa entre las notas SOAP y DAP para psicólogos. Descubre cuál se adapta mejor a tu práctica clínica y cómo implementarlas correctamente.',
                'content' => '<h2>Introducción</h2>
<p>Las notas clínicas son fundamentales para documentar el progreso terapéutico de tus pacientes. Dos de los formatos más utilizados en psicología son SOAP y DAP. ¿Cuál es mejor para tu consulta?</p>

<h2>Notas SOAP</h2>
<p>SOAP es un acrónimo que significa Subjetivo, Objetivo, Análisis y Plan.</p>

<h3>Estructura</h3>
<ul>
    <li><strong>S (Subjetivo)</strong>: Lo que el paciente reporta (síntomas, emociones, pensamientos)</li>
    <li><strong>O (Objetivo)</strong>: Observaciones del terapeuta (comportamiento, apariencia, resultados de tests)</li>
    <li><strong>A (Análisis/Assessment)</strong>: Evaluación profesional del estado del paciente</li>
    <li><strong>P (Plan)</strong>: Intervenciones planificadas y objetivos para la próxima sesión</li>
</ul>

<h3>Ventajas</h3>
<ul>
    <li>Estructura clara y completa</li>
    <li>Ampliamente reconocida en el ámbito sanitario</li>
    <li>Facilita la comunicación con otros profesionales</li>
    <li>Ideal para casos complejos o multidisciplinares</li>
</ul>

<h3>Desventajas</h3>
<ul>
    <li>Puede ser más extensa</li>
    <li>Requiere más tiempo para completar</li>
</ul>

<h2>Notas DAP</h2>
<p>DAP significa Data, Assessment, Plan (Datos, Evaluación, Plan).</p>

<h3>Estructura</h3>
<ul>
    <li><strong>D (Data)</strong>: Información objetiva y subjetiva combinada</li>
    <li><strong>A (Assessment)</strong>: Evaluación del progreso terapéutico</li>
    <li><strong>P (Plan)</strong>: Intervenciones y objetivos</li>
</ul>

<h3>Ventajas</h3>
<ul>
    <li>Más concisa que SOAP</li>
    <li>Más rápida de completar</li>
    <li>Suficiente para la mayoría de sesiones de terapia</li>
</ul>

<h3>Desventajas</h3>
<ul>
    <li>Menos detallada que SOAP</li>
    <li>Puede no ser suficiente para casos complejos</li>
</ul>

<h2>¿Cuál elegir?</h2>

<h3>Usa SOAP si:</h3>
<ul>
    <li>Trabajas en un equipo multidisciplinar</li>
    <li>Atiendes casos complejos</li>
    <li>Necesitas documentación muy detallada</li>
    <li>Debes cumplir con requisitos específicos de tu institución</li>
</ul>

<h3>Usa DAP si:</h3>
<ul>
    <li>Trabajas de forma independiente</li>
    <li>Priorizas la eficiencia</li>
    <li>Atiendes principalmente casos de terapia ambulatoria estándar</li>
</ul>

<h2>La solución ideal</h2>
<p>No tienes que elegir solo uno. Muchos profesionales usan SOAP para la evaluación inicial y sesiones complejas, y DAP para el seguimiento rutinario. Un software de gestión flexible te permite cambiar entre formatos según tus necesidades.</p>

<h2>Conclusión</h2>
<p>Tanto SOAP como DAP son formatos válidos y profesionales. Lo importante es que elijas el que mejor se adapte a tu práctica y lo uses de forma consistente para documentar adecuadamente el proceso terapéutico.</p>',
                'meta_description' => 'Comparativa entre notas SOAP y DAP para psicólogos. Estructura, ventajas, desventajas y consejos para elegir el formato adecuado.',
                'meta_keywords' => 'notas SOAP, notas DAP, documentación clínica, psicología',
                'published_at' => now()->subDays(2),
                'is_published' => true,
            ],
        ];

        foreach ($posts as $postData) {
            \App\Models\Post::create($postData);
        }
    }
}
