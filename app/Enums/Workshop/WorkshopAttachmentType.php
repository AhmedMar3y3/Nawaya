<?php

namespace App\Enums\Workshop;

use App\Traits\HasLocalizedEnum;

enum WorkshopAttachmentType: string {
    use HasLocalizedEnum;

    case AUDIO = 'audio';
    case VIDEO = 'video';
}
