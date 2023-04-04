<?php 
/**
* dkpdf-footer.php
* This template is used to display content in PDF Footer
*
* Do not edit this template directly, 
* copy this template and paste in your theme inside a directory named dkpdf 
*/ 
?>

<?php 
	global $post;
	$pdf_footer_text = sanitize_option( 'dkpdf_pdf_footer_text', get_option( 'dkpdf_pdf_footer_text' ) );
	$pdf_footer_show_title = sanitize_option( 'dkpdf_pdf_footer_show_title', get_option( 'dkpdf_pdf_footer_show_title' ) );
	$pdf_footer_show_pagination = sanitize_option( 'dkpdf_pdf_footer_show_pagination', get_option( 'dkpdf_pdf_footer_show_pagination' ) );
?>

<?php
	// only enter here if any of the settings exists
	if( $pdf_footer_text || $pdf_footer_show_pagination ) { ?>

	  

	<?php }

?>

 <div style="width:100%; padding: 0px 20px; margin-bottom: 0px; clear: both; color: #fff; padding-top: 0px;">
	<div style="width:30%; float: left; padding-right: 3%; margin-left: 40px; margin-bottom: 0px; margin-top:0;">
    <h3 style="margin-bottom: 0px; text-transform: uppercase; font-size: 9px;">Samen uitblinken</h3>
    <p style="padding-top: 10px; font-size: 9px; margin-bottom: 0;">Onze advocaten en het ondersteunend personeel vormen een sterk, gedreven team. Samen werken we snel, accuraat en doelgericht om onze klanten maximaal te ondersteunen. <b>Samen uitblinken</b> is waar we voor staan. </p>
	</div>

	<div style="width:30%; padding-right: 3%; float: left; margin-bottom: 0px; margin-top:0;">
    <h3 style="margin-bottom: 0px;text-transform: uppercase; font-size: 9px;">Contactgegevens</h3>
    <p style="padding-top: 10px; font-size: 9px; margin-bottom: 0; padding-bottom: 0;">
    	<b>De Voort Advocaten | Mediators</b><br>
				Professor Cobbenhagenlaan 75<br>
				5037 DB Tilburg<br>
				Telefoon: <a style="color: #fff;" href="tel:+31134668888">013 466 88 88</a><br>
				Fax: <a style="color: #fff;" href="tel:+31134668866">013 466 88 66</a><br>
				E-mail: <a style="color: #fff;" href="mailto:advocaten@devoort.nl">advocaten@devoort.nl</a></p>
	</div>

	<div style="width:23%; padding-right: 0; float: left; margin-bottom: 0px; margin-top:0;">
    <h3 style="margin-bottom: 0px;text-transform: uppercase; font-size: 9px;">Postadres</h3>
    <p style="padding-top: 10px; font-size: 9px; margin-bottom: 0;">
    	Postbus 414<br>
		5000 AK Tilburg<br></p>
	</div>
</div>



