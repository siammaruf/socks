<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view this page.');
}

class ClassWidgetFilterByPrice extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'widget-filter-product-price',
            __( 'Socks : Filter by Price', 'socks' ),
            array(
                'classname'   => 'widget-filter-product-price',
                'description' => __( 'A custom widget that filter products by price.', 'socks' )
            )
        );

        add_action( 'widgets_init', function() {
            register_widget( 'ClassWidgetFilterByPrice' );
        });
    }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap shop-widget-row">',
        'after_widget'  => '</div>'
    );

    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        $start = $instance['from_price_range'];
        $end = $instance['to_price_range'];
        $slot = [];
        $increment_value = $instance['socks_increment_value'] ? $instance['socks_increment_value'] : 50;
        for ($i = $start; $i <= $end; $i += $increment_value){
            $slot[] = $i;
        }
        $counter = count($slot);
        ?>
        <div class="sidebar py-4 font-Condensed" data-filter-type="productPrice">
            <a href="javascript:void(0)" class="accordion_handler">
                <div class="flex">
                    <div class="flex-auto font-medium text-base align-middle uppercase">
                        <span><?=$instance['title']?></span>
                    </div>
                    <div class="flex-srink">
                        <span class="text-gray-400 text-xl accordion_open"><i class="fal fa-plus"></i></span>
                        <span class="text-gray-400 text-xl accordion_close"><i class="fal fa-minus"></i></span>
                    </div>
                </div>
            </a>

            <div class="sidebar_menu input-holder hidden">
                <ul class="filter-form">
                    <?php foreach ($slot as $key => $item):?>
                        <?php if ($key != ( $counter - 1 )):?>
                            <li class="check-list relative" data-start-price="<?=$item?>" data-end-price="<?=$item + ($increment_value - 1)?>">
                                <input type="checkbox" name="socks-wc-price" id="price_<?=$item ?>–<?=$item + ($increment_value - 1)?>" class="opacity-0 invisible absolute top-0 left-0 w-full h-full socks-select-wc-price">
                                <label for="price_<?=$item ?>–<?=$item + ($increment_value - 1)?>">
                                    <?=$item ?> – <?=$item + ($increment_value - 1)?>
                                </label>
                            </li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <?php

        echo $args['after_widget'];

    }

    public function form( $instance ) {

        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'socks' );
        $from_price_range = ! empty( $instance['from_price_range'] ) ? $instance['from_price_range'] : esc_html__( '', 'socks' );
        $to_price_range = ! empty( $instance['to_price_range'] ) ? $instance['to_price_range'] : esc_html__( '', 'socks' );
        $increment_value = ! empty( $instance['socks_increment_value'] ) ? $instance['socks_increment_value'] : esc_html__( '', 'socks' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'socks' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <h3 style="margin-bottom:0;">Price Range</h3>
            <div style="display: block;width: 100%;">
                <label for="<?php echo esc_attr( $this->get_field_id( 'socks_increment_value' ) ); ?>"><?php echo esc_html__( 'Increment By:', 'socks' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'socks_increment_value' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'socks_increment_value' ) ); ?>" type="number" value="<?php echo esc_attr( $increment_value ); ?>">
            </div>
        </p>
        <p>
            <div style="display:flex;margin:0 -5px">
                <div style="width:50%;padding:0 5px;">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'from_price_range' ) ); ?>"><?php echo esc_html__( 'From:', 'socks' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'from_price_range' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'from_price_range' ) ); ?>" type="number" value="<?php echo esc_attr( $from_price_range ); ?>">
                </div>
                <div style="width: 50%;padding:0 5px;">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'to_price_range' ) ); ?>"><?php echo esc_html__( 'To:', 'socks' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'to_price_range' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'to_price_range' ) ); ?>" type="number" value="<?php echo esc_attr( $to_price_range ); ?>">
                </div>
            </div>
        </p>
        <?php

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['from_price_range'] = ( !empty( $new_instance['from_price_range'] ) ) ? $new_instance['from_price_range'] : '';
        $instance['to_price_range'] = ( !empty( $new_instance['to_price_range'] ) ) ? $new_instance['to_price_range'] : '';
        $instance['socks_increment_value'] = ( !empty( $new_instance['socks_increment_value'] ) ) ? $new_instance['socks_increment_value'] : '';

        return $instance;
    }

}

new ClassWidgetFilterByPrice();