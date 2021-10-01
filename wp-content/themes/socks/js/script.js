// Top Panel button
const topPanelBtn = document.querySelector('#top-close-btn');
const topPanel = document.querySelector('#top-panel');

topPanelBtn.addEventListener('click', () => {

    if(topPanel.classList.contains('visible', 'opacity-100')){
        topPanel.classList.add('hidden');
    }else{
        topPanel.classList.remove('hidden');
    }
});

// Search bar
const toggleBtn = document.querySelector('#toggleBtn');
const toggleBtnClose = document.querySelector('#toggleBtnClose');
const searchBar = document.querySelector('#searchBar');
const searchContent = document.querySelector('#main-search-result-holder');
const searchInput = document.querySelector('#socks-main-search');
const searchLoading = document.querySelector('#loading-text');

toggleBtn.addEventListener('click', () => {
    if(searchBar.classList.contains('invisible', 'opacity-0')){
        searchBar.classList.remove('invisible', 'opacity-0');
        searchBar.classList.add('visible', 'opacity-100');

    }else{
        searchBar.classList.add('invisible', 'opacity-0');
        searchBar.classList.remove('visible', 'opacity-100');
    }
});

toggleBtnClose.addEventListener('click', () => {
    if(searchBar.classList.contains('visible', 'opacity-100')){
        searchBar.classList.add('invisible', 'opacity-0');
        searchBar.classList.remove('visible', 'opacity-100');
        searchInput.value = '';
        searchContent.classList.add('hidden');
        searchLoading.classList.add('hidden');
    }
});


document.getElementById('btn-modal').addEventListener('click', function() {
    document.getElementById('overlay').classList.add('visible', 'opacity-100');
    document.getElementById('modal').classList.add('slideInRight');
    document.getElementById('overlay').classList.remove('invisible', 'opacity-0');
});

document.getElementById('close-btn').addEventListener('click', function() {
    document.getElementById('overlay').classList.remove('visible', 'opacity-100');
    document.getElementById('modal').classList.remove('slideInRight');
    document.getElementById('overlay').classList.add('invisible', 'opacity-0');
});

document.getElementById('backdrop').addEventListener('click', function() {
    document.getElementById('overlay').classList.remove('visible', 'opacity-100');
    document.getElementById('modal').classList.remove('slideInRight');
    document.getElementById('overlay').classList.add('invisible', 'opacity-0');
});

document.addEventListener('DOMContentLoaded', function(event){
    // Filter Button
    //const FilterHandler = document.querySelector('#filter');
    const FilterHandler = document.getElementById('filter');
    const FilterSidebar = document.getElementById('filter-sidebar');
    if(typeof(FilterSidebar) != 'undefined' && FilterSidebar != null) {
        FilterHandler.addEventListener('click', (event) => {

            if (FilterSidebar.classList.contains('hidden', '-translate-x-full')) {
                FilterSidebar.classList.remove('hidden', '-translate-x-full');
                FilterSidebar.classList.add('block', 'translate-x-0');
                FilterHandler.classList.remove('open');
                FilterHandler.classList.add('closeElm');
            } else {
                FilterSidebar.classList.remove('block', 'translate-x-0');
                FilterSidebar.classList.add('hidden', '-translate-x-full');
                FilterHandler.classList.add('open');
                FilterHandler.classList.remove('closeElm');
            }

        });
    }

    // Shop Sidebar Accordion

    document.querySelectorAll('.accordion_handler').forEach((item) => {

        item.addEventListener('click', (event) => {
            item.classList.toggle('opened');
            if(item.nextElementSibling.classList.toggle('block')){
                item.nextElementSibling.classList.remove('hidden');
            }else{
                item.nextElementSibling.classList.add('hidden');
            }
        });
    });
});

function sliderInit() {
    jQuery('.slick').slick({
        dots: false,
        autoplay: true,
        autoplaySpeed: 500,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        prevArrow: '<button class="bg-white rounded-full z-40 focus:outline-none h-10 w-10 absolute top-1/2 left-3 text-2xl text-gray-500 text-center bg-opacity-25"><i class="fal fa-chevron-left"></i></button>',
        nextArrow: '<button class="bg-white rounded-full z-40 focus:outline-none h-10 w-10 absolute top-1/2 right-3 text-2xl text-gray-500 text-center bg-opacity-25"><i class="fal fa-chevron-right"></i></button>'
    });
}

// Load Ajax Shop Products
jQuery(function ($) {
    let shopElem = $("#socksLoadProducts");
    if (shopElem.length){
        socksLoadAjaxShopProducts(1)
    }
});

// Main Filter JS
function showCheckedFilters(filterInputs){
    filterInputs.click(function () {
        let activeFilters = jQuery("#activeFilters");
        let checkedInputs = jQuery("#filter-sidebar .sidebar .input-holder input:checked");
        let _html = '';

        let _cleatHtml = '<li class=" p-3 text-mate-black text-xs font-lato font-medium rounded-sm mr-2">' +
            '                <a href="javaScript:void(0)" id="clearAllFilter" class="underline">Clear all</a>' +
            '            </li>';

        checkedInputs.each(function () {
            let _h = jQuery(this);
            let _getId = _h.attr('id');
            let colorStyle = '';
            let txtW = 'mr-1';

            let _getLabel = _h.next('label').text();
            let _getParentElm = _h.parents('.input-holder');

            if (_getParentElm.hasClass('colors')){
                let getColor = _h.parent('.color-list').attr('data-color');
                _getLabel = '';
                txtW = 'text-white';
                colorStyle = 'style="background: '+getColor+'"';
            }

            _html += '<li '+colorStyle+' class="p-3 bg-gray-100 text-mate-black text-xs font-lato font-medium rounded-sm mr-2 mb-2" data-target="'+_getId+'">' +
                '                <a class="a-filter" href="javaScript:void(0)"><i class="fal fa-times '+txtW+'"></i> '+_getLabel+'</a>' +
                '            </li>';
        });

        if (_html.length === 0 ){
            _cleatHtml = '';
        }

        activeFilters.html(_html);
        activeFilters.append(_cleatHtml);
    });
}

jQuery(function ($) {
    let filterInputs = $("#filter-sidebar .sidebar .input-holder input");
    showCheckedFilters(filterInputs);
});

jQuery(function ($) {
    let singleSaleP = $("#socks-single-sale-price");
    let singleRegularP = $("#socks-regular-sale-price");
    let singleDiscountTarget = $("#single-discount-area");

    if (singleRegularP.length && singleSaleP.length){
        let getRAmount = singleRegularP.find('.socks-wc-price-amount').text();
        let getSAmount = singleSaleP.find('.socks-wc-price-amount').text();
        let discountAmount = ((parseFloat(getRAmount) - parseFloat(getSAmount)) * 100) / parseFloat(getSAmount);
        if (singleDiscountTarget.length){
            //singleDiscountTarget.text('( '+discountAmount.toFixed(2)+'% OFF )');
            singleDiscountTarget.text('( '+Math.round(discountAmount)+'% OFF )');
        }
    }else{
        singleDiscountTarget.remove();
    }
});

//jQuery(document).on('change', '.variation-radios input', function() {
jQuery(document).on('click', '.variation-radios input', function() {
    // jQuery('.variation-radios input:checked').each(function(index, element) {
    //     let $el = jQuery(element);
    //     let thisName = $el.attr('name');
    //     let thisVal  = $el.attr('value');
    //     jQuery('select[name="'+thisName+'"]').val(thisVal).trigger('change');
    // });

    let handler = jQuery(this);
    let thisName = handler.attr('name');
    let thisVal = handler.attr('value');
    let allInputs = handler.parents(".variation-item-holder").find('input');
    jQuery(".reset_variations").remove();

    if (handler.hasClass('selected')){
        allInputs.removeClass('selected');
        handler.removeClass('selected');
        handler.prop('checked',false);
        jQuery('select[name="'+thisName+'"]').val(null).trigger('change');
    }else{
        allInputs.removeClass('selected');
        handler.addClass('selected');
        handler.prop('checked',true);
        jQuery('select[name="'+thisName+'"]').val(thisVal).trigger('change');

    }

});

jQuery(document).on('woocommerce_update_variation_values', function() {
    jQuery('.variation-radios input').each(function(index, element) {
        let $el = jQuery(element);
        let thisName = $el.attr('name');
        let thisVal  = $el.attr('value');
        $el.removeAttr('disabled');
        if(jQuery('select[name="'+thisName+'"] option[value="'+thisVal+'"]').is(':disabled')) {
            $el.prop('disabled', true);
        }
    });
});

jQuery(function ($) {
    $("#socks-single-p-more-info").click(function () {
        let handler = $(this);
        let target = handler.parents("#tab-additional_information").next("#tab-description");

        if (handler.hasClass('tab-opened')){
            handler.removeClass('tab-opened');
            target.slideUp();
        }else{
            handler.addClass('tab-opened');
            target.slideDown();
        }
    });
});

// Main Filter JS
jQuery(function ($) {
    $("input.socks-select-wc-price").click(function () {
        let _h = $(this);
        let _all = $(".socks-select-wc-price");
        let filterInputs = jQuery("#filter-sidebar .sidebar .input-holder input");

        if (_h.hasClass('selected')){
            _all.removeClass('selected');
            _all.prop('checked',false);
            _h.removeClass('selected');
            _h.prop('checked',false);
        }else{
            _all.removeClass('selected');
            _all.prop('checked',false);
            _h.addClass('selected');
            _h.prop('checked',true);
        }
        showCheckedFilters(filterInputs);
    });
});

function socksGetSelectedPriceFilterData() {
    let checkedInputs = jQuery("#filter-sidebar .sidebar .input-holder input.socks-select-wc-price:checked");
    let data = Array();

    checkedInputs.each(function () {
        let _h = jQuery(this);
        let minP = _h.parent('.check-list').attr('data-start-price');
        let maxP = _h.parent('.check-list').attr('data-end-price');
        let priceList = {
            minPrice : minP,
            maxPrice : maxP,
        };
        data.push(priceList);
    });

    return data;
}

function socksGetSelectedTaxFilterData() {
    let getTaxonomies = jQuery("#filter-sidebar .sidebar.sidebar-tax");
    let data = Array();

    getTaxonomies.each(function () {
        let _h = jQuery(this);
        let termsData = Array();
        let _targets = _h.find("input:checked");
        let getTax = _h.attr('data-filter-type');

        _targets.each(function () {
            termsData.push(jQuery(this).parent('.check-holder').attr('data-term-slug'));
        });

        if (termsData.length !== 0){
            let tData = {
                taxonomy : getTax,
                terms : termsData
            };
            data.push(tData);
        }
    });

    return data;
}

jQuery(function ($) {
    $("#filter-sidebar .sidebar .input-holder input").change(function () {
        let getPrice = socksGetSelectedPriceFilterData();
        let getAttr = socksGetSelectedTaxFilterData();

        let filterData = {
            price : getPrice,
            taxonomies : getAttr,
        };

        socksLoadAjaxShopProducts(1, filterData);
    });

    $("#socks-wc-shorting").change(function () {
        let handler = $(this);
        let getPrice = socksGetSelectedPriceFilterData();
        let getAttr = socksGetSelectedTaxFilterData();

        let filterData = {
            price : getPrice,
            taxonomies : getAttr,
            shorting: handler.val(),
        };

        socksLoadAjaxShopProducts(1, filterData);
    });
});


// Ajax Shop Products function
function socksLoadAjaxShopProducts(page, filterData = Array()){
    let _loader  = jQuery(".loading-anim");
    let _content = jQuery("#socksLoadProducts");
    let _main    = jQuery("#socksLoadProducts .products-holder");
    let _pageTerm = jQuery("#shop_body_panel").attr('data-page-term');

    let formData = {
        page : page,
        socksSecurity : socksObj.socksNonce,
        action : "socks_load_products",
        data : filterData
    };

    if (_pageTerm !== ''){
        formData.pageTerm = _pageTerm;
    }

    _main.hide();
    _loader.css('display','block');

    jQuery.post(socksObj.ajaxUrl, formData, function(response) {
        _content.html(response);
        _main.show();
        _loader.hide();
        sliderInit();
        setTimeout(function () {
            socksWcSetCounter();
        },500);
    });
}

function socksWcSetCounter(){
    let _counter = jQuery("#socksLoadProducts #search-results").attr("data-product-counter");
    let _counterElm = jQuery("#socks-wc-product-counter");

    if (_counter === '1'){
        _counterElm.text(_counter+' Item');
    }else{
        _counterElm.text(_counter+' Items');
    }
}

jQuery(document).on('click','#activeFilters .a-filter',function () {
    let handler = jQuery(this);
    let allAf = jQuery("#activeFilters .a-filter");
    let clrBtn = jQuery("#clearAllFilter");
    let filterInputs = jQuery("#filter-sidebar .sidebar .input-holder input");
    let target = handler.parent('li').attr('data-target');
    let targetElm = jQuery('#'+target);

    targetElm.prop('checked',false);
    handler.parent('li').remove();
    if (targetElm.hasClass('selected')){
        targetElm.removeClass('selected');
    }
    showCheckedFilters(filterInputs);
    if (allAf.length === 1){
        clrBtn.parent('li').remove();
    }

    let getPrice = socksGetSelectedPriceFilterData();
    let getAttr = socksGetSelectedTaxFilterData();

    let filterData = {
        price : getPrice,
        taxonomies : getAttr,
    };

    socksLoadAjaxShopProducts(1, filterData);
});

jQuery(document).on('click','#clearAllFilter',function () {
    let handler = jQuery(this);
    let filterInputs = jQuery("#filter-sidebar .sidebar .input-holder input");
    let allAf = jQuery("#activeFilters .a-filter");

    filterInputs.prop('checked',false);
    allAf.parent('li').remove();
    handler.parent('li').remove();
    showCheckedFilters(filterInputs);

    let getPrice = socksGetSelectedPriceFilterData();
    let getAttr = socksGetSelectedTaxFilterData();

    let filterData = {
        price : getPrice,
        taxonomies : getAttr,
    };

    socksLoadAjaxShopProducts(1, filterData);
});

jQuery(document).on('click','#socksLoadProducts .pagination-wrap li.active',function () {
    let handler = jQuery(this);
    let pageNumber = handler.attr('data-page');
    let getPrice = socksGetSelectedPriceFilterData();
    let getAttr = socksGetSelectedTaxFilterData();

    let filterData = {
        price : getPrice,
        taxonomies : getAttr,
    };

    if (getPrice.length !== 0 || getAttr.length !== 0){
        socksLoadAjaxShopProducts(parseInt(pageNumber), filterData);
    }else{
        socksLoadAjaxShopProducts(parseInt(pageNumber));
    }

});

jQuery(function ($) {
    $("#socks-user-login-btn").click(function (event) {
        event.preventDefault();
        let _h = $(this);
        let _target = $("#login-popup-area");
        _target.show();
    });

    $("#socks-close_btn").click(function (event) {
        event.preventDefault();
       let handler = $(this);
       let target = handler.parents("#login-popup-area");
        target.hide();
    });

    $("#socks-login_btn").click(function () {
        let _h = $(this);
        let userName = $("#login-popup-area #socks-username");
        let userPass = $("#login-popup-area #socks-password");
        let _msg = $("#login-popup-area #socks-response-msg");
        let _loadingAnim = $("#login-popup-area #loading-anim");
        let _remember = $("#login-popup-area #remember");

        let data = {
            userName : userName.val(),
            userPass: userPass.val(),
        };

        if (_remember.is(':checked')){
            data.remember = true;
        }

        if (userName.val() !== '' && userPass.val() !== ''){
            _msg.addClass('hidden');
            socksMainUserLogin(data,_loadingAnim,_msg);
        }else{
            _msg.removeClass('hidden');
            _msg.addClass('error');
            _msg.html('<p class="text-red-500">Oops ! Fields should not be empty.</p>');
        }
    });
});

function socksMainUserLogin(fData,loadingElm, msgElm) {

    let formData = {
        socksSecurity : socksObj.socksNonce,
        action : "socks_user_login",
        data : fData
    };

    loadingElm.removeClass('hidden');
    jQuery.post(socksObj.ajaxUrl, formData, function(response) {
        let result = JSON.parse(response);
        if (result.status === 'success'){
            loadingElm.addClass('hidden');
            msgElm.removeClass('hidden');
            msgElm.addClass('success');
            msgElm.html('<p class="text-green-500">Awesome! You are logged in successfully.</p>');
            setTimeout(function () {
                window.location.href = result.redirect;
            },500);
        }else{
            loadingElm.addClass('hidden');
            msgElm.removeClass('hidden');
            msgElm.addClass('error');
            msgElm.html('<p class="text-red-500">Error! Something went wrong with your credentials.</p>');
        }
    });

}

// Ajax Load Main Search Result #main-search-result-holder
function socksLoadAjaxSearchProducts(page, filterData=''){
    let loader  = jQuery("#loading-text");
    let content = jQuery("#main-search-result-holder");
    let main    = jQuery("#main-search-result-holder .sp-holder");

    if (content.hasClass('hidden')){
        content.removeClass('hidden');
    }

    let fData = {
        page : page,
        socksSecurity : socksObj.socksNonce,
        action : "socks_search_products",
        data : filterData
    };

    if (filterData !== '' && filterData.length >= 1){
        main.hide();
        loader.removeClass('hidden');

        jQuery.post(socksObj.ajaxUrl, fData, function(response) {
            content.html(response);
            main.show();
            loader.addClass('hidden');
        });
    }else{
        content.html('');
    }
}

jQuery(function ($) {
    $("#socks-main-search").on('keyup change past cut',function () {
        let handler = $(this);
        socksLoadAjaxSearchProducts(1,handler.val());
    });

    $("#menu_btn").click(function () {
        let _target = $("#mobile-navigation");
        if (_target.hasClass('hidden')){
            _target.removeClass('hidden');
        }
    });

    $("#btn-menu-close").click(function () {
        $(this).parents("#mobile-navigation").addClass('hidden');
    });

    let mobileSub = jQuery("#mobile-nav .menu-item-has-children").children('a');

    mobileSub.click(function (event) {
        event.preventDefault();
        let _h = $(this);
        if (_h.hasClass('opened')){
            _h.removeClass('opened');
            _h.next('ul').slideUp();
        }else{
            _h.addClass('opened');
            _h.next('ul').slideDown();
        }
    });
});