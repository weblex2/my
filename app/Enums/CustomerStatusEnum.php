<?php

namespace App\Enums;

enum CustomerStatusEnum :string {
    case DEAL = 'deal';
    case LEAD = 'lead';
    case CONTACT = 'contact';
    case EXACC = 'exacc';

    public function label(): string
    {
        return match ($this) {
            self::DEAL => 'Deal',
            self::LEAD => 'Lead',
            self::CONTACT => 'Contact',
            self::EXACC => 'Existing Account',
        };
    }
}
