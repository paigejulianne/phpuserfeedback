<?php

namespace App\Helpers;

class Sanitizer {
    public static function clean($html) {
        $config = \HTMLPurifier_Config::createDefault();
        // Allow basic formatting
        $config->set('HTML.Allowed', 'b,i,u,strong,em,p,br,ul,ol,li,span[style],h1,h2,h3,blockquote,pre,code');
        
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
