<?php
namespace MBVMedia;

/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */
class BambeeWalkerComment extends \Walker_Comment {

    /**
     * @inheritdoc
     */
    public function paged_walk( $elements, $max_depth, $page_num, $per_page ) {
        if ( empty( $elements ) || $max_depth < -1 ) {
            return '';
        }

        $args = array_slice( func_get_args(), 4 );
        $output = '';

        $parent_field = $this->db_fields['parent'];

        $count = -1;
        if ( -1 == $max_depth )
            $total_top = count( $elements );
        if ( $page_num < 1 || $per_page < 0  ) {
            // No paging
            $paging = false;
            $start = 0;
            if ( -1 == $max_depth )
                $end = $total_top;
            $this->max_pages = 1;
        } else {
            $paging = true;
            $start = ( (int)$page_num - 1 ) * (int)$per_page;
            $end   = $start + $per_page;
            if ( -1 == $max_depth )
                $this->max_pages = ceil($total_top / $per_page);
        }

        // flat display
        if ( -1 == $max_depth ) {
            if ( !empty($args[0]['reverse_top_level']) ) {
                $elements = array_reverse( $elements );
                $oldstart = $start;
                $start = $total_top - $end;
                $end = $total_top - $oldstart;
            }

            $empty_array = array();
            foreach ( $elements as $e ) {
                $count++;
                if ( $count < $start )
                    continue;
                if ( $count >= $end )
                    break;
                $this->display_element( $e, $empty_array, 1, 0, $args, $output );
            }
            return $output;
        }

        if( !$args[0]['reverse_top_level'] ) {
            $elements = array_reverse( $elements );
        }

        /*
         * Separate elements into two buckets: top level and children elements.
         * Children_elements is two dimensional array, e.g.
         * $children_elements[10][] contains all sub-elements whose parent is 10.
         */
        $top_level_elements = array();
        $children_elements  = array();
        foreach ( $elements as $e) {
            if ( 0 == $e->$parent_field )
                $top_level_elements[] = $e;
            else
                $children_elements[ $e->$parent_field ][] = $e;
        }

        $total_top = count( $top_level_elements );
        if ( $paging )
            $this->max_pages = ceil($total_top / $per_page);
        else
            $end = $total_top;

        if ( !empty($args[0]['reverse_children']) ) {
            foreach ( $children_elements as $parent => $children )
                $children_elements[$parent] = array_reverse( $children );
        }

        foreach ( $top_level_elements as $e ) {
            $count++;

            // For the last page, need to unset earlier children in order to keep track of orphans.
            if ( $end >= $total_top && $count < $start )
                $this->unset_children( $e, $children_elements );

            if ( $count < $start )
                continue;

            if ( $count >= $end )
                break;

            $this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
        }

        if ( $end >= $total_top && count( $children_elements ) > 0 ) {
            $empty_array = array();
            foreach ( $children_elements as $orphans )
                foreach ( $orphans as $op )
                    $this->display_element( $op, $empty_array, 1, 0, $args, $output );
        }

        return $output;
    }
}