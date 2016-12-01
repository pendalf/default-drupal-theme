jQuery(document).ready(function($) { 

  // Анимация поиска
  $('#block-search-form .form-submit').click(function(event){

    var searchBlock = $(this).closest('#block-search-form');

    if (!searchBlock.hasClass('block-search-form-hover')) {
      event.preventDefault();
      searchBlock.addClass('block-search-form-hover').removeClass('default-view-search');
    }
  })
  $('#block-search-form .form-item').append('<div class="close"></div>');
  $('#block-search-form .close').click(function(event){

    searchClose();    
  })

  $('body').click(function(e){

    if (!$(e.target).closest('#search-block-form').size()) {

      searchClose();
    }
  });

  function searchClose(){

    $('#block-search-form').removeClass('block-search-form-hover').addClass('default-view-search');
  }



  $('.node .field-name-quick-order-form h2.block-title').wrap('<div class="block__title"></div>');

  $('.forms_css [type="radio"], .forms_css [type="checkbox"]').forms();
  $('.form-radios .multichoice_row').click(function(){
    $(this).find('[type="radio"]:checked').trigger('change');
  });

  $('.nav-toggle').click(function(e){

    e.preventDefault();
    $('#drop_menu .region_inner').toggleClass('openned');
  });

  $('body').click(function(e){
    var el = $(e.target);
    if (
      $('.openned').size() 
      && !el.parents('.openned').size()
      && !el.hasClass('nav-toggle')
    ){
      $('#drop_menu .region_inner').removeClass('openned');
    }
    
  });
  $("#drop_menu span.close").click( function() {
    $("#drop_menu .region_inner").removeClass('openned');
  });

  $('#webform-client-form-67 .form-item, .block_118 .form-item').each(function(){

    $(this).find('label').show();
    $(this).find('input').wrap('<div class="form-item__inner"></div>').removeAttr('placeholder');
  });
  //$('#webform-client-form-67').closest('.field').find('.block-title').wrap('<div class="block__title"></div>')



  // Анимация боковой колонки
  if ($('.slidebar').size()) {

    $('.slidebar__close').click(function(e){

      e.preventDefault();
      $(this)
          .closest('.slidebar').removeClass('slidebar--openned')
          .closest('.drop_menu--openned').removeClass('drop_menu--openned');
    });
    $('.nav-toggle').click(function(e){

      e.preventDefault();
      $('.slidebar').addClass('slidebar--openned')
      $('#drop_menu').addClass('drop_menu--openned');
    });

    slidebarHeight()
    $(window).resize(function() {

      slidebarHeight()
    });

    $('.slidebar__content').each(function(){
      $(this).jScrollPane({

        scrollbarWidth:4, showArrows:false, contentWidth: '0px', mouseWheelSpeed: 50
      });
      var api = $(this).data('jsp');
      var throttleTimeout;
      $(window).bind(
        'resize scroll',
        function(){
          if (!throttleTimeout) {
            throttleTimeout = setTimeout(
              function(){
                api.reinitialise();
                throttleTimeout = null;
              }, 
              50
            );
          }
        }
      );
    });
  }

  function slidebarHeight(){

    var offsetTopNow = window.pageYOffset || document.documentElement.scrollTop;
    var slidebar = $('.slidebar');
    var slidebarView = $('.slidebar__content');
    var slidebarTop = slidebar.offset().top;
    var checks = slidebarView.position().top;
    var slidebarFooter = slidebar.css('padding-bottom').replace('px', '');
    if (slidebar.hasClass('content')) {

      slidebarTop = 0;
      offsetTopNow = 0;

      if ($('#admin-menu').size()) {
        slidebarTop = $('#admin-menu').outerHeight();
      }
    }
    slidebarView.height($(window).height() - slidebarTop - checks - slidebarFooter + offsetTopNow);
  }

  $('body').click(function(e){
    var el = $(e.target);
    if (
      $('.slidebar--openned').size() 
      && !el.parents('.slidebar--openned').size()
      && !el.hasClass('nav-toggle')
    ){
      $('.slidebar--openned').removeClass('slidebar--openned');
      $('.drop_menu--openned').removeClass('drop_menu--openned');
    }
    
  });

  // События при скролле

  var offsetTopNow = window.pageYOffset || document.documentElement.scrollTop;
  var aNameSize = 0;
  var oldaNameIndex = 0;
  var docHeigth = $('#super').outerHeight() * .8 - $(window).height();

  var scrolled = 0;
  menuHeight = $('#header').height();

  $(window).scroll(function() {

    var scrolled2 = window.pageYOffset || document.documentElement.scrollTop;

    // up
          
    scrolled = scrolled2;

    if (scrolled2 > 152) {
      $('#up').css({'top': 105, 'opacity': 1});
    }
    else {
      $('#up').css({'top': -64, 'opacity': 0});
    }
      
  });
  
  $('#up').click(function(){
    $('body,html').animate({
      scrollTop: 0
    }, 400);
  })


  // Манипуляции с меню при ресайзе

  var menuItem = $('#superfish-1 .menuparent:first').clone(true, true);

  menuItem
    .hide()
    .attr('id', 'menu-more-item-1')
    .removeClass('first')
    .addClass('middle')
    .children('span').html(Drupal.t('More'));
  menuItem
    .children('ul')
      .attr('id', 'more-menu-item-ul')
      .html('');

  $('#superfish-1').append(menuItem);

  headerResize($);


  windowResize();

  setTimeout(function(){

    windowResize();
  }, 100);

  $('.block-main-today .view-content').jScrollPane({scrollbarWidth:10, showArrows:false, contentWidth: '0px', mouseWheelSpeed: 50});
  //alert(1)

  $(window).on('resize', windowResize);

  function windowResize() {

    headerResize($);

    var width = 213 * $('html').css('fontSize').replace('px', '') / 16;

    if ($('.steps__item').size() && $('.steps__item').width() < width) {

      $('.steps__item').css('fontSize', $('.steps__item').width() / width * 18);
    }

    if ($('.node-region').size()) {

      var nodeWidth = $('.node-region').width();

      $('.node-region').attr('style', '').removeClass('node-region--small');
      $('.node-region').css('fontSize', nodeWidth / 791 * 17);
      if (nodeWidth < 500) {

        $('.node-region').addClass('node-region--small');
      }
    }

    // Подгонка по высоте элеметов из блока .block-plates

    if ($('.block-plates').size()) {

      $('.block-plates').each(function(){

        blockPlates($(this));
      });
    }


    // Главное за сегодня

    if ($('#block-views-3v-front-main-news-block-1').size() && $('#block-views-3v-front-main-news-block').size()) {

      setTimeout(function(){

        var mainToday = $('#block-views-3v-front-main-news-block');

        if (window.innerWidth > 640) {

          var mainNewsHeigth = $('#block-views-3v-front-main-news-block-1').outerHeight();

          mainToday.headHeught   = mainToday.find('.view-content').offset().top - mainToday.offset().top;
          mainToday.footerHeight = mainToday.find('.view-footer').outerHeight();
          mainToday.footerHeight += parseInt(mainToday.css('paddingBottom'));

          mainToday.find('.view-content').height(mainNewsHeigth - mainToday.headHeught - mainToday.footerHeight);
        }
        else {

          mainToday.find('.view-content').height('auto').width('auto')
            .find('.jspContainer').height('auto');
        }

        if ($('.jspScrollable').data('jsp')) {

          $('.jspScrollable').data('jsp').reinitialise();
        }
      }, 100);
    }

      
    if ($('#block-views-video-page-list-block-1').size()) {

      var galleryInterval = setInterval(function(){

        var mainImgHeight = $('#block-views-video-page-list-block-1 .views-field-field-main-image').height();
        if (mainImgHeight > 40) {
          $('#block-views-video-page-list-block-1 .slick__arrow').height(mainImgHeight);
          clearInterval(galleryInterval);
        }
      }, 10);

      
    }

  }
 
});

function blockPlates(plates) {

  $ = jQuery;

  var el,
      index      = 1,
      elHeight   = 0,
      maxEl      = 3,
      permission = true;

  if (window.innerWidth < 801) {
    maxEl = 2;
  };
  if (window.innerWidth < 401) {
    maxEl = 1;
  }


  plates.find('img').each(function(){


    if ($(this).height() < 1) {

      permission = false;
      setTimeout(function(){

        blockPlates(plates);
      }, 10);
      return false;
    }
  });

  if (permission) {

    plates.find('.block__content > .view > .view-content > .row >.row__item').each(function(){

      $(this).height('auto');
      
      if ($(this).height() > elHeight) {
        elHeight = $(this).height();
      }
      if (index == 1) {
        el = $(this);
      }
      else if (index == maxEl) {
        el = el.add($(this));
        el.height(elHeight);
        index = 0;
        elHeight = 0;
      }
      else {
        el = el.add($(this));       
      }

      index++;

      
    });
  }
}


function headerResize($, direct) {

  if (direct === undefined) {
    direct = 1;
  }
  var temp   = $('<div class="headerResize"></div>').height((60/16) + 'rem'),
      t      = {
        menu:    $('#superfish-1'),
        more:    $('#menu-more-item-1'),
        moreUl:  $('#more-menu-item-ul')
      };
  $('body').append(temp);
  var height = $('.headerResize').height();
  temp.remove();

  t.more.hide();
  t.menu
    .append(t.moreUl.children())
    .prepend(t.more);

  if (window.innerWidth > 640 && t.menu.height() > 60) {

    var items = $(t.menu.children().get().reverse());
    //console.log(items)
    t.more.show()
    items.each(function(){

      if (t.menu.height() > 60) {

        t.moreUl.prepend($(this));
      }

    });
  }
  t.menu.append(t.more);



  // if ($('header').outerHeight() > 90) {

  //   if (!$('.nav-toggle-wrap--visible').size()) {

  //     $('.nav-toggle-wrap').addClass('nav-toggle-wrap--visible');
  //     $('.menu-top-wrap').addClass('menu-top-wrap--minified');

  //     headerResize($, 2);
  //     return;
  //   }
  //   if ($('.section__inner > .socials').size()) {

  //     $('.menu-top').append($('.section__inner > .socials'));
  //     headerResize($, 2);
  //     return;
  //   }
  //   if ($('.section__inner > .delivery').size()) {

  //     $('.socials').before($('.section__inner > .delivery'));
  //     headerResize($, 2);
  //     return;
  //   }
  // }
  // else if (direct == 1) {

  //   if ($('.menu-top-wrap .delivery').size()) {

  //     $('header > .section__inner').append($('.menu-top-wrap .delivery'));
  //     headerResize($);
  //     return;
  //   }
  //   if ($('.menu-top-wrap .socials').size()) {

  //     $('.section__inner .delivery').before($('.menu-top-wrap .socials'));
  //     headerResize($);
  //     return;
  //   }
  //   if ($('.nav-toggle-wrap--visible').size()) {

  //     $('.nav-toggle-wrap').removeClass('nav-toggle-wrap--visible');
  //     $('.menu-top-wrap').removeClass('menu-top-wrap--minified');

  //     headerResize($);
  //     return;
  //   }
  // }
}

// function _headerResize($, items, el, t) {

//   if (t.menu.height() > 60) {

//     t.moreUl.prepend(el);
//     setTimeout(function(){

//       _headerResize($, items, el.next(), t);
//     }, 1);
//   }
//   else {
//     t.menu.append(t.more);
//   }

// }