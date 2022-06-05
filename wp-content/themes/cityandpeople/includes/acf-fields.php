<?php
if (function_exists( 'acf_add_local_field_group' )):

    acf_add_local_field_group( array(
        'key' => __( 'high-school-fields' ),
        'title' => __( 'High School Fields' ),
        'fields' => array(),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'high-school',
                ),
            ),
        ),
    ) );

    acf_add_local_field( array(
        'key' => 'year',
        'label' => 'Year',
        'name' => 'year',
        'type' => 'number',
        'parent' => 'heigh-school-fields',
    ) );

    acf_add_local_field( array(
        'key' => 'localisation',
        'label' => 'Localisation',
        'name' => 'localisation',
        'type' => 'text',
        'parent' => 'heigh-school-fields',
    ) );

endif;
