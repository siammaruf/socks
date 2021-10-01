<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view this page.');
}

class ClassWidgetFilterByAttributes extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'widget-filter-product-attribute',
            __( 'Socks : Filter by Attribute', 'socks' ),
            array(
                'classname'   => 'widget-filter-product-attribute',
                'description' => __( 'A custom widget that filter products by attribute.', 'socks' )
            )
        );

        add_action( 'widgets_init', function() {
            register_widget( 'ClassWidgetFilterByAttributes' );
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
        $selected_attr = $instance['socks_selected_attr'];
        $selected_attr_type = isset($instance['socks_attr_type']) ? $instance['socks_attr_type'] : '';

        $terms = get_terms( array(
            'taxonomy' => $selected_attr,
            'hide_empty' => true,
        ));

        ?>
        <div class="sidebar sidebar-tax py-4 font-Condensed" data-filter-type="<?=$selected_attr?>">
            <a href="javascript:void(0)" class="accordion_handler">
                <div class="flex">
                    <div class="flex-auto font-medium text-base align-middle uppercase">
                        <span><?=__($instance['title'],'socks')?></span>
                    </div>
                    <div class="flex-srink">
                        <span class="text-gray-400 text-xl accordion_open"><i class="fal fa-plus"></i></span>
                        <span class="text-gray-400 text-xl accordion_close"><i class="fal fa-minus"></i></span>
                    </div>
                </div>
            </a>
            <?php if ( empty($selected_attr_type) ):?>
                <div class="sidebar_menu input-holder hidden">
                    <ul class="filter-form">
                        <?php foreach ($terms as $term):?>
                            <li class="check-list check-holder relative" data-term-id="<?=$term->term_id?>" data-term-slug="<?=$term->slug?>">
                                <input type="checkbox" name="" id="<?=$term->slug?>" class="opacity-0 invisible absolute top-0 left-0 w-full h-full socks-select-wc-attribute" >
                                <label for="<?=$term->slug?>">
                                    <?=__($term->name,'socks')?>
                                </label>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?elseif ($selected_attr_type == 'size'):?>
                <div class="size input-holder pt-4 hidden">
                    <div class="grid grid-cols-4 gap-4 flex-wrap filter-form">
                        <?php foreach ($terms as $term):?>
                        <div class="size-list check-holder relative" data-term-id="<?=$term->term_id?>" data-term-slug="<?=$term->slug?>">
                            <input type="checkbox" name="<?=$term->slug?>" id="<?=$term->slug?>" class="opacity-0 invisible absolute top-0 left-0 w-full h-full socks-select-wc-attribute">
                            <label for="<?=$term->slug?>">
                                <?=__($term->name,'socks')?>
                            </label>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php else:?>
                <div class="colors input-holder pt-4 hidden">
                    <div class="flex gap-4 flex-wrap filter-form">
                        <?php foreach ($terms as $term):?>
                            <?php $color_code = get_field( 'color_code', $term ) ?>
                            <?php if ($color_code):?>
                                <div class="color-list check-holder relative text-red-600" data-color="<?=$color_code?>" data-term-id="<?=$term->term_id?>" data-term-slug="<?=$term->slug?>">
                                    <input type="checkbox" name="<?=$term->slug?>" id="<?=$term->slug?>" class="opacity-0 invisible absolute top-0 left-0 w-full h-full socks-select-wc-attribute">
                                    <label for="<?=$term->slug?>" style="color:<?=$color_code?>" class="check">
                                        <i class="fas fa-circle"></i>
                                    </label>
                                </div>
                            <?php endif;?>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <?php

        echo $args['after_widget'];

    }

    public function form( $instance ) {

        global $product;
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'socks' );
        $selected_attr = ! empty( $instance['socks_selected_attr'] ) ? $instance['socks_selected_attr'] : esc_html__( '', 'socks' );
        $selected_attr_type = ! empty( $instance['socks_attr_type'] ) ? $instance['socks_attr_type'] : esc_html__( '', 'socks' );

        // Get product attributes
        $attributes = wc_get_attribute_taxonomies();

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'socks' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <div style="display: block;width: 100%;">
                <label for="<?php echo esc_attr( $this->get_field_id( 'socks_selected_attr' ) ); ?>"><?php echo esc_html__( 'Select a attribute:', 'socks' ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'socks_selected_attr' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'socks_selected_attr' ) ); ?>">
                    <?php if ( $attributes ):?>
                        <option selected disabled>Select a attribute</option>
                        <?php foreach ($attributes as $attribute):?>
                            <?php
                                $selected_option = $selected_attr == 'pa_'.$attribute->attribute_name ? 'selected' : '';
                            ?>
                            <option value="pa_<?=$attribute->attribute_name?>" <?=$selected_option?>><?=__($attribute->attribute_label,'socks')?></option>
                        <?php endforeach;?>
                    <?php else:?>
                        <option selected disabled>Ops, No attributes found !</option>
                    <?php endif;?>
                </select>
            </div>
        </p>
        <p>
            <input class="widefat" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'socks_attr_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'socks_attr_type' ) ); ?>" value="size" <?=checked( 'size' == $selected_attr_type ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'socks_attr_type' ) ); ?>">Enable size box style</label>
        </p>
        <p>
            <input class="widefat" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'socks_attr_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'socks_attr_type' ) ); ?>" value="color" <?=checked( 'color' == $selected_attr_type ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'socks_attr_type' ) ); ?>">Enable color box style</label>
        </p>
        <?php

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['socks_selected_attr'] = ( !empty( $new_instance['socks_selected_attr'] ) ) ? $new_instance['socks_selected_attr'] : '';
        $instance['socks_attr_type'] = ( !empty( $new_instance['socks_attr_type'] ) ) ? $new_instance['socks_attr_type'] : '';

        return $instance;
    }
}

new ClassWidgetFilterByAttributes();