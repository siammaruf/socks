<?php
/**
 * 404.php
 *
 * The template for displaying 404 pages (Not Found).
 */
?>

<?php get_header(); ?>
    <style>
        .m4h-404-section {
            padding-top: 200px;
            padding-bottom: 120px;
            text-align: center;
        }
        .m4h-404-section h1 {
            font-size: 3rem;
            padding: .5em 0;
        }
        .m4h-404-section p {
            font-size: 1.5rem;
            margin-bottom: 1em;
        }
        .m4h-404-section input[type="text"]{
            height: 50px;
            background-color: rgba(0, 0, 0, 0.2);
            border: 1px solid #ffffff;
        }
        .m4h-404-section input#searchsubmit {
            height: 50px;
            width: 100px;
            background-color: transparent;
            border: 1px solid;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <div id="main-content" class="container-404">
        <section class="m4h-section m4h-404-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1><?php _e( 'Error 404 - Nothing Found', 'm4h' ); ?></h1>
                        <p><?php _e( 'It looks like nothing was found here. Maybe try a search?', 'm4h' ); ?></p>
                        <?php //get_search_form(); ?>
                    </div>
                </div>
            </div>
        </section>
    </div> <!-- end container-404 -->

<?php get_footer(); ?>