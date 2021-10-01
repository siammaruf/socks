<?php
/**
 * footer.php
 *
 * The template for displaying the footer.
 */
?>
    <footer><!-- Start main footer -->
        <!-- Start Newsletter  -->
        <section class="newsletter_panel bg-footer-texture bg-repeat-x bg-light-orange bg-left-top">
            <div class="container mx-auto py-7 lg:px-0 px-2">
                <h3 class="text-center lg:text-2xl text-sm text-base-color font-frank font-normal tracking-normal mb-6">
                    Get exclusive access to new products, deals & surprise treats.
                </h3>
                <form class="lg:w-1/3 md:w-2/3 mx-auto relative newsletter_form mb-6">
                    <input type="email" placeholder="Enter your email to register" class="form_input shadow-2xl">
                    <button type="submit" class="form_submit">Submit</button>
                </form>
                <p class="lg:w-1/3 mx-auto text-xs text-gray-500 font-lato font-normal text-center leading-4">You are signing up to receive product updates and newsletters. By signing up, you are consenting to our <a href="#" class="text-base-color underline">privacy policy</a> but you can opt out at any time.</p>
            </div>
        </section>
        <!-- End News Letter  -->

        <?php get_sidebar('footer')?>

        <!-- Start Copyright Panel -->
        <section class="copyright_panel py-4 bg-custom-black">
            <div class="container_custom">
                <div class="md:flex items-center text-center">
                    <?php if (get_field('fotter_support_mail','option')):?>
                    <div class="md:flex-shrink copyright_logo hidden">
                        <img src="https://bellroy.imgix.net/cms_images/2782/b-corporation_w.png?auto=format&fit=max&w=72" class="w-14 pr-6 mx-auto md:mx-0 md:mb-0 mb-3"/>
                    </div>
                    <div class="md:flex-shrink">
                        <p>
                            Need help? Contact <a target="_blank" href="mailto:<?=__(get_field('fotter_support_mail','option'),'socks')?>" class="text-base-color"><?=__(get_field('fotter_support_mail','option'),'socks')?></a>
                        </p>
                    </div>
                    <?php endif;?>
                    <div class="md:flex-auto">
                        <p class="md:text-right">
                            All rights reserved &copy; <?=__(date("Y"),'socks')?> <?=__(get_bloginfo('name'))?>.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Copyright Panel -->
    </footer><!-- End main footer -->
    <?php wp_footer(); ?>
    </body>
</html>
