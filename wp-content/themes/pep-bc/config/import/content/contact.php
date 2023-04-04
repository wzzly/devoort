<?php
/**
 * PEP.
 *
 * Contact page content
 *
 * @package PEP
 * @author  Bart Pluijms
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */
$company_name=get_bloginfo('name');
$company_mail=get_bloginfo('admin_email');
$genesis_contact_content = <<<CONTENT
<!-- wp:columns -->
<div class="wp-block-columns has-2-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:pep/richcontact {"company_name":"$company_name","street":"Piushaven 6","postcode":"5017 AN","city":"Tilburg","phone":"013-5111151","email":"$company_mail"} /-->

<!-- wp:atomic-blocks/ab-container -->
<div class="wp-block-atomic-blocks-ab-container ab-block-container"><div class="ab-container-inside"><div class="ab-container-content" style="max-width:1600px"><!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph --></div></div></div>
<!-- /wp:atomic-blocks/ab-container --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:atomic-blocks/ab-container {"containerWidth":"full","containerMaxWidth":1140,"containerBackgroundColor":"#eeeeee"} -->
<div style="background-color:#eeeeee" class="wp-block-atomic-blocks-ab-container ab-block-container alignfull"><div class="ab-container-inside"><div class="ab-container-content" style="max-width:1140px"><!-- wp:heading -->
<h2>Direct contact met ons</h2>
<!-- /wp:heading -->

<!-- wp:gravityforms/form {"formId":"1","title":false} /--></div></div></div>
<!-- /wp:atomic-blocks/ab-container -->
CONTENT;

return $genesis_contact_content;