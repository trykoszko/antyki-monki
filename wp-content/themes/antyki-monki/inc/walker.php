<?php

class Antyki_Walker extends Walker_Nav_Menu {

    function start_el( &$output, $item, $depth=0, $args=array(), $id = 0 ) {
        $object = $item->object;
        $type = $item->type;
        $title = $item->title;
        $permalink = $item->url;

        $active = ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current_page_item', $item->classes ) || in_array( 'current_page_parrent', $item->classes ) ) ? 'is-active' : '';

        if (get_post_type() == 'post' && $permalink == get_the_permalink($news_page)){
            $active = 'is-active';
        }

        $output .= "<li class='c-header__menu-item $active'>";

        if ( $permalink && $permalink != '#' ) {
                $output .= '<a href="' . $permalink . '" class="c-link"><span class="c-label">';
        } else {
                $output .= '<span>';
        }

        $output .= $title;
        if ( $permalink && $permalink != '#' ){
            $output .= '</span></a>';
        } else {
            $output .= '</span>';
        }
        $output .= '</li>';
    }

}