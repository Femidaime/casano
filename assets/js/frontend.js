jQuery( function( $ ) {
    "use strict";
    
    function casano_center_nav() {
        $('.owl-carousel.owl-products.nav-center').each(function () {
            var $thisCarousel = $(this);
            var $productItem = $thisCarousel.find('.product-item');
            var thumb_h = $productItem.find('.product-thumb img').innerHeight();
            var pos_top = thumb_h / 2;
            $thisCarousel.find('.owl-prev, .owl-next').css({
                'top': (pos_top) + 'px'
            });
        });
        
    }
    
    function casano_init_lazy_load() {
        if ($('.lazy').length > 0) {
            var _config = [];
            
            _config.beforeLoad = function (element) {
                element.parent().addClass('loading-lazy');
            };
            _config.afterLoad = function (element) {
                element.addClass('lazy-loaded').parent().removeClass('loading-lazy');
                casano_center_nav();
            };
            _config.effect = 'fadeIn';
            _config.enableThrottle = true;
            _config.throttle = 250;
            _config.effectTime = 1000;
            _config.threshold = 0;
            
            $('.lazy').lazy(_config);
            if ($(this).closest('.megamenu').length > 0) {
                _config.delay = 0;
            }
            
        }
    }
    casano_init_lazy_load();
    
    // Document ready
    jQuery(document).ready(function ($) {
        $(window).on('scroll', function (){
            if ($(window).scrollTop() > 0) {
                $('.menu-sticky-mobile') .addClass('menu-bg');
            } else {
                $('.menu-sticky-mobile') .removeClass('menu-bg')
            }
        });
        function casano_isotope() {
            // init Isotope
            $('.casano-isotope').isotope({
                itemSelector: '.isotope-item',
                masonry: {
                    columnWidth: '.isotope-sizer'
                }
            });
        }
        function full_page() {
            var menuType = 'desktop';
            var currMenuType = 'desktop';
            if (matchMedia('only screen and (max-width: 1024px)').matches) {
                currMenuType = 'mobile';
            }
            if (currMenuType !== menuType) {
                menuType = currMenuType;
                if (currMenuType === 'mobile') {
                } else {
                }
            } else {
                jQuery('#fullpage').each(function () {
                    $(this).fullpage({
                        css3: true,
                        navigation: false,
                        verticalCentered: true,
                        scrollOverflow: false,
                        sectionSelector: '.section-slide'
                    });
                });
            }
            
        }
        
        function casano_product_loadmore() {
            $('.woo-product-loadmore').on('click', function (e) {
                var $this = $(this);
                $this.addClass('loading');
                // get post ID in array
                var except_post_ids = new Array(),
                    _product_wrap = $(this).closest('.casano-products');
                _product_wrap.find('.product-item').each(function () {
                    var post_id = $(this).attr('data-id').replace('post-', '');
                    except_post_ids.push(post_id);
                });
                // get post ID in array
                var attr = $(this).attr('data-attribute'),
                    cats = $(this).attr('data-cats'),
                    id = $(this).data('id'),
                    page = parseInt($(this).attr('data-page')),
                    data = {
                        action: 'casano_loadmore_product',
                        except_post_ids: except_post_ids,
                        security: casano_ajax_frontend.security,
                        attr: attr,
                        cats: cats,
                        page: page,
                    };
                $.post(casano_ajax_frontend.ajaxurl, data, function (response) {
                    var items = $('' + response['html'] + '');
                    if ($.trim(response['success']) == 'yes') {
                        var tab_id = '.' + id;
                        $('#' + id).append(items);
                        casano_init_lazy_load();
                        if ($.trim(response['show_bt']) == '0') {
                            $this.addClass('disable');
                            $(tab_id + ' .woo-product-loadmore').html('No More Product');
                        } else {
                            $(tab_id + ' .woo-product-loadmore').attr('data-page', page + 1);
                        }
                    } else {
                        $('#' + id).append('<p class="return-message bg-success">Not ok</p>');
                    }
                    $this.removeClass('loading');
                });
                return false;
            });
        }
        
        function casano_responsive_instagramshop() {
            var window_size = jQuery('body').innerWidth();
            window_size += casano_get_scrollbar_width();
            if (window_size < 1200) {
                $('.casano-instagramshopwrap .ziss-row').not('.slick-initialized').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: false,
                    infinite: false,
                    responsive: [
                        {
                            breakpoint: 991,
                            settings: {
                                arrows: false,
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                arrows: false,
                                slidesToShow: 2
                            }
                        }
                    ]
                });
            }
        }
        
        function sticky_detail_content() {
            $('.sticky_detail .detail-content')
                .theiaStickySidebar({
                    additionalMarginTop: 60
                });
        }
        
        $(document).on('click', function (event) {
            var _target = $(event.target).closest('.casano-dropdown'),
                _parent = $('.casano-dropdown');
            
            if (_target.length > 0) {
                _parent.not(_target).removeClass('open');
                if (
                    $(event.target).is('[data-casano="casano-dropdown"]') ||
                    $(event.target).closest('[data-casano="casano-dropdown"]').length > 0
                ) {
                    _target.toggleClass('open');
                    event.preventDefault();
                }
            } else {
                $('.casano-dropdown').removeClass('open');
            }
        });
        
        casano_init_lazy_load();
        
        // Animation on scroll
        function casano_wow() {
            if (casano_theme_frontend['animation_on_scroll'] != 'yes') {
                return;
            }
            var wow = new WOW(
                {
                    boxClass: 'casano-wow',      // animated element css class (default is wow)
                    animateClass: 'animated', // animation css class (default is animated)
                    offset: 50,          // distance to the element when triggering the animation (default is 0)
                    mobile: true,       // trigger animations on mobile devices (default is true)
                    live: true,       // act on asynchronously loaded content (default is true)
                    callback: function (box) {
                        // the callback is fired every time an animation is started
                        // the argument that is passed in is the DOM node being animated
                    },
                    scrollContainer: null // optional scroll container selector, otherwise use window
                }
            );
            wow.init();
        }
        
        casano_wow();
        
        function casano_init_popup() {
            var popup_news = $('#popup-newsletter');
            if (popup_news.length > 0) {
                if ($('body').hasClass('home')) {
                    setTimeout(function () {
                        $('#popup-newsletter').modal({
                            keyboard: false
                        })
                    }, 80000);
                }
            }
        }
        
        function sticky_scrollup() {
            if ($('.sticky_info_single_product').length) {
                
                var previousScroll = 0,
                    headerOrgOffset = $('.site-header').outerHeight() + $('.site-header').outerHeight() + $('.product-top-inner').offset().top;
                
                $(window).scroll(function () {
                    var currentScroll = $(this).scrollTop();
                    if (currentScroll > headerOrgOffset) {
                        $('body').addClass('show-sticky_info_single');
                    } else {
                        $('body').removeClass('show-sticky_info_single');
                    }
                    previousScroll = currentScroll;
                });
            }
            if ($('.header-pos-absolute').length) {
                $('.header-pos-absolute').each(function () {
                    var headerOffset = $('.header-position').outerHeight() + $('.header-topbar').outerHeight();
                    var contentBannerOffset = headerOffset - $('.header-position').outerHeight() / 2;
                    if ($('.page-wrapper').hasClass('single-layout1')) {
                        $(this).parent().find('.single-container').css({'padding-top': headerOffset + 'px'});
                    } else {
                        $(this).parent().find('.single-container').css({'padding-top': 0 + 'px'});
                    }
                    if ($('.product-toolbar').length) {
                        $(this).parent().find('.product-toolbar').css({'padding-top': headerOffset + 'px'});
                    } else {
                        $(this).parent().find('.product-toolbar').css({'padding-top': 0 + 'px'});
                    }
                    if ($('.content-banner').length) {
                        $('.content-banner').css({'padding-top': headerOffset + 'px'});
                    }
                });
            }
        }
        
        function toggle_form() {
            $('.toggle-form').on('click', function (e) {
                $(this).toggleClass('active');
                $('.block-form').toggleClass('nav-show form-show');
                e.preventDefault();
                e.stopPropagation();
            });
        }
        // search canvas
        function casano_search_offcanvas() {
            $(document).on('click', '.search-block .search-icon, .search-canvas-overlay, .search-close', function (e) {
                $('body,html').toggleClass('search-canvas-open');
                e.preventDefault();
            })
        }
        
        // minicart canvas
        function casano_minicart_offcanvas() {
            $(document).on('click', '.mini-cart-icon,.minicart-canvas-overlay,.close-minicart', function (e) {
                $('body,html').toggleClass('minicart-canvas-open');
                e.preventDefault();
            })
        }
        
        // Close instant search
        function casano_instant_search_close() {
            $(document).on('click', '.header-search-box .icons,.instant-search-close', function (e) {
                $('body').toggleClass('instant-search-open');
                e.preventDefault();
            });
        }
        
        function getCookie(c_name) {
            var c_value = document.cookie;
            var c_start = c_value.indexOf(" " + c_name + "=");
            if (c_start == -1) {
                c_start = c_value.indexOf(c_name + "=");
            }
            if (c_start == -1) {
                c_value = null;
            } else {
                c_start = c_value.indexOf("=", c_start) + 1;
                var c_end = c_value.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = c_value.length;
                }
                c_value = decodeURI(c_value.substring(c_start, c_end));
            }
            return c_value;
        }
        
        function setCookie(c_name, value, exdays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value = encodeURI(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
            document.cookie = c_name + "=" + c_value;
        }
        
        function casano_singleProduct_popup() {
            $('.casano-bt-video a').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                disableOn: false,
                fixedContentPos: false
            });
            $('.casano-demo-wrap .demo-open').magnificPopup({
                type: 'inline',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                disableOn: false,
                preloader: false,
                fixedContentPos: false,
                callbacks: {
                    open: function () {
                        $(window).resize()
                    },
                },
            });
            $('.product-360-button a').magnificPopup({
                type: 'inline',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                disableOn: false,
                preloader: false,
                fixedContentPos: false,
                callbacks: {
                    open: function () {
                        $(window).resize()
                    },
                },
            });
            $('.open-popup-link').magnificPopup({
                mainClass: 'mfp-fade',
                removalDelay: 100,
                type: 'inline',
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
            $('.block-account .acc-popup').magnificPopup({
                mainClass: 'mfp-fade',
                removalDelay: 100,
                type: 'inline',
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
            
            var expireDate = getCookie("showpopup");
            var today = new Date().toUTCString();
            if (expireDate != null && expireDate > today) {
                //Do nothing!
            } else {
                setTimeout(function () {
                    if ($('#popup-discount').length) {
                        $.magnificPopup.open({
                            items: {
                                src: '#popup-discount'
                            },
                            type: 'inline',
                            mainClass: 'mfp-zoom-in'
                        });
                    }
                }, 3000);
                //Create cookie
                setCookie("showpopup", "anything", 1);
            }
            
        }
        
        /* Main Menu */
        
        /* ---------------------------------------------
         Resize mega menu
         --------------------------------------------- */
        function casano_resizeMegamenu() {
            var window_size = jQuery('body').innerWidth();
            window_size += casano_get_scrollbar_width();
            if (window_size > 767) {
                if ($('#header .main-menu-wrapper').length > 0) {
                    var container = $('#header .main-menu-wrapper');
                    if (container != 'undefined') {
                        var container_width = 0;
                        container_width = container.innerWidth();
                        var container_offset = container.offset();
                        setTimeout(function () {
                            $('.main-menu .item-megamenu').each(function (index, element) {
                                $(element).children('.megamenu').css({'max-width': container_width + 'px'});
                                var sub_menu_width = $(element).children('.megamenu').outerWidth();
                                var item_width = $(element).outerWidth();
                                $(element).children('.megamenu').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                                var container_left = container_offset.left;
                                var container_right = (container_left + container_width);
                                var item_left = $(element).offset().left;
                                var overflow_left = (sub_menu_width / 2 > (item_left - container_left));
                                var overflow_right = ((sub_menu_width / 2 + item_left) > container_right);
                                if (overflow_left) {
                                    var left = (item_left - container_left);
                                    $(element).children('.megamenu').css({'left': -left + 'px'});
                                }
                                if (overflow_right && !overflow_left) {
                                    var left = (item_left - container_left);
                                    left = left - (container_width - sub_menu_width);
                                    $(element).children('.megamenu').css({'left': -left + 'px'});
                                }
                            })
                        }, 100);
                    }
                }
            }
        }
        
        function casano_get_scrollbar_width() {
            var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
                $outer = jQuery('<div style="width:200px; height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
                inner = $inner[0],
                outer = $outer[0];
            jQuery('body').append(outer);
            var width1 = inner.offsetWidth;
            $outer.css('overflow', 'scroll');
            var width2 = outer.clientWidth;
            $outer.remove();
            return (width1 - width2);
        }
        
        function dropdown_menu(contain) {
            $(contain).each(function () {
                var _main = $(this);
                _main.children('.menu-item.parent').each(function () {
                    
                    var curent = $(this).find('.submenu');
                    
                    $(this).children('.toggle-submenu').on('click', function () {
                        $(this).parent().children('.submenu').slideToggle(400);
                        _main.find('.submenu').not(curent).slideUp(400);
                        
                        $(this).parent().toggleClass('show-submenu');
                        _main.find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                    });
                    
                    var next_curent = $(this).find('.submenu');
                    
                    next_curent.children('.menu-item.parent').each(function () {
                        
                        var child_curent = $(this).find('.submenu');
                        $(this).children('.toggle-submenu').on('click', function () {
                            $(this).parent().parent().find('.submenu').not(child_curent).slideUp(400);
                            $(this).parent().children('.submenu').slideToggle(400);
                            
                            $(this).parent().parent().find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                            $(this).parent().toggleClass('show-submenu');
                        })
                    });
                });
            });
        };
        // Toggle submenu mobile / responsive
        $(document).on('click', '#box-mobile-menu .menu-item .toggle-submenu', function (e) {
            var $this = $(this);
            var $thisParent = $this.closest('.menu-item-has-children');
            if ($thisParent.length) {
                $thisParent.toggleClass('show-submenu').find('> .submenu').stop().slideToggle();
            }
            // Fix lazy for mobile menu
            if ($(this).parent().find('.fami-lazy:not(.already-fix-lazy)').length) {
                $(this).parent().find('.fami-lazy:not(.already-fix-lazy)').lazy({
                    bind: 'event',
                    delay: 0
                }).addClass('already-fix-lazy');
            }
            e.preventDefault();
            return false;
        });
        
        /*Clone Main Menu*/
        function casano_clone_main_menu() {
            if ($('#header .clone-main-menu').length > 0) {
                var _winw = $(window).innerWidth();
                var _clone_menu = $('#header .clone-main-menu');
                var _target = $('#box-mobile-menu.clone-main-menu');
                var main_menu_break_point = casano_theme_frontend['main_menu_break_point'];
                if (_winw <= main_menu_break_point) {
                    if (_clone_menu.length > 0 && _target.length == 0) {
                        if ($("#box-mobile-menu .box-inner").hasClass('menu-cloned')) {
                            //nope
                        } else {
                            _clone_menu.clone().appendTo("#box-mobile-menu .box-inner");
                            //Replace Id ul, menu item
                            $('.box-mobile-menu .menu-double-menu').each(function () {
                                if ($(this).attr('id') != '') {
                                    var cur_id = $(this).attr('id');
                                    $(this).attr('id', cur_id + '-clone');
                                }
                            });
                            $('.box-mobile-menu li.menu-item').each(function () {
                                if ($(this).attr('id') != '') {
                                    var cur_id = $(this).attr('id');
                                    $(this).attr('id', cur_id + '-clone');
                                }
                            });
                        }
                        $("#box-mobile-menu .box-inner").addClass('menu-cloned');
                    }
                }
            }
        }
        
        /* Carousel  */
        function casano_init_carousel() {
            $('.owl-carousel').each(function (index, el) {
                var config = $(this).data();
                if ($(this).is('.category-filter-mobile')) {
                    config['autoWidth'] = true;
                }
                config.navText = ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'];
                var animateOut = $(this).data('animateout');
                var animateIn = $(this).data('animatein');
                var slidespeed = $(this).data('slidespeed');
                
                if (typeof animateOut != 'undefined') {
                    config.animateOut = animateOut;
                }
                if (typeof animateIn != 'undefined') {
                    config.animateIn = animateIn;
                }
                if (typeof (slidespeed) != 'undefined') {
                    config.smartSpeed = slidespeed;
                }
                
                var owl = $(this);
                owl.on('initialized.owl.carousel', function (event) {
                    var total_active = owl.find('.owl-item.active').length;
                    var i = 0;
                    owl.find('.owl-item').removeClass('item-first item-last');
                    setTimeout(function () {
                        owl.find('.owl-item.active').each(function () {
                            i++;
                            if (i == 1) {
                                $(this).addClass('item-first');
                            }
                            if (i == total_active) {
                                $(this).addClass('item-last');
                            }
                        });
                        
                    }, 100);
                    
                    
                });
                owl.on('refreshed.owl.carousel', function (event) {
                    var total_active = owl.find('.owl-item.active').length;
                    var i = 0;
                    owl.find('.owl-item').removeClass('item-first item-last');
                    setTimeout(function () {
                        owl.find('.owl-item.active').each(function () {
                            i++;
                            if (i == 1) {
                                $(this).addClass('item-first');
                            }
                            if (i == total_active) {
                                $(this).addClass('item-last');
                            }
                        });
                        
                    }, 100);
                })
                owl.on('change.owl.carousel', function (event) {
                    var total_active = owl.find('.owl-item.active').length;
                    var i = 0;
                    owl.find('.owl-item').removeClass('item-first item-last');
                    setTimeout(function () {
                        owl.find('.owl-item.active').each(function () {
                            i++;
                            if (i == 1) {
                                $(this).addClass('item-first');
                            }
                            if (i == total_active) {
                                $(this).addClass('item-last');
                            }
                        });
                        
                    }, 100);
                    
                    
                });
                owl.on('translated.owl.carousel', function (event) {
                    // Fami lazy load for owl
                    if ($('.owl-item .lazy').length) {
                        $('.owl-item .lazy').lazy({
                            bind: "event"
                        });
                    }
                    casano_center_nav();
                });
                owl.owlCarousel(config);
                
            });
        }
        
        function thumbnail_product() {
            $('.default:not(.product-mobile-layout) .flex-control-thumbs').not('.slick-initialized').slick({
                slidesToShow: 4,
                infinite: false,
                slidesToScroll: 1,
                prevArrow: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                nextArrow: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    }
                ]
            });
            $('.vertical_thumnail:not(.product-mobile-layout) .flex-control-thumbs').each(function () {
                if ($(this).not('.slick-initialized').children().length == 0) {
                    return;
                }
                $(this).slick({
                    vertical: true,
                    verticalSwiping: true,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: false,
                    prevArrow: '<i class="fa fa-angle-up" aria-hidden="true"></i>',
                    nextArrow: '<i class="fa fa-angle-down" aria-hidden="true"></i>',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                vertical: false,
                                verticalSwiping: false,
                                slidesToShow: 4,
                                prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                                nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                vertical: false,
                                verticalSwiping: false,
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                                nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                            }
                        },
                        {
                            breakpoint: 320,
                            settings: {
                                vertical: false,
                                verticalSwiping: false,
                                slidesToShow: 2,
                                slidesToScroll: 2,
                                prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                                nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                            }
                        }
                    ]
                });
            });
            
        }
        
        /* ---------------------------------------------
         COUNTDOWN
         --------------------------------------------- */
        function casano_countdown() {
            $('.casano-countdown').each(function () {
                var $el = $(this),
                    $timers = $el.find('.timers'),
                    output = '';
                $timers.countdown($timers.data('date'), function (event) {
                    output = '';
                    var day = event.strftime('%D');
                    for (var i = 0; i < day.length; i++) {
                        output += '<span>' + day[i] + '</span>';
                    }
                    $timers.find('.day').html(output);
                    
                    output = '';
                    var hour = event.strftime('%H');
                    for (i = 0; i < hour.length; i++) {
                        output += '<span>' + hour[i] + '</span>';
                    }
                    $timers.find('.hour').html(output);
                    
                    output = '';
                    var minu = event.strftime('%M');
                    for (i = 0; i < minu.length; i++) {
                        output += '<span>' + minu[i] + '</span>';
                    }
                    $(this).find('.min').html(output);
                    
                    output = '';
                    var secs = event.strftime('%S');
                    for (i = 0; i < secs.length; i++) {
                        output += '<span>' + secs[i] + '</span>';
                    }
                    $timers.find('.secs').html(output);
                });
            });
        };
        
        /* ---------------------------------------------
         Woocommerce Quantily
         --------------------------------------------- */
        function casano_woo_quantily() {
            $('body').on('click', '.quantity .quantity-plus', function () {
                var obj_qty = $(this).closest('.quantity').find('input.qty'),
                    val_qty = parseInt(obj_qty.val()),
                    min_qty = parseInt(obj_qty.data('min')),
                    max_qty = parseInt(obj_qty.data('max')),
                    step_qty = parseInt(obj_qty.data('step'));
                val_qty = val_qty + step_qty;
                if (max_qty && val_qty > max_qty) {
                    val_qty = max_qty;
                }
                obj_qty.val(val_qty);
                obj_qty.trigger("change");
                return false;
            });
            
            $('body').on('click', '.quantity .quantity-minus', function () {
                var obj_qty = $(this).closest('.quantity').find('input.qty'),
                    val_qty = parseInt(obj_qty.val()),
                    min_qty = parseInt(obj_qty.data('min')),
                    max_qty = parseInt(obj_qty.data('max')),
                    step_qty = parseInt(obj_qty.data('step'));
                val_qty = val_qty - step_qty;
                if (min_qty && val_qty < min_qty) {
                    val_qty = min_qty;
                }
                if (!min_qty && val_qty < 0) {
                    val_qty = 0;
                }
                obj_qty.val(val_qty);
                obj_qty.trigger("change");
                return false;
            });
        }
        
        // Single product zoom image (sticky gallery)
        function casano_sticky_details_product_zoom_img() {
            if (typeof wc_single_product_params !== 'undefined') {
                if (!$('.single-product .sticky_detail').length) {
                    return false;
                }
                var $target = $('.single-product .sticky_detail .woocommerce-product-gallery.images');
                var zoomTarget = $('.woocommerce-product-gallery__image');
                var galleryWidth = $target.width(),
                    zoomEnabled = false;
                
                
                $(zoomTarget).each(function (index, target) {
                    var image = $(target).find('img');
                    
                    if (image.data('large_image_width') > galleryWidth) {
                        zoomEnabled = true;
                        return false;
                    }
                });
                
                // But only zoom if the img is larger than its container.
                if (zoomEnabled) {
                    var zoom_options = $.extend({
                        touch: false
                    }, wc_single_product_params.zoom_options);
                    
                    if ('ontouchstart' in document.documentElement) {
                        zoom_options.on = 'click';
                    }
                    
                    zoomTarget.trigger('zoom.destroy');
                    zoomTarget.zoom(zoom_options);
                }
            }
        }
        
        // Single product gallery
        function casano_gallery_details_product_zoom_img() {
            if (typeof wc_single_product_params !== 'undefined') {
                var $gallery = $('.single-product .gallery_detail');
                
                if (!$gallery.length) {
                    return false;
                }
                
                // Init empty gallery array
                var container = [];
                
                // Loop over gallery items and push it to the array
                $gallery.find('.casano-product-gallery__image').each(function () {
                    var $link = $(this).find('a'),
                        
                        item = {
                            src: $link.attr('href'),
                            w: $link.data('img_width'),
                            h: $link.data('img_width')
                        };
                    container.push(item);
                });
                
                // Define click event on gallery item
                $gallery.find('.casano-product-gallery__image a').click(function (event) {
                    
                    // Prevent location change
                    event.preventDefault();
                    
                    // Define object and gallery options
                    var $pswp = $('.pswp')[0],
                        options = {
                            index: $(this).parent('figure').index(),
                            bgOpacity: 0.85,
                            showHideOpacity: true
                        };
                    
                    // Initialize PhotoSwipe
                    var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
                    gallery.init();
                });
                
            }
        }
        
        /* ---------------------------------------------
         TAB EFFECT
         --------------------------------------------- */
        function casano_tab_fade_effect() {
            // effect click
            $(document).on('click', '.casano-tabs .tab-link a', function () {
                var tab_id = $(this).attr('href');
                var tab_animated = $(this).data('animate');
                
                tab_animated = (tab_animated == undefined || tab_animated == "") ? '' : tab_animated;
                if (tab_animated == "") {
                    return false;
                }
                
                $(tab_id).find('.product-list-owl .owl-item.active, .product-item').each(function (i) {
                    
                    var t = $(this);
                    var style = $(this).attr("style");
                    style = (style == undefined) ? '' : style;
                    var delay = i * 400;
                    t.attr("style", style +
                        ";-webkit-animation-delay:" + delay + "ms;"
                        + "-moz-animation-delay:" + delay + "ms;"
                        + "-o-animation-delay:" + delay + "ms;"
                        + "animation-delay:" + delay + "ms;"
                    ).addClass(tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                        t.removeClass(tab_animated + ' animated');
                        t.attr("style", style);
                    });
                })
            })
        }
        
        /* ---------------------------------------------
         Ajax Tab
         --------------------------------------------- */
        $(document).on('click', '[data-ajax="1"]', function () {
            better_equal_elems();
            if (!$(this).hasClass('loaded')) {
                var id = $(this).data('id');
                var tab_id = $(this).attr('href');
                var section_id = tab_id.replace('#', '');
                var t = $(this);
                
                $(tab_id).closest('.tab-container').append('<div class="cssload-wapper" style="min-height: 300px;position: static"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
                $(tab_id).closest('.panel-collapse').append('<div class="cssload-wapper" style="min-height: 300px;position: static"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
                $.ajax({
                    type: 'POST',
                    data: {
                        action: 'casano_ajax_tabs',
                        security: casano_theme_frontend.security,
                        id: id,
                        section_id: section_id,
                    },
                    url: casano_theme_frontend.ajaxurl,
                    success: function (response) {
                        $(tab_id).closest('.tab-container').find('.cssload-wapper').remove();
                        $(tab_id).closest('.panel-collapse').find('.cssload-wapper').remove();
                        $(tab_id).html($(response['html']).find('.vc_tta-panel-body').html());
                        t.addClass('loaded');
                    },
                    complete: function () {
                        better_equal_elems();
                        casano_tab_fade_effect();
                    }
                });
            }
        });
        
        function casano_google_maps() {
            if ($('.casano-google-maps').length <= 0) {
                return;
            }
            $('.casano-google-maps').each(function () {
                var $this = $(this),
                    $id = $this.attr('id'),
                    $title_maps = $this.attr('data-title_maps'),
                    $phone = $this.attr('data-phone'),
                    $email = $this.attr('data-email'),
                    $zoom = parseInt($this.attr('data-zoom')),
                    $latitude = $this.data('latitude'),
                    $longitude = $this.data('longitude'),
                    $address = $this.attr('data-address'),
                    $map_type = $this.attr('data-map-type'),
                    $pin_icon = $this.attr('data-pin-icon'),
                    $modify_coloring = true,
                    $saturation = $this.data('saturation'),
                    $hue = $this.data('hue'),
                    $map_style = $this.data('map-style'),
                    $styles;
                
                if ($modify_coloring == true) {
                    var $styles = [
                        {
                            stylers: [
                                {hue: $hue},
                                {invert_lightness: false},
                                {saturation: $saturation},
                                {lightness: 1},
                                {
                                    featureType: "landscape.man_made",
                                    stylers: [{
                                        visibility: "on"
                                    }]
                                }
                            ]
                        }, {
                            featureType: 'water',
                            elementType: 'geometry',
                            stylers: [
                                {color: '#46bcec'}
                            ]
                        }
                    ];
                }
                var map;
                var bounds = new google.maps.LatLngBounds();
                var mapOptions = {
                    zoom: $zoom,
                    panControl: true,
                    zoomControl: true,
                    mapTypeControl: true,
                    scaleControl: true,
                    draggable: true,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId[$map_type],
                    styles: $styles
                };
                
                map = new google.maps.Map(document.getElementById($id), mapOptions);
                map.setTilt(45);
                
                // Multiple Markers
                var markers = [];
                var infoWindowContent = [];
                
                if ($latitude != '' && $longitude != '') {
                    markers[0] = [$address, $latitude, $longitude];
                    infoWindowContent[0] = [$address];
                }
                
                var infoWindow = new google.maps.InfoWindow(), marker, i;
                
                for (i = 0; i < markers.length; i++) {
                    var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                    bounds.extend(position);
                    marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: markers[i][0],
                        icon: $pin_icon
                    });
                    if ($map_style == '1') {
                        
                        if (infoWindowContent[i][0].length > 1) {
                            infoWindow.setContent(
                                '<div class="casano-map-info">' +
                                '<h4 class="map-title">' + $title_maps + '</h4>' +
                                '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                                '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' +
                                '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                                '</div>'
                            );
                        }
                        
                        infoWindow.open(map, marker);
                        
                    }
                    if ($map_style == '2') {
                        google.maps.event.addListener(marker, 'click', (function (marker, i) {
                            return function () {
                                if (infoWindowContent[i][0].length > 1) {
                                    infoWindow.setContent(
                                        '<div class="casano-map-info">' +
                                        '<h4 class="map-title">' + $title_maps + '</h4>' +
                                        '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                                        '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' +
                                        '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                                        '</div>'
                                    );
                                }
                                
                                infoWindow.open(map, marker);
                            }
                        })(marker, i));
                    }
                    
                    map.fitBounds(bounds);
                }
                
                var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
                    this.setZoom($zoom);
                    google.maps.event.removeListener(boundsListener);
                });
            });
        }
        
        //EQUAL ELEM
        function better_equal_elems() {
            setTimeout(function () {
                $('.equal-container.better-height').each(function () {
                    var $this = $(this);
                    if ($this.find('.equal-elem').length) {
                        $this.find('.equal-elem').css({
                            'height': 'auto'
                        });
                        var elem_height = 0;
                        $this.find('.equal-elem').each(function () {
                            var this_elem_h = $(this).height();
                            if (elem_height < this_elem_h) {
                                elem_height = this_elem_h;
                            }
                        });
                        $this.find('.equal-elem').height(elem_height);
                    }
                });
            }, 1000);
        }
        
        function better_equal_shop() {
            setTimeout(function () {
                $('.drawer_sidebar').each(function () {
                    var $this = $(this);
                    if ($this.find('.drawer_sidebar_elem').length) {
                        $this.find('.drawer_sidebar_elem').css({
                            'height': 'auto'
                        });
                        var elem_height = 0;
                        $this.find('.drawer_sidebar_elem').each(function () {
                            var this_elem_h = $(this).height();
                            if (elem_height < this_elem_h) {
                                elem_height = this_elem_h;
                            }
                        });
                        $this.find('.drawer_sidebar_elem').height(elem_height);
                    }
                });
            }, 1000);
        }
        
        /* update wishlist count */
        function update_wishlist_count() {
            var casano_update_wishlist_count = function () {
                $.ajax({
                    beforeSend: function () {
                    
                    },
                    complete: function () {
                    
                    },
                    data: {
                        action: 'casano_update_wishlist_count'
                    },
                    success: function (data) {
                        //do something
                        $('.block-wishlist .count').text(data);
                    },
                    
                    url: yith_wcwl_l10n.ajax_url
                });
            };
            
            $('body').on('added_to_wishlist removed_from_wishlist', casano_update_wishlist_count);
        }
        
        /* Toggle filter */
        $(document).on('click', '.filter-toggle', function (e) {
            if ($('.prdctfltr_woocommerce_filter').length) {
                $('.prdctfltr_woocommerce_filter').trigger('click');
            }
            $(this).toggleClass('active');
            e.preventDefault();
        });
        $(document).on('click', '.filter-toggle-drawer-canvas', function (e) {
            $(this).toggleClass('active');
            if ($('.drawer_sidebar').length) {
                $('.drawer_sidebar').toggleClass('active');
                better_equal_shop();
            }
            if ($('.offcanvas_sidebar').length) {
                $('.offcanvas_sidebar').toggleClass('active');
            }
            e.preventDefault();
        });
        $(document).on('click', '.main-widget-overlay', function (e) {
            if ($('.offcanvas_sidebar').length) {
                $('.offcanvas_sidebar').toggleClass('active');
            }
        });
        /* ---------------------------------------------
         AJAX LOADMORE
         -----------------------------------------------*/
        
        var initAjaxLoad = function () {
            var button = $('.casano-ajax-load:not(.already-init)');
            
            button.each(function (i, val) {
                $(this).addClass('already-init');
                var _option = $(this).data('load-more');
                var _mode = $(this).data('mode');
                
                if (_option !== undefined) {
                    var page = _option.page,
                        container = _option.container,
                        layout = _option.layout,
                        isLoading = false,
                        anchor = $(val).find('a'),
                        next = $(anchor).attr('href'),
                        cur_page = 2;
                    
                    if (layout == 'loadmore') {
                        $(val).on('click', 'a', function (e) {
                            e.preventDefault();
                            cur_page = parseInt($(val).attr('data-cur_page'));
                            var total_page = parseInt($(val).attr('data-total_page'));
                            anchor = $(val).find('a');
                            next = $(anchor).attr('href');
                            if (total_page <= cur_page) {
                                anchor.text(casano_theme_frontend['text']['no_more_product']).addClass('disabled casano-loadmore-disabled');
                                return false;
                            }
                            
                            $(anchor).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                            
                            getData();
                        });
                    } else {
                        var animationFrame = function () {
                            cur_page = parseInt($(val).attr('data-cur_page'));
                            var total_page = parseInt($(val).attr('data-total_page'));
                            anchor = $(val).find('a');
                            next = $(anchor).attr('href');
                            if (total_page <= cur_page) {
                                anchor.text(casano_theme_frontend['text']['no_more_product']);
                                return false;
                            }
                            
                            var bottomOffset = $('.' + container).offset().top + $('.' + container).height() - $(window).scrollTop();
                            if (bottomOffset < window.innerHeight && bottomOffset > 0 && !isLoading) {
                                if (!next)
                                    return;
                                isLoading = true;
                                $(anchor).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                                
                                getData();
                            }
                        }
                        
                        var scrollHandler = function () {
                            requestAnimationFrame(animationFrame);
                        };
                        
                        $(window).scroll(scrollHandler);
                    }
                    
                    var getData = function () {
                        $.get(next + '', function (data) {
                            var content = $('.' + container, data).wrapInner('').html(),
                                newElement = $('.' + container, data).find('.product-item');
                            
                            $(content).imagesLoaded(function () {
                                next = $(anchor, data).attr('href');
                                $('.' + container).append(newElement);
                                casano_init_products_size();
                                casano_init_lazy_load();
                            });
                            casano_init_products_size();
                            casano_init_lazy_load();
                            $(anchor).text(casano_theme_frontend['text']['load_more']); // casano_theme_frontend
                            
                            if (page > cur_page) {
                                cur_page++;
                                if (ZIGG_Data_Js != undefined && ZIGG_Data_Js['permalink'] == 'plain') {
                                    var link = next.replace(/paged=+[0-9]+/gi, 'paged=' + cur_page);
                                } else {
                                    var link = next.replace(/page\/+[0-9]+\//gi, 'page/' + cur_page + '/');
                                }
                                
                                if (cur_page >= page) {
                                    $(anchor).text(casano_theme_frontend['text']['no_more_product']);
                                    $(anchor).addClass('disabled casano-loadmore-disabled');
                                }
                                
                                $(anchor).attr('href', link);
                            } else {
                                $(anchor).text(casano_theme_frontend['text']['no_more_product']);
                                $(anchor).addClass('disabled casano-loadmore-disabled');
                            }
                            isLoading = false;
                            
                            // cur_page++;
                            $(val).attr('data-cur_page', cur_page);
                        });
                    }
                }
            });
        }
        
        function casano_get_url_var(key, url) {
            var result = new RegExp(key + "=([^&]*)", "i").exec(url);
            return result && result[1] || "";
        }
        
        function casano_remove_product() {
            $(document).on('click', '.minicart-items .product-cart .product-remove a.remove', function (e) {
                var $this = $(this);
                var thisItem = $this.closest('.product-cart');
                var remove_url = $this.attr('href');
                var product_id = $this.attr('data-product_id');
                if (thisItem.is('.loading')) {
                    return false;
                }
                
                if ($.trim(remove_url) !== '' && $.trim(remove_url) !== '#') {
                    
                    thisItem.addClass('loading');
                    
                    var nonce = casano_get_url_var('_wpnonce', remove_url);
                    var cart_item_key = casano_get_url_var('remove_item', remove_url);
                    
                    var data = {
                        action: 'casano_remove_cart_item_via_ajax',
                        product_id: product_id,
                        cart_item_key: cart_item_key,
                        nonce: nonce
                    };
                    
                    $.post(casano_theme_frontend['ajaxurl'], data, function (response) {
                        
                        if (response['err'] != 'yes') {
                            $('.casano-minicart').html(response['mini_cart_html']);
                        }
                        thisItem.removeClass('loading');
                        
                    });
                    
                    e.preventDefault();
                }
                
                return false;
                
            });
            
        }
        
        // Single product: Title, Price And Stars Outside Sumary
        function casano_update_single_product_title_price_stars_outside_summary() {
            $('.single-product .summary.title-price-stars-outside-summary').each(function () {
                var $this = $(this);
                var thisProduct = $this.closest('.product');
                if (thisProduct.is('.product-mobile-layout')) {
                    return;
                }
                var stars_html = $this.find('.woocommerce-product-rating').html();
                var title_html = $this.find('.product_title').html();
                var price_html = $this.find('.price').html();
                var outside_html = '';
                if ($.trim(stars_html) != '') {
                    outside_html += '<div class="woocommerce-product-rating woocommerce-product-rating-outside">' + stars_html + '</div>';
                }
                if ($.trim(title_html) != '') {
                    outside_html += '<h2 class="product_title entry-title product_title-outside">' + title_html + '</h2>';
                }
                if ($.trim(price_html) != '') {
                    outside_html += '<p class="price price-outside">' + price_html + '</p>';
                }
                if ($.trim(outside_html) != '') {
                    outside_html = '<div class="outside-title-price-stars-wrap">' + outside_html + '</div>';
                }
                thisProduct.find('.outside-title-price-stars-wrap').remove();
                thisProduct.find('.main-content-product').append(outside_html);
            });
        }
        
        casano_update_single_product_title_price_stars_outside_summary();
        
        // Single product mobile more detail
        $(document).on('click', '.product-toggle-more-detail', function (e) {
            var thisSummary = $(this).closest('.summary');
            thisSummary.find('.product-mobile-more-detail-wrap').toggleClass('active').slideToggle();
            if (thisSummary.find('.product-mobile-more-detail-wrap').is('.active')) {
                $(this).addClass('active').text(casano_theme_frontend['text']['less_detail']);
            } else {
                $(this).removeClass('active').text(casano_theme_frontend['text']['more_detail']);
            }
            e.preventDefault();
        });
        
        // Single product mobile structure
        function casano_single_product_mobile_structure() {
            $('.product-mobile-layout').each(function (e) {
                var $this = $(this);
                var thisSummary = $this.find('.summary');
                if (thisSummary.is('.moved-some-elems')) {
                    return;
                }
                
                // Star rating
                if (!thisSummary.is('.moved-star-rating')) {
                    if (thisSummary.find('.woocommerce-product-rating').length) {
                        var star_rating_html = thisSummary.find('.woocommerce-product-rating').html();
                        thisSummary.find('.woocommerce-product-rating').remove();
                        thisSummary.find('.product_title').after('<div class="woocommerce-product-rating woocommerce-product-rating-clone">' + star_rating_html + '</div>');
                    }
                    thisSummary.addClass('moved-star-rating');
                }
                thisSummary.addClass('moved-some-elems');
            });
        }
        
        casano_single_product_mobile_structure();
        
        $(document).on('change', '.quantity-clone .input-qty', function (e) {
            var $this = $(this);
            var thisSummary = $this.closest('.summary');
            var this_val = $this.val();
            thisSummary.find('.cart .quantity .input-qty').val(this_val).trigger('change');
        });
        
        // Single product mobile add to cart fixed button
        $(document).on('click', '.add-to-cart-fixed-btn', function (e) {
            if ($('.product .summary button.single_add_to_cart_button').length) {
                $('.product .summary button.single_add_to_cart_button').trigger('click');
            }
            e.preventDefault();
        });
        // Single product mobile add to cart fixed button
        $(document).on('click', '.casano-single-add-to-cart-fixed-top', function (e) {
            if ($('.product .summary button.single_add_to_cart_button').length) {
                $('.product .summary button.single_add_to_cart_button').trigger('click');
            }
            e.preventDefault();
        });
        $(document).on('wc_variation_form', '.variations_form', function (e) {
            $(this).addClass('fami-active-wc_variation_form');
        });
        
        // Single product attributes
        function casano_variations_custom() {
            $('.product-item .variations_form:not(.moved-reset_variations)').each(function () {
                $(this).find('.reset_variations').appendTo($(this));
                $(this).addClass('moved-reset_variations');
            });
            
            $('.variations_form').find('.data-val').html('');
            $('.variations_form select, .fami_variations_form select').each(function () {
                var _this = $(this);
                _this.find('option').each(function () {
                    var _ID = $(this).parent().data('id'),
                        _data = $(this).data(_ID),
                        _value = $(this).attr('value'),
                        _name = $(this).data('name'),
                        _data_type = $(this).data('type'),
                        _itemclass = _data_type;
                    
                    if ($(this).is(':selected')) {
                        _itemclass += ' active';
                    }
                    if (_value !== '') {
                        if (_data_type == 'color' || _data_type == 'photo') {
                            _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" style="background: ' + _data + ';background-size: cover; background-repeat: no-repeat " data-value="' + _value + '"></a>');
                        } else {
                            _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" data-value="' + _value + '">' + _name + '</a>');
                        }
                    }
                });
            });
        }
        
        function casano_variations_custom_ajax() {
            if ($('.products .variations_form:not(.fami-active-wc_variation_form)').length) {
                $('.products .variations_form:not(.fami-active-wc_variation_form)').each(function () {
                    $(this).wc_variation_form();
                });
            }
            casano_variations_custom();
        }
        
        
        $(document).on('click', '.variations_form .change-value', function (e) {
            var _this = $(this),
                _change = _this.data('value');
            
            _this.parent().parent().children('select').val(_change).trigger('change');
            _this.addClass('active').siblings().removeClass('active');
            casano_single_product_mobile_structure();
            casano_update_single_product_title_price_stars_outside_summary();
            if (_this.closest('.product-item').length) {
                var $thisProduct = _this.closest('.product-item');
                $thisProduct.removeAttr('srcset').removeAttr('data-o_sizes').removeAttr('data-o_srcset').removeAttr('sizes');
            }
            e.preventDefault();
        });
        
        $(document).on('woocommerce_variation_has_changed wc_variation_form', function () {
            casano_variations_custom();
            $('.product-item').find('.images .fami-img').removeAttr('data-o_sizes').removeAttr('data-o_srcset').removeAttr('sizes');
        });
        
        function casano_fix_responsive_img_issue_product_var() {
            $('.product-item .variations_form:not(.fami-fixed-res-img)').each(function () {
                var $this = $(this);
                var product_variations = $this.attr('data-product_variations');
                
                product_variations = JSON.parse(product_variations);
                for (var i = 0; i < product_variations.length; i++) {
                    if (product_variations[i].image.hasOwnProperty('srcset')) {
                        product_variations[i].image.srcset = '';
                    }
                }
                product_variations = JSON.stringify(product_variations);
                $this.attr('data-product_variations', product_variations);
                $this.addClass('fami-fixed-res-img');
            });
        }
        
        casano_fix_responsive_img_issue_product_var();
        
        function casano_add_to_cart_single() {
            /* SINGLE ADD TO CART */
            $(document).on('click', '.product:not(.product-type-external) .single_add_to_cart_button', function (e) {
                
                e.preventDefault();
                var _this = $(this);
                var _product_id = _this.val();
                var _form = _this.closest('form');
                var _form_data = _form.serialize();
                
                if (_product_id != '') {
                    var _data = 'add-to-cart=' + _product_id + '&' + _form_data;
                } else {
                    var _data = _form_data;
                }
                if (_this.is('.disabled') || _this.is('.wc-variation-selection-needed')) {
                    return false;
                }
                $('body,html').toggleClass('minicart-canvas-open');
                $('.casano-minicart').addClass('is-adding-to-cart');
                var atcUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace('wc-ajax=%%endpoint%%', 'add-to-cart=' + _product_id + '&casano-ajax-add-to-cart=1');
                $.ajax({
                    type: 'POST',
                    url: atcUrl,
                    data: _data,
                    dataType: 'html',
                    cache: false,
                    headers: {'cache-control': 'no-cache'},
                    success: function () {
                        $(document.body).trigger('wc_fragment_refresh');
                        $(document).on('added_to_cart', function () {
                            $('.casano-minicart').removeClass('is-adding-to-cart');
                        });
                        $('.single_add_to_cart_button-clone').removeClass('loading').addClass('added');
                    }
                });
                
                
            });
            $(document).on('click', '.famibt-add-all-to-cart', function (e) {
                $('body,html').toggleClass('minicart-canvas-open');
                if ($('.casano-minicart').length) {
                    $('.casano-minicart').addClass('is-adding-to-cart');
                }
            });
            
        }
        
        if ($('.products-grid').hasClass('active')) {
            $('.main-content .products').addClass('grid-size');
        }
        
        /* Products size */
        $(document).on('click', '.products-sizes .products-size', function (e) {
            var $this = $(this);
            var product_size = parseInt($this.attr('data-products_num'));
            var thisParent = $this.closest('.products-sizes');
            var thisContainer = $this.closest('.main-container');
            var is_shortcode = thisParent.is('.products-sizes-shortcode');
            if (is_shortcode) {
                thisContainer = $this.closest('.prdctfltr_sc_products');
            }
            var productsList = thisContainer.find('.products');
            var product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6'; // 4 items
            
            // Remove all classes with prefix "products_list-size-"
            productsList.removeClass(function (index, class_name) {
                return (class_name.match(/\bproducts_list-size-\S+/g) || []).join(' '); // removes anything that starts with "products_list-size-"
            }).addClass('products_list-size-' + product_size);
            // Remove all classes with prefix "col-"
            productsList.find('.product-item').removeClass(function (index, class_name) {
                return (class_name.match(/\bcol-\S+/g) || []).join(' '); // removes anything that starts with "col-"
            });
            
            switch (product_size) {
                case 6:
                    product_item_classes = 'col-bg-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ts-6';
                    break;
                case 5:
                    product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-4';
                    break;
                case 4:
                    product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6';
                    break;
                case 3:
                    product_item_classes = 'col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-6';
                    break;
            }
            
            productsList.find('.product-item').addClass(product_item_classes);
            thisParent.find('.products-size').removeClass('active');
            $this.addClass('active');
            
            
            if ($this.hasClass('products-grid')) {
                productsList.addClass('grid-size');
            } else {
                productsList.removeClass('grid-size');
            }
            
            e.preventDefault();
        });
        
        function casano_init_products_size() {
            $('.products-sizes .products-size.active').each(function () {
                var $this = $(this);
                var product_size = parseInt($this.attr('data-products_num'));
                var thisParent = $this.closest('.products-sizes');
                var thisContainer = $this.closest('.main-container');
                var is_shortcode = thisParent.is('.products-sizes-shortcode');
                if (is_shortcode) {
                    thisContainer = $this.closest('.prdctfltr_sc_products');
                }
                var productsList = thisContainer.find('.products');
                var product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6'; // 4 items
                
                // Remove all classes with prefix "col-"
                productsList.find('.product-item').removeClass(function (index, class_name) {
                    return (class_name.match(/\bcol-\S+/g) || []).join(' '); // removes anything that starts with "col-"
                });
                
                switch (product_size) {
                    case 6:
                        product_item_classes = 'col-bg-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ts-6';
                        break;
                    case 5:
                        product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6';
                        break;
                    case 4:
                        product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6';
                        break;
                    case 3:
                        product_item_classes = 'col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-6';
                        break;
                }
                productsList.find('.product-item').addClass(product_item_classes);
            });
        }
        
        function casano_prdctfltr_custom_remove() {
            var remove_prdctfltr_topbar = $('.vc_desktop .prdctfltr_topbar');
            var prdcfltr_reset_clone = $('.prdctfltr_reset span');
            var remove_prdctfltr_cat = $('.prdctfltr_sc .prdctfltr_woocommerce_ordering .prdctfltr_filter[data-filter="product_cat"]');
            remove_prdctfltr_cat.appendTo('.prdctfltr_filter_title');
            remove_prdctfltr_topbar.clone().appendTo('.prdctfltr_wc .prdctfltr_filter_title');
            prdcfltr_reset_clone.addClass('reset-filter-product').clone().appendTo('.prdctfltr_wc .prdctfltr_topbar');
            $('.part-filter-wrap .prdctfltr_title_selected a').removeClass('prdctfltr_title_remove').addClass('prdctfltr_title_remove_clone');
            $('.vc_desktop .prdctfltr_topbar:not(:last-child)').remove();
            $(document).on('click', '.prdctfltr_title_remove_clone', function (e) {
                $('.prdctfltr_title_remove').trigger('click');
                $(this).parent().remove();
                return false;
            })
            $(document).on('click', '.reset-filter-product', function (e) {
                $('.prdctfltr_reset > label').trigger('click');
                $('.prdctfltr_wc .prdctfltr_topbar').remove();
                return false;
            })
        }
        $(document).on('click', '.login-reg-note', function (e) {
            $('.customer-form .nav-tabs li:not(.active)').trigger('click');
        });
        $(document).on('click', '.login-reg-note', function (e) {
            if ($('#login-popup .nav-tabs li:not(.active) a').length) {
                $('#login-popup .nav-tabs li:not(.active) a').trigger('click');
            }
        });
        /* ---------------------------------------------
         Scripts scroll
         --------------------------------------------- */
        
        $(document).on('click', 'a.backtotop', function (e) {
            $('html, body').animate({scrollTop: 0}, 800);
            e.preventDefault();
        });
        
        $(document).on('scroll', function () {
            if ($(window).scrollTop() > 200) {
                $('.backtotop').addClass('active');
            } else {
                $('.backtotop').removeClass('active');
            }
        });
        
        /* Search Instant */
        function casano_search_json(search_key, cat_slug, json_args) {
            var all_results = Array();
            $.each(json_args, function (i, v) {
                var regex = new RegExp(search_key, "i");
                if (v.post_title.search(new RegExp(regex)) != -1) {
                    if (cat_slug != '') {
                        var regex_cat_slug = new RegExp(cat_slug, "i");
                        if (v.cat_slugs.search(new RegExp(regex_cat_slug)) != -1) {
                            all_results.push(v);
                        }
                    } else {
                        all_results.push(v);
                    }
                }
            });
            
            return all_results;
        }
        
        var products_data_array = {};
        
        // Instant Search
        function casano_search_instant() {
            $('.header-search-content').addClass('show-content-search');
            $('.site-header').addClass('show-search');
            $('.site-header .search-field').focus();
            
            if ($.isEmptyObject(products_data_array) && !$('.instant-search-disabled').length) {
                var data = {
                    action: 'casano_instant_search_data',
                    security: casano_theme_frontend.security,
                };
                $.post(casano_theme_frontend.ajaxurl, data, function (response) {
                    $('.site-header .search-field').focus();
                    products_data_array = response['array'];
                    var $modal = $('.search-block'),
                        $form = $modal.find('form');
                    // var url = $form.attr('action') + '?' + $form.serialize();
                    if ($.trim(response['success']) == 'yes') {
                        $(document).on('keyup', '.instant-search .search-fields input[name="s"]', function (e) {
                            var $this = $(this);
                            var thisSeachForm = $this.closest('.instant-search');
                            if (thisSeachForm.is('.instant-search-disabled')) {
                                return false;
                            }
                            var searchWrap = $this.closest('.search-fields').find('.search-results-container .search-results-container-inner');
                            var search_key = $.trim($this.val());
                            var cat_slug = ''; // All cats
                            if ($('.header-search-box .instant-search input[name="product_cat"]:checked').length) {
                                cat_slug = $('.header-search-box .instant-search input[name="product_cat"]:checked').val();
                            }
                            searchWrap.find('.container-search').remove();
                            $(this).removeClass('search-has-results');
                            $('.search-form.instant-search').removeClass('search-form-open');
                            if (products_data_array && search_key != '') {
                                var search_results = casano_search_json(search_key, cat_slug, products_data_array);
                                if (search_results.length > 0) {
                                    $(this).addClass('search-has-results');
                                    $('.search-form.instant-search').addClass('search-form-open');
                                    searchWrap.html('<div class="container-search"><div class="search-results-wrap row auto-clear"></div></div>');
                                    var max_instant_search_results = parseInt(casano_theme_frontend['max_instant_search_results']);
                                    if (isNaN(max_instant_search_results) || max_instant_search_results <= 0) {
                                        max_instant_search_results = 100;
                                    }
                                    for (var i = 0; i < search_results.length && i < max_instant_search_results; i++) {
                                        searchWrap.find('.container-search .search-results-wrap').append(search_results[i]['post_html']);
                                    }
                                    if ($('.product-item-search').length > 0) {
                                        searchWrap.find('.container-search').append('<button type="submit" class="search-view">' + casano_theme_frontend['text']['view_all'] + '</button>');
                                    }
                                    $('.reset-instant-search-wrap').prepend('<span class="reset-instant-search flaticon-close"></span>');
                                    $('.reset-instant-search').on('click', function (e) {
                                        var searchWrap = $(this).closest('.search-fields');
                                        $('.search-form.instant-search').removeClass('search-form-open');
                                        searchWrap.find('.container-search').remove();
                                        $('.search-field').val('');
                                        $('.reset-instant-search').remove();
                                    });
                                    $('.reset-instant-search:not(:last-child)').remove();
                                } else {
                                    $('.search-form.instant-search').addClass('search-form-open');
                                    searchWrap.html('<div class="container-search"><div class="search-results-wrap no-products-found row auto-clear "><span class="no-products-found-text">' + casano_theme_frontend['text']['no_products_found'] + '</span></div></div>');
                                }
                                
                            } else {
                                $('.search-form.instant-search').removeClass('search-form-open');
                                $('.reset-instant-search').remove();
                            }
                            
                        });
                        $('.search-fields input[name="s"]').trigger('keyup');
                        $('.product-cats input[name="product_cat"]').on('click', function (e) {
                            $('.search-fields input[name="s"]').trigger('keyup');
                            $(this).parent().addClass('selected').siblings().removeClass('selected');
                            e.preventDefault();
                        });
                        
                        
                    }
                });
                
            }
        }
        
        function fami_main_header_sticky() {
            if ($('.menu-sticky-smart').length) {
                var mainHeader = $('.header-position');
                var top_spacing = 0;
                var curentScrollTop = 0;
                var admin_bar_h = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
                top_spacing += admin_bar_h;
                mainHeader.sticky({topSpacing: top_spacing});
                //Menu Sticky
                if ($(window).width() > 1024) {
                    $(window).on('scroll', function () {
                        var scroll = $(this).scrollTop(),
                            begenScroll = $('.header-wrap').outerHeight();
                        if (scroll > begenScroll) {
                            $('.menu-sticky-smart').addClass('header-setup-sticky')
                        } else {
                            $('.menu-sticky-smart').removeClass('header-setup-sticky')
                        }
                        if (scroll < curentScrollTop) {
                            $('.menu-sticky-smart').addClass('header-sticky')
                        } else if (scroll > curentScrollTop) {
                            $('.menu-sticky-smart').removeClass('header-sticky')
                        }
                        curentScrollTop = scroll;
                    });
                }
            }
            
        }
        
        function casano_popover_tooltip() {
            $('.product-item .tooltip-style-01 .button-loop-action a').each(function () {
                var $titler = $(this).text();
                $(this).tooltip({
                    title: $titler,
                    trigger: 'hover',
                    placement: 'top'
                });
            });
        }
        
        // Real Single Product mobile
        function single_product_mobile() {
            var $singleSummary = $('.single-product-mobile .entry-summary');
            var $variationsForm = $singleSummary.find('.variable_mobile');
            if ($('.single-product-mobile').length && $variationsForm.length) {
                var qty_html = '';
                var close_btn_html = '<a href="#" class="close-box-content flaticon-close"></a>';
                var add_to_cart_text = $variationsForm.find('.single_add_to_cart_button').text();
                if ($singleSummary.find('.quantity input.qty').length) {
                    var qty_text = $singleSummary.find('.quantity .qty-label').text();
                    var cur_qty = $singleSummary.find('input.qty').val();
                    qty_html = '<span class="qty-label">' + qty_text + '</span>' +
                        '<div class="control">' +
                        '     <a class="btn-number qtyminus quantity-minus" href="#">-</a>' +
                        '     <input type="text" data-step="1" min="1" max="" name="quantity" value="' + cur_qty + '" class="input-qty input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">' +
                        '     <a class="btn-number qtyplus quantity-plus" href="#" style="pointer-events: auto;">+</a>' +
                        '</div>';
                    qty_html = '<div class="quantity">' + qty_html + '</div>';
                }
                if ($singleSummary.find('.single_variation_wrap').length) {
                    $('.single_variation_wrap').prepend('' + qty_html + '');
                }
                
                $('.variable_mobile').append('<button type="button" class="single_add_to_cart_button-clone button alt">' + add_to_cart_text + '</button>' + close_btn_html + '');
            }
            
        }
        
        // Clone Logo header 1
        function clone_logo_header1_res() {
            if ($('.item-logo').length) {
                var logo_clone = $('header .item-logo span.logo').html();
                $('.header-action-res .logo a').append('' + logo_clone + '');
            }
        }
        
        /* ---------------------------------------------
         Scripts ready
         --------------------------------------------- */
        dropdown_menu('.main-header-content #menu-primary-menu');
        fami_main_header_sticky();
        casano_clone_main_menu();
        sticky_scrollup();
        casano_search_instant();
        $('.scrollbar-macosx').scrollbar();
        casano_add_to_cart_single();
        casano_remove_product();
        toggle_form();
        casano_search_offcanvas();
        casano_minicart_offcanvas();
        casano_instant_search_close();
        initAjaxLoad();
        casano_countdown();
        casano_woo_quantily();
        casano_tab_fade_effect();
        casano_google_maps();
        casano_resizeMegamenu();
        update_wishlist_count();
        casano_singleProduct_popup();
        sticky_detail_content();
        casano_init_popup();
        casano_popover_tooltip();
        single_product_mobile();
        clone_logo_header1_res();
        $(document).on('click', '.prdctfltr_filter:not(.prdctfltr_byprice) label', function (e) {
            casano_prdctfltr_custom_remove();
        });
        
        // Reset filter clone button click
        $(document).on('click', '.prdctfltr_reset-clone', function (e) {
            if ($('.prdctfltr_buttons .prdctfltr_reset > label').length) {
                $('.prdctfltr_buttons .prdctfltr_reset > label').trigger('click');
            }
            
            e.preventDefault();
            return false;
        });
        
        
        // VC fix full row (only for Casano)
        function casano_vc_full_width_row() {
            var $elements = $('[data-vc-full-width="true"]');
            $.each($elements, function (key, item) {
                var $el = $(this);
                $el.addClass("vc_hidden");
                var $el_full = $el.next(".vc_row-full-width");
                if ($el_full.length || ($el_full = $el.parent().next(".vc_row-full-width")), $el_full.length) {
                    var padding, paddingRight, el_margin_left = parseInt($el.css("margin-left"), 10),
                        el_margin_right = parseInt($el.css("margin-right"), 10),
                        offset = 0 - $el_full.offset().left - el_margin_left, width = $(window).width();
                    if ("rtl" === $el.css("direction") && (offset -= $el_full.width(), offset += width, offset += el_margin_left, offset += el_margin_right), $el.css({
                            position: "relative",
                            left: offset,
                            "box-sizing": "border-box",
                            width: width
                        }), !$el.data("vcStretchContent")) "rtl" === $el.css("direction") ? ((padding = offset) < 0 && (padding = 0), (paddingRight = offset) < 0 && (paddingRight = 0)) : ((padding = -1 * offset) < 0 && (padding = 0), (paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right) < 0 && (paddingRight = 0)), $el.css({
                        "padding-left": padding + "px",
                        "padding-right": paddingRight + "px"
                    });
                    $el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden"), $(document).trigger("vc-full-width-row-single", {
                        el: $el,
                        offset: offset,
                        marginLeft: el_margin_left,
                        marginRight: el_margin_right,
                        elFull: $el_full,
                        width: width
                    })
                }
            });
        }
        
        // Cat link filter on shop / product archive page
        $(document).on('click', '.product-cat-link', function (e) {
            var $this = $(this);
            var cat_slug = $this.attr('data-slug');
            if ($('.prdctfltr_ft_' + cat_slug).length) {
                $('.prdctfltr_ft_' + cat_slug).trigger('click');
                $('.product-cat-link').removeClass('current-product-cat');
                $this.addClass('current-product-cat');
                return false;
            }
        });
        
        $(document).on('click', '.prdctfltr_filter label[class^="prdctfltr_ft_"]', function () {
            var $this = $(this);
            var thisPrdctfltrFilter = $this.closest('.prdctfltr_filter');
            var slug_val = $this.find('input').val();
            var is_product_cat = thisPrdctfltrFilter.attr('data-filter') == 'product_cat';
            
            if (is_product_cat) {
                $('.product-cat-link').removeClass('current-product-cat');
                if ($('.product-cat-link[data-slug="' + slug_val + '"]').length) {
                    if ($this.is('.prdctfltr_active')) {
                        $('.product-cat-link[data-slug="' + slug_val + '"]').addClass('current-product-cat');
                    } else {
                        $('.product-cat-link[data-slug="' + slug_val + '"]').removeClass('current-product-cat');
                    }
                }
            }
        });
        
        function casano_add_class_active_to_list_cats_on_top_bar_filter() {
            if ($('.prdctfltr_filter[data-filter="product_cat"] .prdctfltr_active').length) {
                $('.panel-categories .product-cat-link').removeClass('current-product-cat');
                $('.prdctfltr_filter[data-filter="product_cat"] .prdctfltr_active').each(function () {
                    var cat_slug = $(this).find('input').val();
                    if ($.trim(cat_slug) != '') {
                        $('.panel-categories .product-cat-link[data-slug="' + cat_slug + '"]').addClass('current-product-cat');
                    }
                });
            } else {
                if ($('.prdctfltr_filter[data-filter="product_cat"]').length) {
                    $('.panel-categories .product-cat-link').removeClass('current-product-cat');
                }
            }
        }
        
        $(document).on('click', '.mobile-navigation', function (e) {
            $('body').toggleClass('box-mobile-menu-open');
        });
        
        $(document).on('click', '.box-mobile-menu .close-menu, .body-overlay,.box-mibile-overlay,.close-menu', function (e) {
            $('body').removeClass('box-mobile-menu-open real-mobile-show-menu');
            $('.hamburger').removeClass('is-active');
        });
        
        $(document).on('click', '.body-overlay,.enable-shop-page-mobile .prdctfltr_showing', function (e) {
            $('html,body').removeClass('wc-prdctfltr-active variable_mobile_show');
            $('.variable_mobile').removeClass('show-box-content');
            $('.prdctfltr_woocommerce_filter').trigger('click');
        });
        $(document).on('click', '.enable-shop-page-mobile .prdctfltr_showing', function (e) {
            $('html,body').toggleClass('wc-prdctfltr-active');
        });
        //Single tabs desc
        $(document).on("click", '.woocommerce-tabs-mobile .button-togole', function (e) {
            var $this = $(this);
            $this.parent().addClass('tab-show');
            $('html').addClass('body-hide');
            e.preventDefault();
        });
        
        $(document).on("click", '.close-tab', function (e) {
            var $this = $(this);
            $('.tabs-mobile-content').removeClass('tab-show');
            $('html').removeClass('body-hide');
            e.preventDefault();
        });
        
        //Single Sticky tabs desc
        $(document).on("click", '.button-togole-tab', function (e) {
            $('.sticky-tab-slide').find('> .content-tab-sticky-element').remove();
            $('body').toggleClass('open-tab-sticky');
            var $this = $(this);
            var thisTab = $this.closest('.tabs-sticky-content');
            var thisShow = thisTab.closest('.woocommerce-tabs-sticky');
            thisTab.find('> .content-tab-sticky-element').clone().removeAttr('id').appendTo(".sticky-tab-slide");
            return false;
            
        });
        $(document).on("click", '.vc_desktop body.single-product .close-tab, body.single-product.open-tab-sticky .body-overlay', function (e) {
            $('body').toggleClass('open-tab-sticky');
        });
        /*  Mobile Menu on real mobile (if header mobile is enabled) */
        $(document).on('click', '.mobile-hamburger-navigation ', function (e) {
            $(this).find('.hamburger').addClass('is-active');
            if ($(this).find('.hamburger').is('.is-active')) {
                $('body').addClass('real-mobile-show-menu box-mobile-menu-open');
            } else {
                $('body').removeClass('real-mobile-show-menu box-mobile-menu-open');
            }
            e.preventDefault();
        });
        
        /* Mobile Tabs on real mobile */
        $(document).on('click', '.box-tabs .box-tab-nav', function (e) {
            var $this = $(this);
            var thisTab = $this.closest('.box-tabs');
            var tab_id = $this.attr('href');
            
            if ($this.is('.active')) {
                return false;
            }
            
            thisTab.find('.box-tab-nav').removeClass('active');
            $this.addClass('active');
            
            thisTab.find('.box-tab-content').removeClass('active');
            thisTab.find(tab_id).addClass('active');
            
            e.preventDefault();
        });
        
        // Wish list on real menu mobile
        if ($('.box-mobile-menu .wish-list-mobile-menu-link-wrap').length) {
            if (!$('.box-mobile-menu').is('.moved-wish-list')) {
                var wish_list_html = $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').html();
                $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').remove();
                $('.box-mobile-menu .main-menu').append('<li class="menu-item-for-wish-list menu-item menu-item-type-custom menu-item-object-custom">' + wish_list_html + '</li>');
                $('.box-mobile-menu').addClass('moved-wish-list');
            }
        }
        
        // Lang real menu mobile
        if ($('.box-mobile-menu .header-lang-mobile').length) {
            if (!$('.box-mobile-menu').is('.moved-lang-mobile')) {
                var lang_mobile_html = $('.box-mobile-menu .header-lang-mobile').html();
                $('.box-mobile-menu .header-lang-mobile').remove();
                $('.box-mobile-menu .main-menu').append('' + lang_mobile_html + '');
                $('.box-mobile-menu').addClass('moved-lang-mobile');
            }
        }
        
        // Language dropdown
        $(document).on('click', '.language-toggle', function (e) {
            var $this = $(this);
            
            if ($('.wcml_currency_switcher .wcml-cs-submenu').length) {
                $('.wcml_currency_switcher .wcml-cs-submenu').css({
                    'visibility': 'hidden'
                });
            }
            
            $this.closest('.dropdown').toggleClass('open');
            
            return false;
            e.preventDefault();
        });
        
        var casano_close_lang_dropdown = function (event) {
            if ($('.switcher-language.dropdown.open').length) {
                $('.switcher-language.dropdown.open').removeClass('open');
            }
        };
        
        // Currency dropdown
        if ($('.wcml_currency_switcher.js-wcml-dropdown-click').length) {
            var wrapperSelector = '.js-wcml-dropdown-click';
            var submenuSelector = '.js-wcml-dropdown-click-submenu';
            var wrappers = document.querySelectorAll(wrapperSelector);
            for (var i = 0; i < wrappers.length; i++) {
                wrappers[i].addEventListener('click', casano_close_lang_dropdown);
            }
        }
        
        /* Loading wishlist */
        $('.add_to_wishlist').on('click', function () {
            $(this).addClass('loading');
        });
        
        // Submit when choose sort by
        $(document).on('change', 'form.fami-woocommerce-ordering select[name="orderby"]', function () {
            var $this = $(this);
            var thisForm = $this.closest('form');
            var order_val = $this.val();
            var trigger_submit = true;
            $('.prdctfltr_wc .prdctfltr_woocommerce_ordering').each(function () {
                if ($(this).closest('.prdctfltr_sc_products').length == 0) {
                    if ($(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val + ' input[type="checkbox"]').length) {
                        $(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val).click();
                        trigger_submit = false;
                        return false;
                    }
                }
            });
            
            if (trigger_submit) {
                thisForm.submit();
            }
        });
        
        $(document).on('click', '.prdctfltr_orderby .prdctfltr_checkboxes label', function () {
            var $this = $(this);
            var order_val = $this.find('input[type="checkbox"]').val();
            if ($('form.fami-woocommerce-ordering select[name="orderby"]').length) {
                $('form.fami-woocommerce-ordering select[name="orderby"]').val(order_val);
                var selected_index = $('form.fami-woocommerce-ordering select[name="orderby"]').prop('selectedIndex');
                var order_text = $('form.fami-woocommerce-ordering select[name="orderby"] option:selected').text();
                $('form.fami-woocommerce-ordering .chosen-results .active-result').removeClass('result-selected highlighted');
                $('form.fami-woocommerce-ordering .chosen-results .active-result[data-option-array-index="' + selected_index + '"]').addClass('result-selected highlighted');
                $('form.fami-woocommerce-ordering .chosen-single span').text(order_text);
                
            }
        });
        
        if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
            $('body').addClass('safari');
        }
        
        // Click add to cart clone button
        $(document).on('click', '.single_add_to_cart_button-clone', function (e) {
            $('.summary .variations_form .single_add_to_cart_button').trigger('click');
            $(this).addClass('loading').removeClass('added');
            $('body,html').toggleClass('minicart-canvas-open');
            e.preventDefault();
            return false;
        });
        
        $(document).on('click', '.toggle-variations-select-mobile', function (e) {
            var $this = $(this);
            $('html,body').toggleClass('variable_mobile_show');
            $this.parent().find('.variable_mobile').toggleClass('show-box-content');
            e.preventDefault();
            return false;
        });
        
        $(document).on('click', '.close-box-content', function (e) {
            $('html,body').removeClass('variable_mobile_show');
            $('.variable_mobile').removeClass('show-box-content');
            e.preventDefault();
            return false;
        });
        
        $('.single_add_to_cart_button-clone').removeClass('added');
        
        // Change clone qty
        $(document).on('change', '.single_variation_wrap > .quantity input.qty', function () {
            var this_val = $(this).val();
            $('.summary .variations_form .woocommerce-variation-add-to-cart input.qty').val(this_val).trigger('change');
        });
        
        if ($('.page-template-fullpage .section-slide').length > 0) {
            full_page();
        }
        
        // Reinit some important things after ajax
        $(document).ajaxComplete(function (event, xhr, settings) {
            if (settings.hasOwnProperty('data')) {
                casano_variations_custom_ajax();
                casano_init_lazy_load();
                casano_init_products_size();
            }
            if (xhr.hasOwnProperty('responseJSON')) {
                var responseJSON = xhr['responseJSON'];
                if (responseJSON.hasOwnProperty('title') && $('.header .page-title').length) {
                    $('.header .page-title').replaceWith(responseJSON['title']);
                    // $('.header .page-title').addClass('title-page');
                }
            }
            casano_fix_responsive_img_issue_product_var();
            initAjaxLoad();
            casano_single_product_mobile_structure();
            casano_update_single_product_title_price_stars_outside_summary();
            casano_init_carousel();
            casano_add_class_active_to_list_cats_on_top_bar_filter();
            casano_prdctfltr_custom_remove();
            $('.scrollbar-macosx').scrollbar();
            casano_remove_product();
            casano_popover_tooltip();
        });
        $(document).on('adding_to_cart', function () {
            $('body,html').toggleClass('minicart-canvas-open');
            if ($('.casano-minicart').length) {
                $('.casano-minicart').addClass('is-adding-to-cart');
            }
        });
        $(document).on('added_to_cart', function () {
            if ($('.casano-minicart').length) {
                $('.casano-minicart').removeClass('is-adding-to-cart');
            }
        });
        
        /* ---------------------------------------------
         Scripts resize
         --------------------------------------------- */
        
        $(window).on('resize', function () {
            if ($('.casano-instagramshopwrap .ziss-row').length) {
                casano_responsive_instagramshop();
            }
            $('.scrollbar-macosx').scrollbar();
            if ($('body.single-product').length) {
                thumbnail_product();
            }
            fami_main_header_sticky();
            better_equal_elems();
            better_equal_shop();
            casano_clone_main_menu();
            casano_resizeMegamenu();
            sticky_scrollup();
        });
        
        /* ---------------------------------------------
         Scripts load
         --------------------------------------------- */
        
        $(window).on('load', function () {
            if ($('.casano-isotope').length > 0) {
                casano_isotope();
            }
            casano_product_loadmore();
            if ($('.casano-instagramshopwrap .ziss-row').length) {
                casano_responsive_instagramshop();
            }
            better_equal_elems();
            better_equal_shop();
            $('.scrollbar-macosx').scrollbar();
            if ($('body.single-product').length) {
                thumbnail_product();
            }
            //Remove added Single Add to cart mobile
            $('.single_add_to_cart_button-clone').removeClass('added');
            casano_init_carousel();
            casano_center_nav();
            casano_single_product_mobile_structure();
            casano_update_single_product_title_price_stars_outside_summary();
            casano_sticky_details_product_zoom_img();
            casano_gallery_details_product_zoom_img();
            setTimeout(function () {
                casano_vc_full_width_row();
            }, 300);
        });
        
    });
});

