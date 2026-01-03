# 🧠 Funcionalidades Profundas para Psicólogos - Guía Completa

## 📋 Índice

1. [Historia Clínica Psicológica](#historia-clínica-psicológica)
2. [Evaluaciones y Test Psicométricos](#evaluaciones-y-test-psicométricos)
3. [Notas de Sesión y Evolución](#notas-de-sesión-y-evolución)
4. [Consentimiento Informado](#consentimiento-informado)
5. [Plan de Tratamiento](#plan-de-tratamiento)
6. [Gestión de Crisis y Riesgo](#gestión-de-crisis-y-riesgo)
7. [Consideraciones Legales y Éticas](#consideraciones-legales-y-éticas)
8. [Features Avanzadas](#features-avanzadas)

---

## 1. Historia Clínica Psicológica

### 📝 Componentes Esenciales

#### **A. Datos Demográficos y de Contacto**
```
- Datos básicos (nombre, edad, género, estado civil)
- Contacto de emergencia (obligatorio)
- Datos de contacto del tutor legal (si es menor)
- Ocupación y nivel educativo
- Religión/creencias (opcional, pero relevante para intervención)
- Situación de vivienda
```

**Importancia:** El contexto sociocultural influye en la conceptualización del caso.

---

#### **B. Motivo de Consulta**
```sql
CREATE TABLE consultation_reasons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Motivo manifestado
    chief_complaint TEXT NOT NULL,  -- "Me siento triste todo el tiempo"
    
    -- Cuándo comenzó
    onset_date DATE,
    onset_description TEXT,  -- "Hace 6 meses, tras la pérdida de mi trabajo"
    
    -- Evolución
    progression ENUM('progresiva', 'estable', 'fluctuante', 'mejora'),
    
    -- Intentos previos de solución
    previous_attempts TEXT,  -- "He probado yoga, meditación, hablar con amigos"
    
    -- Quién derivó al paciente
    referral_source ENUM('auto_referido', 'medico_cabecera', 'psiquiatra', 'familiar', 'otro'),
    referral_details TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Razón:** El motivo de consulta es la base para establecer objetivos terapéuticos.

---

#### **C. Antecedentes Personales**

##### **Antecedentes Médicos**
```
- Enfermedades crónicas
- Medicación actual (especialmente psicofármacos)
- Alergias
- Hospitalizaciones previas
- Cirugías
- Trastornos del sueño
- Trastornos alimentarios
```

##### **Antecedentes Psiquiátricos**
```sql
CREATE TABLE psychiatric_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Diagnósticos previos
    previous_diagnoses JSON,  -- [{"diagnosis": "Depresión Mayor", "year": 2020, "by": "Dr. García"}]
    
    -- Tratamientos previos
    previous_treatments JSON,  -- [{"type": "Terapia Cognitivo-Conductual", "duration": "6 meses", "outcome": "Mejoría parcial"}]
    
    -- Medicación psiquiátrica previa
    psychiatric_medications JSON,  -- [{"medication": "Sertralina", "dose": "50mg", "from": "2020-01", "to": "2020-08"}]
    
    -- Hospitalizaciones psiquiátricas
    psychiatric_hospitalizations JSON,
    
    -- Intentos de suicidio
    suicide_attempts INT DEFAULT 0,
    suicide_attempts_details TEXT,
    last_suicide_attempt_date DATE,
    
    -- Autolesiones
    self_harm_history BOOLEAN DEFAULT FALSE,
    self_harm_details TEXT,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**⚠️ CRÍTICO:** Esta información es esencial para evaluación de riesgo.

---

##### **Historia del Desarrollo**
```
Para niños/adolescentes:
- Embarazo y parto (complicaciones)
- Desarrollo motor (gatear, caminar)
- Desarrollo del lenguaje (primeras palabras)
- Control de esfínteres
- Escolarización (adaptación, rendimiento)
- Hitos del desarrollo
- Traumas tempranos
```

##### **Historia Familiar**
```sql
CREATE TABLE family_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Genograma (representación visual)
    genogram_data JSON,  -- Estructura para representar familia
    
    -- Antecedentes psiquiátricos familiares
    family_psychiatric_history JSON,  -- [{"relation": "madre", "condition": "Depresión", "treatment": "Medicación"}]
    
    -- Antecedentes de suicidio en la familia
    family_suicide_history BOOLEAN DEFAULT FALSE,
    family_suicide_details TEXT,
    
    -- Adicciones en la familia
    family_substance_abuse JSON,
    
    -- Enfermedades crónicas familiares
    family_chronic_illnesses JSON,
    
    -- Dinámica familiar
    family_dynamics TEXT,  -- "Familia nuclear, padres divorciados desde hace 5 años"
    attachment_style ENUM('seguro', 'ansioso', 'evitativo', 'desorganizado'),
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

#### **D. Historia Social y Relacional**

```sql
CREATE TABLE social_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Relaciones significativas
    marital_status ENUM('soltero', 'casado', 'divorciado', 'viudo', 'pareja_de_hecho'),
    relationship_quality ENUM('satisfactoria', 'conflictiva', 'distante', 'abusiva', 'no_aplica'),
    relationship_details TEXT,
    
    -- Hijos
    has_children BOOLEAN DEFAULT FALSE,
    children_details JSON,  -- [{"age": 5, "gender": "M", "relationship": "Buena"}]
    
    -- Apoyo social
    social_support_level ENUM('alto', 'medio', 'bajo', 'nulo'),
    support_network TEXT,  -- "Amigos cercanos: 2, Familia de apoyo: padres y hermana"
    
    -- Red social
    social_activities TEXT,
    hobbies TEXT,
    
    -- Historia laboral/académica
    employment_status ENUM('empleado', 'desempleado', 'estudiante', 'jubilado', 'incapacidad', 'autónomo'),
    occupation TEXT,
    work_satisfaction ENUM('alta', 'media', 'baja', 'muy_baja'),
    work_stress_level ENUM('bajo', 'moderado', 'alto', 'muy_alto'),
    
    -- Historia de trauma
    trauma_history BOOLEAN DEFAULT FALSE,
    trauma_details TEXT,  -- DEBE SER CIFRADO
    trauma_type ENUM('abuso_sexual', 'abuso_fisico', 'abuso_emocional', 'negligencia', 'accidente', 'desastre_natural', 'violencia', 'multiple', 'otro'),
    
    -- Abuso de sustancias
    substance_use BOOLEAN DEFAULT FALSE,
    substances_used JSON,  -- [{"substance": "Alcohol", "frequency": "Fines de semana", "amount": "2-3 copas"}]
    substance_abuse_history TEXT,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 2. Evaluaciones y Test Psicométricos

### 📊 Instrumentos de Evaluación

#### **A. Inventarios de Depresión**

##### **BDI-II (Beck Depression Inventory II)**
```sql
CREATE TABLE assessment_bdi2 (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    
    -- 21 ítems (cada uno puntuado 0-3)
    q1_sadness INT CHECK (q1_sadness BETWEEN 0 AND 3),
    q2_pessimism INT CHECK (q2_pessimism BETWEEN 0 AND 3),
    q3_past_failure INT CHECK (q3_past_failure BETWEEN 0 AND 3),
    q4_loss_of_pleasure INT CHECK (q4_loss_of_pleasure BETWEEN 0 AND 3),
    q5_guilty_feelings INT CHECK (q5_guilty_feelings BETWEEN 0 AND 3),
    q6_punishment_feelings INT CHECK (q6_punishment_feelings BETWEEN 0 AND 3),
    q7_self_dislike INT CHECK (q7_self_dislike BETWEEN 0 AND 3),
    q8_self_criticalness INT CHECK (q8_self_criticalness BETWEEN 0 AND 3),
    q9_suicidal_thoughts INT CHECK (q9_suicidal_thoughts BETWEEN 0 AND 3),  -- ⚠️ ALERTA
    q10_crying INT CHECK (q10_crying BETWEEN 0 AND 3),
    q11_agitation INT CHECK (q11_agitation BETWEEN 0 AND 3),
    q12_loss_of_interest INT CHECK (q12_loss_of_interest BETWEEN 0 AND 3),
    q13_indecisiveness INT CHECK (q13_indecisiveness BETWEEN 0 AND 3),
    q14_worthlessness INT CHECK (q14_worthlessness BETWEEN 0 AND 3),
    q15_loss_of_energy INT CHECK (q15_loss_of_energy BETWEEN 0 AND 3),
    q16_sleep_changes INT CHECK (q16_sleep_changes BETWEEN 0 AND 3),
    q17_irritability INT CHECK (q17_irritability BETWEEN 0 AND 3),
    q18_appetite_changes INT CHECK (q18_appetite_changes BETWEEN 0 AND 3),
    q19_concentration_difficulty INT CHECK (q19_concentration_difficulty BETWEEN 0 AND 3),
    q20_tiredness_fatigue INT CHECK (q20_tiredness_fatigue BETWEEN 0 AND 3),
    q21_loss_of_interest_in_sex INT CHECK (q21_loss_of_interest_in_sex BETWEEN 0 AND 3),
    
    -- Resultados
    total_score INT,  -- 0-63
    severity ENUM('minimal', 'mild', 'moderate', 'severe'),
    /*
      0-13: Depresión mínima
      14-19: Depresión leve
      20-28: Depresión moderada
      29-63: Depresión grave
    */
    
    -- Alertas
    suicide_risk_detected BOOLEAN DEFAULT FALSE,  -- Si q9 > 0
    
    -- Contexto
    administered_at TIMESTAMP NOT NULL,
    administered_by BIGINT UNSIGNED,  -- professional_id
    interpretation TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Interpretación Automática:**
```php
class BDI2Calculator
{
    public function calculate(array $answers): AssessmentResult
    {
        $total = array_sum($answers);
        
        // ALERTA CRÍTICA
        if ($answers['q9_suicidal_thoughts'] > 0) {
            $this->triggerSuicideRiskAlert($answers['contact_id']);
        }
        
        $severity = match(true) {
            $total <= 13 => 'minimal',
            $total <= 19 => 'mild',
            $total <= 28 => 'moderate',
            default => 'severe'
        };
        
        $interpretation = match($severity) {
            'minimal' => 'No presenta síntomas depresivos clínicamente significativos.',
            'mild' => 'Presenta síntomas depresivos leves. Se recomienda psicoterapia.',
            'moderate' => 'Presenta síntomas depresivos moderados. Se recomienda terapia y evaluar farmacoterapia.',
            'severe' => 'Presenta síntomas depresivos graves. Se recomienda derivación urgente a psiquiatría.'
        };
        
        return new AssessmentResult(
            score: $total,
            severity: $severity,
            interpretation: $interpretation,
            alerts: $this->checkAlerts($answers)
        );
    }
    
    private function checkAlerts(array $answers): array
    {
        $alerts = [];
        
        // Ideación suicida
        if ($answers['q9_suicidal_thoughts'] > 0) {
            $alerts[] = [
                'level' => 'CRÍTICO',
                'type' => 'RIESGO_SUICIDIO',
                'message' => 'Paciente presenta ideación suicida. Evaluación de riesgo URGENTE.',
                'action' => 'Realizar entrevista de riesgo suicida inmediatamente'
            ];
        }
        
        // Sentimientos de culpa intensos
        if ($answers['q5_guilty_feelings'] >= 2 && $answers['q6_punishment_feelings'] >= 2) {
            $alerts[] = [
                'level' => 'ALTO',
                'type' => 'CULPA_PATOLÓGICA',
                'message' => 'Sentimientos intensos de culpa y autopunición detectados.'
            ];
        }
        
        return $alerts;
    }
}
```

---

##### **PHQ-9 (Patient Health Questionnaire)**
```sql
CREATE TABLE assessment_phq9 (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- 9 ítems (frecuencia: 0=nunca, 1=varios días, 2=más de la mitad de los días, 3=casi todos los días)
    q1_little_interest INT CHECK (q1_little_interest BETWEEN 0 AND 3),
    q2_feeling_down INT CHECK (q2_feeling_down BETWEEN 0 AND 3),
    q3_sleep_problems INT CHECK (q3_sleep_problems BETWEEN 0 AND 3),
    q4_feeling_tired INT CHECK (q4_feeling_tired BETWEEN 0 AND 3),
    q5_poor_appetite INT CHECK (q5_poor_appetite BETWEEN 0 AND 3),
    q6_feeling_bad_about_self INT CHECK (q6_feeling_bad_about_self BETWEEN 0 AND 3),
    q7_trouble_concentrating INT CHECK (q7_trouble_concentrating BETWEEN 0 AND 3),
    q8_moving_slowly INT CHECK (q8_moving_slowly BETWEEN 0 AND 3),
    q9_suicidal_thoughts INT CHECK (q9_suicidal_thoughts BETWEEN 0 AND 3),  -- ⚠️ CRÍTICO
    
    total_score INT,
    severity ENUM('none', 'mild', 'moderate', 'moderately_severe', 'severe'),
    /*
      0-4: Ninguna/mínima
      5-9: Leve
      10-14: Moderada
      15-19: Moderadamente severa
      20-27: Severa
    */
    
    administered_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

##### **GAD-7 (Generalized Anxiety Disorder)**
```sql
CREATE TABLE assessment_gad7 (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- 7 ítems sobre ansiedad
    q1_feeling_nervous INT CHECK (q1_feeling_nervous BETWEEN 0 AND 3),
    q2_not_stop_worrying INT CHECK (q2_not_stop_worrying BETWEEN 0 AND 3),
    q3_worrying_too_much INT CHECK (q3_worrying_too_much BETWEEN 0 AND 3),
    q4_trouble_relaxing INT CHECK (q4_trouble_relaxing BETWEEN 0 AND 3),
    q5_restless INT CHECK (q5_restless BETWEEN 0 AND 3),
    q6_easily_annoyed INT CHECK (q6_easily_annoyed BETWEEN 0 AND 3),
    q7_feeling_afraid INT CHECK (q7_feeling_afraid BETWEEN 0 AND 3),
    
    total_score INT,
    severity ENUM('minimal', 'mild', 'moderate', 'severe'),
    /*
      0-4: Ansiedad mínima
      5-9: Leve
      10-14: Moderada
      15-21: Severa
    */
    
    administered_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

#### **B. Evaluaciones para Niños y Adolescentes**

##### **CBCL (Child Behavior Checklist)**
```
- Lista de 113 ítems
- Evalúa problemas emocionales y conductuales
- Versiones: 1.5-5 años, 6-18 años
- Rellenado por padres
```

##### **SCARED (Screen for Child Anxiety Related Disorders)**
```
- 41 ítems
- Evalúa trastornos de ansiedad en niños
- 5 subescalas: pánico, ansiedad generalizada, ansiedad de separación, fobia social, fobia escolar
```

---

#### **C. Instrumentos de Personalidad**

##### **MMPI-2 (Minnesota Multiphasic Personality Inventory)**
```
- 567 ítems (versión completa)
- 338 ítems (MMPI-2-RF, versión reducida)
- Escalas de validez + clínicas
- Requiere software especializado para corrección
```

**Implementación:**
```php
// API externa para corrección MMPI-2
class MMPIService
{
    public function score(array $responses): MMPIReport
    {
        // Escalas de validez
        $validity = $this->calculateValidityScales($responses);
        
        if (!$validity->isValid()) {
            throw new InvalidProfileException('Perfil inválido por escalas de validez');
        }
        
        // Escalas clínicas
        $clinical = $this->calculateClinicalScales($responses);
        
        return new MMPIReport(
            validity: $validity,
            clinical: $clinical,
            interpretation: $this->generateInterpretation($clinical)
        );
    }
}
```

---

## 3. Notas de Sesión y Evolución

### 📝 Formato SOAP

```sql
CREATE TABLE session_notes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    appointment_id BIGINT UNSIGNED,
    
    session_number INT NOT NULL,
    session_date DATE NOT NULL,
    duration_minutes INT NOT NULL,
    
    -- S: Subjetivo (lo que el paciente dice)
    subjective TEXT NOT NULL,
    /*
      Ejemplo:
      "La paciente refiere sentirse 'un poco mejor' esta semana. 
      Menciona que logró dormir 6 horas seguidas 3 noches. 
      Continúa con pensamientos intrusivos sobre el accidente, pero con menor frecuencia.
      Describe que utilizó la técnica de respiración cuando sintió ansiedad en el supermercado."
    */
    
    -- O: Objetivo (observaciones del terapeuta)
    objective TEXT NOT NULL,
    /*
      Ejemplo:
      "Se presenta puntual, con aspecto cuidado. 
      Contacto visual adecuado. 
      Lenguaje coherente y organizado. 
      Afecto congruente con el contenido. 
      No se observan síntomas psicóticos. 
      Tono de voz modulado, sin lentificación psicomotriz."
    */
    
    -- A: Análisis/Assessment (conceptualización clínica)
    assessment TEXT NOT NULL,
    /*
      Ejemplo:
      "Evolución favorable de sintomatología depresiva (BDI-II: 18, bajó de 24). 
      Mejora en patrones de sueño indica disminución de hiperactivación del SNA. 
      Persistencia de pensamientos intrusivos compatible con TEPT no resuelto. 
      Buena adherencia a técnicas de manejo de ansiedad. 
      Reestructuración cognitiva mostrando efectos positivos."
    */
    
    -- P: Plan (intervenciones y próximos pasos)
    plan TEXT NOT NULL,
    /*
      Ejemplo:
      "1. Continuar con exposición gradual a estímulos relacionados con trauma
       2. Introduce tarea de registro de pensamientos automáticos
       3. Psicoeducación sobre TEPT - proporcionar material de lectura
       4. Mantener frecuencia semanal de sesiones
       5. Próxima sesión: Trabajar reestructuración cognitiva de creencias nucleares sobre seguridad
       6. Considerar derivación a psiquiatra si persisten dificultades de sueño en 2 semanas"
    */
    
    -- Intervenciones realizadas en sesión
    interventions_used JSON,  -- ["Reestructuración cognitiva", "Relajación muscular progresiva", "EMDR"]
    
    -- Tareas para el paciente
    homework TEXT,
    /*
      "- Registrar situaciones que generan ansiedad (frecuencia, intensidad 1-10, pensamientos)
       - Practicar respiración diafragmática 10 min/día
       - Leer capítulo 3 de 'Vencer la ansiedad' (bibliografía proporcionada)"
    */
    
    -- Riesgo
    risk_assessment ENUM('sin_riesgo', 'riesgo_bajo', 'riesgo_moderado', 'riesgo_alto', 'riesgo_inminente'),
    risk_details TEXT,
    
    -- Progreso
    progress_rating INT CHECK (progress_rating BETWEEN 1 AND 10),  -- 1=empeoramiento, 10=total mejoría
    
    -- Firma y validación
    is_signed BOOLEAN DEFAULT FALSE,
    signed_at TIMESTAMP NULL,
    digital_signature TEXT,  -- Hash de firma digital
    
    -- Auditoría
    created_by BIGINT UNSIGNED,
    updated_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (professional_id) REFERENCES professionals(id),
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);
```

---

### 📊 Seguimiento de Progreso

```sql
CREATE TABLE progress_tracking (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    session_note_id BIGINT UNSIGNED NOT NULL,
    
    -- Objetivos terapéuticos
    therapeutic_goals JSON,
    /*
      [
        {
          "goal": "Reducir frecuencia de ataques de pánico",
          "baseline": "3-4 ataques/semana",
          "current": "1 ataque/semana",
          "target": "0-1 ataques/mes",
          "progress_percentage": 60
        }
      ]
    */
    
    -- Métricas de síntomas
    symptom_severity JSON,
    /*
      {
        "depression": {"score": 18, "change": -6},
        "anxiety": {"score": 12, "change": -3},
        "sleep_quality": {"score": 6, "change": +2}
      }
    */
    
    -- Adherencia al tratamiento
    medication_adherence INT CHECK (medication_adherence BETWEEN 0 AND 100),  -- Porcentaje
    homework_completion INT CHECK (homework_completion BETWEEN 0 AND 100),
    session_attendance ENUM('asistió', 'falta_justificada', 'falta_injustificada', 'canceló_último_momento'),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 4. Consentimiento Informado

### ⚖️ Aspectos Legales Críticos

```sql
CREATE TABLE consent_forms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Tipo de consentimiento
    consent_type ENUM(
        'initial_treatment',      -- Consentimiento inicial
        'teleconsultation',       -- Para sesiones online
        'minors',                 -- Consentimiento parental
        'recording',              -- Grabación de sesiones (con fines de supervisión)
        'research',               -- Participación en investigación
        'third_party_communication', -- Comunicación con otros profesionales
        'medication_referral'     -- Derivación a psiquiatría
    ) NOT NULL,
    
    -- Contenido del consentimiento
    consent_title VARCHAR(255),
    consent_text LONGTEXT NOT NULL,
    /*
      Debe incluir:
      1. Naturaleza del tratamiento
      2. Objetivos esperados
      3. Técnicas que se utilizarán
      4. Duración estimada
      5. Riesgos y beneficios
      6. Alternativas de tratamiento
      7. Confidencialidad y sus límites
      8. Coste y política de cancelaciones
      9. Derecho a retirarse
      10. Gestión de datos personales (RGPD)
    */
    
    -- Información específica para menores
    legal_guardian_name VARCHAR(255),
    legal_guardian_relationship VARCHAR(100),
    legal_guardian_id_document VARCHAR(50),  -- DNI/NIE
    
    minor_assent BOOLEAN DEFAULT FALSE,  -- Asentimiento del menor (si >12 años)
    minor_assent_details TEXT,
    
    -- Firma electrónica
    patient_signature_data TEXT,  -- Base64 de firma
    patient_ip_address VARCHAR(45),
    patient_device_info TEXT,
    
    guardian_signature_data TEXT,  -- Para menores
    
    -- Testigos (si aplica)
    witness_name VARCHAR(255),
    witness_signature_data TEXT,
    
    -- Validación
    signed_at TIMESTAMP NULL,
    is_valid BOOLEAN DEFAULT FALSE,
    
    -- Revocación
    revoked_at TIMESTAMP NULL,
    revocation_reason TEXT,
    
    -- Versión del documento
    document_version VARCHAR(20),  -- Control de versiones del template
    
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (professional_id) REFERENCES professionals(id),
    FOREIGN KEY (contact_id) REFERENCES contacts(id)
);
```

---

### 📋 Template de Consentimiento Informado

```markdown
# CONSENTIMIENTO INFORMADO PARA TRATAMIENTO PSICOLÓGICO

## 1. IDENTIFICACIÓN DEL PROFESIONAL
- Nombre: [NOMBRE_PROFESIONAL]
- Nº de Colegiado: [NUM_COLEGIADO]
- Especialidad: Psicología Clínica
- Centro: [NOMBRE_CENTRO]

## 2. NATURALEZA DEL TRATAMIENTO
El tratamiento psicológico consiste en un proceso de ayuda basado en la interacción 
entre el/la psicólogo/a y el/la paciente, mediante el uso de técnicas psicológicas 
científicamente validadas.

## 3. OBJETIVOS
Los objetivos del tratamiento serán acordados conjuntamente y podrán incluir:
- [OBJETIVOS PERSONALIZADOS]

## 4. METODOLOGÍA
Se utilizarán técnicas basadas en la evidencia científica, que pueden incluir:
- Entrevistas clínicas
- Evaluación psicológica mediante test y cuestionarios
- Técnicas cognitivo-conductuales
- [OTRAS TÉCNICAS SEGÚN CASO]

## 5. DURACIÓN Y FRECUENCIA
- Duración aproximada: [DURACIÓN_ESTIMADA]
- Frecuencia de sesiones: [FRECUENCIA]
- Duración de cada sesión: [MINUTOS] minutos

## 6. CONFIDENCIALIDAD
Toda la información compartida en las sesiones es **estrictamente confidencial** 
y está protegida por el secreto profesional recogido en el Código Deontológico 
del Psicólogo.

### Excepciones a la confidencialidad:
1. **Riesgo grave e inminente** para el paciente o terceros
2. **Orden judicial** que requiera revelación de información
3. **Maltrato de menores o personas vulnerables** (obligación legal de notificación)
4. **Consentimiento expreso** del paciente para compartir información

## 7. PROTECCIÓN DE DATOS (RGPD)
Sus datos personales serán tratados conforme al Reglamento (UE) 2016/679 (RGPD) y la LOPDGDD.
- Responsable: [NOMBRE_PROFESIONAL]
- Finalidad: Prestación de servicios psicológicos
- Conservación: Durante el tratamiento y 5 años posteriores (obligación legal)
- Derechos: Acceso, rectificación, supresión, limitación, portabilidad, oposición

## 8. COSTES Y CANCELACIONES
- Coste por sesión: [PRECIO]€
- Política de cancelación: Las cancelaciones con menos de 24h de antelación se cobrarán al 50%

## 9. RELACIONES PROFESIONALES
La relación será estrictamente profesional. No se permitirán:
- Relaciones duales (amistad, negocios, etc.)
- Contacto por redes sociales personales
- Regalos de valor significativo

## 10. DERECHOS DEL PACIENTE
Usted tiene derecho a:
- Recibir información clara sobre su tratamiento
- Participar en las decisiones terapéuticas
- Solicitar una segunda opinión
- Finalizar el tratamiento en cualquier momento
- Presentar quejas ante el Colegio Oficial de Psicólogos

---

## DECLARACIÓN DE CONSENTIMIENTO

Yo, [NOMBRE_PACIENTE], con DNI [DNI]:

☐ He leído y comprendido la información anterior  
☐ He tenido oportunidad de hacer preguntas  
☐ Consiento voluntariamente recibir tratamiento psicológico  
☐ Autorizo el tratamiento de mis datos según lo indicado  

Firma: _________________ Fecha: _______

[Para menores de edad]
Yo, [NOMBRE_TUTOR], como tutor legal, consiento el tratamiento de [NOMBRE_MENOR]

Firma tutor: _________________ Fecha: _______
```

---

## 5. Plan de Tratamiento

```sql
CREATE TABLE treatment_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    professional_id BIGINT UNSIGNED NOT NULL,
    
    -- Diagnóstico (puede ser provisional)
    primary_diagnosis_code VARCHAR(10),  -- DSM-5 o CIE-10
    primary_diagnosis_name VARCHAR(255),
    secondary_diagnoses JSON,
    
    -- Conceptualización del caso
    case_formulation TEXT NOT NULL,
    /*
      Modelo biopsicosocial:
      - Factores predisponentes
      - Factores precipitantes
      - Factores mantenedores
      - Factores protectores
    */
    
    -- Objetivos SMART
    goals JSON NOT NULL,
    /*
      [
        {
          "specific": "Reducir frecuencia de pensamientos intrusivos",
          "measurable": "De 10-15 veces/día a <3 veces/día",
          "achievable": true,
          "relevant": "Impacta significativamente en calidad de vida",
          "timebound": "8-12 semanas",
          "priority": "alta"
        }
      ]
    */
    
    -- Intervenciones planificadas
    therapeutic_approach ENUM('cognitivo_conductual', 'psicodinámico', 'humanista', 'sistémico', 'integrador', 'EMDR', 'ACT', 'DBT', 'otro'),
    specific_techniques JSON,  -- ["Exposición gradual", "Reestructuración cognitiva", "Mindfulness"]
    
    -- Línea del tiempo
    estimated_duration_weeks INT,
    estimated_sessions INT,
    session_frequency ENUM('semanal', 'quincenal', 'mensual', 'variable'),
    
    -- Criterios de éxito
    success_criteria TEXT,
    /*
      - BDI-II < 10 (depresión mínima)
      - Retorno al funcionamiento laboral
      - Mejora en relaciones interpersonales (escala subjetiva 7+/10)
    */
    
    -- Criterios de alta
    discharge_criteria TEXT,
    
    -- Revisiones del plan
    last_review_date DATE,
    next_review_date DATE,
    
    -- Estado
    status ENUM('active', 'on_hold', 'completed', 'discontinued'),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (professional_id) REFERENCES professionals(id)
);
```

---

## 6. Gestión de Crisis y Riesgo

### 🚨 Evaluación de Riesgo Suicida

```sql
CREATE TABLE suicide_risk_assessments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    assessed_by BIGINT UNSIGNED NOT NULL,
    session_note_id BIGINT UNSIGNED,
    
    -- Factores de riesgo
    ideation_present BOOLEAN NOT NULL,
    ideation_frequency ENUM('ninguna', 'ocasional', 'frecuente', 'constante'),
    ideation_intensity INT CHECK (ideation_intensity BETWEEN 0 AND 10),
    
    has_plan BOOLEAN DEFAULT FALSE,
    plan_details TEXT,  -- CIFRAR
    plan_specificity ENUM('vago', 'general', 'específico', 'muy_específico'),
    
    has_means BOOLEAN DEFAULT FALSE,
    means_access TEXT,  -- "Tiene acceso a medicación", "Arma de fuego en casa"
    
    intent_level ENUM('ninguno', 'ambivalente', 'moderado', 'alto'),
    
    prior_attempts INT DEFAULT 0,
    last_attempt_date DATE,
    last_attempt_method VARCHAR(255),
    
    -- Factores protectores
    protective_factors JSON,
    /*
      [
        "Relación estrecha con hija",
        "Creencias religiosas",
        "Proyectos futuros (viaje programado)",
        "Mascota que depende del paciente"
      ]
    */
    
    -- Factores precipitantes
    recent_stressors JSON,
    
    -- Nivel de riesgo calculado
    risk_level ENUM('bajo', 'moderado', 'alto', 'inminente') NOT NULL,
    
    -- Plan de seguridad
    safety_plan_created BOOLEAN DEFAULT FALSE,
    safety_plan_id BIGINT UNSIGNED,
    
    -- Intervención inmediata
    immediate_actions_taken JSON,
    /*
      [
        "Contacto con familiar de apoyo",
        "Eliminación de medios letales",
        "Programación de seguimiento en 24h",
        "Derivación a urgencias psiquiátricas"
      ]
    */
    
    requires_hospitalization BOOLEAN DEFAULT FALSE,
    hospitalization_arranged BOOLEAN DEFAULT FALSE,
    
    -- Seguimiento
    next_contact_required_within_hours INT,  -- 24, 48, 72
    
    assessed_at TIMESTAMP NOT NULL,
    reassessment_required_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (assessed_by) REFERENCES professionals(id),
    FOREIGN KEY (safety_plan_id) REFERENCES safety_plans(id)
);
```

---

### 🛡️ Plan de Seguridad

```sql
CREATE TABLE safety_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    
    -- Señales de advertencia
    warning_signs JSON NOT NULL,
    /*
      [
        "Pensamientos sobre no querer vivir",
        "Imágenes de hacerme daño",
        "Sensación de desesperanza intensa",
        "Aislamiento social marcado"
      ]
    */
    
    -- Estrategias de afrontamiento internas
    internal_coping_strategies JSON,
    /*
      [
        "Respiración profunda 5 minutos",
        "Escuchar música relajante",
        "Escribir en diario",
        "Acariciar a mi perro"
      ]
    */
    
    -- Distracciones saludables
    healthy_distractions JSON,
    /*
      ["Salir a caminar", "Ver una serie", "Cocinar"]
    */
    
    -- Personas de apoyo
    support_contacts JSON NOT NULL,
    /*
      [
        {"name": "María (hermana)", "phone": "+34 600 XXX XXX", "available": "Cualquier hora"},
        {"name": "Juan (mejor amigo)", "phone": "+34 600 YYY YYY", "available": "Tardes"}
      ]
    */
    
    -- Profesionales de contacto
    professional_contacts JSON,
    /*
      [
        {"role": "Psicólogo/a", "name": "[PROFESIONAL]", "phone": "+34 XXX", "hours": "L-V 9-20h"},
        {"role": "Psiquiatra", "name": "Dr. García", "phone": "+34 YYY", "hours": "L-V 10-14h"}
      ]
    */
    
    -- Líneas de crisis 24/7
    crisis_lines JSON,
    /*
      [
        {"name": "Teléfono de la Esperanza", "phone": "717 003 717", "available": "24/7"},
        {"name": "Teléfono contra el Suicidio", "phone": "911 385 385", "available": "24/7"},
        {"name": "061 - Emergencias", "phone": "061", "available": "24/7"}
      ]
    */
    
    -- Hacer el entorno seguro
    means_restriction_steps JSON,
    /*
      [
        "Medicación guardada por familiar",
        "Objetos punzantes bajo llave",
        "Evitar consumo de alcohol"
      ]
    */
    
    -- Razones para vivir
    reasons_for_living JSON,
    /*
      [
        "Mi hija me necesita",
        "Quiero ver crecer a mis nietos",
        "Tengo un viaje programado con mi mejor amiga",
        "Mi gato depende de mí"
      ]
    */
    
    -- Compromiso de seguridad
    safety_commitment TEXT,
    safety_commitment_signed BOOLEAN DEFAULT FALSE,
    
    -- Estado
    is_active BOOLEAN DEFAULT TRUE,
    last_reviewed_at TIMESTAMP,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (created_by) REFERENCES professionals(id)
);
```

---

## 7. Consideraciones Legales y Éticas

### ⚖️ Obligaciones del Psicólogo

```sql
CREATE TABLE ethical_incidents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    professional_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED,
    
    -- Tipo de situación ética
    incident_type ENUM(
        'dual_relationship',           -- Relación dual
        'confidentiality_breach',      -- Ruptura de confidencialidad
        'boundary_violation',          -- Violación de límites
        'competence_limits',           -- Fuera de competencia
        'mandatory_reporting',         -- Denuncia obligatoria
        'conflict_of_interest',        -- Conflicto de intereses
        'informed_consent_issue',      -- Problema con consentimiento
        'other'
    ),
    
    -- Descripción del incidente
    incident_description TEXT NOT NULL,
    
    -- Acción tomada
    action_taken TEXT NOT NULL,
    
    -- Consulta con colegas/supervisión
    consultation_sought BOOLEAN DEFAULT FALSE,
    consultation_details TEXT,
    
    -- Notificación a autoridades (si aplica)
    authorities_notified BOOLEAN DEFAULT FALSE,
    which_authorities VARCHAR(255),  -- "Servicios Sociales", "Fiscalía", etc.
    
    -- Resolución
    resolution TEXT,
    
    incident_date TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (professional_id) REFERENCES professionals(id),
    FOREIGN KEY (contact_id) REFERENCES contacts(id)
);
```

---

### 🔒 Protección de Datos Sensibles

```sql
-- Tabla de datos sensibles CIFRADOS
CREATE TABLE sensitive_data (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    
    -- Tipo de información sensible
    data_type ENUM(
        'trauma_history',
        'abuse_details',
        'substance_abuse',
        'sexual_history',
        'criminal_history',
        'genetic_information',
        'other_sensitive'
    ),
    
    -- Datos cifrados con AES-256
    encrypted_data LONGBLOB NOT NULL,
    encryption_key_id VARCHAR(50) NOT NULL,  -- Referencia a key management system
    
    -- Metadatos (NO cifrados)
    data_category VARCHAR(100),
    access_level ENUM('professional_only', 'authorized_staff', 'patient_accessible'),
    
    -- Control de acceso
    requires_authentication BOOLEAN DEFAULT TRUE,
    requires_reason_for_access BOOLEAN DEFAULT TRUE,
    
    -- Auditoría de accesos
    last_accessed_by BIGINT UNSIGNED,
    last_accessed_at TIMESTAMP,
    
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (created_by) REFERENCES professionals(id),
    
    INDEX idx_contact_type (contact_id, data_type)
);

-- Log de accesos a datos sensibles
CREATE TABLE sensitive_data_access_log (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sensitive_data_id BIGINT UNSIGNED NOT NULL,
    accessed_by BIGINT UNSIGNED NOT NULL,
    access_reason TEXT NOT NULL,
    access_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    
    FOREIGN KEY (sensitive_data_id) REFERENCES sensitive_data(id),
    FOREIGN KEY (accessed_by) REFERENCES professionals(id)
);
```

---

## 8. Features Avanzadas

### 📊 Visualización de Progreso

```php
class ProgressVisualizationService
{
    public function generateProgressChart(int $contactId, string $metric): ChartData
    {
        // Obtener datos históricos
        $sessions = SessionNote::where('contact_id', $contactId)
            ->orderBy('session_date')
            ->get();
        
        // Extraer métricas
        $data = $sessions->map(function($session) use ($metric) {
            return [
                'date' => $session->session_date,
                'value' => $this->extractMetric($session, $metric),
                'session' => $session->session_number
            ];
        });
        
        return new ChartData(
            labels: $data->pluck('date'),
            values: $data->pluck('value'),
            type: 'line',
            title: "Evolución de {$metric}"
        );
    }
}
```

---

### 🤖 Alertas Inteligentes

```php
class IntelligentAlertSystem
{
    public function checkAlerts(Contact $contact): array
    {
        $alerts = [];
        
        // 1. Deterioro en puntuaciones de tests
        if ($this->detectTestScoreDeterioration($contact)) {
            $alerts[] = new Alert(
                level: 'warning',
                type: 'test_score_deterioration',
                message: 'BDI-II ha aumentado 30% en las últimas 2 evaluaciones',
                action: 'Considerar aumentar frecuencia de sesiones'
            );
        }
        
        // 2. Faltas a sesiones
        if ($this->detectMissedAppointmentPattern($contact)) {
            $alerts[] = new Alert(
                level: 'info',
                type: 'attendance_issue',
                message: '3 faltas en el último mes',
                action: 'Revisar motivación y barreras al tratamiento'
            );
        }
        
        // 3. Riesgo suicida detectado en test
        if ($this->detectSuicideIdeation($contact)) {
            $alerts[] = new Alert(
                level: 'critical',
                type: 'suicide_risk',
                message: 'Ideación suicida detectada en último PHQ-9',
                action: 'EVALUACIÓN DE RIESGO URGENTE - Contactar paciente inmediatamente'
            );
        }
        
        //4. Sin progreso en objetivos
        if ($this->detectLackOfProgress($contact, weeks: 8)) {
            $alerts[] = new Alert(
                level: 'warning',
                type: 'treatment_plateau',
                message: 'Sin cambios significativos en objetivos en 8 semanas',
                action: 'Revisar formulación del caso y enfoque terapéutico'
            );
        }
        
        return $alerts;
    }
}
```

---

### 📚 Biblioteca de Recursos

```sql
CREATE TABLE therapeutic_resources (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Tipo de recurso
    resource_type ENUM('worksheet', 'psychoeducation', 'reading', 'video', 'audio', 'app_recommendation'),
    
    -- Información
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content_url TEXT,  -- Link o archivo almacenado
    
    -- Clasificación
    disorder_type JSON,  -- ["depression", "anxiety", "trauma"]
    therapeutic_approach JSON,  -- ["CBT", "ACT", "mindfulness"]
    age_group ENUM('children', 'adolescents', 'adults', 'elderly', 'all'),
    
    -- Calidad
    evidence_based BOOLEAN DEFAULT FALSE,
    source VARCHAR(255),  -- "APA", "NICE", etc.
    
    -- Tags para búsqueda
    tags JSON,
    
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Asignación de recursos a pacientes
CREATE TABLE assigned_resources (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id BIGINT UNSIGNED NOT NULL,
    resource_id BIGINT UNSIGNED NOT NULL,
    session_note_id BIGINT UNSIGNED,
    
    assigned_date TIMESTAMP NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    completed_date TIMESTAMP NULL,
    
    patient_rating INT CHECK (patient_rating BETWEEN 1 AND 5),
    patient_feedback TEXT,
    
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (resource_id) REFERENCES therapeutic_resources(id),
    FOREIGN KEY (session_note_id) REFERENCES session_notes(id)
);
```

---

## 🎯 Conclusión

Este documento cubre las **funcionalidades profundas y específicas** que los psicólogos necesitan en un software de gestión clínica profesional.

### Prioridades de Implementación:

**Fase 1 - MVP:**
1. Historia clínica básica
2. Notas de sesión SOAP
3. Consentimiento informado
4. BDI-II, PHQ-9, GAD-7

**Fase 2 - Profundización:**
5. Plan de tratamiento
6. Seguimiento de progreso
7. Evaluación de riesgo suicida
8. Plan de seguridad

**Fase 3 - Avanzado:**
9. Alertas inteligentes
10. Visualización de progreso
11. Biblioteca de recursos
12. Protección de datos sensibles avanzada

---

**Nota:** Todas estas funcionalidades deben cumplir con:
- ✅ RGPD (protección de datos)
- ✅ Código Deontológico del Psicólogo
- ✅ Normativa sanitaria vigente
- ✅ Buenas prácticas clínicas basadas en evidencia
