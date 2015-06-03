( $(function() {
	$( "[data-role='navbar'" ).navbar();
	$( "[data-role='header'], [data-role='footer']").toolbar();
}) );

( $( document ).on( "pageshow", "[data-role='page']", function() {
	var current = $( this ).jqmData( "title" ).trim();
	$( "[data-role='header'] h1").text( current );
	$( "[data-role='navbar'] a.ui-btn-active" ).removeClass( "ui-btn-active" );
	$( "[data-role='navbar'] a").each( function() {
		if ( $(this).text().trim() == current ) {
			$(this).addClass( "ui-btn-active" );
		} 
	})
}) );
