<?php

namespace App\Shared\Enums;

/**
 * Assessment Type Enum
 * 
 * Psychological assessment tools supported by the system
 */
enum AssessmentType: string
{
    case BDI2 = 'bdi2'; // Beck Depression Inventory II
    case PHQ9 = 'phq9'; // Patient Health Questionnaire 9
    case GAD7 = 'gad7'; // Generalized Anxiety Disorder 7
    case CUSTOM = 'custom';

    public function label(): string
    {
        return match($this) {
            self::BDI2 => 'BDI-II (Beck Depression Inventory)',
            self::PHQ9 => 'PHQ-9 (Patient Health Questionnaire)',
            self::GAD7 => 'GAD-7 (Generalized Anxiety Disorder)',
            self::CUSTOM => 'Evaluación Personalizada',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::BDI2 => 'Inventario de Depresión de Beck - 21 ítems',
            self::PHQ9 => 'Cuestionario de Salud del Paciente - 9 ítems',
            self::GAD7 => 'Trastorno de Ansiedad Generalizada - 7 ítems',
            self::CUSTOM => 'Evaluación creada por el profesional',
        };
    }
}
