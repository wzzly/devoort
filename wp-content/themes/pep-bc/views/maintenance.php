<div class="maintenance">
	<?php if(!empty($logo)) { ?>
	<img src="<?php echo esc_url($logo);?>" alt="<?php echo get_bloginfo('name');?>" class="maintenance-logo">
	<?php } ?>
    <?php echo $content;?>
</div>



<style>
	@import url('https://fonts.googleapis.com/css2?family=Barlow:wght@400;800&display=swap');
	main {display: block;
    width: 100%;
    left: 0;
    top: 0px;
    background: rgba(111,111,110,0.9);
    border: 15px solid white;
    padding: 0px 0px 60px 0px;}
	html,body {margin:0;padding:0;height:100%;width:100%;}
	body {padding: 0;font-size:1.5em;margin: 0; font-family: 'Barlow', sans-serif;}
	.maintenance {text-align:center;max-width:740px;margin:0 auto;padding-top:10%;color:#fff;}
	p strong,
	span.large {font-weight:700;text-transform:uppercase;line-height:1.2;color: #fff;}
	p strong {font-size:2em;display:block;}
	span.large {font-size:2.5em;}
	a {color:#38d430;}
	
	.pep-copyright{display:none;}
	.maintenance-logo {max-width:90%;margin:0 auto 2em;}
	body {padding: 0;font-size:1.5em;margin: 0; font-family: 'Barlow', sans-serif;}
	body {background: url(https://betonheren.nl/wp-content/uploads/2020/08/Optimized-Betonheren_200724IMG_8865-min.jpg) no-repeat center center / cover #eee;}
	
	@media only screen and (max-width:650px) {
		p strong {font-size:1.5em;}
		body {padding: 0;font-size:1.1em;margin: 0;}
		span.large {font-size:1.8em;}
	}


<?php if($style=='pep') { ?>
	body {border: 15px solid #fff;background: url(https://pepbc.nl/wp-content/uploads/login-pep-dashboard.jpg) no-repeat center center / cover #eee;}
	.logo a {background: url("https://pepbc.nl/wp-content/uploads/2018/11/logopep-web.png") no-repeat 0px 0px / auto 100px;width:100px;height:100px;position:absolute;top:0;left:40px;display:block;text-indent:-9999em;}
	.maintenance,.maintenance a {color:#fff !important;}
	p strong,
	span.large {font-weight:700;text-transform:uppercase;line-height:1.2;}
<?php } ?>
</style>






