<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view this page.');
}

class ClassOnPageScripts
{
     function __construct()
     {
         add_action( 'wp_footer', array($this,'home_page_scripts') );
         add_action( 'wp_head', array($this,'wc_user_login_form') );
     }

     function home_page_scripts(){
        if (is_home()){
            ?>
            <script type="application/javascript">
                const leftSlider = new Splide('#splide-left', {
                    type: "fade",
                    rewind: true,
                    rewindSpeed: 1000,
                    autoplay: true,
                    arrows: false,
                    pagination: false,
                    speed: 2000,
                    interval: 4000,
                    pauseOnHover: false,
                }).mount();

                const rightSlider = new Splide('#splide-right', {
                    type: "fade",
                    rewind: true,
                    rewindSpeed: 1000,
                    autoplay: true,
                    arrows: false,
                    pagination: false,
                    speed: 2000,
                    interval: 2500,
                    pauseOnHover: false,
                }).mount();

                let productSlider = new Splide('#home_products_slider', {
                    perPage: 3,
                    type: "slide",
                    rewind: true,
                    rewindSpeed: 1000,
                    autoplay: true,
                    pagination: false,
                    speed: 500,
                    gap: '2em',
                    breakpoints : {
                        '768': {
                            perPage: 2,
                        },
                        '480': {
                            perPage: 1,
                        }
                    }
                }).mount();
            </script>
            <?php
        }
     }

     function wc_user_login_form(){
         $forget_pass = home_url('/my-account/lost-password');
         $reg_url = home_url('/my-account');
         ?>
            <style type="text/css">
                .login-popup-area{
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    background: rgba(0,0,0,0.5);
                    height: 100%;
                    width: 100%;
                    z-index: 999;
                }
                .load-wrapp {
                  margin: 0 10px 10px 0;
                  padding: 20px 20px 20px;
                  border-radius: 5px;
                  text-align: center;
                }
                
                .load-wrapp p {
                  padding: 0 0 20px;
                }
                .load-wrapp:last-child {
                  margin-right: 0;
                }
                
                .line {
                  display: inline-block;
                  width: 15px;
                  height: 15px;
                  border-radius: 15px;
                  background-color: #fe5f27;
                }
                
                .ring-1 {
                  width: 10px;
                  height: 10px;
                  margin: 0 auto;
                  padding: 10px;
                  border: 7px dashed #fe5f27;
                  border-radius: 100%;
                }
                
                .ring-2 {
                  position: relative;
                  width: 45px;
                  height: 45px;
                  margin: 0 auto;
                  border: 4px solid #fe5f27;
                  border-radius: 100%;
                }
                
                .ball-holder {
                  position: absolute;
                  width: 12px;
                  height: 45px;
                  left: 17px;
                  top: 0px;
                }
                
                .ball {
                  position: absolute;
                  top: -11px;
                  left: 0;
                  width: 16px;
                  height: 16px;
                  border-radius: 100%;
                  background: #4282b3;
                }
                
                .letter-holder {
                  padding: 16px;
                }
                
                .letter {
                  float: left;
                  font-size: 14px;
                  color: #777;
                }
                
                .square {
                  width: 12px;
                  height: 12px;
                  border-radius: 4px;
                  background-color: #4b9cdb;
                }
                
                .spinner {
                  position: relative;
                  width: 45px;
                  height: 45px;
                  margin: 0 auto;
                }
                
                .bubble-1,
                .bubble-2 {
                  position: absolute;
                  top: 0;
                  width: 25px;
                  height: 25px;
                  border-radius: 100%;
                  background-color: #4b9cdb;
                }
                
                .bubble-2 {
                  top: auto;
                  bottom: 0;
                }
                
                .bar {
                  float: left;
                  width: 15px;
                  height: 6px;
                  border-radius: 2px;
                  background-color: #4b9cdb;
                }
                
                /* =Animate the stuff
                ------------------------ */
                .load-1 .line:nth-last-child(1) {
                  animation: loadingA 1.5s 1s infinite;
                }
                .load-1 .line:nth-last-child(2) {
                  animation: loadingA 1.5s 0.5s infinite;
                }
                .load-1 .line:nth-last-child(3) {
                  animation: loadingA 1.5s 0s infinite;
                }
                
                .load-2 .line:nth-last-child(1) {
                  animation: loadingB 1.5s 1s infinite;
                }
                .load-2 .line:nth-last-child(2) {
                  animation: loadingB 1.5s 0.5s infinite;
                }
                .load-2 .line:nth-last-child(3) {
                  animation: loadingB 1.5s 0s infinite;
                }
                
                .load-3 .line:nth-last-child(1) {
                  animation: loadingC 0.6s 0.1s linear infinite;
                }
                .load-3 .line:nth-last-child(2) {
                  animation: loadingC 0.6s 0.2s linear infinite;
                }
                .load-3 .line:nth-last-child(3) {
                  animation: loadingC 0.6s 0.3s linear infinite;
                }
                
                .load-4 .ring-1 {
                  animation: loadingD 1.5s 0.3s cubic-bezier(0.17, 0.37, 0.43, 0.67) infinite;
                }
                
                .load-5 .ball-holder {
                  animation: loadingE 1.3s linear infinite;
                }
                
                .load-6 .letter {
                  animation-name: loadingF;
                  animation-duration: 1.6s;
                  animation-iteration-count: infinite;
                  animation-direction: linear;
                }
                
                .l-1 {
                  animation-delay: 0.48s;
                }
                .l-2 {
                  animation-delay: 0.6s;
                }
                .l-3 {
                  animation-delay: 0.72s;
                }
                .l-4 {
                  animation-delay: 0.84s;
                }
                .l-5 {
                  animation-delay: 0.96s;
                }
                .l-6 {
                  animation-delay: 1.08s;
                }
                .l-7 {
                  animation-delay: 1.2s;
                }
                .l-8 {
                  animation-delay: 1.32s;
                }
                .l-9 {
                  animation-delay: 1.44s;
                }
                .l-10 {
                  animation-delay: 1.56s;
                }
                
                .load-7 .square {
                  animation: loadingG 1.5s cubic-bezier(0.17, 0.37, 0.43, 0.67) infinite;
                }
                
                .load-8 .line {
                  animation: loadingH 1.5s cubic-bezier(0.17, 0.37, 0.43, 0.67) infinite;
                }
                
                .load-9 .spinner {
                  animation: loadingI 2s linear infinite;
                }
                .load-9 .bubble-1,
                .load-9 .bubble-2 {
                  animation: bounce 2s ease-in-out infinite;
                }
                .load-9 .bubble-2 {
                  animation-delay: -1s;
                }
                
                .load-10 .bar {
                  animation: loadingJ 2s cubic-bezier(0.17, 0.37, 0.43, 0.67) infinite;
                }
                
                @keyframes loadingA {
                  0 {
                    height: 15px;
                  }
                  50% {
                    height: 35px;
                  }
                  100% {
                    height: 15px;
                  }
                }
                
                @keyframes loadingB {
                  0 {
                    width: 15px;
                  }
                  50% {
                    width: 35px;
                  }
                  100% {
                    width: 15px;
                  }
                }
                
                @keyframes loadingC {
                  0 {
                    transform: translate(0, 0);
                  }
                  50% {
                    transform: translate(0, 15px);
                  }
                  100% {
                    transform: translate(0, 0);
                  }
                }
                
                @keyframes loadingD {
                  0 {
                    transform: rotate(0deg);
                  }
                  50% {
                    transform: rotate(180deg);
                  }
                  100% {
                    transform: rotate(360deg);
                  }
                }
                
                @keyframes loadingE {
                  0 {
                    transform: rotate(0deg);
                  }
                  100% {
                    transform: rotate(360deg);
                  }
                }
                
                @keyframes loadingF {
                  0% {
                    opacity: 0;
                  }
                  100% {
                    opacity: 1;
                  }
                }
                
                @keyframes loadingG {
                  0% {
                    transform: translate(0, 0) rotate(0deg);
                  }
                  50% {
                    transform: translate(70px, 0) rotate(360deg);
                  }
                  100% {
                    transform: translate(0, 0) rotate(0deg);
                  }
                }
                
                @keyframes loadingH {
                  0% {
                    width: 15px;
                  }
                  50% {
                    width: 35px;
                    padding: 4px;
                  }
                  100% {
                    width: 15px;
                  }
                }
                
                @keyframes loadingI {
                  100% {
                    transform: rotate(360deg);
                  }
                }
                
                @keyframes bounce {
                  0%,
                  100% {
                    transform: scale(0);
                  }
                  50% {
                    transform: scale(1);
                  }
                }
                
                @keyframes loadingJ {
                  0%,
                  100% {
                    transform: translate(0, 0);
                  }
                
                  50% {
                    transform: translate(80px, 0);
                    background-color: #f5634a;
                    width: 25px;
                  }
                }

            </style>
            <div id="login-popup-area" class="login-popup-area hidden" >
                <div class="login-popup-holder flex items-center justify-center w-full h-full">
                      <div class="rounded bg-white p-5 lg:w-2/6 w-10/12 relative">
                          <a href="" id="socks-close_btn" class="flex items-center justify-center w-10 h-10 absolute -top-4 text-lg text-base-color bg-white rounded-full -right-3"><i class="fal fa-times-circle"></i></a>
                         <div class="mb-2 md:flex md:items-center md:gap-4 space-y-2 md:space-y-0  border-b border-gray-100 mb-4 pb-4">
                            
                            <a href="javaScript:void(0)" class="text-center hover:bg-indigo-900 md:flex-auto block bg-indigo-500 text-sm px-4 py-3 text-white rounded-sm text-xs font-lato font-semibold"><i class="fab fa-facebook-square mr-2"></i>Login With Facebook</a>
                            <a href="javaScript:void(0)" class="text-center hover:bg-red-900 md:flex-auto block bg-red-500 text-sm px-4 py-3 text-white rounded-sm text-xs font-lato font-semibold "><i class="fab fa-google-plus-square mr-2"></i> Login With Google</a>
                        </div>
                        <div class="mb-2">
                            <label for="username" class="text-sm text-custom-dark-gray block transition-all  hover:text-black py-2">Username or email address</label>
                            <input type="text" name="socks-username" id="socks-username" class="bg-gray-50 border border-gray-100 m-0 text-xs text-gray-500 py-3 px-5 pr-16 focus:outline-none w-full rounded font-lato">
                        </div>
                        <div class="mb-2">
                            <label for="password" class="text-sm text-custom-dark-gray block transition-all  hover:text-black py-2">Password</label>
                            <input type="password" name="socks-password" id="socks-password" class="bg-gray-50 border border-gray-100 m-0 text-xs text-gray-500 py-3 px-5 pr-16 focus:outline-none w-full rounded font-lato">
                        </div>
                        <div class="mb-2 flex items-center space-x-2">
                            <label for="remember" class="flex-auto text-sm text-custom-dark-gray block transition-all  hover:text-black py-2">
                                <input type="checkbox" name="remember" id="remember">
                                Remember me
                            </label>

                            <div class="mb-2">
                                <a href="<?= $forget_pass ?>" class="text-sm text-custom-dark-gray block transition-all  hover:text-black py-2">Lost your password?</a>
                            </div>
                            
                        </div>
                        <div class="mb-2 grid md:grid-cols-2 gap-4">
                            <a href="javaScript:void(0)" id="socks-login_btn" class="text-center md:flex-auto bg-base-color text-sm px-5 py-3 rounded-sm text-lg font-lato font-semibold text-white"><i class="far fa-sign-in mr-2"></i>Login</a>
                            <a href="<?= $reg_url ?>" target="_blank" class="text-center md:flex-auto bg-gray-100 text-sm px-5 py-3 rounded-sm text-lg font-lato font-semibold text-gray-600"><i class="far fa-user-plus mr-2"></i>Registration</a>
                        </div>
                        <div id="socks-response-msg" class="response-msg hidden"></div>
                        <div id="loading-anim" class="load-wrapp bg-gray-100 hidden">
                            <div class="load-2">
                                <p class="text-gray-600">Cheeking Please Wait</p>
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
     }
}

new ClassOnPageScripts();