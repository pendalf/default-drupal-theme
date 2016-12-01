jQuery(document).ready(function($) {
  
  
  // // Twitter share

  // var API_URL = "http://cdn.api.twitter.com/1/urls/count.json",
  //   TWEET_URL = "https://twitter.com/share";
     
  // $(".tweet").each(function() {
  //   var elem  = $(this),
  //   // Use current page URL as default link
  //   url       = encodeURIComponent(elem.attr("data-url") || document.location.href),
  //   // Use page title as default tweet message
  //   text      = elem.attr("data-text") || document.title,
  //   via       = elem.attr("data-via") || "",
  //   related   = encodeURIComponent(elem.attr("data-related")) || "",
  //   hashtags  = encodeURIComponent(elem.attr("data-hashtags")) || "";
  //   href      = TWEET_URL + "?original_referer=" + encodeURIComponent(document.location.href) + "&url=" + url + "&source=tweetbutton&text=" + text;

  //   if (via != '' && via != 'undefined') {
  //     href   += "&via=" + via;
  //   }
  //   if (related != '' && related != 'undefined') {
  //     href   += "&related=" + related;
  //   }
  //   if (hashtags != '' && hashtags != 'undefined') {
  //     href   += "&hashtags=" + hashtags;
  //   }
     
  //   // Set href to tweet page
  //   elem.click(function(e){
  //     var width  = 575,
  //     height = 400,
  //     left   = ($(window).width()  - width)  / 2,
  //     top    = ($(window).height() - height) / 2,
  //     //url    = this.href,
  //     opts   = 'status=1' +
  //              ',width='  + width  +
  //              ',height=' + height +
  //              ',top='    + top    +
  //              ',left='   + left;
  //     window.open(href, 'twitter', opts);
  //     e.preventDefault();      
  //   })
     
  //   // Get count and set it as the inner HTML of .count
  //   $.getJSON(API_URL + "?callback=?&url=" + url, function(data) {
  //       elem.find(".count").html(data.count);
  //   });
  // });

  // Facebook share

  // $('.fb-like').each(function(){

  //   var url = document.location.href,
  //      elem = $(this),
  //       url = encodeURIComponent(elem.attr("data-url") || document.location.href),
  //      text = elem.attr("data-text") || document.title; 

  //   $.getJSON('http://graph.facebook.com/' + url, function (json){
  //     //$('#facebookCount').html(json.shares);
  //     elem.html(json.shares);
  //   });

  //   href  = 'http://www.facebook.com/sharer.php?s=100';
  //   href += '&p[title]=' + text;
  //   //href += '&p[summary]=<?php echo $summary;?>';
  //   href += '&p[url]=' + url;
  //   //href += '&p[images][0]=<?php echo $image;?>';

  //    // Set href to facebook page
  //   elem.click(function(e){
  //     var width  = 575,
  //     height = 400,
  //     left   = ($(window).width()  - width)  / 2,
  //     top    = ($(window).height() - height) / 2,
  //     //url    = this.href,
  //     opts   = 'status=0' +
  //              ',toolbar=0' +
  //              ',width='  + width  +
  //              ',height=' + height +
  //              ',top='    + top    +
  //              ',left='   + left;
  //     window.open(href, 'sharer', opts);
  //     e.preventDefault();      
  //   })
  // })

  socOpts = {
    'def': {
      'opts': {
        'status': 0,
        'width' : 575,
        'height': 400
      }
    },
    // 'fb': {
    //   'api_url'  : 'http://graph.facebook.com/',
    //   'share_url': 'http://www.facebook.com/sharer.php?s=100',
    //   'sharer'   : 'sharer',
    //   'opts'     : {
    //     'toolbar': 0
    //   }
    // },
    // 'tw': {
    //   'api_url'  : 'https://api.twitter.com/1.1/search/tweets.json?q=',
    //   'share_url': 'https://twitter.com/intent/tweet',
    //   'sharer'   : 'twitter',
    //   'opts'     : {
    //     'status' : 1
    //   }
    // },
    // 'vk': {
    //   'api_url'  : 'http://vkontakte.ru/share.php?act=count&index=1&url=',
    //   'share_url': 'http://vkontakte.ru/share.php?',
    //   'sharer'   : 'vkontakte'
    // }
  }

  $('.socials-share-wrap').each(function(){
    var elem  = $(this);
    for(prop in socOpts) if (socOpts.hasOwnProperty(prop)) {
      if(elem.is('.' + prop)) {
        var    url = encodeURIComponent(elem.attr("data-url") || document.location.href),
          toApiUrl = url,
            socObj = socOpts[prop],
              text = encodeURIComponent(elem.attr("data-text") || document.title),
              href = socObj.share_url,
               via = elem.attr("data-via") || "",
           related = encodeURIComponent(elem.attr("data-related")) || "",
          hashtags = encodeURIComponent(elem.attr("data-hashtags")) || "";

        
        // Facebook share

        if(prop == 'fb') {
          href += '&p[title]=' + text;
          //href += '&p[summary]=<?php echo $summary;?>';
          href += '&p[url]=' + url;
          //href += '&p[images][0]=<?php echo $image;?>';

          $.getJSON(socObj.api_url + url, function (data){
            elem.find('a').html(data.shares);
            //alert(data.shares)
          });
        }

        
        // Twitter share

        if(prop == 'tw') {
          href += "?url=" + url + "&text=" + text;

          if (via != '' && via != 'undefined') {
            href   += "&via=" + via;
          }
          if (related != '' && related != 'undefined') {
            href   += "&related=" + related;
          }
          if (hashtags != '' && hashtags != 'undefined') {
            href   += "&hashtags=" + hashtags;
          }

          $.getJSON(socObj.api_url + url, function (data){
            elem.find('a').html(data.shares);
            //alert(data.shares)
          });
        }

        // Vkontakte share

        if(prop == 'vk') {

          href += 'url=' + url;
          href += '&title=' + text;

          $.getJSON(socObj.api_url + url + '&callback=?', function (response){});
        }


        

        elem.click(function(e){
          e.preventDefault();  

          var optsObj = objectsConcat(socOpts.def.opts, socObj.opts);
          optsObj.left = ($(window).width()  - optsObj.width)  / 2,
          optsObj.top  = ($(window).height() - optsObj.height) / 2,
          //url    = this.href,
          opts = '';
          i = 0;
          for(key in optsObj) if (optsObj.hasOwnProperty(key)) {
            //alert(optsObj[key]);
            if (i > 0) {
              opts += ',';
            }
            opts += key + '=' + optsObj[key];
            i++;
          }
          //alert(opts);
          socialWindow = window.open(href, socObj.sharer, opts);
          socialWindow.focus();
          interval = setInterval(function(){
            if (socialWindow.closed) {
              clearInterval(interval);
              $.getJSON(socObj.api_url + url, function (json){
                elem.find('a').html(json.shares);
              });
            }
          }, 500);

              
        })

      }
    }
  });

  $('.socials-share-wrap').each(function(){
    var elem = $(this);
    elem.soc = (' ' + elem.attr('class')).match(/ +([^-]+)-like/i)[1];;
    if (typeof(Share[elem.soc]) !== 'undefined') {

      elem.opts = {
        cleanUrl: document.location.href,
        purl:     encodeURIComponent(elem.attr("data-url") || document.location.href),
        ptitle:   encodeURIComponent(elem.attr("data-title") || document.title),
        pimg:     encodeURIComponent(elem.attr("data-img") || ""),
        text:     encodeURIComponent(elem.attr("data-text") || ""),
        via:      (elem.attr("data-via") || ""),
        related:  encodeURIComponent(elem.attr("data-related") || ""),
        hashtags: encodeURIComponent(elem.attr("data-hashtags") || ""),
      }

      Share[elem.soc].count(elem);

      elem.find('a').click(function(e){
        e.preventDefault(); 
        Share[elem.soc].popup(elem);
      });
    }
  });
 
});


( function($) {


  Share = {

    // VKontakte
    vk: {
      popup: function(el){
        opts = el.opts
        url  = 'http://vkontakte.ru/share.php?';
        url += 'url='            + opts.purl;
        url += '&title='         + opts.ptitle;
        if (opts.text != '' && typeof(opts.text) !== 'undefined') {
          url += '&description=' + opts.text;
        }
        if (opts.pimg != '' && typeof(opts.pimg) !== 'undefined') {
          url += '&image='       + opts.pimg;
        }
        url += '&noparse=true';

        Share.popup(url, '', el);
      },

      count: function(el){
        opts = el.opts
        jQuery.getJSON('http://vkontakte.ru/share.php?act=count&index=1&url=' + opts.purl + '&callback=?', function (response){});
      }
    },

    // Facebook
    fb: {
      popup: function(el){
        opts = el.opts
        url  = 'http://www.facebook.com/sharer.php?s=100';
        url += '&p[url]='            + opts.purl;
        url += '&p[title]='         + opts.ptitle;

        if (opts.text != '' && typeof(opts.text) !== 'undefined') {
          url += '&p[summary]=' + opts.text;
        }

        if (opts.pimg != '' && typeof(opts.pimg) !== 'undefined') {
          url += '&p[images][0]='       + opts.pimg;
        }

        opts = {
          'toolbar': 0
        }

        Share.popup(url, opts, el);
      },

      count: function(el){
        opts = el.opts
        jQuery.getJSON('http://graph.facebook.com/' + opts.purl, function (data){
          el.find('a').html(data.shares);
        });
      }
    },

    // Google plus
    gp: {
      popup: function(el){
        opts = el.opts
        url  = 'https://plus.google.com/share?';
        url += 'url=' + opts.purl;

        opts = {
          'toolbar': 0
        }

        Share.popup(url, opts, el);
      },

      count: function(el){
        opts = el.opts
        jQuery.post(
          Drupal.settings.basePath + 'nloader_ajax2',
          {
            url: opts.purl,
            soc: el.soc
          },
          function (response) {
            el.find('a').html(response);
          }
        );
      }
    },

    // Twitter
    tw: {
      popup: function(el){
        opts = el.opts
        url  = 'https://twitter.com/intent/tweet';
        url += "?url=" + opts.purl + "&text=" + opts.ptitle;

        if (opts.via != '' && opts.via !== 'undefined') {
          url   += "&via=" + opts.via;
        }
        if (opts.related != '' && opts.related !== 'undefined') {
          url   += "&related=" + opts.related;
        }
        if (opts.hashtags != '' && opts.hashtags !== 'undefined') {
          url   += "&hashtags=" + opts.hashtags;
        }

        opts = {
          'status' : 1
        }

        Share.popup(url, opts, el);
      },

      count: function(el){
        opts = el.opts
        // jQuery.getJSON('https://api.twitter.com/1.1/search/tweets.json?q=' + opts.purl, function (data){
        //   el.find('a').html(data.shares);
        // });
      }
    },

    // Odnoklassniki
    ok: {
      popup: function(el){
        opts = el.opts
        url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
        url += '&st.comments=' + opts.ptitle;
        url += '&st._surl='    + opts.purl;

        Share.popup(url, '', el);
      },

      count: function(el){
        opts = el.opts
        jQuery.post(
          Drupal.settings.basePath + 'nloader_ajax2',
          {
            url: opts.purl,
            soc: el.soc
          },
          function (response) {
            jQuery.globalEval(response);
          }
        );
      }
    },

    // Mail.ru
    mailru: {
      popup: function(el){
        opts = el.opts
        url  = 'http://connect.mail.ru/share?';
        url += 'url='            + opts.purl;
        url += '&title='         + opts.ptitle;
        if (opts.text != '' && typeof(opts.text) !== 'undefined') {
          url += '&description=' + opts.text;
        }
        if (opts.pimg != '' && typeof(opts.pimg) !== 'undefined') {
          url += '&imageurl='       + opts.pimg;
        }

        Share.popup(url, '', el);
      },

      count: function(el){
        opts = el.opts
        jQuery.post(
          Drupal.settings.basePath + 'nloader_ajax2',
          {
            url: opts.purl,
            soc: el.soc
          },
          function (response) {
            el.find('a').html(response);
          }
        );
      }
    },

    // open popup
    popup: function(url, opts, el){
      defOpts = {
        'status': 0,
        'width' : 575,
        'height': 400
      }
      if (typeof(opts) !== 'undefined') {
        optsObj = Share.Concat(defOpts, opts);
      }
      else {
        optsObj = defOpts;
      }
      optsObj.left = ($(window).width()  - optsObj.width)  / 2;
      optsObj.top  = ($(window).height() - optsObj.height) / 2;

      opts = '';
      i = 0;
      for(key in optsObj) if (optsObj.hasOwnProperty(key)) {
        //alert(optsObj[key]);
        if (i > 0) {
          opts += ',';
        }
        opts += key + '=' + optsObj[key];
        i++;
      }

      var socialWindow = window.open(url, '', opts);
      socialWindow.focus();
      if (typeof(el) !== 'undefined') {
        interval = setInterval(function(){
          if (socialWindow.closed) {
            clearInterval(interval);
            Share[el.soc].count(el);
          }
        }, 500);
      }
    },

    Concat: function (a,b) {
      if (typeof(b) === 'undefined' || b == '') {
        return a;
      }
      var c = {},
      key;
      for (key in a) {
        if (a.hasOwnProperty(key)) {
         c[key] = key in b ? b[key] : a[key];
        }
      }
      for (key in b) {
        if (b.hasOwnProperty(key)) {
         c[key] = key in a ? b[key] : b[key];
        }
      }
      return c;
    }
  }
} ) ( jQuery );


var VK = {
  Share: {
    count: function(value, count) {
      count = (count === 0) ? count + '' : count;
      jQuery('.vk-like a').html(count);
    }
  }
}
var ODKL = {
  updateCount: function(value, count){
    console.log(count);
    count = (count === 0) ? count + '' : count;
    jQuery('.ok-like a').html(count);
  }
}
