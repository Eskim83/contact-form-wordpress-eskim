<?php

/**
 * Plugin Name:       Contact Form Eskim
 * Plugin URI:        https://eskim.pl/piszemy-wtyczke-w-wordpress-do-obslugi-formularzy/
 * Description:       Na podstawie artykułu https://eskim.pl/piszemy-wtyczke-w-wordpress-do-obslugi-formularzy/
 * Version:           1.03
 * Requires at least: 5.2
 * Requires PHP:      7.1
 * Author:            Maciej Włodarczak
 * Author URI:        https://eskim.pl
 * License:           GPL v3 or later
 * Text Domain:       eskim_pl_contact_form
 * Domain Path:       /languages
 */
 
if ( !function_exists( 'add_action' ) ) {
	echo 'Cześć! Jestem tylko pluginem, nic więcej.';
	exit;
}

add_action('init', function() {
	wp_register_style ('eskim_bootstrap53', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css', );
});


if ( !function_exists('eskim_pl_contact_form') ) :
function eskim_pl_contact_form() {
	
	
	wp_enqueue_style ('eskim_bootstrap53');
	$return = '';
	
	//var_dump($_POST);
	
	if (isset ($_REQUEST['_wpnonce']) && wp_verify_nonce ($_REQUEST['_wpnonce'], 'eskim_contact_form')) {

		if (!isset($_POST['mach1']) && isset($_POST['mach2'])) {
			
			$data = [
			  'comment_post_ID'  => $_POST['postID'],
			  'comment_author' => $_POST['eskim_name'],
			  'comment_author_email' => $_POST['eskim_email'],
			  'comment_content' => $_POST['eskim_description']
			];

			$comment_id = wp_insert_comment ( wp_slash ($data) );
			
			$return = '<div class="alert alert-success" role="alert">Twoja wiadomość została wysłana!</div>';
			
		}  else {
		
			$return = '<div class="alert alert-danger" role="alert">Twoja wiadomość nie została wysłana! Spróbuj za chwilę.</div>';
		}
	}
	
	$return .= '
		<form method="POST" id="eskim_contact_form">

		  <div class="mb-3">
			<label for="eskim_name" class="form-label">Wpisz swoje imię, nazwisko lub nick</label>
			<input class="form-control" type="text" placeholder="Imię" name="eskim_name" maxlength=30 required>
		  </div>

		  <div class="mb-3">
			<label for="eskim_email" class="form-label">Podaj adres e-mail</label>
			<input class="form-control" type="email" placeholder="Adres e-mail" name="eskim_email" maxlength=30 required>
		  </div>

		  <div class="mb-3">
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" value="" name="mach1" checked>
			  <label class="form-check-label" for="mach1">
				Oczywiście, że jestem maszyną
			  </label>
			</div>
		  </div>

		  <div class="mb-3">
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" value="" name="mach2" checked>
			  <label class="form-check-label" for="mach2">
				Ani myślę być robotem
			  </label>
			</div>
		  </div>

		  <div class="mb-3">
			<div class="form-floating">

			  <label for="eskim_description">W jakiej sprawie się kontaktujesz?</label>
			  <textarea class="form-control" placeholder="Opis" name="eskim_description" maxlength=2000 style="height: 100px" required></textarea>

			</div>
		  </div>
		 
		  ' . wp_nonce_field ( 'eskim_contact_form' ) . '
		  <input type="hidden" name="postID" value="'.get_the_ID().'"></input>
		  <div class="mb-3">

			<input type="submit" value="Wyślij formularz"></input>

		  </div>

		</form>
			<?
		';

	return $return;
}

add_shortcode('eskim_pl_contact_form', 'eskim_pl_contact_form');
endif;

?>
