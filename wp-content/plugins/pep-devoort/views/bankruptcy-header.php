<div class="read-progress"></div>
<script>
'use strict';
const addProgressBar = () => {
    const element = document.createElement('div');
    element.id = 'progress-container';
    element.innerHTML = '<div id="progress-bar"></div>';

    document.getElementById("blog-header").appendChild(element);
};
jQuery(document).ready(function() {
// Add "in view" class to several blocks during scroll
  jQuery(".blog-header > *").each(function() {
    if( isOnScreen( jQuery( this) ) ) {
      jQuery(this).attr('data-view','in-view');
    }
  });

  jQuery(window).on('scroll', function() {
    var height=jQuery(window).height();
    var scroll = jQuery(window).scrollTop();
    jQuery(".blog-header  > *").each(function() {
      if( isOnScreen( jQuery( this) ) ) {
        jQuery(this).attr('data-view','in-view');
      } else {
        jQuery(this).delay(500).attr('data-view','outside-view');
      }
    });
  });

  jQuery(window).on('scroll', function() {
    var height=jQuery(window).height();
    var scroll = jQuery(window).scrollTop();
    jQuery(".blog-header > *").each(function() {
      if( isOnScreen( jQuery( this) ) ) {
        jQuery(this).attr('data-view','in-view');
      } else {
        jQuery(this).delay(500).attr('data-view','outside-view');
      }
    });
  });
});
const readingProgress = target => {

    const winTop = window.pageYOffset || document.documentElement.scrollTop;
    const targetBottom = target.offsetTop + target.scrollHeight;
    const windowBottom = winTop + window.outerHeight;
    const progress = 100 - (((targetBottom - windowBottom + window.outerHeight / 3) / (targetBottom - window.outerHeight + window.outerHeight / 3)) * 100);

    document.querySelector('#progress-bar').style.width = progress + '%';

    (progress > 100) ? document.querySelector('#progress-container').classList.add('ready') : document.querySelector('#progress-container').classList.remove('ready');
};

document.addEventListener('DOMContentLoaded', () => {
   const content = document.querySelector('#genesis-content');
   addProgressBar();
   readingProgress(content);
   window.addEventListener('scroll', () => {
        readingProgress(content);
   });
});
</script>