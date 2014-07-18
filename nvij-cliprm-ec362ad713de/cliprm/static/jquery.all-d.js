/*!
* Bootstrap.js by @fat & @mdo
* Copyright 2012 Twitter, Inc.
* http://www.apache.org/licenses/LICENSE-2.0.txt
*/
!function(e){e(function(){"use strict";e.support.transition=function(){var e=function(){var e=document.createElement("bootstrap"),t={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"},n;for(n in t)if(e.style[n]!==undefined)return t[n]}();return e&&{end:e}}()})}(window.jQuery),!function(e){"use strict";var t='[data-dismiss="alert"]',n=function(n){e(n).on("click",t,this.close)};n.prototype.close=function(t){function s(){i.trigger("closed").remove()}var n=e(this),r=n.attr("data-target"),i;r||(r=n.attr("href"),r=r&&r.replace(/.*(?=#[^\s]*$)/,"")),i=e(r),t&&t.preventDefault(),i.length||(i=n.hasClass("alert")?n:n.parent()),i.trigger(t=e.Event("close"));if(t.isDefaultPrevented())return;i.removeClass("in"),e.support.transition&&i.hasClass("fade")?i.on(e.support.transition.end,s):s()},e.fn.alert=function(t){return this.each(function(){var r=e(this),i=r.data("alert");i||r.data("alert",i=new n(this)),typeof t=="string"&&i[t].call(r)})},e.fn.alert.Constructor=n,e(function(){e("body").on("click.alert.data-api",t,n.prototype.close)})}(window.jQuery),!function(e){"use strict";var t=function(t,n){this.$element=e(t),this.options=e.extend({},e.fn.button.defaults,n)};t.prototype.setState=function(e){var t="disabled",n=this.$element,r=n.data(),i=n.is("input")?"val":"html";e+="Text",r.resetText||n.data("resetText",n[i]()),n[i](r[e]||this.options[e]),setTimeout(function(){e=="loadingText"?n.addClass(t).attr(t,t):n.removeClass(t).removeAttr(t)},0)},t.prototype.toggle=function(){var e=this.$element.closest('[data-toggle="buttons-radio"]');e&&e.find(".active").removeClass("active"),this.$element.toggleClass("active")},e.fn.button=function(n){return this.each(function(){var r=e(this),i=r.data("button"),s=typeof n=="object"&&n;i||r.data("button",i=new t(this,s)),n=="toggle"?i.toggle():n&&i.setState(n)})},e.fn.button.defaults={loadingText:"loading..."},e.fn.button.Constructor=t,e(function(){e("body").on("click.button.data-api","[data-toggle^=button]",function(t){var n=e(t.target);n.hasClass("btn")||(n=n.closest(".btn")),n.button("toggle")})})}(window.jQuery),!function(e){"use strict";var t=function(t,n){this.$element=e(t),this.options=n,this.options.slide&&this.slide(this.options.slide),this.options.pause=="hover"&&this.$element.on("mouseenter",e.proxy(this.pause,this)).on("mouseleave",e.proxy(this.cycle,this))};t.prototype={cycle:function(t){return t||(this.paused=!1),this.options.interval&&!this.paused&&(this.interval=setInterval(e.proxy(this.next,this),this.options.interval)),this},to:function(t){var n=this.$element.find(".item.active"),r=n.parent().children(),i=r.index(n),s=this;if(t>r.length-1||t<0)return;return this.sliding?this.$element.one("slid",function(){s.to(t)}):i==t?this.pause().cycle():this.slide(t>i?"next":"prev",e(r[t]))},pause:function(t){return t||(this.paused=!0),this.$element.find(".next, .prev").length&&e.support.transition.end&&(this.$element.trigger(e.support.transition.end),this.cycle()),clearInterval(this.interval),this.interval=null,this},next:function(){if(this.sliding)return;return this.slide("next")},prev:function(){if(this.sliding)return;return this.slide("prev")},slide:function(t,n){var r=this.$element.find(".item.active"),i=n||r[t](),s=this.interval,o=t=="next"?"left":"right",u=t=="next"?"first":"last",a=this,f=e.Event("slide",{relatedTarget:i[0]});this.sliding=!0,s&&this.pause(),i=i.length?i:this.$element.find(".item")[u]();if(i.hasClass("active"))return;if(e.support.transition&&this.$element.hasClass("slide")){this.$element.trigger(f);if(f.isDefaultPrevented())return;i.addClass(t),i[0].offsetWidth,r.addClass(o),i.addClass(o),this.$element.one(e.support.transition.end,function(){i.removeClass([t,o].join(" ")).addClass("active"),r.removeClass(["active",o].join(" ")),a.sliding=!1,setTimeout(function(){a.$element.trigger("slid")},0)})}else{this.$element.trigger(f);if(f.isDefaultPrevented())return;r.removeClass("active"),i.addClass("active"),this.sliding=!1,this.$element.trigger("slid")}return s&&this.cycle(),this}},e.fn.carousel=function(n){return this.each(function(){var r=e(this),i=r.data("carousel"),s=e.extend({},e.fn.carousel.defaults,typeof n=="object"&&n),o=typeof n=="string"?n:s.slide;i||r.data("carousel",i=new t(this,s)),typeof n=="number"?i.to(n):o?i[o]():s.interval&&i.cycle()})},e.fn.carousel.defaults={interval:5e3,pause:"hover"},e.fn.carousel.Constructor=t,e(function(){e("body").on("click.carousel.data-api","[data-slide]",function(t){var n=e(this),r,i=e(n.attr("data-target")||(r=n.attr("href"))&&r.replace(/.*(?=#[^\s]+$)/,"")),s=!i.data("modal")&&e.extend({},i.data(),n.data());i.carousel(s),t.preventDefault()})})}(window.jQuery),!function(e){"use strict";var t=function(t,n){this.$element=e(t),this.options=e.extend({},e.fn.collapse.defaults,n),this.options.parent&&(this.$parent=e(this.options.parent)),this.options.toggle&&this.toggle()};t.prototype={constructor:t,dimension:function(){var e=this.$element.hasClass("width");return e?"width":"height"},show:function(){var t,n,r,i;if(this.transitioning)return;t=this.dimension(),n=e.camelCase(["scroll",t].join("-")),r=this.$parent&&this.$parent.find("> .accordion-group > .in");if(r&&r.length){i=r.data("collapse");if(i&&i.transitioning)return;r.collapse("hide"),i||r.data("collapse",null)}this.$element[t](0),this.transition("addClass",e.Event("show"),"shown"),e.support.transition&&this.$element[t](this.$element[0][n])},hide:function(){var t;if(this.transitioning)return;t=this.dimension(),this.reset(this.$element[t]()),this.transition("removeClass",e.Event("hide"),"hidden"),this.$element[t](0)},reset:function(e){var t=this.dimension();return this.$element.removeClass("collapse")[t](e||"auto")[0].offsetWidth,this.$element[e!==null?"addClass":"removeClass"]("collapse"),this},transition:function(t,n,r){var i=this,s=function(){n.type=="show"&&i.reset(),i.transitioning=0,i.$element.trigger(r)};this.$element.trigger(n);if(n.isDefaultPrevented())return;this.transitioning=1,this.$element[t]("in"),e.support.transition&&this.$element.hasClass("collapse")?this.$element.one(e.support.transition.end,s):s()},toggle:function(){this[this.$element.hasClass("in")?"hide":"show"]()}},e.fn.collapse=function(n){return this.each(function(){var r=e(this),i=r.data("collapse"),s=typeof n=="object"&&n;i||r.data("collapse",i=new t(this,s)),typeof n=="string"&&i[n]()})},e.fn.collapse.defaults={toggle:!0},e.fn.collapse.Constructor=t,e(function(){e("body").on("click.collapse.data-api","[data-toggle=collapse]",function(t){var n=e(this),r,i=n.attr("data-target")||t.preventDefault()||(r=n.attr("href"))&&r.replace(/.*(?=#[^\s]+$)/,""),s=e(i).data("collapse")?"toggle":n.data();n[e(i).hasClass("in")?"addClass":"removeClass"]("collapsed"),e(i).collapse(s)})})}(window.jQuery),!function(e){"use strict";function r(){i(e(t)).removeClass("open")}function i(t){var n=t.attr("data-target"),r;return n||(n=t.attr("href"),n=n&&/#/.test(n)&&n.replace(/.*(?=#[^\s]*$)/,"")),r=e(n),r.length||(r=t.parent()),r}var t="[data-toggle=dropdown]",n=function(t){var n=e(t).on("click.dropdown.data-api",this.toggle);e("html").on("click.dropdown.data-api",function(){n.parent().removeClass("open")})};n.prototype={constructor:n,toggle:function(t){var n=e(this),s,o;if(n.is(".disabled, :disabled"))return;return s=i(n),o=s.hasClass("open"),r(),o||(s.toggleClass("open"),n.focus()),!1},keydown:function(t){var n,r,s,o,u,a;if(!/(38|40|27)/.test(t.keyCode))return;n=e(this),t.preventDefault(),t.stopPropagation();if(n.is(".disabled, :disabled"))return;o=i(n),u=o.hasClass("open");if(!u||u&&t.keyCode==27)return n.click();r=e("[role=menu] li:not(.divider) a",o);if(!r.length)return;a=r.index(r.filter(":focus")),t.keyCode==38&&a>0&&a--,t.keyCode==40&&a<r.length-1&&a++,~a||(a=0),r.eq(a).focus()}},e.fn.dropdown=function(t){return this.each(function(){var r=e(this),i=r.data("dropdown");i||r.data("dropdown",i=new n(this)),typeof t=="string"&&i[t].call(r)})},e.fn.dropdown.Constructor=n,e(function(){e("html").on("click.dropdown.data-api touchstart.dropdown.data-api",r),e("body").on("click.dropdown touchstart.dropdown.data-api",".dropdown form",function(e){e.stopPropagation()}).on("click.dropdown.data-api touchstart.dropdown.data-api",t,n.prototype.toggle).on("keydown.dropdown.data-api touchstart.dropdown.data-api",t+", [role=menu]",n.prototype.keydown)})}(window.jQuery),!function(e){"use strict";var t=function(t,n){this.options=n,this.$element=e(t).delegate('[data-dismiss="modal"]',"click.dismiss.modal",e.proxy(this.hide,this)),this.options.remote&&this.$element.find(".modal-body").load(this.options.remote)};t.prototype={constructor:t,toggle:function(){return this[this.isShown?"hide":"show"]()},show:function(){var t=this,n=e.Event("show");this.$element.trigger(n);if(this.isShown||n.isDefaultPrevented())return;e("body").addClass("modal-open"),this.isShown=!0,this.escape(),this.backdrop(function(){var n=e.support.transition&&t.$element.hasClass("fade");t.$element.parent().length||t.$element.appendTo(document.body),t.$element.show(),n&&t.$element[0].offsetWidth,t.$element.addClass("in").attr("aria-hidden",!1).focus(),t.enforceFocus(),n?t.$element.one(e.support.transition.end,function(){t.$element.trigger("shown")}):t.$element.trigger("shown")})},hide:function(t){t&&t.preventDefault();var n=this;t=e.Event("hide"),this.$element.trigger(t);if(!this.isShown||t.isDefaultPrevented())return;this.isShown=!1,e("body").removeClass("modal-open"),this.escape(),e(document).off("focusin.modal"),this.$element.removeClass("in").attr("aria-hidden",!0),e.support.transition&&this.$element.hasClass("fade")?this.hideWithTransition():this.hideModal()},enforceFocus:function(){var t=this;e(document).on("focusin.modal",function(e){t.$element[0]!==e.target&&!t.$element.has(e.target).length&&t.$element.focus()})},escape:function(){var e=this;this.isShown&&this.options.keyboard?this.$element.on("keyup.dismiss.modal",function(t){t.which==27&&e.hide()}):this.isShown||this.$element.off("keyup.dismiss.modal")},hideWithTransition:function(){var t=this,n=setTimeout(function(){t.$element.off(e.support.transition.end),t.hideModal()},500);this.$element.one(e.support.transition.end,function(){clearTimeout(n),t.hideModal()})},hideModal:function(e){this.$element.hide().trigger("hidden"),this.backdrop()},removeBackdrop:function(){this.$backdrop.remove(),this.$backdrop=null},backdrop:function(t){var n=this,r=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var i=e.support.transition&&r;this.$backdrop=e('<div class="modal-backdrop '+r+'" />').appendTo(document.body),this.options.backdrop!="static"&&this.$backdrop.click(e.proxy(this.hide,this)),i&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),i?this.$backdrop.one(e.support.transition.end,t):t()}else!this.isShown&&this.$backdrop?(this.$backdrop.removeClass("in"),e.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one(e.support.transition.end,e.proxy(this.removeBackdrop,this)):this.removeBackdrop()):t&&t()}},e.fn.modal=function(n){return this.each(function(){var r=e(this),i=r.data("modal"),s=e.extend({},e.fn.modal.defaults,r.data(),typeof n=="object"&&n);i||r.data("modal",i=new t(this,s)),typeof n=="string"?i[n]():s.show&&i.show()})},e.fn.modal.defaults={backdrop:!0,keyboard:!0,show:!0},e.fn.modal.Constructor=t,e(function(){e("body").on("click.modal.data-api",'[data-toggle="modal"]',function(t){var n=e(this),r=n.attr("href"),i=e(n.attr("data-target")||r&&r.replace(/.*(?=#[^\s]+$)/,"")),s=i.data("modal")?"toggle":e.extend({remote:!/#/.test(r)&&r},i.data(),n.data());t.preventDefault(),i.modal(s).one("hide",function(){n.focus()})})})}(window.jQuery),!function(e){"use strict";var t=function(e,t){this.init("tooltip",e,t)};t.prototype={constructor:t,init:function(t,n,r){var i,s;this.type=t,this.$element=e(n),this.options=this.getOptions(r),this.enabled=!0,this.options.trigger=="click"?this.$element.on("click."+this.type,this.options.selector,e.proxy(this.toggle,this)):this.options.trigger!="manual"&&(i=this.options.trigger=="hover"?"mouseenter":"focus",s=this.options.trigger=="hover"?"mouseleave":"blur",this.$element.on(i+"."+this.type,this.options.selector,e.proxy(this.enter,this)),this.$element.on(s+"."+this.type,this.options.selector,e.proxy(this.leave,this))),this.options.selector?this._options=e.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},getOptions:function(t){return t=e.extend({},e.fn[this.type].defaults,t,this.$element.data()),t.delay&&typeof t.delay=="number"&&(t.delay={show:t.delay,hide:t.delay}),t},enter:function(t){var n=e(t.currentTarget)[this.type](this._options).data(this.type);if(!n.options.delay||!n.options.delay.show)return n.show();clearTimeout(this.timeout),n.hoverState="in",this.timeout=setTimeout(function(){n.hoverState=="in"&&n.show()},n.options.delay.show)},leave:function(t){var n=e(t.currentTarget)[this.type](this._options).data(this.type);this.timeout&&clearTimeout(this.timeout);if(!n.options.delay||!n.options.delay.hide)return n.hide();n.hoverState="out",this.timeout=setTimeout(function(){n.hoverState=="out"&&n.hide()},n.options.delay.hide)},show:function(){var e,t,n,r,i,s,o;if(this.hasContent()&&this.enabled){e=this.tip(),this.setContent(),this.options.animation&&e.addClass("fade"),s=typeof this.options.placement=="function"?this.options.placement.call(this,e[0],this.$element[0]):this.options.placement,t=/in/.test(s),e.remove().css({top:0,left:0,display:"block"}).appendTo(t?this.$element:document.body),n=this.getPosition(t),r=e[0].offsetWidth,i=e[0].offsetHeight;switch(t?s.split(" ")[1]:s){case"bottom":o={top:n.top+n.height,left:n.left+n.width/2-r/2};break;case"top":o={top:n.top-i,left:n.left+n.width/2-r/2};break;case"left":o={top:n.top+n.height/2-i/2,left:n.left-r};break;case"right":o={top:n.top+n.height/2-i/2,left:n.left+n.width}}e.css(o).addClass(s).addClass("in")}},setContent:function(){var e=this.tip(),t=this.getTitle();e.find(".tooltip-inner")[this.options.html?"html":"text"](t),e.removeClass("fade in top bottom left right")},hide:function(){function r(){var t=setTimeout(function(){n.off(e.support.transition.end).remove()},500);n.one(e.support.transition.end,function(){clearTimeout(t),n.remove()})}var t=this,n=this.tip();return n.removeClass("in"),e.support.transition&&this.$tip.hasClass("fade")?r():n.remove(),this},fixTitle:function(){var e=this.$element;(e.attr("title")||typeof e.attr("data-original-title")!="string")&&e.attr("data-original-title",e.attr("title")||"").removeAttr("title")},hasContent:function(){return this.getTitle()},getPosition:function(t){return e.extend({},t?{top:0,left:0}:this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight})},getTitle:function(){var e,t=this.$element,n=this.options;return e=t.attr("data-original-title")||(typeof n.title=="function"?n.title.call(t[0]):n.title),e},tip:function(){return this.$tip=this.$tip||e(this.options.template)},validate:function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},enable:function(){this.enabled=!0},disable:function(){this.enabled=!1},toggleEnabled:function(){this.enabled=!this.enabled},toggle:function(){this[this.tip().hasClass("in")?"hide":"show"]()},destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}},e.fn.tooltip=function(n){return this.each(function(){var r=e(this),i=r.data("tooltip"),s=typeof n=="object"&&n;i||r.data("tooltip",i=new t(this,s)),typeof n=="string"&&i[n]()})},e.fn.tooltip.Constructor=t,e.fn.tooltip.defaults={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover",title:"",delay:0,html:!0}}(window.jQuery),!function(e){"use strict";var t=function(e,t){this.init("popover",e,t)};t.prototype=e.extend({},e.fn.tooltip.Constructor.prototype,{constructor:t,setContent:function(){var e=this.tip(),t=this.getTitle(),n=this.getContent();e.find(".popover-title")[this.options.html?"html":"text"](t),e.find(".popover-content > *")[this.options.html?"html":"text"](n),e.removeClass("fade top bottom left right in")},hasContent:function(){return this.getTitle()||this.getContent()},getContent:function(){var e,t=this.$element,n=this.options;return e=t.attr("data-content")||(typeof n.content=="function"?n.content.call(t[0]):n.content),e},tip:function(){return this.$tip||(this.$tip=e(this.options.template)),this.$tip},destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}}),e.fn.popover=function(n){return this.each(function(){var r=e(this),i=r.data("popover"),s=typeof n=="object"&&n;i||r.data("popover",i=new t(this,s)),typeof n=="string"&&i[n]()})},e.fn.popover.Constructor=t,e.fn.popover.defaults=e.extend({},e.fn.tooltip.defaults,{placement:"right",trigger:"click",content:"",template:'<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'})}(window.jQuery),!function(e){"use strict";function t(t,n){var r=e.proxy(this.process,this),i=e(t).is("body")?e(window):e(t),s;this.options=e.extend({},e.fn.scrollspy.defaults,n),this.$scrollElement=i.on("scroll.scroll-spy.data-api",r),this.selector=(this.options.target||(s=e(t).attr("href"))&&s.replace(/.*(?=#[^\s]+$)/,"")||"")+" .nav li > a",this.$body=e("body"),this.refresh(),this.process()}t.prototype={constructor:t,refresh:function(){var t=this,n;this.offsets=e([]),this.targets=e([]),n=this.$body.find(this.selector).map(function(){var t=e(this),n=t.data("target")||t.attr("href"),r=/^#\w/.test(n)&&e(n);return r&&r.length&&[[r.position().top,n]]||null}).sort(function(e,t){return e[0]-t[0]}).each(function(){t.offsets.push(this[0]),t.targets.push(this[1])})},process:function(){var e=this.$scrollElement.scrollTop()+this.options.offset,t=this.$scrollElement[0].scrollHeight||this.$body[0].scrollHeight,n=t-this.$scrollElement.height(),r=this.offsets,i=this.targets,s=this.activeTarget,o;if(e>=n)return s!=(o=i.last()[0])&&this.activate(o);for(o=r.length;o--;)s!=i[o]&&e>=r[o]&&(!r[o+1]||e<=r[o+1])&&this.activate(i[o])},activate:function(t){var n,r;this.activeTarget=t,e(this.selector).parent(".active").removeClass("active"),r=this.selector+'[data-target="'+t+'"],'+this.selector+'[href="'+t+'"]',n=e(r).parent("li").addClass("active"),n.parent(".dropdown-menu").length&&(n=n.closest("li.dropdown").addClass("active")),n.trigger("activate")}},e.fn.scrollspy=function(n){return this.each(function(){var r=e(this),i=r.data("scrollspy"),s=typeof n=="object"&&n;i||r.data("scrollspy",i=new t(this,s)),typeof n=="string"&&i[n]()})},e.fn.scrollspy.Constructor=t,e.fn.scrollspy.defaults={offset:10},e(window).on("load",function(){e('[data-spy="scroll"]').each(function(){var t=e(this);t.scrollspy(t.data())})})}(window.jQuery),!function(e){"use strict";var t=function(t){this.element=e(t)};t.prototype={constructor:t,show:function(){var t=this.element,n=t.closest("ul:not(.dropdown-menu)"),r=t.attr("data-target"),i,s,o;r||(r=t.attr("href"),r=r&&r.replace(/.*(?=#[^\s]*$)/,""));if(t.parent("li").hasClass("active"))return;i=n.find(".active a").last()[0],o=e.Event("show",{relatedTarget:i}),t.trigger(o);if(o.isDefaultPrevented())return;s=e(r),this.activate(t.parent("li"),n),this.activate(s,s.parent(),function(){t.trigger({type:"shown",relatedTarget:i})})},activate:function(t,n,r){function o(){i.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"),t.addClass("active"),s?(t[0].offsetWidth,t.addClass("in")):t.removeClass("fade"),t.parent(".dropdown-menu")&&t.closest("li.dropdown").addClass("active"),r&&r()}var i=n.find("> .active"),s=r&&e.support.transition&&i.hasClass("fade");s?i.one(e.support.transition.end,o):o(),i.removeClass("in")}},e.fn.tab=function(n){return this.each(function(){var r=e(this),i=r.data("tab");i||r.data("tab",i=new t(this)),typeof n=="string"&&i[n]()})},e.fn.tab.Constructor=t,e(function(){e("body").on("click.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(t){t.preventDefault(),e(this).tab("show")})})}(window.jQuery),!function(e){"use strict";var t=function(t,n){this.$element=e(t),this.options=e.extend({},e.fn.typeahead.defaults,n),this.matcher=this.options.matcher||this.matcher,this.sorter=this.options.sorter||this.sorter,this.highlighter=this.options.highlighter||this.highlighter,this.updater=this.options.updater||this.updater,this.$menu=e(this.options.menu).appendTo("body"),this.source=this.options.source,this.shown=!1,this.listen()};t.prototype={constructor:t,select:function(){var e=this.$menu.find(".active").attr("data-value");return this.$element.val(this.updater(e)).change(),this.hide()},updater:function(e){return e},show:function(){var t=e.extend({},this.$element.offset(),{height:this.$element[0].offsetHeight});return this.$menu.css({top:t.top+t.height,left:t.left}),this.$menu.show(),this.shown=!0,this},hide:function(){return this.$menu.hide(),this.shown=!1,this},lookup:function(t){var n;return this.query=this.$element.val(),!this.query||this.query.length<this.options.minLength?this.shown?this.hide():this:(n=e.isFunction(this.source)?this.source(this.query,e.proxy(this.process,this)):this.source,n?this.process(n):this)},process:function(t){var n=this;return t=e.grep(t,function(e){return n.matcher(e)}),t=this.sorter(t),t.length?this.render(t.slice(0,this.options.items)).show():this.shown?this.hide():this},matcher:function(e){return~e.toLowerCase().indexOf(this.query.toLowerCase())},sorter:function(e){var t=[],n=[],r=[],i;while(i=e.shift())i.toLowerCase().indexOf(this.query.toLowerCase())?~i.indexOf(this.query)?n.push(i):r.push(i):t.push(i);return t.concat(n,r)},highlighter:function(e){var t=this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&");return e.replace(new RegExp("("+t+")","ig"),function(e,t){return"<strong>"+t+"</strong>"})},render:function(t){var n=this;return t=e(t).map(function(t,r){return t=e(n.options.item).attr("data-value",r),t.find("a").html(n.highlighter(r)),t[0]}),t.first().addClass("active"),this.$menu.html(t),this},next:function(t){var n=this.$menu.find(".active").removeClass("active"),r=n.next();r.length||(r=e(this.$menu.find("li")[0])),r.addClass("active")},prev:function(e){var t=this.$menu.find(".active").removeClass("active"),n=t.prev();n.length||(n=this.$menu.find("li").last()),n.addClass("active")},listen:function(){this.$element.on("blur",e.proxy(this.blur,this)).on("keypress",e.proxy(this.keypress,this)).on("keyup",e.proxy(this.keyup,this)),(e.browser.chrome||e.browser.webkit||e.browser.msie)&&this.$element.on("keydown",e.proxy(this.keydown,this)),this.$menu.on("click",e.proxy(this.click,this)).on("mouseenter","li",e.proxy(this.mouseenter,this))},move:function(e){if(!this.shown)return;switch(e.keyCode){case 9:case 13:case 27:e.preventDefault();break;case 38:e.preventDefault(),this.prev();break;case 40:e.preventDefault(),this.next()}e.stopPropagation()},keydown:function(t){this.suppressKeyPressRepeat=!~e.inArray(t.keyCode,[40,38,9,13,27]),this.move(t)},keypress:function(e){if(this.suppressKeyPressRepeat)return;this.move(e)},keyup:function(e){switch(e.keyCode){case 40:case 38:break;case 9:case 13:if(!this.shown)return;this.select();break;case 27:if(!this.shown)return;this.hide();break;default:this.lookup()}e.stopPropagation(),e.preventDefault()},blur:function(e){var t=this;setTimeout(function(){t.hide()},150)},click:function(e){e.stopPropagation(),e.preventDefault(),this.select()},mouseenter:function(t){this.$menu.find(".active").removeClass("active"),e(t.currentTarget).addClass("active")}},e.fn.typeahead=function(n){return this.each(function(){var r=e(this),i=r.data("typeahead"),s=typeof n=="object"&&n;i||r.data("typeahead",i=new t(this,s)),typeof n=="string"&&i[n]()})},e.fn.typeahead.defaults={source:[],items:8,menu:'<ul class="typeahead dropdown-menu"></ul>',item:'<li><a href="#"></a></li>',minLength:1},e.fn.typeahead.Constructor=t,e(function(){e("body").on("focus.typeahead.data-api",'[data-provide="typeahead"]',function(t){var n=e(this);if(n.data("typeahead"))return;t.preventDefault(),n.typeahead(n.data())})})}(window.jQuery),!function(e){"use strict";var t=function(t,n){this.options=e.extend({},e.fn.affix.defaults,n),this.$window=e(window).on("scroll.affix.data-api",e.proxy(this.checkPosition,this)),this.$element=e(t),this.checkPosition()};t.prototype.checkPosition=function(){if(!this.$element.is(":visible"))return;var t=e(document).height(),n=this.$window.scrollTop(),r=this.$element.offset(),i=this.options.offset,s=i.bottom,o=i.top,u="affix affix-top affix-bottom",a;typeof i!="object"&&(s=o=i),typeof o=="function"&&(o=i.top()),typeof s=="function"&&(s=i.bottom()),a=this.unpin!=null&&n+this.unpin<=r.top?!1:s!=null&&r.top+this.$element.height()>=t-s?"bottom":o!=null&&n<=o?"top":!1;if(this.affixed===a)return;this.affixed=a,this.unpin=a=="bottom"?r.top-n:null,this.$element.removeClass(u).addClass("affix"+(a?"-"+a:""))},e.fn.affix=function(n){return this.each(function(){var r=e(this),i=r.data("affix"),s=typeof n=="object"&&n;i||r.data("affix",i=new t(this,s)),typeof n=="string"&&i[n]()})},e.fn.affix.Constructor=t,e.fn.affix.defaults={offset:0},e(window).on("load",function(){e('[data-spy="affix"]').each(function(){var t=e(this),n=t.data();n.offset=n.offset||{},n.offsetBottom&&(n.offset.bottom=n.offsetBottom),n.offsetTop&&(n.offset.top=n.offsetTop),t.affix(n)})})}(window.jQuery);

$('#main-menu').load('/js/snippets/main-menu.html', function(){
	if($('body').hasClass('preview')){
		$('#main-menu-left').append('<li class="dropdown" id="preview-menu"></li>');
		$('#preview-menu').load('/js/snippets/preview-menu.html');
	}

	$('a[rel=tooltip]').tooltip({
		'placement': 'bottom'
	});
});

// JavaScript Document
$.fn.preloader = function(options){
	
	var defaults = {
		             delay:1,
					 preload_parent:"a",
					 check_timer:300,
					 ondone:function(){ },
					 oneachload:function(image){  },
					 fadein:50 
					};
	
	// variables declaration and precaching images and parent container
	 var options = $.extend(defaults, options),
	 root = $(this) , images = root.find("img").css({"visibility":"hidden",opacity:0}) ,  timer ,  counter = 0, i=0 , checkFlag = [] , delaySum = options.delay ,
	 
	 init = function(){
		
		timer = setInterval(function(){
			
			if(counter>=checkFlag.length)
			{
			clearInterval(timer);
			options.ondone();
			return;
			}
		
			for(i=0;i<images.length;i++)
			{
				if(images[i].complete==true)
				{
					if(checkFlag[i]==false)
					{
						checkFlag[i] = true;
						options.oneachload(images[i]);
						counter++;
						
						delaySum = delaySum + options.delay;
					}
					
					$(images[i]).css("visibility","visible").delay(delaySum).animate({opacity:1},options.fadein,
					function(){ $(this).parent().removeClass("preloader");   });
					
					
					
				 
				}
			}
		
			},options.check_timer) 
		 
		 
		 } ;
	
	images.each(function(){
		
		if($(this).parent(options.preload_parent).length==0)
		$(this).wrap("<a class='preloader' />");
		else
		$(this).parent().addClass("preloader");
		
		checkFlag[i++] = false;
		
		
		}); 
	images = $.makeArray(images); 
	
	
	var icon = jQuery("<img />",{
		
		id : 'loadingicon' ,
		src : ''
		
		}).hide().appendTo("body");
	
	
	
	timer = setInterval(function(){
		
		if(icon[0].complete==true)
		{
			clearInterval(timer);
			init();
			 icon.remove();
			return;
		}
		
		},100);
	
	};
/*
 * jQuery Impromptu
 * By: Trent Richardson [http://trentrichardson.com]
 * Version 4.0.1
 * Last Modified: 03/03/2012
 * 
 * Copyright 2012 Trent Richardson
 * Dual licensed under the MIT and GPL licenses.
 * http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 * http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 * 
 */
 (function($) {
	$.prompt = function(message, options) {
		$.prompt.options = $.extend({},$.prompt.defaults,options);
		$.prompt.currentPrefix = $.prompt.options.prefix;
		$.prompt.currentStateName = "";

		var $body	= $(document.body);
		var $window	= $(window);

		$.prompt.options.classes = $.trim($.prompt.options.classes);
		if($.prompt.options.classes != '')
			$.prompt.options.classes = ' '+ $.prompt.options.classes;

		//build the box and fade
		var msgbox = '<div class="'+ $.prompt.options.prefix +'box'+ $.prompt.options.classes +'">';
		if($.prompt.options.useiframe && ($('object, applet').length > 0)) {
			msgbox += '<iframe src="javascript:false;" style="display:block;position:absolute;z-index:-1;" class="'+ $.prompt.options.prefix +'fade"></iframe>';
		} else {
			msgbox +='<div class="'+ $.prompt.options.prefix +'fade"></div>';
		}
		msgbox += '<div class="'+ $.prompt.options.prefix +'"><div class="'+ $.prompt.options.prefix +'container"><div class="';
		msgbox += $.prompt.options.prefix +'close">X</div><div class="'+ $.prompt.options.prefix +'states"></div>';
		msgbox += '</div></div></div>';

		$.prompt.jqib = $(msgbox).appendTo($body);
		$.prompt.jqi	 = $.prompt.jqib.children('.'+ $.prompt.options.prefix);
		$.prompt.jqif	= $.prompt.jqib.children('.'+ $.prompt.options.prefix +'fade');

		//if a string was passed, convert to a single state
		if(message.constructor == String){
			message = {
				state0: {
					title: $.prompt.options.title,
					html: message,
				 	buttons: $.prompt.options.buttons,
				 	position: $.prompt.options.position,
				 	focus: $.prompt.options.focus,
				 	submit: $.prompt.options.submit
			 	}
		 	};
		}

		//build the states
		var states = "";

		$.each(message,function(statename,stateobj){
				stateobj = $.extend({},$.prompt.defaults.state,stateobj);
				message[statename] = stateobj;

				var arrow = "",
					title = "";
				if(stateobj.position.arrow !== null)
					arrow = '<div class="'+ $.prompt.options.prefix + 'arrow '+ $.prompt.options.prefix + 'arrow'+ stateobj.position.arrow +'"></div>';
				if(stateobj.title && stateobj.title !== '')
				    title = '<div class="'+ $.prompt.options.prefix + 'title">'+  stateobj.title +'</div>';
				states += '<div id="'+ $.prompt.options.prefix +'state_'+ statename +'" class="'+ $.prompt.options.prefix + 'state" style="display:none;">'+ arrow + title +'<div class="'+ $.prompt.options.prefix +'message">' + stateobj.html +'</div><div class="'+ $.prompt.options.prefix +'buttons">';

				$.each(stateobj.buttons, function(k, v){
					if(typeof v == 'object'){
						states += '<button ';

						if(typeof v.classes !== "undefined"){
							states += 'class="' + ($.isArray(v.classes)? v.classes.join(' ') : v.classes) + '" ';
						}

						states += ' name="' + $.prompt.options.prefix + '_' + statename + '_button' + v.title.replace(/[^a-z0-9]+/gi,'') + '" id="' + $.prompt.options.prefix + '_' + statename + '_button' + v.title.replace(/[^a-z0-9]+/gi,'') + '" value="' + v.value + '">' + v.title + '</button>';

					} else {
						states += '<button name="' + $.prompt.options.prefix + '_' + statename + '_button' + k + '" id="' + $.prompt.options.prefix +  '_' + statename + '_button' + k + '" value="' + v + '">' + k + '</button>';

					}
				});
				states += '</div></div>';
		});

		//insert the states...
		$.prompt.states = message;
		$.prompt.jqi.find('.'+ $.prompt.options.prefix +'states').html(states).children('.'+ $.prompt.options.prefix +'state:first').css('display','block');
		$.prompt.jqi.find('.'+ $.prompt.options.prefix +'buttons:empty').css('display','none');

		//Events
		$.each(message,function(statename,stateobj){
			var $state = $.prompt.jqi.find('#'+ $.prompt.options.prefix +'state_'+ statename);

			if($.prompt.currentStateName === "")
				$.prompt.currentStateName = statename;

			$state.bind('promptsubmit', stateobj.submit);

			$state.children('.'+ $.prompt.options.prefix +'buttons').children('button').click(function(){
				var $t = $(this),
					msg = $state.children('.'+ $.prompt.options.prefix +'message'),
					clicked = stateobj.buttons[$t.text()] || stateobj.buttons[$t.html()];
				if(clicked == undefined){
					for(var i in stateobj.buttons)
						if(stateobj.buttons[i].title == $t.text() || stateobj.buttons[i].title == $t.html())
							clicked = stateobj.buttons[i].value;
				}

				if(typeof clicked == 'object')
					clicked = clicked.value;
				var forminputs = {};

				//collect all form element values from all states
				$.each($.prompt.jqi.find('.'+ $.prompt.options.prefix +'states :input').serializeArray(),function(i,obj){
					if (forminputs[obj.name] === undefined) {
						forminputs[obj.name] = obj.value;
					} else if (typeof forminputs[obj.name] == Array || typeof forminputs[obj.name] == 'object') {
						forminputs[obj.name].push(obj.value);
					} else {
						forminputs[obj.name] = [forminputs[obj.name],obj.value];	
					} 
				});

				// trigger an event
				var promptsubmite = new $.Event('promptsubmit');
				promptsubmite.stateName = statename;
				promptsubmite.state = $state;
				$state.trigger(promptsubmite, [clicked, msg, forminputs]);

				if(!promptsubmite.isDefaultPrevented()){
					$.prompt.close(true, clicked,msg,forminputs);
				}
			});
			$state.find('.'+ $.prompt.options.prefix +'buttons button:eq('+ stateobj.focus +')').addClass($.prompt.options.prefix +'defaultbutton');

		});

		var fadeClicked = function(){
			if($.prompt.options.persistent){
				var offset = ($.prompt.options.top.toString().indexOf('%') >= 0? ($window.height()*(parseInt($.prompt.options.top,10)/100)) : parseInt($.prompt.options.top,10)),
					top = parseInt($.prompt.jqi.css('top').replace('px',''),10) - offset;

				//$window.scrollTop(top);
				$('html,body').animate({ scrollTop: top }, 'fast', function(){
					var i = 0;
					$.prompt.jqib.addClass($.prompt.options.prefix +'warning');
					var intervalid = setInterval(function(){
						$.prompt.jqib.toggleClass($.prompt.options.prefix +'warning');
						if(i++ > 1){
							clearInterval(intervalid);
							$.prompt.jqib.removeClass($.prompt.options.prefix +'warning');
						}
					}, 100);
				});
			}
			else {
				$.prompt.close(true);
			}
		};

		var keyPressEventHandler = function(e){
			var key = (window.event) ? event.keyCode : e.keyCode; // MSIE or Firefox?

			//escape key closes
			if(key==27) {
				fadeClicked();	
			}

			//constrain tabs
			if (key == 9){
				var $inputels = $(':input:enabled:visible',$.prompt.jqib);
				var fwd = !e.shiftKey && e.target == $inputels[$inputels.length-1];
				var back = e.shiftKey && e.target == $inputels[0];
				if (fwd || back) {
				setTimeout(function(){ 
					if (!$inputels)
						return;
					var el = $inputels[back===true ? $inputels.length-1 : 0];

					if (el)
						el.focus();						
				},10);
				return false;
				}
			}
		};

		$.prompt.position();
		$.prompt.style();

		$.prompt.jqif.click(fadeClicked);
		$window.resize({animate:false}, $.prompt.position);
		$.prompt.jqi.find('.'+ $.prompt.options.prefix +'close').click($.prompt.close);
		$.prompt.jqib.bind("keydown keypress",keyPressEventHandler)
					.bind('promptloaded', $.prompt.options.loaded)
					.bind('promptclose', $.prompt.options.close)
					.bind('promptstatechanging', $.prompt.options.statechanging)
					.bind('promptstatechanged', $.prompt.options.statechanged);

		//Show it
		$.prompt.jqif.fadeIn($.prompt.options.overlayspeed);
		$.prompt.jqi[$.prompt.options.show]($.prompt.options.promptspeed, function(){
			$.prompt.jqib.trigger('promptloaded');
		});
		$.prompt.jqi.find('.'+ $.prompt.options.prefix +'states .'+ $.prompt.options.prefix +'state:first .'+ $.prompt.options.prefix +'defaultbutton').focus();

		if($.prompt.options.timeout > 0)
			setTimeout($.prompt.close,$.prompt.options.timeout);

		return $.prompt.jqib;
	};

	$.prompt.defaults = {
		prefix:'jqi',
		classes: '',
		title: '',
		buttons: {
			Ok: true
		},
	 	loaded: function(e){},
	  	submit: function(e,v,m,f){},
	 	close: function(e,v,m,f){},
	 	statechanging: function(e, from, to){},
	 	statechanged: function(e, to){},
		opacity: 0.6,
	 	zIndex: 999,
	  	overlayspeed: 'slow',
	   	promptspeed: 'fast',
   		show: 'fadeIn',
	   	focus: 0,
	   	useiframe: false,
	 	top: '15%',
		position: { 
			container: null, 
			x: null, 
			y: null,
			arrow: null,
			width: null
		},
	  	persistent: true,
	  	timeout: 0,
	  	state: {
	  		title: '',
			html: '',
		 	buttons: {
		 		Ok: true
		 	},
		  	focus: 0,
		  	position: { 
		  		container: null, 
		  		x: null, 
		  		y: null,
		  		arrow: null,
		  		width: null
		  	},
		   	submit: function(e,v,m,f){
		   		return true;
		   }
	  	}
	};

	$.prompt.currentPrefix = $.prompt.defaults.prefix;

	$.prompt.currentStateName = "";

	$.prompt.setDefaults = function(o) {
		$.prompt.defaults = $.extend({}, $.prompt.defaults, o);
	};

	$.prompt.setStateDefaults = function(o) {
		$.prompt.defaults.state = $.extend({}, $.prompt.defaults.state, o);
	};

	$.prompt.position = function(e){
		var restoreFx = $.fx.off,
			$window = $(window),
			bodyHeight = $(document.body).outerHeight(true),
			windowHeight = $(window).height(),
			documentHeight = $(document).height(),
			height = bodyHeight > windowHeight ? bodyHeight : windowHeight,
			top = parseInt($window.scrollTop(),10) + ($.prompt.options.top.toString().indexOf('%') >= 0? 
					(windowHeight*(parseInt($.prompt.options.top,10)/100)) : parseInt($.prompt.options.top,10)),
			pos = $.prompt.states[$.prompt.currentStateName].position;

		// This fixes the whitespace at the bottom of the fade, but it is 
		// inconsistant and can cause an unneeded scrollbar, making the page jump
		//height = height > documentHeight? height : documentHeight;

		// when resizing the window turn off animation
		if(e !== undefined && e.data.animate === false)
			$.fx.off = true;

		$.prompt.jqib.css({
			position: "absolute",
			height: height,
			width: "100%",
			top: 0,
			left: 0,
			right: 0,
			bottom: 0
		});
		$.prompt.jqif.css({
			position: "absolute",
			height: height,
			width: "100%",
			top: 0,
			left: 0,
			right: 0,
			bottom: 0
		});

		// tour positioning
		if(pos && pos.container){
			var offset = $(pos.container).offset();

			if($.isPlainObject(offset) && offset.top !== undefined){
				$.prompt.jqi.css({
					position: "absolute"
				});
				$.prompt.jqi.animate({
					top: offset.top + pos.y,
					left: offset.left + pos.x,
					marginLeft: 0,
					width: (pos.width !== undefined)? pos.width : null
				});
				top = (offset.top + pos.y) - ($.prompt.options.top.toString().indexOf('%') >= 0? (windowHeight*(parseInt($.prompt.options.top,10)/100)) : parseInt($.prompt.options.top,10));
				$('html,body').animate({ scrollTop: top }, 'slow', 'swing', function(){});
			}
		}
		// custom state width animation
		else if(pos && pos.width){
			$.prompt.jqi.css({
					position: "absolute",
					left: '50%'
				});
			$.prompt.jqi.animate({
					top: pos.y || top,
					left: pos.x || '50%',
					marginLeft: ((pos.width/2)*-1),
					width: pos.width
				});
		}
		// standard prompt positioning
		else{
			$.prompt.jqi.css({
				position: "absolute",
				top: top,
				left: '50%',//$window.width()/2,
				marginLeft: (($.prompt.jqi.outerWidth()/2)*-1)
			});
		}

		// restore fx settings
		if(e !== undefined && e.data.animate === false)
			$.fx.off = restoreFx;
	};

	$.prompt.style = function(){
		$.prompt.jqif.css({
			zIndex: $.prompt.options.zIndex,
			display: "none",
			opacity: $.prompt.options.opacity
		});
		$.prompt.jqi.css({
			zIndex: $.prompt.options.zIndex+1,
			display: "none"
		});
		$.prompt.jqib.css({
			zIndex: $.prompt.options.zIndex
		});
	};

	$.prompt.getStateContent = function(state) {
		return $('#'+ $.prompt.currentPrefix +'state_'+ state);
	};

	$.prompt.getCurrentState = function() {
		return $('.'+ $.prompt.currentPrefix +'state:visible');
	};

	$.prompt.getCurrentStateName = function() {
		var stateid = $.prompt.getCurrentState().attr('id');

		return stateid.replace($.prompt.currentPrefix +'state_','');
	};

	$.prompt.goToState = function(state, callback) {
		var promptstatechanginge = new $.Event('promptstatechanging');
		$.prompt.jqib.trigger(promptstatechanginge, [$.prompt.currentStateName, state]);

		if(!promptstatechanginge.isDefaultPrevented()){
			$.prompt.currentStateName = state;

			$('.'+ $.prompt.currentPrefix +'state').slideUp('slow')
				.find('.'+ $.prompt.currentPrefix +'arrow').fadeOut();

			$('#'+ $.prompt.currentPrefix +'state_'+ state).slideDown('slow',function(){
				var $t = $(this);
				$t.find('.'+ $.prompt.currentPrefix +'defaultbutton').focus();
				$t.find('.'+ $.prompt.currentPrefix +'arrow').fadeIn('slow');

				if (typeof callback == 'function'){
					$.prompt.jqib.bind('promptstatechanged.tmp', callback);
				}
				$.prompt.jqib.trigger('promptstatechanged', [state]);
				if (typeof callback == 'function'){
					$.prompt.jqib.unbind('promptstatechanged.tmp');
				}
			});

			$.prompt.position();

		}
	};

	$.prompt.nextState = function(callback) {
		var $next = $('#'+ $.prompt.currentPrefix +'state_'+ $.prompt.currentStateName).next();
		$.prompt.goToState( $next.attr('id').replace($.prompt.currentPrefix +'state_',''), callback );
	};

	$.prompt.prevState = function(callback) {
		var $prev = $('#'+ $.prompt.currentPrefix +'state_'+ $.prompt.currentStateName).prev();
		$.prompt.goToState( $prev.attr('id').replace($.prompt.currentPrefix +'state_',''), callback );
	};

	$.prompt.close = function(callCallback, clicked, msg, formvals){
		$.prompt.jqib.fadeOut('fast',function(){

			if(callCallback) {
				$.prompt.jqib.trigger('promptclose', [clicked,msg,formvals]);
			}
			$.prompt.jqib.remove();

			$('window').unbind('resize',$.prompt.position);

		});
	};

	$.fn.extend({ 
		prompt: function(options){
			if(options == undefined) 
				options = {};
			if(options.withDataAndEvents == undefined)
				options.withDataAndEvents = false;

			$.prompt($(this).clone(options.withDataAndEvents).html(),options);
		}		
	});

})(jQuery);
/*jshint undef: true */
/*global jQuery: true */

/*
   --------------------------------
   Infinite Scroll
   --------------------------------
   + https://github.com/paulirish/infinite-scroll
   + version 2.0b2.120519
   + Copyright 2011/12 Paul Irish & Luke Shumard
   + Licensed under the MIT license

   + Documentation: http://infinite-scroll.com/
*/

(function(window,$,undefined){"use strict";$.infinitescroll=function infscr(options,callback,element){this.element=$(element);if(!this._create(options,callback)){this.failed=true}};$.infinitescroll.defaults={loading:{finished:undefined,finishedMsg:"<em>Congratulations, you've reached the end of the internet.</em>",img:"data:image/gif;base64,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",msg:null,msgText:"<em>Loading the next set of posts...</em>",selector:null,speed:'fast',start:undefined},state:{isDuringAjax:false,isInvalidPage:false,isDestroyed:false,isDone:false,isPaused:false,currPage:1},debug:false,behavior:undefined,binder:$(window),nextSelector:"div.navigation a:first",navSelector:"div.navigation",contentSelector:null,extraScrollPx:150,itemSelector:"div.post",animate:false,pathParse:undefined,dataType:'html',appendCallback:true,bufferPx:40,errorCallback:function(){},infid:0,pixelsFromNavToBottom:undefined,path:undefined,prefill:false,maxPage:undefined};$.infinitescroll.prototype={_binding:function infscr_binding(binding){var instance=this,opts=instance.options;opts.v='2.0b2.120520';if(!!opts.behavior&&this['_binding_'+opts.behavior]!==undefined){this['_binding_'+opts.behavior].call(this);return}if(binding!=='bind'&&binding!=='unbind'){this._debug('Binding value  '+binding+' not valid');return false}if(binding==='unbind'){(this.options.binder).unbind('smartscroll.infscr.'+instance.options.infid)}else{(this.options.binder)[binding]('smartscroll.infscr.'+instance.options.infid,function(){instance.scroll()})}this._debug('Binding',binding)},_create:function infscr_create(options,callback){var opts=$.extend(true,{},$.infinitescroll.defaults,options);this.options=opts;var $window=$(window);var instance=this;if(!instance._validate(options)){return false}var path=$(opts.nextSelector).attr('href');if(!path){this._debug('Navigation selector not found');return false}opts.path=opts.path||this._determinepath(path);opts.contentSelector=opts.contentSelector||this.element;opts.loading.selector=opts.loading.selector||opts.contentSelector;opts.loading.msg=opts.loading.msg||$('<div id="infscr-loading"><img alt="Loading..." src="'+opts.loading.img+'" /><div>'+opts.loading.msgText+'</div></div>');(new Image()).src=opts.loading.img;if(opts.pixelsFromNavToBottom===undefined){opts.pixelsFromNavToBottom=$(document).height()-$(opts.navSelector).offset().top}var self=this;opts.loading.start=opts.loading.start||function(){$(opts.navSelector).hide();opts.loading.msg.appendTo(opts.loading.selector).show(opts.loading.speed,$.proxy(function(){this.beginAjax(opts)},self))};opts.loading.finished=opts.loading.finished||function(){opts.loading.msg.fadeOut(opts.loading.speed)};opts.callback=function(instance,data,url){if(!!opts.behavior&&instance['_callback_'+opts.behavior]!==undefined){instance['_callback_'+opts.behavior].call($(opts.contentSelector)[0],data,url)}if(callback){callback.call($(opts.contentSelector)[0],data,opts,url)}if(opts.prefill){$window.bind("resize.infinite-scroll",instance._prefill)}};if(options.debug){if(Function.prototype.bind&&(typeof console==='object'||typeof console==='function')&&typeof console.log==="object"){["log","info","warn","error","assert","dir","clear","profile","profileEnd"].forEach(function(method){console[method]=this.call(console[method],console)},Function.prototype.bind)}}this._setup();if(opts.prefill){this._prefill()}return true},_prefill:function infscr_prefill(){var instance=this;var $document=$(document);var $window=$(window);function needsPrefill(){return($document.height()<=$window.height())}this._prefill=function(){if(needsPrefill()){instance.scroll()}$window.bind("resize.infinite-scroll",function(){if(needsPrefill()){$window.unbind("resize.infinite-scroll");instance.scroll()}})};this._prefill()},_debug:function infscr_debug(){if(true!==this.options.debug){return}if(typeof console!=='undefined'&&typeof console.log==='function'){if((Array.prototype.slice.call(arguments)).length===1&&typeof Array.prototype.slice.call(arguments)[0]==='string'){console.log((Array.prototype.slice.call(arguments)).toString())}else{console.log(Array.prototype.slice.call(arguments))}}else if(!Function.prototype.bind&&typeof console!=='undefined'&&typeof console.log==='object'){Function.prototype.call.call(console.log,console,Array.prototype.slice.call(arguments))}},_determinepath:function infscr_determinepath(path){var opts=this.options;if(!!opts.behavior&&this['_determinepath_'+opts.behavior]!==undefined){return this['_determinepath_'+opts.behavior].call(this,path)}if(!!opts.pathParse){this._debug('pathParse manual');return opts.pathParse(path,this.options.state.currPage+1)}else if(path.match(/^(.*?)\b2\b(.*?$)/)){path=path.match(/^(.*?)\b2\b(.*?$)/).slice(1)}else if(path.match(/^(.*?)2(.*?$)/)){if(path.match(/^(.*?page=)2(\/.*|$)/)){path=path.match(/^(.*?page=)2(\/.*|$)/).slice(1);return path}path=path.match(/^(.*?)2(.*?$)/).slice(1)}else{if(path.match(/^(.*?page=)1(\/.*|$)/)){path=path.match(/^(.*?page=)1(\/.*|$)/).slice(1);return path}else{this._debug('Sorry, we couldn\'t parse your Next (Previous Posts) URL. Verify your the css selector points to the correct A tag. If you still get this error: yell, scream, and kindly ask for help at infinite-scroll.com.');opts.state.isInvalidPage=true}}this._debug('determinePath',path);return path},_error:function infscr_error(xhr){var opts=this.options;if(!!opts.behavior&&this['_error_'+opts.behavior]!==undefined){this['_error_'+opts.behavior].call(this,xhr);return}if(xhr!=='destroy'&&xhr!=='end'){xhr='unknown'}this._debug('Error',xhr);if(xhr==='end'){this._showdonemsg()}opts.state.isDone=true;opts.state.currPage=1;opts.state.isPaused=false;this._binding('unbind')},_loadcallback:function infscr_loadcallback(box,data,url){var opts=this.options,callback=this.options.callback,result=(opts.state.isDone)?'done':(!opts.appendCallback)?'no-append':'append',frag;if(!!opts.behavior&&this['_loadcallback_'+opts.behavior]!==undefined){this['_loadcallback_'+opts.behavior].call(this,box,data);return}switch(result){case'done':this._showdonemsg();return false;case'no-append':if(opts.dataType==='html'){data='<div>'+data+'</div>';data=$(data).find(opts.itemSelector)}break;case'append':var children=box.children();if(children.length===0){return this._error('end')}frag=document.createDocumentFragment();while(box[0].firstChild){frag.appendChild(box[0].firstChild)}this._debug('contentSelector',$(opts.contentSelector)[0]);$(opts.contentSelector)[0].appendChild(frag);data=children.get();break}opts.loading.finished.call($(opts.contentSelector)[0],opts);if(opts.animate){var scrollTo=$(window).scrollTop()+$('#infscr-loading').height()+opts.extraScrollPx+'px';$('html,body').animate({scrollTop:scrollTo},800,function(){opts.state.isDuringAjax=false})}if(!opts.animate){opts.state.isDuringAjax=false}callback(this,data,url);if(opts.prefill){this._prefill()}},_nearbottom:function infscr_nearbottom(){var opts=this.options,pixelsFromWindowBottomToBottom=0+$(document).height()-(opts.binder.scrollTop())-$(window).height();if(!!opts.behavior&&this['_nearbottom_'+opts.behavior]!==undefined){return this['_nearbottom_'+opts.behavior].call(this)}this._debug('math:',pixelsFromWindowBottomToBottom,opts.pixelsFromNavToBottom);return(pixelsFromWindowBottomToBottom-opts.bufferPx<opts.pixelsFromNavToBottom)},_pausing:function infscr_pausing(pause){var opts=this.options;if(!!opts.behavior&&this['_pausing_'+opts.behavior]!==undefined){this['_pausing_'+opts.behavior].call(this,pause);return}if(pause!=='pause'&&pause!=='resume'&&pause!==null){this._debug('Invalid argument. Toggling pause value instead')}pause=(pause&&(pause==='pause'||pause==='resume'))?pause:'toggle';switch(pause){case'pause':opts.state.isPaused=true;break;case'resume':opts.state.isPaused=false;break;case'toggle':opts.state.isPaused=!opts.state.isPaused;break}this._debug('Paused',opts.state.isPaused);return false},_setup:function infscr_setup(){var opts=this.options;if(!!opts.behavior&&this['_setup_'+opts.behavior]!==undefined){this['_setup_'+opts.behavior].call(this);return}this._binding('bind');return false},_showdonemsg:function infscr_showdonemsg(){var opts=this.options;if(!!opts.behavior&&this['_showdonemsg_'+opts.behavior]!==undefined){this['_showdonemsg_'+opts.behavior].call(this);return}opts.loading.msg.find('img').hide().parent().find('div').html(opts.loading.finishedMsg).animate({opacity:1},2000,function(){$(this).parent().fadeOut(opts.loading.speed)});opts.errorCallback.call($(opts.contentSelector)[0],'done')},_validate:function infscr_validate(opts){for(var key in opts){if(key.indexOf&&key.indexOf('Selector')>-1&&$(opts[key]).length===0){this._debug('Your '+key+' found no elements.');return false}}return true},bind:function infscr_bind(){this._binding('bind')},destroy:function infscr_destroy(){this.options.state.isDestroyed=true;this.options.loading.finished();return this._error('destroy')},pause:function infscr_pause(){this._pausing('pause')},resume:function infscr_resume(){this._pausing('resume')},beginAjax:function infscr_ajax(opts){var instance=this,path=opts.path,box,desturl,method,condition;opts.state.currPage++;if(opts.maxPage!=undefined&&opts.state.currPage>opts.maxPage){this.destroy();return}box=$(opts.contentSelector).is('table')?$('<tbody/>'):$('<div/>');desturl=(typeof path==='function')?path(opts.state.currPage):path.join(opts.state.currPage);instance._debug('heading into ajax',desturl);method=(opts.dataType==='html'||opts.dataType==='json')?opts.dataType:'html+callback';if(opts.appendCallback&&opts.dataType==='html'){method+='+callback'}switch(method){case'html+callback':instance._debug('Using HTML via .load() method');box.load(desturl+' '+opts.itemSelector,undefined,function infscr_ajax_callback(responseText){instance._loadcallback(box,responseText,desturl)});break;case'html':instance._debug('Using '+(method.toUpperCase())+' via $.ajax() method');$.ajax({url:desturl,dataType:opts.dataType,complete:function infscr_ajax_callback(jqXHR,textStatus){condition=(typeof(jqXHR.isResolved)!=='undefined')?(jqXHR.isResolved()):(textStatus==="success"||textStatus==="notmodified");if(condition){instance._loadcallback(box,jqXHR.responseText,desturl)}else{instance._error('end')}}});break;case'json':instance._debug('Using '+(method.toUpperCase())+' via $.ajax() method');$.ajax({dataType:'json',type:'GET',url:desturl,success:function(data,textStatus,jqXHR){condition=(typeof(jqXHR.isResolved)!=='undefined')?(jqXHR.isResolved()):(textStatus==="success"||textStatus==="notmodified");if(opts.appendCallback){if(opts.template!==undefined){var theData=opts.template(data);box.append(theData);if(condition){instance._loadcallback(box,theData)}else{instance._error('end')}}else{instance._debug("template must be defined.");instance._error('end')}}else{if(condition){instance._loadcallback(box,data,desturl)}else{instance._error('end')}}},error:function(){instance._debug("JSON ajax request failed.");instance._error('end')}});break}},retrieve:function infscr_retrieve(pageNum){pageNum=pageNum||null;var instance=this,opts=instance.options;if(!!opts.behavior&&this['retrieve_'+opts.behavior]!==undefined){this['retrieve_'+opts.behavior].call(this,pageNum);return}if(opts.state.isDestroyed){this._debug('Instance is destroyed');return false}opts.state.isDuringAjax=true;opts.loading.start.call($(opts.contentSelector)[0],opts)},scroll:function infscr_scroll(){var opts=this.options,state=opts.state;if(!!opts.behavior&&this['scroll_'+opts.behavior]!==undefined){this['scroll_'+opts.behavior].call(this);return}if(state.isDuringAjax||state.isInvalidPage||state.isDone||state.isDestroyed||state.isPaused){return}if(!this._nearbottom()){return}this.retrieve()},toggle:function infscr_toggle(){this._pausing()},unbind:function infscr_unbind(){this._binding('unbind')},update:function infscr_options(key){if($.isPlainObject(key)){this.options=$.extend(true,this.options,key)}}};$.fn.infinitescroll=function infscr_init(options,callback){var thisCall=typeof options;switch(thisCall){case'string':var args=Array.prototype.slice.call(arguments,1);this.each(function(){var instance=$.data(this,'infinitescroll');if(!instance){return false}if(!$.isFunction(instance[options])||options.charAt(0)==="_"){return false}instance[options].apply(instance,args)});break;case'object':this.each(function(){var instance=$.data(this,'infinitescroll');if(instance){instance.update(options)}else{instance=new $.infinitescroll(options,callback,this);if(!instance.failed){$.data(this,'infinitescroll',instance)}}});break}return this};var event=$.event,scrollTimeout;event.special.smartscroll={setup:function(){$(this).bind("scroll",event.special.smartscroll.handler)},teardown:function(){$(this).unbind("scroll",event.special.smartscroll.handler)},handler:function(event,execAsap){var context=this,args=arguments;event.type="smartscroll";if(scrollTimeout){clearTimeout(scrollTimeout)}scrollTimeout=setTimeout(function(){$.event.handle.apply(context,args)},execAsap==="execAsap"?0:100)}};$.fn.smartscroll=function(fn){return fn?this.bind("smartscroll",fn):this.trigger("smartscroll",["execAsap"])}})(window,jQuery);

/**
 * jQuery MD5 hash algorithm function
 * 
 * 	Code
 * 		<code>
 * 			$.md5("I'm Persian."); 
 * 		</code>
 * 	Result
 * 		<code>
 * 			"b8c901d0f02223f9761016cfff9d68df"
 * 		</code>
 */

(function($){

    var rotateLeft = function(lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    }

    var addUnsigned = function(lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4) return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        if (lX4 | lY4) {
            if (lResult & 0x40000000) return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            else return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    }

    var F = function(x, y, z) {
        return (x & y) | ((~ x) & z);
    }

    var G = function(x, y, z) {
        return (x & z) | (y & (~ z));
    }

    var H = function(x, y, z) {
        return (x ^ y ^ z);
    }

    var I = function(x, y, z) {
        return (y ^ (x | (~ z)));
    }

    var FF = function(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var GG = function(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var HH = function(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var II = function(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var convertToWordArray = function(string) {
        var lWordCount;
        var lMessageLength = string.length;
        var lNumberOfWordsTempOne = lMessageLength + 8;
        var lNumberOfWordsTempTwo = (lNumberOfWordsTempOne - (lNumberOfWordsTempOne % 64)) / 64;
        var lNumberOfWords = (lNumberOfWordsTempTwo + 1) * 16;
        var lWordArray = Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
    };

    var wordToHex = function(lValue) {
        var WordToHexValue = "", WordToHexValueTemp = "", lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            WordToHexValueTemp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValueTemp.substr(WordToHexValueTemp.length - 2, 2);
        }
        return WordToHexValue;
    };

    var uTF8Encode = function(string) {
        string = string.replace(/\x0d\x0a/g, "\x0a");
        var output = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                output += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                output += String.fromCharCode((c >> 6) | 192);
                output += String.fromCharCode((c & 63) | 128);
            } else {
                output += String.fromCharCode((c >> 12) | 224);
                output += String.fromCharCode(((c >> 6) & 63) | 128);
                output += String.fromCharCode((c & 63) | 128);
            }
        }
        return output;
    };

    $.extend({
        md5: function(string) {
                 var x = Array();
                 var k, AA, BB, CC, DD, a, b, c, d;
                 var S11=7, S12=12, S13=17, S14=22;
                 var S21=5, S22=9 , S23=14, S24=20;
                 var S31=4, S32=11, S33=16, S34=23;
                 var S41=6, S42=10, S43=15, S44=21;
                 string = uTF8Encode(string);
                 x = convertToWordArray(string);
                 a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
                 for (k = 0; k < x.length; k += 16) {
                     AA = a; BB = b; CC = c; DD = d;
                     a = FF(a, b, c, d, x[k+0],  S11, 0xD76AA478);
                     d = FF(d, a, b, c, x[k+1],  S12, 0xE8C7B756);
                     c = FF(c, d, a, b, x[k+2],  S13, 0x242070DB);
                     b = FF(b, c, d, a, x[k+3],  S14, 0xC1BDCEEE);
                     a = FF(a, b, c, d, x[k+4],  S11, 0xF57C0FAF);
                     d = FF(d, a, b, c, x[k+5],  S12, 0x4787C62A);
                     c = FF(c, d, a, b, x[k+6],  S13, 0xA8304613);
                     b = FF(b, c, d, a, x[k+7],  S14, 0xFD469501);
                     a = FF(a, b, c, d, x[k+8],  S11, 0x698098D8);
                     d = FF(d, a, b, c, x[k+9],  S12, 0x8B44F7AF);
                     c = FF(c, d, a, b, x[k+10], S13, 0xFFFF5BB1);
                     b = FF(b, c, d, a, x[k+11], S14, 0x895CD7BE);
                     a = FF(a, b, c, d, x[k+12], S11, 0x6B901122);
                     d = FF(d, a, b, c, x[k+13], S12, 0xFD987193);
                     c = FF(c, d, a, b, x[k+14], S13, 0xA679438E);
                     b = FF(b, c, d, a, x[k+15], S14, 0x49B40821);
                     a = GG(a, b, c, d, x[k+1],  S21, 0xF61E2562);
                     d = GG(d, a, b, c, x[k+6],  S22, 0xC040B340);
                     c = GG(c, d, a, b, x[k+11], S23, 0x265E5A51);
                     b = GG(b, c, d, a, x[k+0],  S24, 0xE9B6C7AA);
                     a = GG(a, b, c, d, x[k+5],  S21, 0xD62F105D);
                     d = GG(d, a, b, c, x[k+10], S22, 0x2441453);
                     c = GG(c, d, a, b, x[k+15], S23, 0xD8A1E681);
                     b = GG(b, c, d, a, x[k+4],  S24, 0xE7D3FBC8);
                     a = GG(a, b, c, d, x[k+9],  S21, 0x21E1CDE6);
                     d = GG(d, a, b, c, x[k+14], S22, 0xC33707D6);
                     c = GG(c, d, a, b, x[k+3],  S23, 0xF4D50D87);
                     b = GG(b, c, d, a, x[k+8],  S24, 0x455A14ED);
                     a = GG(a, b, c, d, x[k+13], S21, 0xA9E3E905);
                     d = GG(d, a, b, c, x[k+2],  S22, 0xFCEFA3F8);
                     c = GG(c, d, a, b, x[k+7],  S23, 0x676F02D9);
                     b = GG(b, c, d, a, x[k+12], S24, 0x8D2A4C8A);
                     a = HH(a, b, c, d, x[k+5],  S31, 0xFFFA3942);
                     d = HH(d, a, b, c, x[k+8],  S32, 0x8771F681);
                     c = HH(c, d, a, b, x[k+11], S33, 0x6D9D6122);
                     b = HH(b, c, d, a, x[k+14], S34, 0xFDE5380C);
                     a = HH(a, b, c, d, x[k+1],  S31, 0xA4BEEA44);
                     d = HH(d, a, b, c, x[k+4],  S32, 0x4BDECFA9);
                     c = HH(c, d, a, b, x[k+7],  S33, 0xF6BB4B60);
                     b = HH(b, c, d, a, x[k+10], S34, 0xBEBFBC70);
                     a = HH(a, b, c, d, x[k+13], S31, 0x289B7EC6);
                     d = HH(d, a, b, c, x[k+0],  S32, 0xEAA127FA);
                     c = HH(c, d, a, b, x[k+3],  S33, 0xD4EF3085);
                     b = HH(b, c, d, a, x[k+6],  S34, 0x4881D05);
                     a = HH(a, b, c, d, x[k+9],  S31, 0xD9D4D039);
                     d = HH(d, a, b, c, x[k+12], S32, 0xE6DB99E5);
                     c = HH(c, d, a, b, x[k+15], S33, 0x1FA27CF8);
                     b = HH(b, c, d, a, x[k+2],  S34, 0xC4AC5665);
                     a = II(a, b, c, d, x[k+0],  S41, 0xF4292244);
                     d = II(d, a, b, c, x[k+7],  S42, 0x432AFF97);
                     c = II(c, d, a, b, x[k+14], S43, 0xAB9423A7);
                     b = II(b, c, d, a, x[k+5],  S44, 0xFC93A039);
                     a = II(a, b, c, d, x[k+12], S41, 0x655B59C3);
                     d = II(d, a, b, c, x[k+3],  S42, 0x8F0CCC92);
                     c = II(c, d, a, b, x[k+10], S43, 0xFFEFF47D);
                     b = II(b, c, d, a, x[k+1],  S44, 0x85845DD1);
                     a = II(a, b, c, d, x[k+8],  S41, 0x6FA87E4F);
                     d = II(d, a, b, c, x[k+15], S42, 0xFE2CE6E0);
                     c = II(c, d, a, b, x[k+6],  S43, 0xA3014314);
                     b = II(b, c, d, a, x[k+13], S44, 0x4E0811A1);
                     a = II(a, b, c, d, x[k+4],  S41, 0xF7537E82);
                     d = II(d, a, b, c, x[k+11], S42, 0xBD3AF235);
                     c = II(c, d, a, b, x[k+2],  S43, 0x2AD7D2BB);
                     b = II(b, c, d, a, x[k+9],  S44, 0xEB86D391);
                     a = addUnsigned(a, AA);
                     b = addUnsigned(b, BB);
                     c = addUnsigned(c, CC);
                     d = addUnsigned(d, DD);
                 }
                 var tempValue = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);
                 return tempValue.toLowerCase();
             }
    });
})(jQuery);
/*!
 * jCarousel - Riding carousels with jQuery
 *   http://sorgalla.com/jcarousel/
 *
 * Copyright (c) 2006 Jan Sorgalla (http://sorgalla.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Built on top of the jQuery library
 *   http://jquery.com
 *
 * Inspired by the "Carousel Component" by Bill Scott
 *   http://billwscott.com/carousel/
 */

/*global window, jQuery */
(function($) {
    // Default configuration properties.
    var defaults = {
        vertical: false,
        rtl: false,
        start: 1,
        offset: 1,
        size: null,
        scroll: 3,
        visible: null,
        animation: 'normal',
        easing: 'swing',
        auto: 0,
        wrap: null,
        initCallback: null,
        setupCallback: null,
        reloadCallback: null,
        itemLoadCallback: null,
        itemFirstInCallback: null,
        itemFirstOutCallback: null,
        itemLastInCallback: null,
        itemLastOutCallback: null,
        itemVisibleInCallback: null,
        itemVisibleOutCallback: null,
        animationStepCallback: null,
        buttonNextHTML: '<div></div>',
        buttonPrevHTML: '<div></div>',
        buttonNextEvent: 'click',
        buttonPrevEvent: 'click',
        buttonNextCallback: null,
        buttonPrevCallback: null,
        itemFallbackDimension: null
    }, windowLoaded = false;

    $(window).bind('load.jcarousel', function() { windowLoaded = true; });

    /**
     * The jCarousel object.
     *
     * @constructor
     * @class jcarousel
     * @param e {HTMLElement} The element to create the carousel for.
     * @param o {Object} A set of key/value pairs to set as configuration properties.
     * @cat Plugins/jCarousel
     */
    $.jcarousel = function(e, o) {
        this.options    = $.extend({}, defaults, o || {});

        this.locked          = false;
        this.autoStopped     = false;

        this.container       = null;
        this.clip            = null;
        this.list            = null;
        this.buttonNext      = null;
        this.buttonPrev      = null;
        this.buttonNextState = null;
        this.buttonPrevState = null;

        // Only set if not explicitly passed as option
        if (!o || o.rtl === undefined) {
            this.options.rtl = ($(e).attr('dir') || $('html').attr('dir') || '').toLowerCase() == 'rtl';
        }

        this.wh = !this.options.vertical ? 'width' : 'height';
        this.lt = !this.options.vertical ? (this.options.rtl ? 'right' : 'left') : 'top';

        // Extract skin class
        var skin = '', split = e.className.split(' ');

        for (var i = 0; i < split.length; i++) {
            if (split[i].indexOf('jcarousel-skin') != -1) {
                $(e).removeClass(split[i]);
                skin = split[i];
                break;
            }
        }

        if (e.nodeName.toUpperCase() == 'UL' || e.nodeName.toUpperCase() == 'OL') {
            this.list      = $(e);
            this.clip      = this.list.parents('.jcarousel-clip');
            this.container = this.list.parents('.jcarousel-container');
        } else {
            this.container = $(e);
            this.list      = this.container.find('ul,ol').eq(0);
            this.clip      = this.container.find('.jcarousel-clip');
        }

        if (this.clip.size() === 0) {
            this.clip = this.list.wrap('<div></div>').parent();
        }

        if (this.container.size() === 0) {
            this.container = this.clip.wrap('<div></div>').parent();
        }

        if (skin !== '' && this.container.parent()[0].className.indexOf('jcarousel-skin') == -1) {
            this.container.wrap('<div class=" '+ skin + '"></div>');
        }

        this.buttonPrev = $('.jcarousel-prev', this.container);

        if (this.buttonPrev.size() === 0 && this.options.buttonPrevHTML !== null) {
            this.buttonPrev = $(this.options.buttonPrevHTML).appendTo(this.container);
        }

        this.buttonPrev.addClass(this.className('jcarousel-prev'));

        this.buttonNext = $('.jcarousel-next', this.container);

        if (this.buttonNext.size() === 0 && this.options.buttonNextHTML !== null) {
            this.buttonNext = $(this.options.buttonNextHTML).appendTo(this.container);
        }

        this.buttonNext.addClass(this.className('jcarousel-next'));

        this.clip.addClass(this.className('jcarousel-clip')).css({
            position: 'relative'
        });

        this.list.addClass(this.className('jcarousel-list')).css({
            overflow: 'hidden',
            position: 'relative',
            top: 0,
            margin: 0,
            padding: 0
        }).css((this.options.rtl ? 'right' : 'left'), 0);

        this.container.addClass(this.className('jcarousel-container')).css({
            position: 'relative'
        });

        if (!this.options.vertical && this.options.rtl) {
            this.container.addClass('jcarousel-direction-rtl').attr('dir', 'rtl');
        }

        var di = this.options.visible !== null ? Math.ceil(this.clipping() / this.options.visible) : null;
        var li = this.list.children('li');

        var self = this;

        if (li.size() > 0) {
            var wh = 0, j = this.options.offset;
            li.each(function() {
                self.format(this, j++);
                wh += self.dimension(this, di);
            });

            this.list.css(this.wh, (wh + 100) + 'px');

            // Only set if not explicitly passed as option
            if (!o || o.size === undefined) {
                this.options.size = li.size();
            }
        }

        // For whatever reason, .show() does not work in Safari...
        this.container.css('display', 'block');
        this.buttonNext.css('display', 'block');
        this.buttonPrev.css('display', 'block');

        this.funcNext   = function() { self.next(); };
        this.funcPrev   = function() { self.prev(); };
        this.funcResize = function() { 
            if (self.resizeTimer) {
                clearTimeout(self.resizeTimer);
            }

            self.resizeTimer = setTimeout(function() {
                self.reload();
            }, 100);
        };

        if (this.options.initCallback !== null) {
            this.options.initCallback(this, 'init');
        }

        // if (!windowLoaded && $.browser.safari) {
        //     this.buttons(false, false);
        //     $(window).bind('load.jcarousel', function() { self.setup(); });
        // } else {
            this.setup();
        // }
    };

    // Create shortcut for internal use
    var $jc = $.jcarousel;

    $jc.fn = $jc.prototype = {
        jcarousel: '0.2.8'
    };

    $jc.fn.extend = $jc.extend = $.extend;

    $jc.fn.extend({
        /**
         * Setups the carousel.
         *
         * @method setup
         * @return undefined
         */
        setup: function() {
            this.first       = null;
            this.last        = null;
            this.prevFirst   = null;
            this.prevLast    = null;
            this.animating   = false;
            this.timer       = null;
            this.resizeTimer = null;
            this.tail        = null;
            this.inTail      = false;

            if (this.locked) {
                return;
            }

            this.list.css(this.lt, this.pos(this.options.offset) + 'px');
            var p = this.pos(this.options.start, true);
            this.prevFirst = this.prevLast = null;
            this.animate(p, false);

            $(window).unbind('resize.jcarousel', this.funcResize).bind('resize.jcarousel', this.funcResize);

            if (this.options.setupCallback !== null) {
                this.options.setupCallback(this);
            }
        },

        /**
         * Clears the list and resets the carousel.
         *
         * @method reset
         * @return undefined
         */
        reset: function() {
            this.list.empty();

            this.list.css(this.lt, '0px');
            this.list.css(this.wh, '10px');

            if (this.options.initCallback !== null) {
                this.options.initCallback(this, 'reset');
            }

            this.setup();
        },

        /**
         * Reloads the carousel and adjusts positions.
         *
         * @method reload
         * @return undefined
         */
        reload: function() {
            if (this.tail !== null && this.inTail) {
                this.list.css(this.lt, this.getLeft() + this.tail);
            }

            this.tail   = null;
            this.inTail = false;

            if (this.options.reloadCallback !== null) {
                this.options.reloadCallback(this);
            }

            if (this.options.visible !== null) {
                var self = this;
                var di = Math.ceil(this.clipping() / this.options.visible), wh = 0, lt = 0;
                this.list.children('li').each(function(i) {
                    wh += self.dimension(this, di);
                    if (i + 1 < self.first) {
                        lt = wh;
                    }
                });

                this.list.css(this.wh, wh + 'px');
                this.list.css(this.lt, -lt + 'px');
            }

            this.scroll(this.first, false);
        },

        /**
         * Locks the carousel.
         *
         * @method lock
         * @return undefined
         */
        lock: function() {
            this.locked = true;
            this.buttons();
        },

        /**
         * Unlocks the carousel.
         *
         * @method unlock
         * @return undefined
         */
        unlock: function() {
            this.locked = false;
            this.buttons();
        },

        /**
         * Sets the size of the carousel.
         *
         * @method size
         * @return undefined
         * @param s {Number} The size of the carousel.
         */
        size: function(s) {
            if (s !== undefined) {
                this.options.size = s;
                if (!this.locked) {
                    this.buttons();
                }
            }

            return this.options.size;
        },

        /**
         * Checks whether a list element exists for the given index (or index range).
         *
         * @method get
         * @return bool
         * @param i {Number} The index of the (first) element.
         * @param i2 {Number} The index of the last element.
         */
        has: function(i, i2) {
            if (i2 === undefined || !i2) {
                i2 = i;
            }

            if (this.options.size !== null && i2 > this.options.size) {
                i2 = this.options.size;
            }

            for (var j = i; j <= i2; j++) {
                var e = this.get(j);
                if (!e.length || e.hasClass('jcarousel-item-placeholder')) {
                    return false;
                }
            }

            return true;
        },

        /**
         * Returns a jQuery object with list element for the given index.
         *
         * @method get
         * @return jQuery
         * @param i {Number} The index of the element.
         */
        get: function(i) {
            return $('>.jcarousel-item-' + i, this.list);
        },

        /**
         * Adds an element for the given index to the list.
         * If the element already exists, it updates the inner html.
         * Returns the created element as jQuery object.
         *
         * @method add
         * @return jQuery
         * @param i {Number} The index of the element.
         * @param s {String} The innerHTML of the element.
         */
        add: function(i, s) {
            var e = this.get(i), old = 0, n = $(s);

            if (e.length === 0) {
                var c, j = $jc.intval(i);
                e = this.create(i);
                while (true) {
                    c = this.get(--j);
                    if (j <= 0 || c.length) {
                        if (j <= 0) {
                            this.list.prepend(e);
                        } else {
                            c.after(e);
                        }
                        break;
                    }
                }
            } else {
                old = this.dimension(e);
            }

            if (n.get(0).nodeName.toUpperCase() == 'LI') {
                e.replaceWith(n);
                e = n;
            } else {
                e.empty().append(s);
            }

            this.format(e.removeClass(this.className('jcarousel-item-placeholder')), i);

            var di = this.options.visible !== null ? Math.ceil(this.clipping() / this.options.visible) : null;
            var wh = this.dimension(e, di) - old;

            if (i > 0 && i < this.first) {
                this.list.css(this.lt, this.getLeft() - wh + 'px');
            }

            this.list.css(this.wh, $jc.intval(this.list.css(this.wh)) + wh + 'px');

            return e;
        },

        /**
         * Removes an element for the given index from the list.
         *
         * @method remove
         * @return undefined
         * @param i {Number} The index of the element.
         */
        remove: function(i) {
            var e = this.get(i);

            // Check if item exists and is not currently visible
            if (!e.length || (i >= this.first && i <= this.last)) {
                return;
            }

            var d = this.dimension(e);

            if (i < this.first) {
                this.list.css(this.lt, this.getLeft() + d + 'px');
            }

            e.remove();

            this.list.css(this.wh, $jc.intval(this.list.css(this.wh)) - d + 'px');
        },

        /**
         * Moves the carousel forwards.
         *
         * @method next
         * @return undefined
         */
        next: function() {
            if (this.tail !== null && !this.inTail) {
                this.scrollTail(false);
            } else {
                this.scroll(((this.options.wrap == 'both' || this.options.wrap == 'last') && this.options.size !== null && this.last == this.options.size) ? 1 : this.first + this.options.scroll);
            }
        },

        /**
         * Moves the carousel backwards.
         *
         * @method prev
         * @return undefined
         */
        prev: function() {
            if (this.tail !== null && this.inTail) {
                this.scrollTail(true);
            } else {
                this.scroll(((this.options.wrap == 'both' || this.options.wrap == 'first') && this.options.size !== null && this.first == 1) ? this.options.size : this.first - this.options.scroll);
            }
        },

        /**
         * Scrolls the tail of the carousel.
         *
         * @method scrollTail
         * @return undefined
         * @param b {Boolean} Whether scroll the tail back or forward.
         */
        scrollTail: function(b) {
            if (this.locked || this.animating || !this.tail) {
                return;
            }

            this.pauseAuto();

            var pos  = this.getLeft();
            console.log("scrollingTail "+ b + " from " + pos +" to " + (!b ? pos - this.tail : pos + this.tail));
            pos = !b ? pos - this.tail : pos + this.tail;
            this.inTail = !b;

            // Save for callbacks
            this.prevFirst = this.first;
            this.prevLast  = this.last;

            this.animate(pos);
        },

        /**
         * Scrolls the carousel to a certain position.
         *
         * @method scroll
         * @return undefined
         * @param i {Number} The index of the element to scoll to.
         * @param a {Boolean} Flag indicating whether to perform animation.
         */
        scroll: function(i, a) {
            if (this.locked || this.animating) {
                return;
            }
            console.log("scrolling "+ i);
            this.pauseAuto();
            this.animate(this.pos(i), a);
        },

        // fix added from this link for chrome
        // https://github.com/jsor/jcarousel/issues/330
        getLeft: function() {
            // jQuery 1.7 Chrome left position value bug
            if(this.lt == "top")
                return $jc.intval(this.list.css(this.lt))
            // $jc.intval(thisTop-parentTop);
            else{
	            thisLeft = this.list.offset().left;
	            thisParent = this.list.parent();
	            parentLeft = thisParent.offset().left;
                if($jc.intval(thisLeft-parentLeft) > 0) {
                    return ($jc.intval(thisLeft-parentLeft))/2;
                }
                else
                {
                    return $jc.intval(thisLeft-parentLeft);
                }
            }
        },



        /**
         * Prepares the carousel and return the position for a certian index.
         *
         * @method pos
         * @return {Number}
         * @param i {Number} The index of the element to scoll to.
         * @param fv {Boolean} Whether to force last item to be visible.
         */
        pos: function(i, fv) {
            var pos  = this.getLeft();

            if (this.locked || this.animating) {
                return pos;
            }

            if (this.options.wrap != 'circular') {
                i = i < 1 ? 1 : (this.options.size && i > this.options.size ? this.options.size : i);
            }

            var back = this.first > i;

            // Create placeholders, new list width/height
            // and new list position
            var f = this.options.wrap != 'circular' && this.first <= 1 ? 1 : this.first;
            var c = back ? this.get(f) : this.get(this.last);
            var j = back ? f : f - 1;
            var e = null, l = 0, p = false, d = 0, g;

            while (back ? --j >= i : ++j < i) {
                e = this.get(j);
                p = !e.length;
                if (e.length === 0) {
                    e = this.create(j).addClass(this.className('jcarousel-item-placeholder'));
                    c[back ? 'before' : 'after' ](e);

                    if (this.first !== null && this.options.wrap == 'circular' && this.options.size !== null && (j <= 0 || j > this.options.size)) {
                        g = this.get(this.index(j));
                        if (g.length) {
                            e = this.add(j, g.clone(true));
                        }
                    }
                }

                c = e;
                d = this.dimension(e);

                if (p) {
                    l += d;
                }

                if (this.first !== null && (this.options.wrap == 'circular' || (j >= 1 && (this.options.size === null || j <= this.options.size)))) {
                    pos = back ? pos + d : pos - d;
                }
            }

            // Calculate visible items
            var clipping = this.clipping(), cache = [], visible = 0, v = 0;
            c = this.get(i - 1);
            j = i;

            while (++visible) {
                e = this.get(j);
                p = !e.length;
                if (e.length === 0) {
                    e = this.create(j).addClass(this.className('jcarousel-item-placeholder'));
                    // This should only happen on a next scroll
                    if (c.length === 0) {
                        this.list.prepend(e);
                    } else {
                        c[back ? 'before' : 'after' ](e);
                    }

                    if (this.first !== null && this.options.wrap == 'circular' && this.options.size !== null && (j <= 0 || j > this.options.size)) {
                        g = this.get(this.index(j));
                        if (g.length) {
                            e = this.add(j, g.clone(true));
                        }
                    }
                }

                c = e;
                d = this.dimension(e);
                if (d === 0) {
                    throw new Error('jCarousel: No width/height set for items. This will cause an infinite loop. Aborting...');
                }

                if (this.options.wrap != 'circular' && this.options.size !== null && j > this.options.size) {
                    cache.push(e);
                } else if (p) {
                    l += d;
                }

                v += d;

                if (v >= clipping) {
                    break;
                }

                j++;
            }

             // Remove out-of-range placeholders
            for (var x = 0; x < cache.length; x++) {
                cache[x].remove();
            }

            // Resize list
            if (l > 0) {
                this.list.css(this.wh, this.dimension(this.list) + l + 'px');

                if (back) {
                    pos -= l;
                    this.list.css(this.lt, this.getLeft() - l + 'px');
                }
            }

            // Calculate first and last item
            var last = i + visible - 1;
            if (this.options.wrap != 'circular' && this.options.size && last > this.options.size) {
                last = this.options.size;
            }

            if (j > last) {
                visible = 0;
                j = last;
                v = 0;
                while (++visible) {
                    e = this.get(j--);
                    if (!e.length) {
                        break;
                    }
                    v += this.dimension(e);
                    if (v >= clipping) {
                        break;
                    }
                }
            }

            var first = last - visible + 1;
            if (this.options.wrap != 'circular' && first < 1) {
                first = 1;
            }

            if (this.inTail && back) {
                pos += this.tail;
                this.inTail = false;
            }

            this.tail = null;
            if (this.options.wrap != 'circular' && last == this.options.size && (last - visible + 1) >= 1) {
                var m = $jc.intval(this.get(last).css(!this.options.vertical ? 'marginRight' : 'marginBottom'));
                if ((v - m) > clipping) {
                    this.tail = v - clipping - m;
                }
            }

            if (fv && i === this.options.size && this.tail) {
                pos -= this.tail;
                this.inTail = true;
            }

            // Adjust position
            while (i-- > first) {
                pos += this.dimension(this.get(i));
            }

            // Save visible item range
            this.prevFirst = this.first;
            this.prevLast  = this.last;
            this.first     = first;
            this.last      = last;

            return pos;
        },

        /**
         * Animates the carousel to a certain position.
         *
         * @method animate
         * @return undefined
         * @param p {Number} Position to scroll to.
         * @param a {Boolean} Flag indicating whether to perform animation.
         */
        animate: function(p, a) {
            if (this.locked || this.animating) {
                return;
            }

            this.animating = true;

            var self = this;
            var scrolled = function() {
                self.animating = false;

                if (p === 0) {
                    self.list.css(self.lt,  0);
                }

                if (!self.autoStopped && (self.options.wrap == 'circular' || self.options.wrap == 'both' || self.options.wrap == 'last' || self.options.size === null || self.last < self.options.size || (self.last == self.options.size && self.tail !== null && !self.inTail))) {
                    self.startAuto();
                }

                self.buttons();
                self.notify('onAfterAnimation');

                // This function removes items which are appended automatically for circulation.
                // This prevents the list from growing infinitely.
                if (self.options.wrap == 'circular' && self.options.size !== null) {
                    for (var i = self.prevFirst; i <= self.prevLast; i++) {
                        if (i !== null && !(i >= self.first && i <= self.last) && (i < 1 || i > self.options.size)) {
                            self.remove(i);
                        }
                    }
                }
            };

            this.notify('onBeforeAnimation');

            // Animate
            if (!this.options.animation || a === false) {
                this.list.css(this.lt, p + 'px');
                scrolled();
            } else {
                var o = !this.options.vertical ? (this.options.rtl ? {'right': p} : {'left': p}) : {'top': p};
                // Define animation settings.
                var settings = {
                    duration: this.options.animation,
                    easing:   this.options.easing,
                    complete: scrolled
                };
                // If we have a step callback, specify it as well.
                if ($.isFunction(this.options.animationStepCallback)) {
                    settings.step = this.options.animationStepCallback;
                }
                // Start the animation.
                this.list.animate(o, settings);
            }
        },

        /**
         * Starts autoscrolling.
         *
         * @method auto
         * @return undefined
         * @param s {Number} Seconds to periodically autoscroll the content.
         */
        startAuto: function(s) {
            if (s !== undefined) {
                this.options.auto = s;
            }

            if (this.options.auto === 0) {
                return this.stopAuto();
            }

            if (this.timer !== null) {
                return;
            }

            this.autoStopped = false;

            var self = this;
            this.timer = window.setTimeout(function() { self.next(); }, this.options.auto * 1000);
        },

        /**
         * Stops autoscrolling.
         *
         * @method stopAuto
         * @return undefined
         */
        stopAuto: function() {
            this.pauseAuto();
            this.autoStopped = true;
        },

        /**
         * Pauses autoscrolling.
         *
         * @method pauseAuto
         * @return undefined
         */
        pauseAuto: function() {
            if (this.timer === null) {
                return;
            }

            window.clearTimeout(this.timer);
            this.timer = null;
        },

        /**
         * Sets the states of the prev/next buttons.
         *
         * @method buttons
         * @return undefined
         */
        buttons: function(n, p) {
            if (n == null) {
                n = !this.locked && this.options.size !== 0 && ((this.options.wrap && this.options.wrap != 'first') || this.options.size === null || this.last < this.options.size);
                if (!this.locked && (!this.options.wrap || this.options.wrap == 'first') && this.options.size !== null && this.last >= this.options.size) {
                    n = this.tail !== null && !this.inTail;
                }
            }

            if (p == null) {
                p = !this.locked && this.options.size !== 0 && ((this.options.wrap && this.options.wrap != 'last') || this.first > 1);
                if (!this.locked && (!this.options.wrap || this.options.wrap == 'last') && this.options.size !== null && this.first == 1) {
                    p = this.tail !== null && this.inTail;
                }
            }

            var self = this;

            if (this.buttonNext.size() > 0) {
                this.buttonNext.unbind(this.options.buttonNextEvent + '.jcarousel', this.funcNext);

                if (n) {
                    this.buttonNext.bind(this.options.buttonNextEvent + '.jcarousel', this.funcNext);
                }

                this.buttonNext[n ? 'removeClass' : 'addClass'](this.className('jcarousel-next-disabled')).attr('disabled', n ? false : true);

                if (this.options.buttonNextCallback !== null && this.buttonNext.data('jcarouselstate') != n) {
                    this.buttonNext.each(function() { self.options.buttonNextCallback(self, this, n); }).data('jcarouselstate', n);
                }
            } else {
                if (this.options.buttonNextCallback !== null && this.buttonNextState != n) {
                    this.options.buttonNextCallback(self, null, n);
                }
            }

            if (this.buttonPrev.size() > 0) {
                this.buttonPrev.unbind(this.options.buttonPrevEvent + '.jcarousel', this.funcPrev);

                if (p) {
                    this.buttonPrev.bind(this.options.buttonPrevEvent + '.jcarousel', this.funcPrev);
                }

                this.buttonPrev[p ? 'removeClass' : 'addClass'](this.className('jcarousel-prev-disabled')).attr('disabled', p ? false : true);

                if (this.options.buttonPrevCallback !== null && this.buttonPrev.data('jcarouselstate') != p) {
                    this.buttonPrev.each(function() { self.options.buttonPrevCallback(self, this, p); }).data('jcarouselstate', p);
                }
            } else {
                if (this.options.buttonPrevCallback !== null && this.buttonPrevState != p) {
                    this.options.buttonPrevCallback(self, null, p);
                }
            }

            this.buttonNextState = n;
            this.buttonPrevState = p;
        },

        /**
         * Notify callback of a specified event.
         *
         * @method notify
         * @return undefined
         * @param evt {String} The event name
         */
        notify: function(evt) {
            var state = this.prevFirst === null ? 'init' : (this.prevFirst < this.first ? 'next' : 'prev');

            // Load items
            this.callback('itemLoadCallback', evt, state);

            if (this.prevFirst !== this.first) {
                this.callback('itemFirstInCallback', evt, state, this.first);
                this.callback('itemFirstOutCallback', evt, state, this.prevFirst);
            }

            if (this.prevLast !== this.last) {
                this.callback('itemLastInCallback', evt, state, this.last);
                this.callback('itemLastOutCallback', evt, state, this.prevLast);
            }

            this.callback('itemVisibleInCallback', evt, state, this.first, this.last, this.prevFirst, this.prevLast);
            this.callback('itemVisibleOutCallback', evt, state, this.prevFirst, this.prevLast, this.first, this.last);
        },

        callback: function(cb, evt, state, i1, i2, i3, i4) {
            if (this.options[cb] == null || (typeof this.options[cb] != 'object' && evt != 'onAfterAnimation')) {
                return;
            }

            var callback = typeof this.options[cb] == 'object' ? this.options[cb][evt] : this.options[cb];

            if (!$.isFunction(callback)) {
                return;
            }

            var self = this;

            if (i1 === undefined) {
                callback(self, state, evt);
            } else if (i2 === undefined) {
                this.get(i1).each(function() { callback(self, this, i1, state, evt); });
            } else {
                var call = function(i) {
                    self.get(i).each(function() { callback(self, this, i, state, evt); });
                };
                for (var i = i1; i <= i2; i++) {
                    if (i !== null && !(i >= i3 && i <= i4)) {
                        call(i);
                    }
                }
            }
        },

        create: function(i) {
            return this.format('<li></li>', i);
        },

        format: function(e, i) {
            e = $(e);
            var split = e.get(0).className.split(' ');
            for (var j = 0; j < split.length; j++) {
                if (split[j].indexOf('jcarousel-') != -1) {
                    e.removeClass(split[j]);
                }
            }
            e.addClass(this.className('jcarousel-item')).addClass(this.className('jcarousel-item-' + i)).css({
                'float': (this.options.rtl ? 'right' : 'left'),
                'list-style': 'none'
            }).attr('jcarouselindex', i);
            return e;
        },

        className: function(c) {
            return c + ' ' + c + (!this.options.vertical ? '-horizontal' : '-vertical');
        },

        dimension: function(e, d) {
            var el = $(e);

            if (d == null) {
                return !this.options.vertical ?
                       (el.outerWidth(true) || $jc.intval(this.options.itemFallbackDimension)) :
                       (el.outerHeight(true) || $jc.intval(this.options.itemFallbackDimension));
            } else {
                var w = !this.options.vertical ?
                    d - $jc.intval(el.css('marginLeft')) - $jc.intval(el.css('marginRight')) :
                    d - $jc.intval(el.css('marginTop')) - $jc.intval(el.css('marginBottom'));

                $(el).css(this.wh, w + 'px');

                return this.dimension(el);
            }
        },

        clipping: function() {
            return !this.options.vertical ?
                this.clip[0].offsetWidth - $jc.intval(this.clip.css('borderLeftWidth')) - $jc.intval(this.clip.css('borderRightWidth')) :
                this.clip[0].offsetHeight - $jc.intval(this.clip.css('borderTopWidth')) - $jc.intval(this.clip.css('borderBottomWidth'));
        },

        index: function(i, s) {
            if (s == null) {
                s = this.options.size;
            }

            return Math.round((((i-1) / s) - Math.floor((i-1) / s)) * s) + 1;
        }
    });

    $jc.extend({
        /**
         * Gets/Sets the global default configuration properties.
         *
         * @method defaults
         * @return {Object}
         * @param d {Object} A set of key/value pairs to set as configuration properties.
         */
        defaults: function(d) {
            return $.extend(defaults, d || {});
        },

        intval: function(v) {
            v = parseInt(v, 10);
            return isNaN(v) ? 0 : v;
        },

        windowLoaded: function() {
            windowLoaded = true;
        }
    });

    $.fn.jcarousel = function(o) {
        if (typeof o == 'string') {
            var instance = $(this).data('jcarousel'), args = Array.prototype.slice.call(arguments, 1);
            return instance[o].apply(instance, args);
        } else {
            return this.each(function() {
                var instance = $(this).data('jcarousel');
                if (instance) {
                    if (o) {
                        $.extend(instance.options, o);
                    }
                    instance.reload();
                } else {
                    $(this).data('jcarousel', new $jc(this, o));
                }
            });
        }
    };

})(jQuery);
/*
 * jScrollPane - v2.0.0beta12 - 2012-09-27
 * http://jscrollpane.kelvinluck.com/
 *
 * Copyright (c) 2010 Kelvin Luck
 * Dual licensed under the MIT or GPL licenses.
 */
(function(e,t,n){e.fn.jScrollPane=function(t){function r(t,r){function X(r){var s,m,g,b,w,S,x,T,N=false,C=false;i=r;if(o===n){w=t.scrollTop();S=t.scrollLeft();t.css({overflow:"hidden",padding:0});u=t.innerWidth()+j;a=t.innerHeight();t.width(u);o=e('<div class="jspPane" />').css("padding",B).append(t.children());f=e('<div class="jspContainer" />').css({width:u+"px",height:a+"px"}).append(o).appendTo(t)}else{t.css("width","");f.css({width:"auto",height:"auto"});o.css("position","static");x=t.innerWidth()+j;T=t.innerHeight();o.css("position","absolute");N=i.stickToBottom&&vt();C=i.stickToRight&&mt();b=x!==u||T!==a;u=x;a=T;f.css({width:u,height:a});if(!b&&F==l&&o.outerHeight()==c){t.width(u);return}F=l;o.css("width","");t.width(u);f.find(">.jspVerticalBar,>.jspHorizontalBar").remove().end()}o.css("overflow","auto");if(r.contentWidth){l=r.contentWidth}else{l=o[0].scrollWidth}c=o[0].scrollHeight;o.css("overflow","");h=l/u;p=c/a;d=p>1;v=h>1;if(!(v||d)){t.removeClass("jspScrollable");o.css({top:0,width:f.width()-j});yt();Et();xt();nt();Nt()}else{t.addClass("jspScrollable");s=i.maintainPosition&&(y||E);if(s){m=pt();g=dt()}V();K();G();if(s){ct(C?l-u:m,false);lt(N?c-a:g,false)}wt();gt();kt();if(i.enableKeyboardNavigation){St()}if(i.clickOnTrack){tt()}Tt();if(i.hijackInternalLinks){Ct()}}if(i.autoReinitialise&&!H){H=setInterval(function(){X(i)},i.autoReinitialiseDelay)}else if(!i.autoReinitialise&&H){clearInterval(H)}w&&t.scrollTop(0)&&lt(w,false);S&&t.scrollLeft(0)&&ct(S,false);t.trigger("jsp-initialised",[v||d])}function V(){if(d){f.append(e('<div class="jspVerticalBar" />').append(e('<div class="jspCap jspCapTop" />'),e('<div class="jspTrack" />').append(e('<div class="jspDrag" />').append(e('<div class="jspDragTop" />'),e('<div class="jspDragBottom" />'))),e('<div class="jspCap jspCapBottom" />')));S=f.find(">.jspVerticalBar");x=S.find(">.jspTrack");m=x.find(">.jspDrag");if(i.showArrows){k=e('<a class="jspArrow jspArrowUp" />').bind("mousedown.jsp",Z(0,-1)).bind("click.jsp",bt);L=e('<a class="jspArrow jspArrowDown" />').bind("mousedown.jsp",Z(0,1)).bind("click.jsp",bt);if(i.arrowScrollOnHover){k.bind("mouseover.jsp",Z(0,-1,k));L.bind("mouseover.jsp",Z(0,1,L))}Y(x,i.verticalArrowPositions,k,L)}N=a;f.find(">.jspVerticalBar>.jspCap:visible,>.jspVerticalBar>.jspArrow").each(function(){N-=e(this).outerHeight()});m.hover(function(){m.addClass("jspHover")},function(){m.removeClass("jspHover")}).bind("mousedown.jsp",function(t){e("html").bind("dragstart.jsp selectstart.jsp",bt);m.addClass("jspActive");var n=t.pageY-m.position().top;e("html").bind("mousemove.jsp",function(e){it(e.pageY-n,false)}).bind("mouseup.jsp mouseleave.jsp",rt);return false});J()}}function J(){x.height(N+"px");y=0;T=i.verticalGutter+x.outerWidth();o.width(u-T-j);try{if(S.position().left===0){o.css("margin-left",T+"px")}}catch(e){}}function K(){if(v){f.append(e('<div class="jspHorizontalBar" />').append(e('<div class="jspCap jspCapLeft" />'),e('<div class="jspTrack" />').append(e('<div class="jspDrag" />').append(e('<div class="jspDragLeft" />'),e('<div class="jspDragRight" />'))),e('<div class="jspCap jspCapRight" />')));A=f.find(">.jspHorizontalBar");O=A.find(">.jspTrack");b=O.find(">.jspDrag");if(i.showArrows){D=e('<a class="jspArrow jspArrowLeft" />').bind("mousedown.jsp",Z(-1,0)).bind("click.jsp",bt);P=e('<a class="jspArrow jspArrowRight" />').bind("mousedown.jsp",Z(1,0)).bind("click.jsp",bt);if(i.arrowScrollOnHover){D.bind("mouseover.jsp",Z(-1,0,D));P.bind("mouseover.jsp",Z(1,0,P))}Y(O,i.horizontalArrowPositions,D,P)}b.hover(function(){b.addClass("jspHover")},function(){b.removeClass("jspHover")}).bind("mousedown.jsp",function(t){e("html").bind("dragstart.jsp selectstart.jsp",bt);b.addClass("jspActive");var n=t.pageX-b.position().left;e("html").bind("mousemove.jsp",function(e){ot(e.pageX-n,false)}).bind("mouseup.jsp mouseleave.jsp",rt);return false});M=f.innerWidth();Q()}}function Q(){f.find(">.jspHorizontalBar>.jspCap:visible,>.jspHorizontalBar>.jspArrow").each(function(){M-=e(this).outerWidth()});O.width(M+"px");E=0}function G(){if(v&&d){var t=O.outerHeight(),n=x.outerWidth();N-=t;e(A).find(">.jspCap:visible,>.jspArrow").each(function(){M+=e(this).outerWidth()});M-=n;a-=n;u-=t;O.parent().append(e('<div class="jspCorner" />').css("width",t+"px"));J();Q()}if(v){o.width(f.outerWidth()-j+"px")}c=o.outerHeight();p=c/a;if(v){_=Math.ceil(1/h*M);if(_>i.horizontalDragMaxWidth){_=i.horizontalDragMaxWidth}else if(_<i.horizontalDragMinWidth){_=i.horizontalDragMinWidth}b.width(_+"px");w=M-_;ut(E)}if(d){C=Math.ceil(1/p*N);if(C>i.verticalDragMaxHeight){C=i.verticalDragMaxHeight}else if(C<i.verticalDragMinHeight){C=i.verticalDragMinHeight}m.height(C+"px");g=N-C;st(y)}}function Y(e,t,n,r){var i="before",s="after",o;if(t=="os"){t=/Mac/.test(navigator.platform)?"after":"split"}if(t==i){s=t}else if(t==s){i=t;o=n;n=r;r=o}e[i](n)[s](r)}function Z(e,t,n){return function(){et(e,t,this,n);this.blur();return false}}function et(t,n,r,o){r=e(r).addClass("jspActive");var u,a,f=true,l=function(){if(t!==0){s.scrollByX(t*i.arrowButtonSpeed)}if(n!==0){s.scrollByY(n*i.arrowButtonSpeed)}a=setTimeout(l,f?i.initialDelay:i.arrowRepeatFreq);f=false};l();u=o?"mouseout.jsp":"mouseup.jsp";o=o||e("html");o.bind(u,function(){r.removeClass("jspActive");a&&clearTimeout(a);a=null;o.unbind(u)})}function tt(){nt();if(d){x.bind("mousedown.jsp",function(t){if(t.originalTarget===n||t.originalTarget==t.currentTarget){var r=e(this),o=r.offset(),u=t.pageY-o.top-y,f,l=true,h=function(){var e=r.offset(),n=t.pageY-e.top-C/2,o=a*i.scrollPagePercent,d=g*o/(c-a);if(u<0){if(y-d>n){s.scrollByY(-o)}else{it(n)}}else if(u>0){if(y+d<n){s.scrollByY(o)}else{it(n)}}else{p();return}f=setTimeout(h,l?i.initialDelay:i.trackClickRepeatFreq);l=false},p=function(){f&&clearTimeout(f);f=null;e(document).unbind("mouseup.jsp",p)};h();e(document).bind("mouseup.jsp",p);return false}})}if(v){O.bind("mousedown.jsp",function(t){if(t.originalTarget===n||t.originalTarget==t.currentTarget){var r=e(this),o=r.offset(),a=t.pageX-o.left-E,f,c=true,h=function(){var e=r.offset(),n=t.pageX-e.left-_/2,o=u*i.scrollPagePercent,d=w*o/(l-u);if(a<0){if(E-d>n){s.scrollByX(-o)}else{ot(n)}}else if(a>0){if(E+d<n){s.scrollByX(o)}else{ot(n)}}else{p();return}f=setTimeout(h,c?i.initialDelay:i.trackClickRepeatFreq);c=false},p=function(){f&&clearTimeout(f);f=null;e(document).unbind("mouseup.jsp",p)};h();e(document).bind("mouseup.jsp",p);return false}})}}function nt(){if(O){O.unbind("mousedown.jsp")}if(x){x.unbind("mousedown.jsp")}}function rt(){e("html").unbind("dragstart.jsp selectstart.jsp mousemove.jsp mouseup.jsp mouseleave.jsp");if(m){m.removeClass("jspActive")}if(b){b.removeClass("jspActive")}}function it(e,t){if(!d){return}if(e<0){e=0}else if(e>g){e=g}if(t===n){t=i.animateScroll}if(t){s.animate(m,"top",e,st)}else{m.css("top",e);st(e)}}function st(e){if(e===n){e=m.position().top}f.scrollTop(0);y=e;var r=y===0,i=y==g,s=e/g,u=-s*(c-a);if(I!=r||R!=i){I=r;R=i;t.trigger("jsp-arrow-change",[I,R,q,U])}at(r,i);o.css("top",u);t.trigger("jsp-scroll-y",[-u,r,i]).trigger("scroll")}function ot(e,t){if(!v){return}if(e<0){e=0}else if(e>w){e=w}if(t===n){t=i.animateScroll}if(t){s.animate(b,"left",e,ut)}else{b.css("left",e);ut(e)}}function ut(e){if(e===n){e=b.position().left}f.scrollTop(0);E=e;var r=E===0,i=E==w,s=e/w,a=-s*(l-u);if(q!=r||U!=i){q=r;U=i;t.trigger("jsp-arrow-change",[I,R,q,U])}ft(r,i);o.css("left",a);t.trigger("jsp-scroll-x",[-a,r,i]).trigger("scroll")}function at(e,t){if(i.showArrows){k[e?"addClass":"removeClass"]("jspDisabled");L[t?"addClass":"removeClass"]("jspDisabled")}}function ft(e,t){if(i.showArrows){D[e?"addClass":"removeClass"]("jspDisabled");P[t?"addClass":"removeClass"]("jspDisabled")}}function lt(e,t){var n=e/(c-a);it(n*g,t)}function ct(e,t){var n=e/(l-u);ot(n*w,t)}function ht(t,n,r){var s,o,l,c=0,h=0,p,d,v,m,g,y;try{s=e(t)}catch(b){return}o=s.outerHeight();l=s.outerWidth();f.scrollTop(0);f.scrollLeft(0);while(!s.is(".jspPane")){c+=s.position().top;h+=s.position().left;s=s.offsetParent();if(/^body|html$/i.test(s[0].nodeName)){return}}p=dt();v=p+a;if(c<p||n){g=c-i.verticalGutter}else if(c+o>v){g=c-a+o+i.verticalGutter}if(g){lt(g,r)}d=pt();m=d+u;if(h<d||n){y=h-i.horizontalGutter}else if(h+l>m){y=h-u+l+i.horizontalGutter}if(y){ct(y,r)}}function pt(){return-o.position().left}function dt(){return-o.position().top}function vt(){var e=c-a;return e>20&&e-dt()<10}function mt(){var e=l-u;return e>20&&e-pt()<10}function gt(){f.unbind(W).bind(W,function(e,t,n,r){var o=E,u=y;s.scrollBy(n*i.mouseWheelSpeed,-r*i.mouseWheelSpeed,false);return o==E&&u==y})}function yt(){f.unbind(W)}function bt(){return false}function wt(){o.find(":input,a").unbind("focus.jsp").bind("focus.jsp",function(e){ht(e.target,false)})}function Et(){o.find(":input,a").unbind("focus.jsp")}function St(){function l(){var e=E,t=y;switch(n){case 40:s.scrollByY(i.keyboardSpeed,false);break;case 38:s.scrollByY(-i.keyboardSpeed,false);break;case 34:case 32:s.scrollByY(a*i.scrollPagePercent,false);break;case 33:s.scrollByY(-a*i.scrollPagePercent,false);break;case 39:s.scrollByX(i.keyboardSpeed,false);break;case 37:s.scrollByX(-i.keyboardSpeed,false);break}r=e!=E||t!=y;return r}var n,r,u=[];v&&u.push(A[0]);d&&u.push(S[0]);o.focus(function(){t.focus()});t.attr("tabindex",0).unbind("keydown.jsp keypress.jsp").bind("keydown.jsp",function(t){if(t.target!==this&&!(u.length&&e(t.target).closest(u).length)){return}var i=E,s=y;switch(t.keyCode){case 40:case 38:case 34:case 32:case 33:case 39:case 37:n=t.keyCode;l();break;case 35:lt(c-a);n=null;break;case 36:lt(0);n=null;break}r=t.keyCode==n&&i!=E||s!=y;return!r}).bind("keypress.jsp",function(e){if(e.keyCode==n){l()}return!r});if(i.hideFocus){t.css("outline","none");if("hideFocus"in f[0]){t.attr("hideFocus",true)}}else{t.css("outline","");if("hideFocus"in f[0]){t.attr("hideFocus",false)}}}function xt(){t.attr("tabindex","-1").removeAttr("tabindex").unbind("keydown.jsp keypress.jsp")}function Tt(){if(location.hash&&location.hash.length>1){var t,n;try{t=e(location.hash)}catch(r){return}if(t.length&&o.find(location.hash)){if(f.scrollTop()===0){n=setInterval(function(){if(f.scrollTop()>0){ht(location.hash,true);e(document).scrollTop(f.position().top);clearInterval(n)}},50)}else{ht(location.hash,true);e(document).scrollTop(f.position().top)}}}}function Nt(){e("a.jspHijack").unbind("click.jsp-hijack").removeClass("jspHijack")}function Ct(){Nt();e("a[href^=#]").addClass("jspHijack").bind("click.jsp-hijack",function(){var e=this.href.split("#"),t;if(e.length>1){t=e[1];if(t.length>0&&o.find("#"+t).length>0){ht("#"+t,true);return false}}})}function kt(){var e,t,n,r,i,o=false;f.unbind("touchstart.jsp touchmove.jsp touchend.jsp click.jsp-touchclick").bind("touchstart.jsp",function(s){var u=s.originalEvent.touches[0];e=pt();t=dt();n=u.pageX;r=u.pageY;i=false;o=true}).bind("touchmove.jsp",function(u){if(!o){return}var a=u.originalEvent.touches[0],f=E,l=y;s.scrollTo(e+n-a.pageX,t+r-a.pageY);i=i||Math.abs(n-a.pageX)>5||Math.abs(r-a.pageY)>5;return f==E&&l==y}).bind("touchend.jsp",function(e){o=false}).bind("click.jsp-touchclick",function(e){if(i){i=false;return false}})}function Lt(){var e=dt(),n=pt();t.removeClass("jspScrollable").unbind(".jsp");t.replaceWith(z.append(o.children()));z.scrollTop(e);z.scrollLeft(n)}var i,s=this,o,u,a,f,l,c,h,p,d,v,m,g,y,b,w,E,S,x,T,N,C,k,L,A,O,M,_,D,P,H,B,j,F,I=true,q=true,R=false,U=false,z=t.clone(false,false).empty(),W=e.fn.mwheelIntent?"mwheelIntent.jsp":"mousewheel.jsp";B=t.css("paddingTop")+" "+t.css("paddingRight")+" "+t.css("paddingBottom")+" "+t.css("paddingLeft");j=(parseInt(t.css("paddingLeft"),10)||0)+(parseInt(t.css("paddingRight"),10)||0);e.extend(s,{reinitialise:function(t){t=e.extend({},i,t);X(t)},scrollToElement:function(e,t,n){ht(e,t,n)},scrollTo:function(e,t,n){ct(e,n);lt(t,n)},scrollToX:function(e,t){ct(e,t)},scrollToY:function(e,t){lt(e,t)},scrollToPercentX:function(e,t){ct(e*(l-u),t)},scrollToPercentY:function(e,t){lt(e*(c-a),t)},scrollBy:function(e,t,n){s.scrollByX(e,n);s.scrollByY(t,n)},scrollByX:function(e,t){e=e>=0?Math.max(e,1):Math.min(e,-1);var n=pt()+e,r=n/(l-u);ot(r*w,t)},scrollByY:function(e,t){e=e>=0?Math.max(e,1):Math.min(e,-1);var n=dt()+e,r=n/(c-a);it(r*g,t)},positionDragX:function(e,t){ot(e,t)},positionDragY:function(e,t){it(e,t)},animate:function(e,t,n,r){var s={};s[t]=n;e.animate(s,{duration:i.animateDuration,ease:i.animateEase,queue:false,step:r})},getContentPositionX:function(){return pt()},getContentPositionY:function(){return dt()},getContentWidth:function(){return l},getContentHeight:function(){return c},getPercentScrolledX:function(){return pt()/(l-u)},getPercentScrolledY:function(){return dt()/(c-a)},getIsScrollableH:function(){return v},getIsScrollableV:function(){return d},getContentPane:function(){return o},scrollToBottom:function(e){it(g,e)},hijackInternalLinks:function(){Ct()},destroy:function(){Lt()}});X(r)}t=e.extend({},e.fn.jScrollPane.defaults,t);e.each(["mouseWheelSpeed","arrowButtonSpeed","trackClickSpeed","keyboardSpeed"],function(){t[this]=t[this]||t.speed});var i;this.each(function(){var n=e(this),s=n.data("jsp");if(s){s.reinitialise(t)}else{s=new r(n,t);n.data("jsp",s)}i=i?i.add(n):n});return i};e.fn.jScrollPane.defaults={showArrows:false,maintainPosition:true,stickToBottom:false,stickToRight:false,clickOnTrack:true,autoReinitialise:false,autoReinitialiseDelay:500,verticalDragMinHeight:0,verticalDragMaxHeight:99999,horizontalDragMinWidth:0,horizontalDragMaxWidth:99999,contentWidth:n,animateScroll:false,animateDuration:300,animateEase:"linear",hijackInternalLinks:false,verticalGutter:4,horizontalGutter:4,mouseWheelSpeed:0,arrowButtonSpeed:0,arrowRepeatFreq:50,arrowScrollOnHover:false,trackClickSpeed:0,trackClickRepeatFreq:70,verticalArrowPositions:"split",horizontalArrowPositions:"split",enableKeyboardNavigation:true,hideFocus:false,keyboardSpeed:0,initialDelay:300,speed:30,scrollPagePercent:.8}})(jQuery,this);
/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery);
(function(e){function t(t,r){if(e.trim(t.text()).length<=r.condensedLength+r.minTrail){o("element too short: skipping.");return false}var i=e.trim(t.html());var s=e.trim(t.text());var u=r.delim;var a=t.clone();var f=0;var of=-1;do{var l;if(f<=of){of=of+1;l=n(i,r.delim,r.condensedLength+of+1);}else {of=f;l=n(i,r.delim,r.condensedLength+f);} a.html(e.trim(i.substring(0,l+1)));var c=a.text().length;var h=a.html().length;f=a.html().length-c;o("condensing... [html-length:"+h+" text-length:"+c+" delta: "+f+" break-point: "+l+"]")}while(f&&a.text().length<r.condensedLength);if(s.length-c<r.minTrail){o("not enough trailing text: skipping.");return false}o("clone condensed. [text-length:"+c+"]");return a}function n(e,t,n){var i=false;var s=n;do{var s=e.indexOf(t,s);if(s<0){o("No delimiter found.");return e.length}i=true;while(r(e,s)){s++;i=false}}while(!i);o("Delimiter found in html at: "+s);return s}function r(e,t){return e.indexOf(">",t)<e.indexOf("<",t)}function i(e,t){o("Condense Trigger: "+e.html());var n=e.parent();var r=n.next();r.show();var i=r.width();var s=r.height();r.hide();var u=n.width();var a=n.height();n.animate({height:s,width:i,opacity:1},t.lessSpeed,t.easing,function(){n.height(a).width(u).hide();r.show()})}function s(e,t){o("Expand Trigger: "+e.html());var n=e.parent();var r=n.prev();if(t.expandedWidth){r.width(t.expandedWidth)}r.show();var i=r.width();var s=r.height();r.width(n.width()+"px").height(n.height()+"px");n.hide();r.animate({height:s,width:i,opacity:1},t.moreSpeed,t.easing);if(n.attr("id")){var u=n.attr("id");n.attr("id","condensed_"+u);r.attr("id",u)}}function o(e){if(window.console&&window.console.log&&false){window.console.log(e)}}e.fn.condense=function(n){e.metadata?o("metadata plugin detected"):o("metadata plugin not present");var r=e.extend({},e.fn.condense.defaults,n);return this.each(function(){$this=e(this);var n=e.metadata?e.extend({},r,$this.metadata()):r;o("Condensing ["+$this.text().length+"]: "+$this.text());var u=t($this,n);if(u){$this.attr("id")?$this.attr("id","condensed_"+$this.attr("id")):false;var a=" <span class='condense_control condense_control_more' style='cursor:pointer;'>"+n.moreText+"</span>";var f=" <span class='condense_control condense_control_less' style='cursor:pointer;'>"+n.lessText+"</span>";u.append(n.ellipsis+a);$this.after(u).hide().append(f);e(".condense_control_more",u).click(function(){o("moreControl clicked.");s(e(this),n)});e(".condense_control_less",$this).click(function(){o("lessControl clicked.");i(e(this),n)})}})};e.fn.condense.defaults={condensedLength:200,minTrail:20,delim:" ",moreText:"[more]",lessText:"[less]",ellipsis:" ( ... )",moreSpeed:"normal",lessSpeed:"normal",easing:"linear"}})(jQuery);
/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);
/*!
 * jQuery Element Rotation Plugin
 *
 * Requires jQueryUI 
 *
 * Copyright (c) 2010 Pavel Markovnin
 * Dual licensed under the MIT and GPL licenses.
 *
 * http://vremenno.net
 */

(function($) {
	$.fn.rotatable = function(options) {
	
		// Default Values
		var defaults = {
 			rotatorClass: 'ui-rotatable-handle',
 			mtx:          [1, 0, 0, 1]
  		},  opts        = $.extend(defaults, options),
  		    _this       = this,
  		    _rotator;      
  		  
  		// Initialization 
  		this.intialize = function() {
        	this.createHandler();
        	
        	dims = {
				'w': _this.width(),
				'h': _this.height()
			};
        	this.updateRotationMatrix(opts.mtx);
        };
        
        // Create Rotation Handler
        this.createHandler = function() {
        	_rotator = $('<div class="'+ opts.rotatorClass+ '"></div>');
  			_this.append(_rotator);
  			
  			this.bindRotation();
        };
                
        // Bind Rotation to Handler
        this.bindRotation = function() {
        
        	// IE Fix
        	if($.browser.msie) {
	        	_rotator.mousedown(function(e) {
	        		e.stopPropagation();
	        	});
	        
	        	_rotator.mouseup(function(e) {
	        		e.stopPropagation();
	        	});
	        }
			
        	_rotator.draggable({
				handle: _rotator,
				helper: 'clone',
				revert: false,
				start:  function(e) {
        			e.stopPropagation();
        			e.stopImmediatePropagation();
        			
        			// TL Corner Coords
        			tl_coords = {
        				'x': parseInt(_this.parent().css('left')),
						'y': parseInt(_this.parent().css('top'))
        			};
        			
        			// Element Width & Height()
        			dims = {
        				'w': _this.width(),
        				'h': _this.height()
        			};
					
					// Center Coords
					center_coords = {
						'x': _this.offset().left + _this.width()  * 0.5,
						'y': _this.offset().top  + _this.height() * 0.5
					};
				},
				drag:  function(e) {
        			e.stopPropagation();
        			e.stopImmediatePropagation();
        			
					// Mouse Coords
					mouse_coords = {
						'x': e.pageX,
						'y': e.pageY
					};	
					
					angle = _this.radToDeg(_this.getAngle(mouse_coords, center_coords)) - 90;
					if($.browser.msie)
						angle = - angle;
					
					return _this.rotate(angle);
				}
        	});
        };
        
        // Get Angle
        this.getAngle = function(ms, ctr) {
        	var x     = ms.x - ctr.x,
        	    y     = - ms.y + ctr.y,
        	    hyp   = Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2)),
        	    angle = Math.acos(x / hyp);
        	
        	if (y < 0) {    
        		angle = 2 * Math.PI - angle;
        	}
        	
		    return angle;
        };
        
        // Convert from Degrees to Radians
        this.degToRad = function(d) {
        	return (d * (Math.PI / 180));
        };
        
        // Convert from Radians to Degrees
        this.radToDeg = function(r) {
        	return (r * (180 / Math.PI));
        };
        
        // Rotate Element to the Given Degree
        this.rotate = function(degree) {
        	var cos = Math.cos(_this.degToRad(-degree)),
        	    sin = Math.sin(_this.degToRad(-degree)),
        	    mtx = [cos, sin, (-sin), cos];
        	    
        	this.updateRotationMatrix(mtx);
        };
        
        // Get CSS Transform Matrix (transform: matrix)
        this.getRotationMatrix = function() {
        	var _matrix = _this.css('transform') ? _this.css('transform') : 'matrix(1, 0, 0, 1, 0, 0)';
			    _m      = _matrix.split(','),
        	    m       = [];
        	    
        	for (i = 0; i < 4; i++) {
        		m[i] = parseFloat(_m[i].replace('matrix(', ''));
        	}
        	        	
        	return m;
        };
        
        // Update CSS Transform Matrix (transform: matrix)
        this.updateRotationMatrix = function(m) {
        	var matrix = 'matrix('+ m[0] +', '+ m[1] +', '+ m[2] +', '+ m[3] +', 0, 0)',
        	    ie_matrix = "progid:DXImageTransform.Microsoft.Matrix(M11='"+m[0]+"', M12='"+m[1]+"', M21='"+m[2]+"', M22='"+m[3]+"', sizingMethod='auto expand')";        	
        	  
        	_this.css({
				'-moz-transform'   : matrix,
				'-o-transform'     : matrix,
        		'-webkit-transform': matrix,
        		'-ms-transform'    : matrix,
				'transform'        : matrix,
				'filter'           : ie_matrix,
				'-ms-filter'       : '"' + ie_matrix + '"'
			});
        	
        	// IE Fix
        	if($.browser.msie) {
        		var	coef    = dims.w / dims.h,
	        	    _height = _this.parent().parent().height()
	        	    _width  = coef * _height,
	        	    _top    = (dims.h - _height) / 2,
	        	    _left   = (dims.w - _width) / 2;
	        	
	        	_this.parent().parent().css({
	        		'width'      : _width
	        	});
	        	
	        	_this.parent().css({
	        		'left': _left,
	        		'top' : _top
	        	});
        	}
        };
        
        return this.intialize();  		
	}
})(jQuery);

// VERSION: 3.1 LAST UPDATE: 13.03.2012
/* 
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 * 
 * Made by Wilq32, wilq32@gmail.com, Wroclaw, Poland, 01.2009
 * Website: http://code.google.com/p/jqueryrotate/ 
 */

// Documentation removed from script file (was kinda useless and outdated)

(function($) {
var supportedCSS,styles=document.getElementsByTagName("head")[0].style,toCheck="transformProperty WebkitTransform OTransform msTransform MozTransform".split(" "); //MozTransform <- firefox works slower with css!!!
for (var a=0;a<toCheck.length;a++) if (styles[toCheck[a]] !== undefined) supportedCSS = toCheck[a];
// Bad eval to preven google closure to remove it from code o_O
// After compresion replace it back to var IE = 'v' == '\v'
var IE = eval('"v"=="\v"');

jQuery.fn.extend({
    rotate:function(parameters)
    {
        if (this.length===0||typeof parameters=="undefined") return;
            if (typeof parameters=="number") parameters={angle:parameters};
        var returned=[];
        for (var i=0,i0=this.length;i<i0;i++)
            {
                var element=this.get(i);	
                if (!element.Wilq32 || !element.Wilq32.PhotoEffect) {

                    var paramClone = $.extend(true, {}, parameters); 
                    var newRotObject = new Wilq32.PhotoEffect(element,paramClone)._rootObj;

                    returned.push($(newRotObject));
                }
                else { 
                    element.Wilq32.PhotoEffect._handleRotation(parameters);
                }
            }
            return returned;
    },
    getRotateAngle: function(){
        var ret = [];
        for (var i=0,i0=this.length;i<i0;i++)
            {
                var element=this.get(i);	
                if (element.Wilq32 && element.Wilq32.PhotoEffect) {
                    ret[i] = element.Wilq32.PhotoEffect._angle;
                }
            }
            return ret;
    },
    stopRotate: function(){
        for (var i=0,i0=this.length;i<i0;i++)
            {
                var element=this.get(i);	
                if (element.Wilq32 && element.Wilq32.PhotoEffect) {
                    clearTimeout(element.Wilq32.PhotoEffect._timer);
                }
            }
    }
});

// Library agnostic interface

Wilq32=window.Wilq32||{};
Wilq32.PhotoEffect=(function(){

	if (supportedCSS) {
		return function(img,parameters){
			img.Wilq32 = {PhotoEffect: this};
            
            this._img = this._rootObj = this._eventObj = img;
            this._handleRotation(parameters);
		}
	} else if (IE) {
		return function(img,parameters) {
			// Make sure that class and id are also copied - just in case you would like to refeer to an newly created object
            this._img = img;

			this._rootObj=document.createElement('span');
			this._rootObj.style.display="inline-block";
			this._rootObj.Wilq32 = {PhotoEffect: this};
			img.parentNode.insertBefore(this._rootObj,img);
            this._Loader(parameters);
		}
	} else {
        return function(img,parameters){
            // Just for now... Dont do anything if CSS3 is not supported
        this._rootObj = img;
        }
    }
})();

Wilq32.PhotoEffect.prototype={
    _setupParameters : function (parameters){
		this._parameters = this._parameters || {};
        if (typeof this._angle !== "number") this._angle = 0 ;
        if (typeof parameters.angle==="number") this._angle = parameters.angle;
        this._parameters.animateTo = (typeof parameters.animateTo==="number") ? (parameters.animateTo) : (this._angle); 

        this._parameters.step = parameters.step || this._parameters.step || null;
		this._parameters.easing = parameters.easing || this._parameters.easing || function (x, t, b, c, d) { return -c * ((t=t/d-1)*t*t*t - 1) + b; }
		this._parameters.duration = parameters.duration || this._parameters.duration || 1000;
        this._parameters.callback = parameters.callback || this._parameters.callback || function(){};
        if (parameters.bind && parameters.bind != this._parameters.bind) this._BindEvents(parameters.bind); 
	},
	_handleRotation : function(parameters){
          this._setupParameters(parameters);
          if (this._angle==this._parameters.animateTo) {
              this._rotate(this._angle);
          }
          else { 
              this._animateStart();          
          }
	},

	_BindEvents:function(events){
		if (events && this._eventObj) 
		{
            // Unbinding previous Events
            if (this._parameters.bind){
                var oldEvents = this._parameters.bind;
                for (var a in oldEvents) if (oldEvents.hasOwnProperty(a)) 
                        // TODO: Remove jQuery dependency
                        jQuery(this._eventObj).unbind(a,oldEvents[a]);
            }

            this._parameters.bind = events;
			for (var a in events) if (events.hasOwnProperty(a)) 
				// TODO: Remove jQuery dependency
					jQuery(this._eventObj).bind(a,events[a]);
		}
	},

	_Loader: function(parameters)
		{
			var width=this._img.width;
			var height=this._img.height;
			//this._img.parentNode.removeChild(this._img);
            //this._rootObj.parentNode.removeChild(this._rootObj);
            
            this._rootObj.appendChild(this._img);

            this._rootObj.style.width = this._img.offsetWidth;
            this._rootObj.style.height = this._img.offsetHeight;

            this._img.style.position = "absolute";

            this._rootObj = this._img;
            this._rootObj.Wilq32 = {PhotoEffect: this}

            this._rootObj.style.filter += "progid:DXImageTransform.Microsoft.Matrix(M11=1,M12=1,M21=1,M22=1,sizingMethod='auto expand')";

		    this._eventObj = this._rootObj;	
		    this._handleRotation(parameters);
		},

	_animateStart:function()
	{	
		if (this._timer) {
			clearTimeout(this._timer);
		}
		this._animateStartTime = +new Date;
		this._animateStartAngle = this._angle;
		this._animate();
	},
    _animate:function()
    {
         var actualTime = +new Date;
         var checkEnd = actualTime - this._animateStartTime > this._parameters.duration;

         // TODO: Bug for animatedGif for static rotation ? (to test)
         if (checkEnd && !this._parameters.animatedGif) 
         {
             clearTimeout(this._timer);
         }
         else 
         {
             if (this._canvas||this._vimage||this._img) {
                 var angle = this._parameters.easing(0, actualTime - this._animateStartTime, this._animateStartAngle, this._parameters.animateTo - this._animateStartAngle, this._parameters.duration);
                 this._rotate((~~(angle*10))/10);
             }
             if (this._parameters.step) {
                this._parameters.step(this._angle);
             }
             var self = this;
             this._timer = setTimeout(function()
                     {
                     self._animate.call(self);
                     }, 10);
         }

         // To fix Bug that prevents using recursive function in callback I moved this function to back
         if (this._parameters.callback && checkEnd){
             this._angle = this._parameters.animateTo;
             this._rotate(this._angle);
             this._parameters.callback.call(this._rootObj);
         }
     },

	_rotate : (function()
	{
		var rad = Math.PI/180;
		if (IE)
		return function(angle)
		{
            this._angle = angle;
			//this._container.style.rotation=(angle%360)+"deg";
            var _rad = angle * rad ;
            costheta = Math.cos(_rad);
            sintheta = Math.sin(_rad);
            var fil = this._rootObj.filters.item("DXImageTransform.Microsoft.Matrix");
            fil.M11=costheta; fil.M12=-sintheta; fil.M21=sintheta; fil.M22=costheta;

            this._rootObj.style.marginLeft = -(this._rootObj.offsetWidth - this._rootObj.clientWidth)/2 +"px";
            this._rootObj.style.marginTop = -(this._rootObj.offsetHeight - this._rootObj.clientHeight)/2 +"px";
		}
		else if (supportedCSS)
		return function(angle){
            this._angle = angle;
			this._img.style[supportedCSS]="rotate("+(angle%360)+"deg)";
		}

	})()
}
})(jQuery);
/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals.
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function raw(s) {
		return s;
	}

	function decoded(s) {
		return decodeURIComponent(s.replace(pluses, ' '));
	}

	function converted(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}
		try {
			return config.json ? JSON.parse(s) : s;
		} catch(er) {}
	}

	var config = $.cookie = function (key, value, options) {

		// write
		if (value !== undefined) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = config.json ? JSON.stringify(value) : String(value);

			return (document.cookie = [
				config.raw ? key : encodeURIComponent(key),
				'=',
				config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// read
		var decode = config.raw ? raw : decoded;
		var cookies = document.cookie.split('; ');
		var result = key ? undefined : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = decode(parts.join('='));

			if (key && key === name) {
				result = converted(cookie);
				break;
			}

			if (!key) {
				result[name] = converted(cookie);
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) !== undefined) {
			$.cookie(key, '', $.extend(options, { expires: -1 }));
			return true;
		}
		return false;
	};

}));

/* turn.js 4.1.0 | Copyright (c) 2012 Emmanuel Garcia | turnjs.com | turnjs.com/license.txt */

(function(f){function I(a,b,c){if(!c[0]||"object"==typeof c[0])return b.init.apply(a,c);if(b[c[0]])return b[c[0]].apply(a,Array.prototype.slice.call(c,1));throw p(c[0]+" is not a method or property");}function l(a,b,c,d){return{css:{position:"absolute",top:a,left:b,overflow:d||"hidden",zIndex:c||"auto"}}}function R(a,b,c,d,e){var h=1-e,f=h*h*h,g=e*e*e;return j(Math.round(f*a.x+3*e*h*h*b.x+3*e*e*h*c.x+g*d.x),Math.round(f*a.y+3*e*h*h*b.y+3*e*e*h*c.y+g*d.y))}function j(a,b){return{x:a,y:b}}function E(a,
b,c){return y&&c?" translate3d("+a+"px,"+b+"px, 0px) ":" translate("+a+"px, "+b+"px) "}function F(a){return" rotate("+a+"deg) "}function n(a,b){return Object.prototype.hasOwnProperty.call(b,a)}function S(){for(var a=["Moz","Webkit","Khtml","O","ms"],b=a.length,c="";b--;)a[b]+"Transform"in document.body.style&&(c="-"+a[b].toLowerCase()+"-");return c}function O(a,b,c,d,e){var h,f=[];if("-webkit-"==v){for(h=0;h<e;h++)f.push("color-stop("+d[h][0]+", "+d[h][1]+")");a.css({"background-image":"-webkit-gradient(linear, "+
b.x+"% "+b.y+"%,"+c.x+"% "+c.y+"%, "+f.join(",")+" )"})}else{var b={x:b.x/100*a.width(),y:b.y/100*a.height()},c={x:c.x/100*a.width(),y:c.y/100*a.height()},g=c.x-b.x;h=c.y-b.y;var i=Math.atan2(h,g),w=i-Math.PI/2,w=Math.abs(a.width()*Math.sin(w))+Math.abs(a.height()*Math.cos(w)),g=Math.sqrt(h*h+g*g),c=j(c.x<b.x?a.width():0,c.y<b.y?a.height():0),k=Math.tan(i);h=-1/k;k=(h*c.x-c.y-k*b.x+b.y)/(h-k);c=h*k-h*c.x+c.y;b=Math.sqrt(Math.pow(k-b.x,2)+Math.pow(c-b.y,2));for(h=0;h<e;h++)f.push(" "+d[h][1]+" "+100*
(b+g*d[h][0])/w+"%");a.css({"background-image":v+"linear-gradient("+-i+"rad,"+f.join(",")+")"})}}function s(a,b,c){a=f.Event(a);b.trigger(a,c);return a.isDefaultPrevented()?"prevented":a.isPropagationStopped()?"stopped":""}function p(a){function b(a){this.name="TurnJsError";this.message=a}b.prototype=Error();b.prototype.constructor=b;return new b(a)}function C(a){var b={top:0,left:0};do b.left+=a.offsetLeft,b.top+=a.offsetTop;while(a=a.offsetParent);return b}var y,T,v="",J=Math.PI,K=J/2,t="ontouchstart"in
window,q=t?{down:"touchstart",move:"touchmove",up:"touchend",over:"touchstart",out:"touchend"}:{down:"mousedown",move:"mousemove",up:"mouseup",over:"mouseover",out:"mouseout"},o={backward:["bl","tl"],forward:["br","tr"],all:"tl bl tr br l r".split(" ")},U=["single","double"],V=["ltr","rtl"],W={acceleration:!0,display:"double",duration:600,page:1,gradients:!0,turnCorners:"bl,br",when:null},X={cornerSize:100},g={init:function(a){y="WebKitCSSMatrix"in window||"MozPerspective"in document.body.style;var b;
T=(b=/AppleWebkit\/([0-9\.]+)/i.exec(navigator.userAgent))?534.3<parseFloat(b[1]):!0;v=S();var c;b=0;var d=this.data(),e=this.children(),a=f.extend({width:this.width(),height:this.height(),direction:this.attr("dir")||this.css("direction")||"ltr"},W,a);d.opts=a;d.pageObjs={};d.pages={};d.pageWrap={};d.pageZoom={};d.pagePlace={};d.pageMv=[];d.zoom=1;d.totalPages=a.pages||0;d.eventHandlers={touchStart:f.proxy(g._touchStart,this),touchMove:f.proxy(g._touchMove,this),touchEnd:f.proxy(g._touchEnd,this),
start:f.proxy(g._eventStart,this)};if(a.when)for(c in a.when)n(c,a.when)&&this.bind(c,a.when[c]);this.css({position:"relative",width:a.width,height:a.height});this.turn("display",a.display);""!==a.direction&&this.turn("direction",a.direction);y&&(!t&&a.acceleration)&&this.transform(E(0,0,!0));for(c=0;c<e.length;c++)"1"!=f(e[c]).attr("ignore")&&this.turn("addPage",e[c],++b);f(this).bind(q.down,d.eventHandlers.touchStart).bind("end",g._eventEnd).bind("pressed",g._eventPressed).bind("released",g._eventReleased).bind("flip",
g._flip);f(this).parent().bind("start",d.eventHandlers.start);f(document).bind(q.move,d.eventHandlers.touchMove).bind(q.up,d.eventHandlers.touchEnd);this.turn("page",a.page);d.done=!0;return this},addPage:function(a,b){var c,d=!1,e=this.data(),h=e.totalPages+1;if(e.destroying)return!1;if(c=/\bp([0-9]+)\b/.exec(f(a).attr("class")))b=parseInt(c[1],10);if(b)if(b==h)d=!0;else{if(b>h)throw p('Page "'+b+'" cannot be inserted');}else b=h,d=!0;1<=b&&b<=h&&(c="double"==e.display?b%2?" odd":" even":"",e.done&&
this.turn("stop"),b in e.pageObjs&&g._movePages.call(this,b,1),d&&(e.totalPages=h),e.pageObjs[b]=f(a).css({"float":"left"}).addClass("page p"+b+c),-1!=navigator.userAgent.indexOf("MSIE 9.0")&&e.pageObjs[b].hasClass("hard")&&e.pageObjs[b].removeClass("hard"),g._addPage.call(this,b),g._removeFromDOM.call(this));return this},_addPage:function(a){var b=this.data(),c=b.pageObjs[a];if(c)if(g._necessPage.call(this,a)){if(!b.pageWrap[a]){b.pageWrap[a]=f("<div/>",{"class":"page-wrapper",page:a,css:{position:"absolute",
overflow:"hidden"}});this.append(b.pageWrap[a]);b.pagePlace[a]||(b.pagePlace[a]=a,b.pageObjs[a].appendTo(b.pageWrap[a]));var d=g._pageSize.call(this,a,!0);c.css({width:d.width,height:d.height});b.pageWrap[a].css(d)}b.pagePlace[a]==a&&g._makeFlip.call(this,a)}else b.pagePlace[a]=0,b.pageObjs[a]&&b.pageObjs[a].remove()},hasPage:function(a){return n(a,this.data().pageObjs)},center:function(a){var b=this.data(),c=f(this).turn("size"),d=0;b.noCenter||("double"==b.display&&(a=this.turn("view",a||b.tpage||
b.page),"ltr"==b.direction?a[0]?a[1]||(d+=c.width/4):d-=c.width/4:a[0]?a[1]||(d-=c.width/4):d+=c.width/4),f(this).css({marginLeft:d}));return this},destroy:function(){var a=this,b=this.data(),c="end first flip last pressed released start turning turned zooming missing".split(" ");if("prevented"!=s("destroying",this)){b.destroying=!0;f.each(c,function(b,c){a.unbind(c)});this.parent().unbind("start",b.eventHandlers.start);for(f(document).unbind(q.move,b.eventHandlers.touchMove).unbind(q.up,b.eventHandlers.touchEnd);0!==
b.totalPages;)this.turn("removePage",b.totalPages);b.fparent&&b.fparent.remove();b.shadow&&b.shadow.remove();this.removeData();b=null;return this}},is:function(){return"object"==typeof this.data().pages},zoom:function(a){var b=this.data();if("number"==typeof a){if(0.0010>a||100<a)throw p(a+" is not a value for zoom");if("prevented"==s("zooming",this,[a,b.zoom]))return this;var c=this.turn("size"),d=this.turn("view"),e=1/b.zoom,h=Math.round(c.width*e*a),c=Math.round(c.height*e*a);b.zoom=a;f(this).turn("stop").turn("size",
h,c);b.opts.autoCenter&&this.turn("center");g._updateShadow.call(this);for(a=0;a<d.length;a++)d[a]&&b.pageZoom[d[a]]!=b.zoom&&(this.trigger("zoomed",[d[a],d,b.pageZoom[d[a]],b.zoom]),b.pageZoom[d[a]]=b.zoom);return this}return b.zoom},_pageSize:function(a,b){var c=this.data(),d={};if("single"==c.display)d.width=this.width(),d.height=this.height(),b&&(d.top=0,d.left=0,d.right="auto");else{var e=this.width()/2,h=this.height();c.pageObjs[a].hasClass("own-size")?(d.width=c.pageObjs[a].width(),d.height=
c.pageObjs[a].height()):(d.width=e,d.height=h);if(b){var f=a%2;d.top=(h-d.height)/2;"ltr"==c.direction?(d[f?"right":"left"]=e-d.width,d[f?"left":"right"]="auto"):(d[f?"left":"right"]=e-d.width,d[f?"right":"left"]="auto")}}return d},_makeFlip:function(a){var b=this.data();if(!b.pages[a]&&b.pagePlace[a]==a){var c="single"==b.display,d=a%2;b.pages[a]=b.pageObjs[a].css(g._pageSize.call(this,a)).flip({page:a,next:d||c?a+1:a-1,turn:this}).flip("disable",b.disabled);g._setPageLoc.call(this,a);b.pageZoom[a]=
b.zoom}return b.pages[a]},_makeRange:function(){var a,b;if(!(1>this.data().totalPages)){b=this.turn("range");for(a=b[0];a<=b[1];a++)g._addPage.call(this,a)}},range:function(a){var b,c,d,e=this.data(),a=a||e.tpage||e.page||1;d=g._view.call(this,a);if(1>a||a>e.totalPages)throw p('"'+a+'" is not a valid page');d[1]=d[1]||d[0];1<=d[0]&&d[1]<=e.totalPages?(a=Math.floor(2),e.totalPages-d[1]>d[0]?(b=Math.min(d[0]-1,a),c=2*a-b):(c=Math.min(e.totalPages-d[1],a),b=2*a-c)):c=b=5;return[Math.max(1,d[0]-b),Math.min(e.totalPages,
d[1]+c)]},_necessPage:function(a){if(0===a)return!0;var b=this.turn("range");return this.data().pageObjs[a].hasClass("fixed")||a>=b[0]&&a<=b[1]},_removeFromDOM:function(){var a,b=this.data();for(a in b.pageWrap)n(a,b.pageWrap)&&!g._necessPage.call(this,a)&&g._removePageFromDOM.call(this,a)},_removePageFromDOM:function(a){var b=this.data();if(b.pages[a]){var c=b.pages[a].data();i._moveFoldingPage.call(b.pages[a],!1);c.f&&c.f.fwrapper&&c.f.fwrapper.remove();b.pages[a].removeData();b.pages[a].remove();
delete b.pages[a]}b.pageObjs[a]&&b.pageObjs[a].remove();b.pageWrap[a]&&(b.pageWrap[a].remove(),delete b.pageWrap[a]);g._removeMv.call(this,a);delete b.pagePlace[a];delete b.pageZoom[a]},removePage:function(a){var b=this.data();if("*"==a)for(;0!==b.totalPages;)this.turn("removePage",b.totalPages);else{if(1>a||a>b.totalPages)throw p("The page "+a+" doesn't exist");b.pageObjs[a]&&(this.turn("stop"),g._removePageFromDOM.call(this,a),delete b.pageObjs[a]);g._movePages.call(this,a,-1);b.totalPages-=1;b.page>
b.totalPages?(b.page=null,g._fitPage.call(this,b.totalPages)):(g._makeRange.call(this),this.turn("update"))}return this},_movePages:function(a,b){var c,d=this,e=this.data(),h="single"==e.display,f=function(a){var c=a+b,f=c%2,i=f?" odd ":" even ";e.pageObjs[a]&&(e.pageObjs[c]=e.pageObjs[a].removeClass("p"+a+" odd even").addClass("p"+c+i));e.pagePlace[a]&&e.pageWrap[a]&&(e.pagePlace[c]=c,e.pageWrap[c]=e.pageObjs[c].hasClass("fixed")?e.pageWrap[a].attr("page",c):e.pageWrap[a].css(g._pageSize.call(d,
c,!0)).attr("page",c),e.pages[a]&&(e.pages[c]=e.pages[a].flip("options",{page:c,next:h||f?c+1:c-1})),b&&(delete e.pages[a],delete e.pagePlace[a],delete e.pageZoom[a],delete e.pageObjs[a],delete e.pageWrap[a]))};if(0<b)for(c=e.totalPages;c>=a;c--)f(c);else for(c=a;c<=e.totalPages;c++)f(c)},display:function(a){var b=this.data(),c=b.display;if(void 0===a)return c;if(-1==f.inArray(a,U))throw p('"'+a+'" is not a value for display');switch(a){case "single":b.pageObjs[0]||(this.turn("stop").css({overflow:"hidden"}),
b.pageObjs[0]=f("<div />",{"class":"page p-temporal"}).css({width:this.width(),height:this.height()}).appendTo(this));this.addClass("shadow");break;case "double":b.pageObjs[0]&&(this.turn("stop").css({overflow:""}),b.pageObjs[0].remove(),delete b.pageObjs[0]),this.removeClass("shadow")}b.display=a;c&&(a=this.turn("size"),g._movePages.call(this,1,0),this.turn("size",a.width,a.height).turn("update"));return this},direction:function(a){var b=this.data();if(void 0===a)return b.direction;a=a.toLowerCase();
if(-1==f.inArray(a,V))throw p('"'+a+'" is not a value for direction');"rtl"==a&&f(this).attr("dir","ltr").css({direction:"ltr"});b.direction=a;b.done&&this.turn("size",f(this).width(),f(this).height());return this},animating:function(){return 0<this.data().pageMv.length},corner:function(){var a,b,c=this.data();for(b in c.pages)if(n(b,c.pages)&&(a=c.pages[b].flip("corner")))return a;return!1},data:function(){return this.data()},disable:function(a){var b,c=this.data(),d=this.turn("view");c.disabled=
void 0===a||!0===a;for(b in c.pages)n(b,c.pages)&&c.pages[b].flip("disable",c.disabled?!0:-1==f.inArray(parseInt(b,10),d));return this},disabled:function(a){return void 0===a?!0===this.data().disabled:this.turn("disable",a)},size:function(a,b){if(void 0===a||void 0===b)return{width:this.width(),height:this.height()};this.turn("stop");var c,d,e=this.data();d="double"==e.display?a/2:a;this.css({width:a,height:b});e.pageObjs[0]&&e.pageObjs[0].css({width:d,height:b});for(c in e.pageWrap)n(c,e.pageWrap)&&
(d=g._pageSize.call(this,c,!0),e.pageObjs[c].css({width:d.width,height:d.height}),e.pageWrap[c].css(d),e.pages[c]&&e.pages[c].css({width:d.width,height:d.height}));this.turn("resize");return this},resize:function(){var a,b=this.data();b.pages[0]&&(b.pageWrap[0].css({left:-this.width()}),b.pages[0].flip("resize",!0));for(a=1;a<=b.totalPages;a++)b.pages[a]&&b.pages[a].flip("resize",!0);g._updateShadow.call(this);b.opts.autoCenter&&this.turn("center")},_removeMv:function(a){var b,c=this.data();for(b=
0;b<c.pageMv.length;b++)if(c.pageMv[b]==a)return c.pageMv.splice(b,1),!0;return!1},_addMv:function(a){var b=this.data();g._removeMv.call(this,a);b.pageMv.push(a)},_view:function(a){var b=this.data(),a=a||b.page;return"double"==b.display?a%2?[a-1,a]:[a,a+1]:[a]},view:function(a){var b=this.data(),a=g._view.call(this,a);return"double"==b.display?[0<a[0]?a[0]:0,a[1]<=b.totalPages?a[1]:0]:[0<a[0]&&a[0]<=b.totalPages?a[0]:0]},stop:function(a,b){if(this.turn("animating")){var c,d,e,h=this.data();h.tpage&&
(h.page=h.tpage,delete h.tpage);for(c=0;c<h.pageMv.length;c++)h.pageMv[c]&&h.pageMv[c]!==a&&(e=h.pages[h.pageMv[c]],d=e.data().f.opts,e.flip("hideFoldedPage",b),b||i._moveFoldingPage.call(e,!1),d.force&&(d.next=0===d.page%2?d.page-1:d.page+1,delete d.force))}this.turn("update");return this},pages:function(a){var b=this.data();if(a){if(a<b.totalPages)for(var c=b.totalPages;c>a;c--)this.turn("removePage",c);b.totalPages=a;g._fitPage.call(this,b.page);return this}return b.totalPages},_missing:function(a){var b=
this.data();if(!(1>b.totalPages)){for(var c=this.turn("range",a),d=[],a=c[0];a<=c[1];a++)b.pageObjs[a]||d.push(a);0<d.length&&this.trigger("missing",[d])}},_fitPage:function(a){var b=this.data(),c=this.turn("view",a);g._missing.call(this,a);if(b.pageObjs[a]){b.page=a;this.turn("stop");for(var d=0;d<c.length;d++)c[d]&&b.pageZoom[c[d]]!=b.zoom&&(this.trigger("zoomed",[c[d],c,b.pageZoom[c[d]],b.zoom]),b.pageZoom[c[d]]=b.zoom);g._removeFromDOM.call(this);g._makeRange.call(this);g._updateShadow.call(this);
this.trigger("turned",[a,c]);this.turn("update");b.opts.autoCenter&&this.turn("center")}},_turnPage:function(a){var b,c,d=this.data(),e=d.pagePlace[a],h=this.turn("view"),i=this.turn("view",a);if(d.page!=a){var j=d.page;if("prevented"==s("turning",this,[a,i])){j==d.page&&-1!=f.inArray(e,d.pageMv)&&d.pages[e].flip("hideFoldedPage",!0);return}-1!=f.inArray(1,i)&&this.trigger("first");-1!=f.inArray(d.totalPages,i)&&this.trigger("last")}"single"==d.display?(b=h[0],c=i[0]):h[1]&&a>h[1]?(b=h[1],c=i[0]):
h[0]&&a<h[0]&&(b=h[0],c=i[1]);e=d.opts.turnCorners.split(",");h=d.pages[b].data().f;i=h.opts;j=h.point;g._missing.call(this,a);d.pageObjs[a]&&(this.turn("stop"),d.page=a,g._makeRange.call(this),d.tpage=c,i.next!=c&&(i.next=c,i.force=!0),this.turn("update"),h.point=j,"hard"==h.effect?"ltr"==d.direction?d.pages[b].flip("turnPage",a>b?"r":"l"):d.pages[b].flip("turnPage",a>b?"l":"r"):"ltr"==d.direction?d.pages[b].flip("turnPage",e[a>b?1:0]):d.pages[b].flip("turnPage",e[a>b?0:1]))},page:function(a){var b=
this.data();if(void 0===a)return b.page;if(!b.disabled&&!b.destroying){a=parseInt(a,10);if(0<a&&a<=b.totalPages)return a!=b.page&&(!b.done||-1!=f.inArray(a,this.turn("view"))?g._fitPage.call(this,a):g._turnPage.call(this,a)),this;throw p("The page "+a+" does not exist");}},next:function(){return this.turn("page",Math.min(this.data().totalPages,g._view.call(this,this.data().page).pop()+1))},previous:function(){return this.turn("page",Math.max(1,g._view.call(this,this.data().page).shift()-1))},peel:function(a,
b){var c=this.data(),d=this.turn("view"),b=void 0===b?!0:!0===b;!1===a?this.turn("stop",null,b):"single"==c.display?c.pages[c.page].flip("peel",a,b):(d="ltr"==c.direction?-1!=a.indexOf("l")?d[0]:d[1]:-1!=a.indexOf("l")?d[1]:d[0],c.pages[d]&&c.pages[d].flip("peel",a,b));return this},_addMotionPage:function(){var a=f(this).data().f.opts,b=a.turn;b.data();g._addMv.call(b,a.page)},_eventStart:function(a,b,c){var d=b.turn.data(),e=d.pageZoom[b.page];a.isDefaultPrevented()||(e&&e!=d.zoom&&(b.turn.trigger("zoomed",
[b.page,b.turn.turn("view",b.page),e,d.zoom]),d.pageZoom[b.page]=d.zoom),"single"==d.display&&c&&("l"==c.charAt(1)&&"ltr"==d.direction||"r"==c.charAt(1)&&"rtl"==d.direction?(b.next=b.next<b.page?b.next:b.page-1,b.force=!0):b.next=b.next>b.page?b.next:b.page+1),g._addMotionPage.call(a.target));g._updateShadow.call(b.turn)},_eventEnd:function(a,b,c){f(a.target).data();var a=b.turn,d=a.data();if(c){if(c=d.tpage||d.page,c==b.next||c==b.page)delete d.tpage,g._fitPage.call(a,c||b.next,!0)}else g._removeMv.call(a,
b.page),g._updateShadow.call(a),a.turn("update")},_eventPressed:function(a){var a=f(a.target).data().f,b=a.opts.turn;b.data().mouseAction=!0;b.turn("update");return a.time=(new Date).getTime()},_eventReleased:function(a,b){var c;c=f(a.target);var d=c.data().f,e=d.opts.turn,h=e.data();c="single"==h.display?"br"==b.corner||"tr"==b.corner?b.x<c.width()/2:b.x>c.width()/2:0>b.x||b.x>c.width();if(200>(new Date).getTime()-d.time||c)a.preventDefault(),g._turnPage.call(e,d.opts.next);h.mouseAction=!1},_flip:function(a){a.stopPropagation();
a=f(a.target).data().f.opts;a.turn.trigger("turn",[a.next]);a.turn.data().opts.autoCenter&&a.turn.turn("center",a.next)},_touchStart:function(){var a=this.data(),b;for(b in a.pages)if(n(b,a.pages)&&!1===i._eventStart.apply(a.pages[b],arguments))return!1},_touchMove:function(){var a=this.data(),b;for(b in a.pages)n(b,a.pages)&&i._eventMove.apply(a.pages[b],arguments)},_touchEnd:function(){var a=this.data(),b;for(b in a.pages)n(b,a.pages)&&i._eventEnd.apply(a.pages[b],arguments)},calculateZ:function(a){var b,
c,d,e,h=this,f=this.data();b=this.turn("view");var i=b[0]||b[1],g=a.length-1,j={pageZ:{},partZ:{},pageV:{}},k=function(a){a=h.turn("view",a);a[0]&&(j.pageV[a[0]]=!0);a[1]&&(j.pageV[a[1]]=!0)};for(b=0;b<=g;b++)c=a[b],d=f.pages[c].data().f.opts.next,e=f.pagePlace[c],k(c),k(d),c=f.pagePlace[d]==d?d:c,j.pageZ[c]=f.totalPages-Math.abs(i-c),j.partZ[e]=2*f.totalPages-g+b;return j},update:function(){var a,b=this.data();if(this.turn("animating")&&0!==b.pageMv[0]){var c,d=this.turn("calculateZ",b.pageMv),e=
this.turn("corner"),h=this.turn("view"),i=this.turn("view",b.tpage);for(a in b.pageWrap)if(n(a,b.pageWrap)&&(c=b.pageObjs[a].hasClass("fixed"),b.pageWrap[a].css({display:d.pageV[a]||c?"":"none",zIndex:(b.pageObjs[a].hasClass("hard")?d.partZ[a]:d.pageZ[a])||(c?-1:0)}),c=b.pages[a]))c.flip("z",d.partZ[a]||null),d.pageV[a]&&c.flip("resize"),b.tpage?c.flip("hover",!1).flip("disable",-1==f.inArray(parseInt(a,10),b.pageMv)&&a!=i[0]&&a!=i[1]):c.flip("hover",!1===e).flip("disable",a!=h[0]&&a!=h[1])}else for(a in b.pageWrap)n(a,
b.pageWrap)&&(d=g._setPageLoc.call(this,a),b.pages[a]&&b.pages[a].flip("disable",b.disabled||1!=d).flip("hover",!0).flip("z",null));return this},_updateShadow:function(){var a,b,c=this.data(),d=this.width(),e=this.height(),h="single"==c.display?d:d/2;a=this.turn("view");c.shadow||(c.shadow=f("<div />",{"class":"shadow",css:l(0,0,0).css}).appendTo(this));for(var i=0;i<c.pageMv.length&&a[0]&&a[1];i++)a=this.turn("view",c.pages[c.pageMv[i]].data().f.opts.next),b=this.turn("view",c.pageMv[i]),a[0]=a[0]&&
b[0],a[1]=a[1]&&b[1];switch(a[0]?a[1]?3:"ltr"==c.direction?2:1:"ltr"==c.direction?1:2){case 1:c.shadow.css({width:h,height:e,top:0,left:h});break;case 2:c.shadow.css({width:h,height:e,top:0,left:0});break;case 3:c.shadow.css({width:d,height:e,top:0,left:0})}},_setPageLoc:function(a){var b=this.data(),c=this.turn("view"),d=0;if(a==c[0]||a==c[1])d=1;else if("single"==b.display&&a==c[0]+1||"double"==b.display&&a==c[0]-2||a==c[1]+2)d=2;if(!this.turn("animating"))switch(d){case 1:b.pageWrap[a].css({zIndex:b.totalPages,
display:""});break;case 2:b.pageWrap[a].css({zIndex:b.totalPages-1,display:""});break;case 0:b.pageWrap[a].css({zIndex:0,display:b.pageObjs[a].hasClass("fixed")?"":"none"})}return d},options:function(a){if(void 0===a)return this.data().opts;var b=this.data();f.extend(b.opts,a);a.pages&&this.turn("pages",a.pages);a.page&&this.turn("page",a.page);a.display&&this.turn("display",a.display);a.direction&&this.turn("direction",a.direction);a.width&&a.height&&this.turn("size",a.width,a.height);if(a.when)for(var c in a.when)n(c,
a.when)&&this.unbind(c).bind(c,a.when[c]);return this},version:function(){return"4.1.0"}},i={init:function(a){this.data({f:{disabled:!1,hover:!1,effect:this.hasClass("hard")?"hard":"sheet"}});this.flip("options",a);i._addPageWrapper.call(this);return this},setData:function(a){var b=this.data();b.f=f.extend(b.f,a);return this},options:function(a){var b=this.data().f;return a?(i.setData.call(this,{opts:f.extend({},b.opts||X,a)}),this):b.opts},z:function(a){var b=this.data().f;b.opts["z-index"]=a;b.fwrapper&&
b.fwrapper.css({zIndex:a||parseInt(b.parent.css("z-index"),10)||0});return this},_cAllowed:function(){var a=this.data().f,b=a.opts.page,c=a.opts.turn.data(),d=b%2;return"hard"==a.effect?"ltr"==c.direction?[d?"r":"l"]:[d?"l":"r"]:"single"==c.display?1==b?"ltr"==c.direction?o.forward:o.backward:b==c.totalPages?"ltr"==c.direction?o.backward:o.forward:o.all:"ltr"==c.direction?o[d?"forward":"backward"]:o[d?"backward":"forward"]},_cornerActivated:function(a){var b=this.data().f,c=this.width(),d=this.height(),
a={x:a.x,y:a.y,corner:""},e=b.opts.cornerSize;if(0>=a.x||0>=a.y||a.x>=c||a.y>=d)return!1;var h=i._cAllowed.call(this);switch(b.effect){case "hard":if(a.x>c-e)a.corner="r";else if(a.x<e)a.corner="l";else return!1;break;case "sheet":if(a.y<e)a.corner+="t";else if(a.y>=d-e)a.corner+="b";else return!1;if(a.x<=e)a.corner+="l";else if(a.x>=c-e)a.corner+="r";else return!1}return!a.corner||-1==f.inArray(a.corner,h)?!1:a},_isIArea:function(a){var b=this.data().f.parent.offset(),a=t&&a.originalEvent?a.originalEvent.touches[0]:
a;return i._cornerActivated.call(this,{x:a.pageX-b.left,y:a.pageY-b.top})},_c:function(a,b){b=b||0;switch(a){case "tl":return j(b,b);case "tr":return j(this.width()-b,b);case "bl":return j(b,this.height()-b);case "br":return j(this.width()-b,this.height()-b);case "l":return j(b,0);case "r":return j(this.width()-b,0)}},_c2:function(a){switch(a){case "tl":return j(2*this.width(),0);case "tr":return j(-this.width(),0);case "bl":return j(2*this.width(),this.height());case "br":return j(-this.width(),
this.height());case "l":return j(2*this.width(),0);case "r":return j(-this.width(),0)}},_foldingPage:function(){var a=this.data().f;if(a){var b=a.opts;if(b.turn)return a=b.turn.data(),"single"==a.display?1<b.next||1<b.page?a.pageObjs[0]:null:a.pageObjs[b.next]}},_backGradient:function(){var a=this.data().f,b=a.opts.turn.data();if((b=b.opts.gradients&&("single"==b.display||2!=a.opts.page&&a.opts.page!=b.totalPages-1))&&!a.bshadow)a.bshadow=f("<div/>",l(0,0,1)).css({position:"",width:this.width(),height:this.height()}).appendTo(a.parent);
return b},type:function(){return this.data().f.effect},resize:function(a){var b=this.data().f,c=b.opts.turn.data(),d=this.width(),e=this.height();switch(b.effect){case "hard":a&&(b.wrapper.css({width:d,height:e}),b.fpage.css({width:d,height:e}),c.opts.gradients&&(b.ashadow.css({width:d,height:e}),b.bshadow.css({width:d,height:e})));break;case "sheet":a&&(a=Math.round(Math.sqrt(Math.pow(d,2)+Math.pow(e,2))),b.wrapper.css({width:a,height:a}),b.fwrapper.css({width:a,height:a}).children(":first-child").css({width:d,
height:e}),b.fpage.css({width:d,height:e}),c.opts.gradients&&b.ashadow.css({width:d,height:e}),i._backGradient.call(this)&&b.bshadow.css({width:d,height:e})),b.parent.is(":visible")&&(c=C(b.parent[0]),b.fwrapper.css({top:c.top,left:c.left}),c=C(b.opts.turn[0]),b.fparent.css({top:-c.top,left:-c.left})),this.flip("z",b.opts["z-index"])}},_addPageWrapper:function(){var a=this.data().f,b=a.opts.turn.data(),c=this.parent();a.parent=c;if(!a.wrapper)switch(a.effect){case "hard":var d={};d[v+"transform-style"]=
"preserve-3d";d[v+"backface-visibility"]="hidden";a.wrapper=f("<div/>",l(0,0,2)).css(d).appendTo(c).prepend(this);a.fpage=f("<div/>",l(0,0,1)).css(d).appendTo(c);b.opts.gradients&&(a.ashadow=f("<div/>",l(0,0,0)).hide().appendTo(c),a.bshadow=f("<div/>",l(0,0,0)));break;case "sheet":var d=this.width(),e=this.height();Math.round(Math.sqrt(Math.pow(d,2)+Math.pow(e,2)));a.fparent=a.opts.turn.data().fparent;a.fparent||(d=f("<div/>",{css:{"pointer-events":"none"}}).hide(),d.data().flips=0,d.css(l(0,0,"auto",
"visible").css).appendTo(a.opts.turn),a.opts.turn.data().fparent=d,a.fparent=d);this.css({position:"absolute",top:0,left:0,bottom:"auto",right:"auto"});a.wrapper=f("<div/>",l(0,0,this.css("z-index"))).appendTo(c).prepend(this);a.fwrapper=f("<div/>",l(c.offset().top,c.offset().left)).hide().appendTo(a.fparent);a.fpage=f("<div/>",l(0,0,0,"visible")).css({cursor:"default"}).appendTo(a.fwrapper);b.opts.gradients&&(a.ashadow=f("<div/>",l(0,0,1)).appendTo(a.fpage));i.setData.call(this,a)}i.resize.call(this,
!0)},_fold:function(a){var b=this.data().f,c=b.opts.turn.data(),d=i._c.call(this,a.corner),e=this.width(),h=this.height();switch(b.effect){case "hard":a.x="l"==a.corner?Math.min(Math.max(a.x,0),2*e):Math.max(Math.min(a.x,e),-e);var f,g,r,w,k,n=c.totalPages,l=b.opts["z-index"]||n,p={overflow:"visible"},o=d.x?(d.x-a.x)/e:a.x/e,q=90*o,s=90>q;switch(a.corner){case "l":w="0% 50%";k="100% 50%";s?(f=0,g=0<b.opts.next-1,r=1):(f="100%",g=b.opts.page+1<n,r=0);break;case "r":w="100% 50%",k="0% 50%",q=-q,e=-e,
s?(f=0,g=b.opts.next+1<n,r=0):(f="-100%",g=1!=b.opts.page,r=1)}p[v+"perspective-origin"]=k;b.wrapper.transform("rotateY("+q+"deg)translate3d(0px, 0px, "+(this.attr("depth")||0)+"px)",k);b.fpage.transform("translateX("+e+"px) rotateY("+(180+q)+"deg)",w);b.parent.css(p);s?(o=-o+1,b.wrapper.css({zIndex:l+1}),b.fpage.css({zIndex:l})):(o-=1,b.wrapper.css({zIndex:l}),b.fpage.css({zIndex:l+1}));c.opts.gradients&&(g?b.ashadow.css({display:"",left:f,backgroundColor:"rgba(0,0,0,"+0.5*o+")"}).transform("rotateY(0deg)"):
b.ashadow.hide(),b.bshadow.css({opacity:-o+1}),s?b.bshadow.parent()[0]!=b.wrapper[0]&&b.bshadow.appendTo(b.wrapper):b.bshadow.parent()[0]!=b.fpage[0]&&b.bshadow.appendTo(b.fpage),O(b.bshadow,j(100*r,0),j(100*(-r+1),0),[[0,"rgba(0,0,0,0.3)"],[1,"rgba(0,0,0,0)"]],2));break;case "sheet":var t=this,G=0,y,z,A,L,x,M,C,u=j(0,0),P=j(0,0),m=j(0,0),I=i._foldingPage.call(this);Math.tan(0);var N=c.opts.acceleration,Q=b.wrapper.height(),D="t"==a.corner.substr(0,1),B="l"==a.corner.substr(1,1),H=function(){var b=
j(0,0),f=j(0,0);b.x=d.x?d.x-a.x:a.x;b.y=T?d.y?d.y-a.y:a.y:0;f.x=B?e-b.x/2:a.x+b.x/2;f.y=b.y/2;var g=K-Math.atan2(b.y,b.x),k=g-Math.atan2(f.y,f.x),k=Math.max(0,Math.sin(k)*Math.sqrt(Math.pow(f.x,2)+Math.pow(f.y,2)));G=180*(g/J);m=j(k*Math.sin(g),k*Math.cos(g));if(g>K&&(m.x+=Math.abs(m.y*b.y/b.x),m.y=0,Math.round(m.x*Math.tan(J-g))<h))return a.y=Math.sqrt(Math.pow(h,2)+2*f.x*b.x),D&&(a.y=h-a.y),H();if(g>K&&(b=J-g,f=Q-h/Math.sin(b),u=j(Math.round(f*Math.cos(b)),Math.round(f*Math.sin(b))),B&&(u.x=-u.x),
D))u.y=-u.y;y=Math.round(m.y/Math.tan(g)+m.x);b=e-y;f=b*Math.cos(2*g);k=b*Math.sin(2*g);P=j(Math.round(B?b-f:y+f),Math.round(D?k:h-k));if(c.opts.gradients&&(x=b*Math.sin(g),b=i._c2.call(t,a.corner),b=Math.sqrt(Math.pow(b.x-a.x,2)+Math.pow(b.y-a.y,2))/e,C=Math.sin(K*(1<b?2-b:b)),M=Math.min(b,1),L=100<x?(x-100)/x:0,z=j(100*(x*Math.sin(g)/e),100*(x*Math.cos(g)/h)),i._backGradient.call(t)&&(A=j(100*(1.2*x*Math.sin(g)/e),100*(1.2*x*Math.cos(g)/h)),B||(A.x=100-A.x),!D)))A.y=100-A.y;m.x=Math.round(m.x);
m.y=Math.round(m.y);return!0};f=function(a,d,f,g){var k=["0","auto"],m=(e-Q)*f[0]/100,l=(h-Q)*f[1]/100,d={left:k[d[0]],top:k[d[1]],right:k[d[2]],bottom:k[d[3]]},k={},n=90!=g&&-90!=g?B?-1:1:0,r=f[0]+"% "+f[1]+"%";t.css(d).transform(F(g)+E(a.x+n,a.y,N),r);b.fpage.css(d).transform(F(g)+E(a.x+P.x-u.x-e*f[0]/100,a.y+P.y-u.y-h*f[1]/100,N)+F((180/g-2)*g),r);b.wrapper.transform(E(-a.x+m-n,-a.y+l,N)+F(-g),r);b.fwrapper.transform(E(-a.x+u.x+m,-a.y+u.y+l,N)+F(-g),r);c.opts.gradients&&(f[0]&&(z.x=100-z.x),f[1]&&
(z.y=100-z.y),k["box-shadow"]="0 0 20px rgba(0,0,0,"+0.5*C+")",I.css(k),O(b.ashadow,j(B?100:0,D?0:100),j(z.x,z.y),[[L,"rgba(0,0,0,0)"],[0.8*(1-L)+L,"rgba(0,0,0,"+0.2*M+")"],[1,"rgba(255,255,255,"+0.2*M+")"]],3,0),i._backGradient.call(t)&&O(b.bshadow,j(B?0:100,D?0:100),j(A.x,A.y),[[0.6,"rgba(0,0,0,0)"],[0.8,"rgba(0,0,0,"+0.3*M+")"],[1,"rgba(0,0,0,0)"]],3))};switch(a.corner){case "tl":a.x=Math.max(a.x,1);H();f(m,[1,0,0,1],[100,0],G);break;case "tr":a.x=Math.min(a.x,e-1);H();f(j(-m.x,m.y),[0,0,0,1],
[0,0],-G);break;case "bl":a.x=Math.max(a.x,1);H();f(j(m.x,-m.y),[1,1,0,0],[100,100],-G);break;case "br":a.x=Math.min(a.x,e-1),H(),f(j(-m.x,-m.y),[0,1,1,0],[0,100],G)}}b.point=a},_moveFoldingPage:function(a){var b=this.data().f;if(b){var c=b.opts.turn,d=c.data(),e=d.pagePlace;a?(d=b.opts.next,e[d]!=b.opts.page&&(b.folding&&i._moveFoldingPage.call(this,!1),i._foldingPage.call(this).appendTo(b.fpage),e[d]=b.opts.page,b.folding=d),c.turn("update")):b.folding&&(d.pages[b.folding]?(c=d.pages[b.folding].data().f,
d.pageObjs[b.folding].appendTo(c.wrapper)):d.pageWrap[b.folding]&&d.pageObjs[b.folding].appendTo(d.pageWrap[b.folding]),b.folding in e&&(e[b.folding]=b.folding),delete b.folding)}},_showFoldedPage:function(a,b){var c=i._foldingPage.call(this),d=this.data(),e=d.f,f=e.visible;if(c){if(!f||!e.point||e.point.corner!=a.corner)if(c="hover"==e.status||"peel"==e.status||e.opts.turn.data().mouseAction?a.corner:null,f=!1,"prevented"==s("start",this,[e.opts,c]))return!1;if(b){var g=this,d=e.point&&e.point.corner==
a.corner?e.point:i._c.call(this,a.corner,1);this.animatef({from:[d.x,d.y],to:[a.x,a.y],duration:500,frame:function(b){a.x=Math.round(b[0]);a.y=Math.round(b[1]);i._fold.call(g,a)}})}else i._fold.call(this,a),d.effect&&!d.effect.turning&&this.animatef(!1);if(!f)switch(e.effect){case "hard":e.visible=!0;i._moveFoldingPage.call(this,!0);e.fpage.show();e.opts.shadows&&e.bshadow.show();break;case "sheet":e.visible=!0,e.fparent.show().data().flips++,i._moveFoldingPage.call(this,!0),e.fwrapper.show(),e.bshadow&&
e.bshadow.show()}return!0}return!1},hide:function(){var a=this.data().f,b=a.opts.turn.data(),c=i._foldingPage.call(this);switch(a.effect){case "hard":b.opts.gradients&&(a.bshadowLoc=0,a.bshadow.remove(),a.ashadow.hide());a.wrapper.transform("");a.fpage.hide();break;case "sheet":0===--a.fparent.data().flips&&a.fparent.hide(),this.css({left:0,top:0,right:"auto",bottom:"auto"}).transform(""),a.wrapper.transform(""),a.fwrapper.hide(),a.bshadow&&a.bshadow.hide(),c.transform("")}a.visible=!1;return this},
hideFoldedPage:function(a){var b=this.data().f;if(b.point){var c=this,d=b.point,e=function(){b.point=null;b.status="";c.flip("hide");c.trigger("end",[b.opts,!1])};if(a){var f=i._c.call(this,d.corner),a="t"==d.corner.substr(0,1)?Math.min(0,d.y-f.y)/2:Math.max(0,d.y-f.y)/2,g=j(d.x,d.y+a),l=j(f.x,f.y-a);this.animatef({from:0,to:1,frame:function(a){a=R(d,g,l,f,a);d.x=a.x;d.y=a.y;i._fold.call(c,d)},complete:e,duration:800,hiding:!0})}else this.animatef(!1),e()}},turnPage:function(a){var b=this,c=this.data().f,
d=c.opts.turn.data(),a={corner:c.corner?c.corner.corner:a||i._cAllowed.call(this)[0]},e=c.point||i._c.call(this,a.corner,c.opts.turn?d.opts.elevation:0),f=i._c2.call(this,a.corner);this.trigger("flip").animatef({from:0,to:1,frame:function(c){c=R(e,e,f,f,c);a.x=c.x;a.y=c.y;i._showFoldedPage.call(b,a)},complete:function(){b.trigger("end",[c.opts,!0])},duration:d.opts.duration,turning:!0});c.corner=null},moving:function(){return"effect"in this.data()},isTurning:function(){return this.flip("moving")&&
this.data().effect.turning},corner:function(){return this.data().f.corner},_eventStart:function(a){var b=this.data().f,c=b.opts.turn;if(!b.corner&&!b.disabled&&!this.flip("isTurning")&&b.opts.page==c.data().pagePlace[b.opts.page]){b.corner=i._isIArea.call(this,a);if(b.corner&&i._foldingPage.call(this))return this.trigger("pressed",[b.point]),i._showFoldedPage.call(this,b.corner),!1;b.corner=null}},_eventMove:function(a){var b=this.data().f;if(!b.disabled)if(a=t?a.originalEvent.touches:[a],b.corner){var c=
b.parent.offset();b.corner.x=a[0].pageX-c.left;b.corner.y=a[0].pageY-c.top;i._showFoldedPage.call(this,b.corner)}else if(b.hover&&!this.data().effect&&this.is(":visible"))if(a=i._isIArea.call(this,a[0])){if("sheet"==b.effect&&2==a.corner.length||"hard"==b.effect)b.status="hover",b=i._c.call(this,a.corner,b.opts.cornerSize/2),a.x=b.x,a.y=b.y,i._showFoldedPage.call(this,a,!0)}else"hover"==b.status&&(b.status="",i.hideFoldedPage.call(this,!0))},_eventEnd:function(){var a=this.data().f,b=a.corner;!a.disabled&&
b&&"prevented"!=s("released",this,[a.point||b])&&i.hideFoldedPage.call(this,!0);a.corner=null},disable:function(a){i.setData.call(this,{disabled:a});return this},hover:function(a){i.setData.call(this,{hover:a});return this},peel:function(a,b){var c=this.data().f;if(a){if(-1==f.inArray(a,o.all))throw p("Corner "+a+" is not permitted");if(-1!=f.inArray(a,i._cAllowed.call(this))){var d=i._c.call(this,a,c.opts.cornerSize/2);c.status="peel";i._showFoldedPage.call(this,{corner:a,x:d.x,y:d.y},b)}}else c.status=
"",i.hideFoldedPage.call(this,b);return this}};window.requestAnim=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(a){window.setTimeout(a,1E3/60)};f.extend(f.fn,{flip:function(){return I(f(this[0]),i,arguments)},turn:function(){return I(f(this[0]),g,arguments)},transform:function(a,b){var c={};b&&(c[v+"transform-origin"]=b);c[v+"transform"]=a;return this.css(c)},animatef:function(a){var b=
this.data();b.effect&&b.effect.stop();if(a){a.to.length||(a.to=[a.to]);a.from.length||(a.from=[a.from]);for(var c=[],d=a.to.length,e=!0,g=this,i=(new Date).getTime(),j=function(){if(b.effect&&e){for(var f=[],k=Math.min(a.duration,(new Date).getTime()-i),l=0;l<d;l++)f.push(b.effect.easing(1,k,a.from[l],c[l],a.duration));a.frame(d==1?f[0]:f);if(k==a.duration){delete b.effect;g.data(b);a.complete&&a.complete()}else window.requestAnim(j)}},l=0;l<d;l++)c.push(a.to[l]-a.from[l]);b.effect=f.extend({stop:function(){e=
false},easing:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c}},a);this.data(b);j()}else delete b.effect}});f.isTouch=t;f.mouseEvents=q;f.cssPrefix=S;f.cssTransitionEnd=function(){var a,b=document.createElement("fakeelement"),c={transition:"transitionend",OTransition:"oTransitionEnd",MSTransition:"transitionend",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(a in c)if(void 0!==b.style[a])return c[a]};f.findPos=C})(jQuery);

/*
	jQuery Autosize v1.16.5
	(c) 2013 Jack Moore - jacklmoore.com
	updated: 2013-02-11
	license: http://www.opensource.org/licenses/mit-license.php
*/
(function(e){var t,o={className:"autosizejs",append:"",callback:!1},i="hidden",n="border-box",s="lineHeight",a='<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden;"/>',r=["fontFamily","fontSize","fontWeight","fontStyle","letterSpacing","textTransform","wordSpacing","textIndent"],l="oninput",c="onpropertychange",h=e(a).data("autosize",!0)[0];h.style.lineHeight="99px","99px"===e(h).css(s)&&r.push(s),h.style.lineHeight="",e.fn.autosize=function(s){return s=e.extend({},o,s||{}),h.parentNode!==document.body&&e(document.body).append(h),this.each(function(){function o(){t=b,h.className=s.className,e.each(r,function(e,t){h.style[t]=f.css(t)})}function a(){var e,n,a;if(t!==b&&o(),!d){d=!0,h.value=b.value+s.append,h.style.overflowY=b.style.overflowY,a=parseInt(b.style.height,10),h.style.width=Math.max(f.width(),0)+"px",h.scrollTop=0,h.scrollTop=9e4,e=h.scrollTop;var r=parseInt(f.css("maxHeight"),10);r=r&&r>0?r:9e4,e>r?(e=r,n="scroll"):p>e&&(e=p),e+=g,b.style.overflowY=n||i,a!==e&&(b.style.height=e+"px",x&&s.callback.call(b)),setTimeout(function(){d=!1},1)}}var p,d,u,b=this,f=e(b),g=0,x=e.isFunction(s.callback);f.data("autosize")||((f.css("box-sizing")===n||f.css("-moz-box-sizing")===n||f.css("-webkit-box-sizing")===n)&&(g=f.outerHeight()-f.height()),p=Math.max(parseInt(f.css("minHeight"),10)-g,f.height()),u="none"===f.css("resize")||"vertical"===f.css("resize")?"none":"horizontal",f.css({overflow:i,overflowY:i,wordWrap:"break-word",resize:u}).data("autosize",!0),c in b?l in b?b[l]=b.onkeyup=a:b[c]=a:b[l]=a,e(window).resize(function(){d=!1,a()}),f.bind("autosize",function(){d=!1,a()}),a())})}})(window.jQuery||window.Zepto);

 /*
 * AutoSuggest
 * Copyright 2009-2010 Drew Wilson
 * www.drewwilson.com
 * code.drewwilson.com/entry/autosuggest-jquery-plugin
 *
 * Version 1.4   -   Updated: Mar. 23, 2010
 *
 */

(function($){$.fn.autoSuggest=function(data,options){var defaults={asHtmlID:false,startText:"Enter Name Here",emptyText:"No Results Found",preFill:{},limitText:"No More Selections Are Allowed",selectedItemProp:"value",selectedValuesProp:"value",searchObjProps:"value",queryParam:"q",retrieveLimit:false,extraParams:"",matchCase:false,minChars:1,keyDelay:400,resultsHighlight:true,neverSubmit:false,selectionLimit:false,showResultList:true,start:function(){},selectionClick:function(elem){},selectionAdded:function(elem){},selectionRemoved:function(elem){elem.remove()},formatList:false,beforeRetrieve:function(string){return string},retrieveComplete:function(data){return data},resultClick:function(data){},resultsComplete:function(){}};var opts=$.extend(defaults,options);var d_type="object";var d_count=0;if(typeof data=="string"){d_type="string";var req_string=data}else{var org_data=data;for(k in data)if(data.hasOwnProperty(k))d_count++}if((d_type=="object"&&d_count>0)||d_type=="string"){return this.each(function(x){if(!opts.asHtmlID){x=x+""+Math.floor(Math.random()*100);var x_id="as-input-"+x}else{x=opts.asHtmlID;var x_id=x}opts.start.call(this);var input=$(this);input.attr("autocomplete","off").addClass("as-input").attr("id",x_id).val(opts.startText);var input_focus=false;input.wrap('<ul class="as-selections" id="as-selections-'+x+'"></ul>').wrap('<li class="as-original" id="as-original-'+x+'"></li>');var selections_holder=$("#as-selections-"+x);var org_li=$("#as-original-"+x);var results_holder=$('<div class="as-results" id="as-results-'+x+'"></div>').hide();var results_ul=$('<ul class="as-list"></ul>');var values_input=$('<input type="hidden" class="as-values" name="as_values_'+x+'" id="as-values-'+x+'" />');var prefill_value="";if(typeof opts.preFill=="string"){var vals=opts.preFill.split(",");for(var i=0;i<vals.length;i++){var v_data={};v_data[opts.selectedValuesProp]=vals[i];if(vals[i]!=""){add_selected_item(v_data,"000"+i)}}prefill_value=opts.preFill}else{prefill_value="";var prefill_count=0;for(k in opts.preFill)if(opts.preFill.hasOwnProperty(k))prefill_count++;if(prefill_count>0){for(var i=0;i<prefill_count;i++){var new_v=opts.preFill[i][opts.selectedValuesProp];if(new_v==undefined){new_v=""}prefill_value=prefill_value+new_v+",";if(new_v!=""){add_selected_item(opts.preFill[i],"000"+i)}}}}if(prefill_value!=""){input.val("");var lastChar=prefill_value.substring(prefill_value.length-1);if(lastChar!=","){prefill_value=prefill_value+","}values_input.val(","+prefill_value);$("li.as-selection-item",selections_holder).addClass("blur").removeClass("selected")}input.after(values_input);selections_holder.click(function(){input_focus=true;input.focus()}).mousedown(function(){input_focus=false}).after(results_holder);var timeout=null;var prev="";var totalSelections=0;var tab_press=false;input.focus(function(){if($(this).val()==opts.startText&&values_input.val()==""){$(this).val("")}else if(input_focus){$("li.as-selection-item",selections_holder).removeClass("blur");if($(this).val()!=""){results_ul.css("width",selections_holder.outerWidth());results_holder.show()}}input_focus=true;return true}).blur(function(){if($(this).val()==""&&values_input.val()==""&&prefill_value==""){$(this).val(opts.startText)}else if(input_focus){$("li.as-selection-item",selections_holder).addClass("blur").removeClass("selected");results_holder.hide()}}).keydown(function(e){lastKeyPressCode=e.keyCode;first_focus=false;switch(e.keyCode){case 38:e.preventDefault();moveSelection("up");break;case 40:e.preventDefault();moveSelection("down");break;case 8:if(input.val()==""){var vals=values_input.val().split(",");if (vals.length >= 2)vals.splice(vals.length - 2, 1);selections_holder.children().not(org_li.prev()).removeClass("selected");if(org_li.prev().hasClass("selected")){values_input.val(vals.join(","));opts.selectionRemoved.call(this,org_li.prev())}else{opts.selectionClick.call(this,org_li.prev());org_li.prev().addClass("selected")}}if(input.val().length==1){results_holder.hide();prev=""}if($(":visible",results_holder).length>0){if(timeout){clearTimeout(timeout)}timeout=setTimeout(function(){keyChange()},opts.keyDelay)}break;case 188:tab_press=true;var i_input=input.val().replace(/(,)/g,"");if(i_input!=""&&values_input.val().search(","+i_input+",")<0&&i_input.length>=opts.minChars){e.preventDefault();var n_data={};n_data[opts.selectedItemProp]=i_input;n_data[opts.selectedValuesProp]=i_input;var lis=$("li",selections_holder).length;add_selected_item(n_data,"00"+(lis+1));input.val("")}case 13:tab_press=false;var active=$("li.active:first",results_holder);if(active.length>0){active.click();results_holder.hide()}if(opts.neverSubmit||active.length>0){e.preventDefault()}break;default:if(opts.showResultList){if(opts.selectionLimit&&$("li.as-selection-item",selections_holder).length>=opts.selectionLimit){results_ul.html('<li class="as-message">'+opts.limitText+'</li>');results_holder.show()}else{if(timeout){clearTimeout(timeout)}timeout=setTimeout(function(){keyChange()},opts.keyDelay)}}break}});function keyChange(){if(lastKeyPressCode==46||(lastKeyPressCode>8&&lastKeyPressCode<32)){return results_holder.hide()}var string=input.val().replace(/[\\]+|[\/]+/g,"");if(string==prev)return;prev=string;if(string.length>=opts.minChars){selections_holder.addClass("loading");if(d_type=="string"){var limit="";if(opts.retrieveLimit){limit="&limit="+encodeURIComponent(opts.retrieveLimit)}if(opts.beforeRetrieve){string=opts.beforeRetrieve.call(this,string)}$.getJSON(req_string+"?"+opts.queryParam+"="+encodeURIComponent(string)+limit+opts.extraParams,function(data){d_count=0;var new_data=opts.retrieveComplete.call(this,data);for(k in new_data)if(new_data.hasOwnProperty(k))d_count++;processData(new_data,string)})}else{if(opts.beforeRetrieve){string=opts.beforeRetrieve.call(this,string)}processData(org_data,string)}}else{selections_holder.removeClass("loading");results_holder.hide()}}var num_count=0;function processData(data,query){if(!opts.matchCase){query=query.toLowerCase()}var matchCount=0;results_holder.html(results_ul.html("")).hide();for(var i=0;i<d_count;i++){var num=i;num_count++;var forward=false;if(opts.searchObjProps=="value"){var str=data[num].value}else{var str="";var names=opts.searchObjProps.split(",");for(var y=0;y<names.length;y++){var name=$.trim(names[y]);str=str+data[num][name]+" "}}if(str){if(!opts.matchCase){str=str.toLowerCase()}if(str.search(query)!=-1&&values_input.val().search(","+data[num][opts.selectedValuesProp]+",")==-1){forward=true}}if(forward){var formatted=$('<li class="as-result-item" id="as-result-item-'+num+'"></li>').click(function(){var raw_data=$(this).data("data");var number=raw_data.num;if($("#as-selection-"+number,selections_holder).length<=0&&!tab_press){var data=raw_data.attributes;input.val("").focus();prev="";add_selected_item(data,number);opts.resultClick.call(this,raw_data);results_holder.hide()}tab_press=false}).mousedown(function(){input_focus=false}).mouseover(function(){$("li",results_ul).removeClass("active");$(this).addClass("active")}).data("data",{attributes:data[num],num:num_count});var this_data=$.extend({},data[num]);if(!opts.matchCase){var regx=new RegExp("(?![^&;]+;)(?!<[^<>]*)("+query+")(?![^<>]*>)(?![^&;]+;)","gi")}else{var regx=new RegExp("(?![^&;]+;)(?!<[^<>]*)("+query+")(?![^<>]*>)(?![^&;]+;)","g")}if(opts.resultsHighlight){this_data[opts.selectedItemProp]=this_data[opts.selectedItemProp].replace(regx,"<em>$1</em>")}if(!opts.formatList){formatted=formatted.html(this_data[opts.selectedItemProp])}else{formatted=opts.formatList.call(this,this_data,formatted)}results_ul.append(formatted);delete this_data;matchCount++;if(opts.retrieveLimit&&opts.retrieveLimit==matchCount){break}}}selections_holder.removeClass("loading");if(matchCount<=0){results_ul.html('<li class="as-message">'+opts.emptyText+'</li>')}results_ul.css("width",selections_holder.outerWidth());results_holder.show();opts.resultsComplete.call(this)}function add_selected_item(data,num){values_input.val(values_input.val()+data[opts.selectedValuesProp]+",");var item=$('<li class="as-selection-item" id="as-selection-'+num+'"></li>').click(function(){opts.selectionClick.call(this,$(this));selections_holder.children().removeClass("selected");$(this).addClass("selected")}).mousedown(function(){input_focus=false});var close=$('<a class="as-close">&times;</a>').click(function(){var vals = values_input.val().split(",");for (var i = 0; i < vals.length; i ++) {if (vals[i] == data[opts.selectedValuesProp]) {vals.splice(i, 1);break;}}values_input.val(vals.join(","));opts.selectionRemoved.call(this,item);input_focus=true;input.focus();return false});org_li.before(item.html(data[opts.selectedItemProp]).prepend(close));opts.selectionAdded.call(this,org_li.prev())}function moveSelection(direction){if($(":visible",results_holder).length>0){var lis=$("li",results_holder);if(direction=="down"){var start=lis.eq(0)}else{var start=lis.filter(":last")}var active=$("li.active:first",results_holder);if(active.length>0){if(direction=="down"){start=active.next()}else{start=active.prev()}}lis.removeClass("active");start.addClass("active")}}})}}})(jQuery);
/* ===========================================================
 * bootstrap-modalmanager.js v2.1
 * ===========================================================
 * Copyright 2012 Jordan Schroter.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
!function(e){"use strict";function r(e){return function(t){if(this===t.target){return e.apply(this,arguments)}}}var t=function(e,t){this.init(e,t)};t.prototype={constructor:t,init:function(t,n){this.$element=e(t);this.options=e.extend({},e.fn.modalmanager.defaults,this.$element.data(),typeof n=="object"&&n);this.stack=[];this.backdropCount=0;if(this.options.resize){var r,i=this;e(window).on("resize.modal",function(){r&&clearTimeout(r);r=setTimeout(function(){for(var e=0;e<i.stack.length;e++){i.stack[e].isShown&&i.stack[e].layout()}},10)})}},createModal:function(t,n){e(t).modal(e.extend({manager:this},n))},appendModal:function(t){this.stack.push(t);var n=this;t.$element.on("show.modalmanager",r(function(r){var i=function(){t.isShown=true;var r=e.support.transition&&t.$element.hasClass("fade");n.$element.toggleClass("modal-open",n.hasOpenModal()).toggleClass("page-overflow",e(window).height()<n.$element.height());t.$parent=t.$element.parent();t.$container=n.createContainer(t);t.$element.appendTo(t.$container);n.backdrop(t,function(){t.$element.show();if(r){t.$element[0].offsetWidth}t.layout();t.$element.addClass("in").attr("aria-hidden",false);var i=function(){n.setFocus();t.$element.trigger("shown")};r?t.$element.one(e.support.transition.end,i):i()})};t.options.replace?n.replace(i):i()}));t.$element.on("hidden.modalmanager",r(function(r){n.backdrop(t);if(t.$backdrop){e.support.transition&&t.$element.hasClass("fade")?t.$backdrop.one(e.support.transition.end,function(){n.destroyModal(t)}):n.destroyModal(t)}else{n.destroyModal(t)}}));t.$element.on("destroy.modalmanager",r(function(e){n.removeModal(t)}))},destroyModal:function(e){e.destroy();var t=this.hasOpenModal();this.$element.toggleClass("modal-open",t);if(!t){this.$element.removeClass("page-overflow")}this.removeContainer(e);this.setFocus()},hasOpenModal:function(){for(var e=0;e<this.stack.length;e++){if(this.stack[e].isShown)return true}return false},setFocus:function(){var e;for(var t=0;t<this.stack.length;t++){if(this.stack[t].isShown)e=this.stack[t]}if(!e)return;e.focus()},removeModal:function(e){e.$element.off(".modalmanager");if(e.$backdrop)this.removeBackdrop(e);this.stack.splice(this.getIndexOfModal(e),1)},getModalAt:function(e){return this.stack[e]},getIndexOfModal:function(e){for(var t=0;t<this.stack.length;t++){if(e===this.stack[t])return t}},replace:function(t){var n;for(var i=0;i<this.stack.length;i++){if(this.stack[i].isShown)n=this.stack[i]}if(n){this.$backdropHandle=n.$backdrop;n.$backdrop=null;t&&n.$element.one("hidden",r(e.proxy(t,this)));n.hide()}else if(t){t()}},removeBackdrop:function(e){e.$backdrop.remove();e.$backdrop=null},createBackdrop:function(t){var n;if(!this.$backdropHandle){n=e('<div class="modal-backdrop '+t+'" />').appendTo(this.$element)}else{n=this.$backdropHandle;n.off(".modalmanager");this.$backdropHandle=null;this.isLoading&&this.removeSpinner()}return n},removeContainer:function(e){e.$container.remove();e.$container=null},createContainer:function(t){var i;i=e('<div class="modal-scrollable">').css("z-index",n("modal",t?this.getIndexOfModal(t):this.stack.length)).appendTo(this.$element);if(t&&t.options.backdrop!="static"){i.on("click.modal",r(function(e){t.hide()}))}else if(t){i.on("click.modal",r(function(e){t.attention()}))}return i},backdrop:function(t,r){var i=t.$element.hasClass("fade")?"fade":"",s=t.options.backdrop&&this.backdropCount<this.options.backdropLimit;if(t.isShown&&s){var o=e.support.transition&&i&&!this.$backdropHandle;t.$backdrop=this.createBackdrop(i);t.$backdrop.css("z-index",n("backdrop",this.getIndexOfModal(t)));if(o)t.$backdrop[0].offsetWidth;t.$backdrop.addClass("in");this.backdropCount+=1;o?t.$backdrop.one(e.support.transition.end,r):r()}else if(!t.isShown&&t.$backdrop){t.$backdrop.removeClass("in");this.backdropCount-=1;var u=this;e.support.transition&&t.$element.hasClass("fade")?t.$backdrop.one(e.support.transition.end,function(){u.removeBackdrop(t)}):u.removeBackdrop(t)}else if(r){r()}},removeSpinner:function(){this.$spinner&&this.$spinner.remove();this.$spinner=null;this.isLoading=false},removeLoading:function(){this.$backdropHandle&&this.$backdropHandle.remove();this.$backdropHandle=null;this.removeSpinner()},loading:function(t){t=t||function(){};this.$element.toggleClass("modal-open",!this.isLoading||this.hasOpenModal()).toggleClass("page-overflow",e(window).height()<this.$element.height());if(!this.isLoading){this.$backdropHandle=this.createBackdrop("fade");this.$backdropHandle[0].offsetWidth;this.$backdropHandle.css("z-index",n("backdrop",this.stack.length)).addClass("in");var r=e(this.options.spinner).css("z-index",n("modal",this.stack.length)).appendTo(this.$element).addClass("in");this.$spinner=e(this.createContainer()).append(r).on("click.modalmanager",e.proxy(this.loading,this));this.isLoading=true;e.support.transition?this.$backdropHandle.one(e.support.transition.end,t):t()}else if(this.isLoading&&this.$backdropHandle){this.$backdropHandle.removeClass("in");var i=this;e.support.transition?this.$backdropHandle.one(e.support.transition.end,function(){i.removeLoading()}):i.removeLoading()}else if(t){t(this.isLoading)}}};var n=function(){var t,n={};return function(r,i){if(typeof t==="undefined"){var s=e('<div class="modal hide" />').appendTo("body"),o=e('<div class="modal-backdrop hide" />').appendTo("body");n["modal"]=+s.css("z-index");n["backdrop"]=+o.css("z-index");t=n["modal"]-n["backdrop"];s.remove();o.remove();o=s=null}return n[r]+t*i}}();e.fn.modalmanager=function(n,r){return this.each(function(){var i=e(this),s=i.data("modalmanager");if(!s)i.data("modalmanager",s=new t(this,n));if(typeof n==="string")s[n].apply(s,[].concat(r))})};e.fn.modalmanager.defaults={backdropLimit:999,resize:true,spinner:'<div class="loading-spinner fade" style="width: 200px; margin-left: -100px;"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div>'};e.fn.modalmanager.Constructor=t}(jQuery)
/* ===========================================================
 * bootstrap-modal.js v2.1
 * ===========================================================
 * Copyright 2012 Jordan Schroter
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
!function(e){"use strict";var t=function(e,t){this.init(e,t)};t.prototype={constructor:t,init:function(t,n){this.options=n;this.$element=e(t).delegate('[data-dismiss="modal"]',"click.dismiss.modal",e.proxy(this.hide,this));this.options.remote&&this.$element.find(".modal-body").load(this.options.remote);var r=typeof this.options.manager==="function"?this.options.manager.call(this):this.options.manager;r=r.appendModal?r:e(r).modalmanager().data("modalmanager");r.appendModal(this)},toggle:function(){return this[!this.isShown?"show":"hide"]()},show:function(){var t=e.Event("show");if(this.isShown)return;this.$element.trigger(t);if(t.isDefaultPrevented())return;this.escape();this.tab();this.options.loading&&this.loading()},hide:function(t){t&&t.preventDefault();t=e.Event("hide");this.$element.trigger(t);if(!this.isShown||t.isDefaultPrevented())return this.isShown=false;this.isShown=false;this.escape();this.tab();this.isLoading&&this.loading();e(document).off("focusin.modal");this.$element.removeClass("in").removeClass("animated").removeClass(this.options.attentionAnimation).removeClass("modal-overflow").attr("aria-hidden",true);e.support.transition&&this.$element.hasClass("fade")?this.hideWithTransition():this.hideModal()},layout:function(){var t=this.options.height?"height":"max-height",n=this.options.height||this.options.maxHeight;if(this.options.width){this.$element.css("width",this.options.width);var r=this;this.$element.css("margin-left",function(){if(/%/ig.test(r.options.width)){return-(parseInt(r.options.width)/2)+"%"}else{return-(e(this).width()/2)+"px"}})}else{this.$element.css("width","");this.$element.css("margin-left","")}this.$element.find(".modal-body").css("overflow","").css(t,"");if(n){this.$element.find(".modal-body").css("overflow","auto").css(t,n)}var i=e(window).height()-10<this.$element.height();if(i||this.options.modalOverflow){this.$element.css("margin-top",0).addClass("modal-overflow")}else{this.$element.css("margin-top",0-this.$element.height()/2).removeClass("modal-overflow")}},tab:function(){var t=this;if(this.isShown&&this.options.consumeTab){this.$element.on("keydown.tabindex.modal","[data-tabindex]",function(n){if(n.keyCode&&n.keyCode==9){var r=e(this),i=e(this);t.$element.find("[data-tabindex]:enabled:not([readonly])").each(function(t){if(!t.shiftKey){r=r.data("tabindex")<e(this).data("tabindex")?r=e(this):i=e(this)}else{r=r.data("tabindex")>e(this).data("tabindex")?r=e(this):i=e(this)}});r[0]!==e(this)[0]?r.focus():i.focus();n.preventDefault()}})}else if(!this.isShown){this.$element.off("keydown.tabindex.modal")}},escape:function(){var e=this;if(this.isShown&&this.options.keyboard){if(!this.$element.attr("tabindex"))this.$element.attr("tabindex",-1);this.$element.on("keyup.dismiss.modal",function(t){t.which==27&&e.hide()})}else if(!this.isShown){this.$element.off("keyup.dismiss.modal")}},hideWithTransition:function(){var t=this,n=setTimeout(function(){t.$element.off(e.support.transition.end);t.hideModal()},500);this.$element.one(e.support.transition.end,function(){clearTimeout(n);t.hideModal()})},hideModal:function(){var e=this.options.height?"height":"max-height";var t=this.options.height||this.options.maxHeight;if(t){this.$element.find(".modal-body").css("overflow","").css(e,"")}this.$element.hide().trigger("hidden")},removeLoading:function(){this.$loading.remove();this.$loading=null;this.isLoading=false},loading:function(t){t=t||function(){};var n=this.$element.hasClass("fade")?"fade":"";if(!this.isLoading){var r=e.support.transition&&n;this.$loading=e('<div class="loading-mask '+n+'">').append(this.options.spinner).appendTo(this.$element);if(r)this.$loading[0].offsetWidth;this.$loading.addClass("in");this.isLoading=true;r?this.$loading.one(e.support.transition.end,t):t()}else if(this.isLoading&&this.$loading){this.$loading.removeClass("in");var i=this;e.support.transition&&this.$element.hasClass("fade")?this.$loading.one(e.support.transition.end,function(){i.removeLoading()}):i.removeLoading()}else if(t){t(this.isLoading)}},focus:function(){var e=this.$element.find(this.options.focusOn);e=e.length?e:this.$element;e.focus()},attention:function(){if(this.options.attentionAnimation){this.$element.removeClass("animated").removeClass(this.options.attentionAnimation);var e=this;setTimeout(function(){e.$element.addClass("animated").addClass(e.options.attentionAnimation)},0)}this.focus()},destroy:function(){var t=e.Event("destroy");this.$element.trigger(t);if(t.isDefaultPrevented())return;this.teardown()},teardown:function(){if(!this.$parent.length){this.$element.remove();this.$element=null;return}if(this.$parent!==this.$element.parent()){this.$element.appendTo(this.$parent)}this.$element.off(".modal");this.$element.removeData("modal");this.$element.removeClass("in").attr("aria-hidden",true)}};e.fn.modal=function(n,r){return this.each(function(){var i=e(this),s=i.data("modal"),o=e.extend({},e.fn.modal.defaults,i.data(),typeof n=="object"&&n);if(!s)i.data("modal",s=new t(this,o));if(typeof n=="string")s[n].apply(s,[].concat(r));else if(o.show)s.show()})};e.fn.modal.defaults={keyboard:true,backdrop:true,loading:false,show:true,width:null,height:null,maxHeight:null,modalOverflow:false,consumeTab:true,focusOn:null,replace:false,resize:false,attentionAnimation:"shake",manager:"body",spinner:'<div class="loading-spinner" style="width: 200px; margin-left: -100px;"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div>'};e.fn.modal.Constructor=t;e(function(){e(document).off("click.modal").on("click.modal.data-api",'[data-toggle="modal"]',function(t){var n=e(this),r=n.attr("href"),i=e(n.attr("data-target")||r&&r.replace(/.*(?=#[^\s]+$)/,"")),s=i.data("modal")?"toggle":e.extend({remote:!/#/.test(r)&&r},i.data(),n.data());t.preventDefault();i.modal(s).one("hide",function(){n.focus()})})})}(window.jQuery);