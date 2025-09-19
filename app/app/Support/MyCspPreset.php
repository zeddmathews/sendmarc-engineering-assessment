<?php

namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class MyCspPreset implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::SCRIPT, Keyword::SELF);
        // $policy->add(Directive::SCRIPT, 'http://127.0.0.1:5173/'); //dev only
        // $policy->add(Directive::CONNECT, 'ws://127.0.0.1:5173');
        $policy->add(Directive::STYLE, Keyword::SELF);
        $policy->add(Directive::STYLE, 'https://fonts.bunny.net');
        // $policy->add(Directive::STYLE, 'http://127.0.0.1:5173');
        $policy->add(Directive::FONT, Keyword::SELF);
        $policy->add(Directive::FONT, 'https://fonts.bunny.net');
    }
}
