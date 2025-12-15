<?php

namespace App\View\Composers;

use Illuminate\View\View;

class AboutComposer
{
    public function compose(View $view)
    {
        $view->with('message', 'هذا الموقع تم تطويره بواسطة عيسى');
    }
}
