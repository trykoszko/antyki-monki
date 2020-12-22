<?php
    $filters = [
        // [
        //     'title' => __( 'Kategoria', 'antyki' ),
        //     'slug' => 'category',
        //     'type' => 'checkboxes', // checkboxes | select | range
        //     'choices' => []
        // ],
        [
            'title' => __( 'Kategoria', 'antyki' ),
            'slug' => 'category',
            'type' => 'checkboxes',
            'choices' => [
                // @todo get all cats and pass here
                'meble' => 'Meble',
                'stoly-i-krzesla' => 'Stoły i krzesła',
                'szafy-i-komody' => 'Szafy i komody'
            ]
        ],
        [
            'title' => __( 'Materiał', 'antyki' ),
            'slug' => 'material',
            'type' => 'checkboxes',
            'choices' => [
                // @todo get all metas `material` and pass here
                'wood' => 'drewno',
                'steel' => 'stal',
                'iron' => 'zelazo'
            ]
        ],
        [
            'title' => __( 'Cena', 'antyki' ),
            'slug' => 'price',
            'type' => 'range',
            'from' => '0',
            // @todo get max price of item and pass below
            'to' => '1500'
        ]
    ];
?>
<div class="c-main-grid-filters">
    <ul class="c-main-grid-filters__list">
        <?php foreach ( $filters as $filter ) : ?>
            <li class="c-main-grid-filter c-main-grid-filter--<?php echo $filter['type']; ?>">
                <div class="c-main-grid-filter__wrapper js-accordion">
                    <label for="<?php echo $filter['slug']; ?>" class="c-main-grid-filter__label js-accordion-title">
                        <h4>
                            <?php echo $filter['title']; ?>
                        </h4>
                        <div>
                            <span></span>
                            <span></span>
                        </div>
                    </label>
                    <div class="c-main-grid-filter__content">
                        <?php
                            switch ( $filter['type'] ):
                                case 'checkboxes':
                                    ?>
                                        <?php foreach ( $filter['choices'] as $key => $val ) : ?>
                                            <div>
                                                <input type="checkbox" id="<?php echo $key; ?>" name="<?php echo $key; ?>">
                                                <label for="<?php echo $key; ?>"><?php echo $val; ?></label>
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240.608 240.608">
                                                        <defs/>
                                                        <path fill="#ffffff" d="M208.789 29.972l31.819 31.82L91.763 210.637 0 118.876l31.819-31.82 59.944 59.942L208.789 29.972z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php
                                    break;
                                case 'select':
                                    ?>
                                        <select name="<?php echo $filter['slug']; ?>" id="<?php echo $filter['slug']; ?>">
                                            <?php foreach ( $filter['choices'] as $key => $val ): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php
                                    break;
                                case 'range':
                                    ?>
                                        <select name="<?php echo $filter['slug']; ?>" id="<?php echo $filter['slug']; ?>">
                                            <?php foreach ( $filter['choices'] as $key => $val ): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php
                                    break;
                                default:
                                    echo 'default_filter';
                                    break;
                            endswitch;
                        ?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
