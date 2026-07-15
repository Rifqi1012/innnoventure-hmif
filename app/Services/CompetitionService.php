<?php

namespace App\Services;

use App\Models\WebdevProgress;
use App\Models\UiProgress;

class CompetitionService
{
    public function submitWebdev(array $data)
    {
        // Handle file uploads if they exist (ppt, pdf)
        if (isset($data['ppt']) && $data['ppt']->isValid()) {
            $data['ppt'] = $data['ppt']->store('webdev/ppt', 'public');
        }

        if (isset($data['pdf']) && $data['pdf']->isValid()) {
            $data['pdf'] = $data['pdf']->store('webdev/pdf', 'public');
        }

        return WebdevProgress::create($data);
    }

    public function submitUi(array $data)
    {
        // Handle file uploads if they exist (ppt, pdf)
        if (isset($data['ppt']) && $data['ppt']->isValid()) {
            $data['ppt'] = $data['ppt']->store('ui/ppt', 'public');
        }

        if (isset($data['pdf']) && $data['pdf']->isValid()) {
            $data['pdf'] = $data['pdf']->store('ui/pdf', 'public');
        }

        return UiProgress::create($data);
    }
}
