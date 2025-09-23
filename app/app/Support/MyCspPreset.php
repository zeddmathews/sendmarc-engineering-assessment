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
        $policy
            ->add(Directive::DEFAULT, Keyword::SELF)
            ->add(Directive::FORM_ACTION, Keyword::SELF)
            ->add(Directive::IMG, Keyword::SELF);
        $policy
            ->add(Directive::SCRIPT, Keyword::SELF)
            ->add(Directive::STYLE, Keyword::SELF)
            ->add(Directive::STYLE, 'https://fonts.bunny.net')
            ->add(Directive::STYLE, 'https://rsms.me/')
            ->add(Directive::FONT, Keyword::SELF)
            ->add(Directive::FONT, 'https://fonts.bunny.net')
            ->add(Directive::FONT, 'https://rsms.me/');
        $policy
            ->addNonce(Directive::SCRIPT)
            ->addNonce(Directive::STYLE);
    }
}
