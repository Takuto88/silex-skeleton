var messageRestClient = (function(){
	
	function initCallbacks(callbacks) {
		// Dummy callbacks
		var beforeSend = function(){};
		var success = function(){};
		var error = function(){};
		var complete = function(){};
		
		if(typeof callbacks !== 'undefined') {
			if(typeof callbacks.beforeSend !== 'undefinded') {
				beforeSend = callbacks.beforeSend;
			}
			
			if(typeof callbacks.success !== 'undefinded') {
				success = callbacks.success;
			}
			
			if(typeof callbacks.error !== 'undefinded') {
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
		data = initCallbacks(data);
		
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
		data = initCallbacks();
		
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
	
	function update(data) {
		data = initCallbacks(data);
		
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
		data = initCallbacks(data);
		
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
	$('#msg-btn').click(function(evt){
		evt.preventDefault();
		messageRestClient.index({
			beforeSend: function(){
				$('#msg-btn').attr('disabled', 'disabled');
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
			complete: function() {
				$('#msg-btn').removeAttr('disabled');
			}
		});
	});
});