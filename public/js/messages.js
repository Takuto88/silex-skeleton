var messageRestClient = (function(){
	
	function initCallbacks(callbacks) {
		// Dummy callbacks
		var beforeSend = function(){};
		var success = function(){};
		var error = function(){};
		var complete = function(){};
		
		if(typeof callbacks !== 'undefined') {
			if(typeof callbacks.beforeSend !== 'undefined') {
				beforeSend = callbacks.beforeSend;
			}
			
			if(typeof callbacks.success !== 'undefined') {
				success = callbacks.success;
			}
			
			if(typeof callbacks.error !== 'undefined') {
				error = callbacks.error;
			}
			
			if(typeof callbacks.complete !== 'undefined') {
				complete = callbacks.complete;
			}
		}
		
		return {
			beforeSend: beforeSend,
			success: success,
			error: error,
			complete: complete
		};
	}
	
	function index(callbacks) {
		callbacks = initCallbacks(callbacks);
		
		$.ajax({
			url: '/rest/api/1/messages',
			success: callbacks.success,
			beforeSend: callbacks.beforeSend,
			complete: callbacks.complete,
			error: callbacks.error
		});
	}
	
	function create(data) {
		callbacks = initCallbacks(data);
		
		if(typeof data.message === 'undefined') {
			data.message = ''
		}
		
		$.ajax({
			url: '/rest/api/1/messages',
			type: 'POST',
			data: {
				message: data.message
			},
			success: callbacks.success,
			beforeSend: callbacks.beforeSend,
			complete: callbacks.complete,
			error: callbacks.error
		});
	}
	
	function get(data) {
		callbacks = initCallbacks(data);
		
		if(typeof data.id === 'undefined') {
			data.id = 0
		}
		
		$.ajax({
			url: '/rest/api/1/messages/' + data.id,
			type: 'GET',
			success: callbacks.success,
			beforeSend: callbacks.beforeSend,
			complete: callbacks.complete,
			error: callbacks.error
		});
		
	}
	
	function update(data) {
		callbacks = initCallbacks(data);
		
		if(typeof data.message === 'undefined') {
			data.message = ''
		}
		
		if(typeof data.id === 'undefined') {
			data.id = 0
		}
		
		$.ajax({
			url: '/rest/api/1/messages/' + data.id,
			type: 'PUT',
			data: {
				message: data.message
			},
			success: callbacks.success,
			beforeSend: callbacks.beforeSend,
			complete: callbacks.complete,
			error: callbacks.error
		});
	}
	
	function deleteMessage(data) {
		callbacks = initCallbacks(data);
		
		if(typeof data.id === 'undefined') {
			data.id = 0
		}
		
		$.ajax({
			url: '/rest/api/1/messages/' + data.id,
			type: 'DELETE',
			success: callbacks.success,
			beforeSend: callbacks.beforeSend,
			complete: callbacks.complete,
			error: callbacks.error
		});
	}
	
	return {
		index: index,
		get: get,
		create: create,
		update: update,
		deleteMessage: deleteMessage
	};
	
})();

$(document).ready(function(){
	$('#index').click(function(evt){
		evt.preventDefault();
		messageRestClient.index({
			beforeSend: function(){
				$('#index').attr('disabled', 'disabled');
			},
			success: function(messages) {
				var html = '<div class="row">';
				for(var i = 0; i < messages.length; i++) {
					var message = messages[i];
					if(i % 3 === 0) {
						html += '</div><div class="row">';
					}
					html += '<div class="col-md-4"><h2> Message ID: ' + message.id + '</h2><p>' + message.message + '</p></div>';
				}
				html += '</div>';
				$('#messages').html(html);
			},
			error: function(error) {
				alert(error.responseJSON.message);
			},
			complete: function() {
				$('#index').removeAttr('disabled');
			}
		});
	});
	
	$('#get').click(function(evt){
		evt.preventDefault();
		messageRestClient.get({
			beforeSend: function(){
				$('#get').attr('disabled', 'disabled');
			},
			id: $('#get-id').val(),
			success: function(message) {
				var html = '<div class="row">';
				html += '<div class="col-md-4"><h2> Message ID: ' + message.id + '</h2><p>' + message.message + '</p></div>';
				html += '</div>';
				$('#message').html(html);
			},
			error: function(error) {
				alert(error.responseJSON.message);
			},
			complete: function() {
				$('#get').removeAttr('disabled');
			}
		});
	});
	
	$('#create').click(function(evt){
		evt.preventDefault();
		messageRestClient.create({
			beforeSend: function(){
				$('#create').attr('disabled', 'disabled');
				$('#created-alert').hide();
			},
			message: $('#create-msg').val(),
			success: function(message) {
				$('#created-alert').show();
			},
			error: function(error) {
				alert(error.responseJSON.message);
			},
			complete: function() {
				$('#create').removeAttr('disabled');
			}
		});
	});
	
});