/*!
 * jQuery UI Touch Punch 0.2.3
 *
 * Copyright 2011-2014, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
(function(e) {
	e.support.touch = "ontouchend" in document;
	if (!e.support.touch) {
		return;
	}
	var d = e.ui.mouse.prototype, h = d._mouseInit, i = d._mouseDestroy, f = d._mouseDown, j = d._mouseMove, b, k, c;
	function a(m, n) {
		if (m.originalEvent.touches.length > 1) {
			return;
		}
		b = m;
		var o = m.originalEvent.changedTouches[0], l = document
				.createEvent("MouseEvents");
		l.initMouseEvent(n, true, true, window, 1, o.screenX, o.screenY,
				o.clientX, o.clientY, false, false, false, false, 0, null);
		m.target.dispatchEvent(l);
	}
	function g(l) {
		return {
			x : l.originalEvent.changedTouches[0].pageX,
			y : l.originalEvent.changedTouches[0].pageY
		};
	}
	d._touchStart = function(m) {
		var l = this;
		if (k || !l._mouseCapture(m.originalEvent.changedTouches[0])) {
			return;
		}
		k = true;
		c = false;
		l._startPos = g(m);
		a(m, "mouseover");
		a(m, "mousemove");
		a(m, "mousedown");
	};
	d._touchMove = function(l) {
		if (!k) {
			return;
		}
		a(l, "mousemove");
	};
	d._touchEnd = function(m) {
		if (!k) {
			return;
		}
		if (c) {
			a(m, "mouseup");
			a(m, "mouseout");
			var l = g(m);
			if ((Math.abs(l.x - this._startPos.x) < 10)
					&& (Math.abs(l.y - this._startPos.y) < 10)) {
				a(m, "click");
			}
		}
		k = false;
	};
	d._mouseInit = function() {
		var l = this;
		l.element.bind({
			touchstart : e.proxy(l, "_touchStart"),
			touchmove : e.proxy(l, "_touchMove"),
			touchend : e.proxy(l, "_touchEnd")
		});
		h.call(l);
	};
	d._mouseDestroy = function() {
		var l = this;
		l.element.unbind({
			touchstart : e.proxy(l, "_touchStart"),
			touchmove : e.proxy(l, "_touchMove"),
			touchend : e.proxy(l, "_touchEnd")
		});
		i.call(l);
	};
	d._mouseDown = function(m) {
		var l = this;
		f.call(l, m);
		if (m.isDefaultPrevented() && b) {
			b.preventDefault();
			c = true;
		}
	};
	d._mouseMove = function(m) {
		var l = this;
		j.call(l, m);
		if (m.isDefaultPrevented() && b) {
			b.preventDefault();
		}
	};
})(jQuery);