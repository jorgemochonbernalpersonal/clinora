<?php

namespace App\Modules\Psychology;

use App\Shared\Interfaces\ModuleInterface;

/**
 * Psychology Module
 * 
 * Module for psychology professionals with SOAP notes, assessments, and consent forms
 */
class PsychologyModule implements ModuleInterface
{
    public function getName(): string
    {
        return 'Psychology';
    }

    public function getProfessionType(): string
    {
        return 'psychologist';
    }

    public function getLabel(): string
    {
        return 'Psicología';
    }

    public function getWebRoutesPath(): ?string
    {
        return base_path('routes/psychologist.php');
    }

    public function getApiRoutesPath(): ?string
    {
        return base_path('routes/api/psychology.php');
    }

    public function getMigrationsPath(): ?string
    {
        return database_path('migrations/modules/psychology');
    }

    public function getViewsNamespace(): ?string
    {
        return 'psychology';
    }

    public function isEnabled(): bool
    {
        return config('modules.psychology.enabled', true);
    }

    public function getServiceProviderClass(): string
    {
        return PsychologyModuleServiceProvider::class;
    }
}

