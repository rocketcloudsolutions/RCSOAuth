<?php
return array(
    'factories' => array(
        'rcsbase_doctrine_descriminatorlistener' => function ($sl) {

                $Configuration = $sl->get('doctrine.configuration.orm_default');
                return new RCSBase\Doctrine\DiscriminatorListener( $Configuration ) ;
            },
    )
);