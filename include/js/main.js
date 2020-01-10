$(function () {
	$(document).on('click', '.tarif-block--inner-link, .tarif-block--return-link', function () {
		var screenOpen = $(this).data('screen-open'),
			tarif = $(this).data('tarif'),
			tarifIn = $(this).data('tarif-in');
			
		$('.tarifs-container, .inner-screen, .tarif-block--third').removeClass('active');
		$('.tarifs-container[data-screen="'+screenOpen+'"]').addClass('active');
		if (!!tarif)
			$('.inner-screen[data-tarif="'+tarif+'"]').addClass('active');
		if (!!tarifIn)
			$('.tarif-block--third[data-tarif-in="'+tarifIn+'"]').addClass('active');
		
		up();
	});
});

function up () {
	$('html, body').animate({scrollTop: 0}, 500);
}