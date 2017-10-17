<?php

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() )
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}

#-----------------------------------------------------------------#

# Allow simpgas to export csv

#-----------------------------------------------------------------#

$simpgas = get_role( 'editor' );
$simpgas->add_cap( 'export' );    //Allows simpgas to export


add_shortcode('creocodamico', 'creo_codamico');

  function creo_codamico() {
  $amico=uniqid();
  return $amico;
  }


add_shortcode('codamico', 'get_codamico');

  function get_codamico() {
    if (isset($_GET['codamico']) && $_GET['codamico']!=='' && basename($_SERVER['HTTP_REFERER'])=='presentaunamico-step4')
    {
          $pre_codamico = '
      <h2 style="" class="vc_custom_heading">Ecco il tuo codice amico!</h2>
      <div class="wpb_text_column wpb_content_element ">
        <div class="wpb_wrapper">
          <h1 id="friendcode" style="color:#6b2f13;text-transform:uppercase;">
          ';
          $post_codamico = '
          </h1>
          <p>&nbsp;</p>
          <h4>
          <a class="nectar-button small regular accent-color  regular-button" style="visibility: visible; margin-left: 0;" href="#"
          data-color-override="false" data-hover-color-override="false" data-hover-text-color-override="#fff" onclick="copyToClipboard(\'#friendcode\')">
          <span>copialo</span></a>&nbsp; e comunicalo subito al tuo amico.
          </h4>
          <p>&nbsp;</p>
          <p>In ogni caso, a breve, sia tu che il tuo amico riceverete una mail di riepilogo riportante i dati da te inseriti ed il <b>codice amico</b>.</p>
          <p>Quando il tuo amico stipulerà un contratto luce/gas, di almeno 12 mesi, con il codice che gli hai fornito,
          ti verrà subito inviato il tuo <b>Buono Regalo Amazon.it</b> all\'indirizzo di posta elettronica che hai indicato durante la compilazione.</p>
          <p>&nbsp;</p>
          <p style="text-align:right;">Grazie per aver partecipato<br>
          <a href="/presentaunamico/regolamento-completo/" style="font-style: italic;" target="_blank">Vedi regolamento completo</a></p>
        </div>
      </div>
      ';
      return $pre_codamico.strtoupper($_GET['codamico']).$post_codamico;
    }
    else
    {
      return '<p>Per favore compila il modulo nella pagina precedente</p>';
    }
  }
?>
