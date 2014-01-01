<?php

namespace nk\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class nkUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
