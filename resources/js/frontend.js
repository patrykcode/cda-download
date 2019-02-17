$(document).ready(function () {
    $('.btn-download').click(function () {
	init()
    });
    $('.download').click(function () {
	setTimeout(function () {
	    error(false);
	}, 200);

    });
});
function init(query) {

    $.post('/api/download', {_token: $('[name="_token"]').attr('content'), url: $('[name="url"]').val() + (query ? query : '')}, function (json) {
	if (json['error'] == undefined) {
	    success(json.video);
	    console.log(json.quality);
	    if (json.quality) {
		render(json.quality);
	    }
	} else {
	    error(true);
	}
	$('.modal').modal('show');
    });
}

function reload() {

    $('.modal-title').text('Przeładownuje...');

    $('.success').addClass('d-none');
    $('.error').addClass('d-none');
    $('.loader').removeClass('d-none');
    $('.modal-body .success img').attr('src', '');
    $('.download').attr('download', '');
    $('.download').addClass('d-none');
    $('.download').attr('href', '');
}

function render(data) {

    $('.quality').text('');
    for (var i in data) {
	var href = data[i].a.attribute.href[0];
	var quality = href.split('?');
	quality = quality[1] ? quality[1] : '';
	var li, a;
	li = document.createElement('li');
	a = document.createElement('a');
	a.onclick = function (e) {
	    e.preventDefault();
	    reload();
	    init('?' + this.getAttribute('qli'));
	}
	a.classList = 'btn btn-link';
	a.setAttribute('qli', quality);
	$(a).text(quality.replace('=',' '));
	$(li).html(a);
	$('.quality').append(li);
    }

}
function success(json) {
    var title = decodeURIComponent(json.video.title);
    $('.modal-title').text(title);
    $('.error').addClass('d-none');
    $('.loader').addClass('d-none');
    $('.success').removeClass('d-none');
    $('.modal-body .success img').attr('src', json.video.thumb);
    $('.download').attr('download', true);
    $('.download').removeClass('d-none');
    $('.download').attr('href', json.video.file);
}
function error(e) {
    var title = e ? 'Nie podano linku.' : 'Ups... Coś poszło nie tak :(';
    $('.modal-title').text(title);
    if (e) {
	$('.success').addClass('d-none');
	$('.error').removeClass('d-none');
	$('.loader').addClass('d-none');

    } else {
	$('[name="url"]').val('');
	$('.modal').modal('hide');
    }
    $('.modal-body .success img').attr('src', '');
    $('.download').attr('download', '');
    $('.download').addClass('d-none');
    $('.download').attr('href', '');
}


