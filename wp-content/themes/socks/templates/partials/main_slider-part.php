<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}
?>

<?php if( have_rows('main_slider', 'option') ): ?>
    <?php while( have_rows('main_slider', 'option') ): the_row(); ?>
        <section class="main-slider w-full overflow-hidden relative"> <!-- Start main slider -->
            <div class="flex">

                <?php if( have_rows('left_part', 'option') ): ?>
                    <div class="slider-left w-6/12">
                        <div id="splide-left" class="splide">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <?php while( have_rows('left_part', 'option') ): the_row(); ?>
                                        <?php
                                        $slide__obj = (object)get_sub_field('upload_add_image');
                                        ?>
                                        <li class="splide__slide">
                                            <img
                                                class="w-full h-screen object-cover object-center"
                                                src="<?=$slide__obj->url?>"
                                                alt="<?=$slide__obj->title?>"
                                            />
                                        </li>
                                    <?php endwhile;?>
                                </ul>
                            </div>
                        </div>
                    </div><!--./end left slider -->
                <?php endif;?>

                <?php if( have_rows('right_part', 'option') ): ?>
                    <div class="right-slider w-6/12">
                        <div id="splide-right" class="splide">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <?php while( have_rows('right_part', 'option') ): the_row(); ?>
                                        <?php
                                        $slide_obj = (object)get_sub_field('upload__add_image');
                                        ?>
                                        <li class="splide__slide">
                                            <img
                                                class="w-full h-screen object-cover object-center"
                                                src="<?=$slide_obj->url?>"
                                                alt="<?=$slide_obj->title?>"
                                            />
                                        </li>
                                    <?php endwhile;?>
                                </ul>
                            </div>
                        </div>
                    </div><!--./end right slider -->
                <?php endif;?>

                <div class="slider-caption absolute w-full h-full top-0 left-0 bg-black bg-opacity-5">
                    <div class="flex justify-center items-center w-full h-full">
                        <div class="block text-center">
                            <h1 class="cap-header text-white font-frank block">Bring your life to tech</h1>
                            <a href="#" class="custom-btn font-lato bg-base-color">Shop Now</a>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End main slider -->
    <?php endwhile;?>
<?php endif;?>
