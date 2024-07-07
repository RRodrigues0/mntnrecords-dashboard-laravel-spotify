import './bootstrap';
import './jquery';
import './tilt';

window.addEventListener("load", function() {
	$('.tilt').tilt();
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
