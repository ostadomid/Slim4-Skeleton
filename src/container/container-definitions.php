<?php 
use function \DI\create;
use function \DI\get;

return [
    'welcome'=>'Slim v4 App',
    'twig.templates'=>__DIR__.'./../templates',
    'twig.loader'=>create('\Twig\Loader\FilesystemLoader')->constructor(get('twig.templates')),
    'view'=> create('\Twig\Environment')->constructor(get('twig.loader'))
];