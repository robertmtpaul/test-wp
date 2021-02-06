/*jquery.typer.js*/
!function(o,t){"use strict";var i,e="typer",s={highlightSpeed:20,typeSpeed:100,clearDelay:500,typeDelay:200,typerInterval:2e3,highlightEverything:!0,typerDataAttr:"data-typer-targets",backgroundColor:"auto",highlightColor:"auto",typerOrder:"sequential",typerDirection:"rtl",typerStartFrom:0,inlineHighlightStyle:!0};function h(t,i){this._defaults=s,this._name=e,this.element=t,this.settings=o.extend({},s,i),this.init()}o.extend(h.prototype,{init:function(){var t;void 0!==o(this.element).attr(this.settings.typerDataAttr)&&(0<this.settings.typerStartFrom&&this.highlight(),this.last=this.settings.typerStartFrom,this.typeWithAttribute(),(t=this).intervalHandle=setInterval(function(){t.typeWithAttribute.call(t)},this.settings.typerInterval))},destroy:function(){clearInterval(this.intervalHandle),o(this.element).removeData("plugin_"+e)},clearData:function(){this.highlightPosition=null,this.leftStop=null,this.rightStop=null,this.text=null,this.typing=null},typeWithAttribute:function(){var t,i=o(this.element);if(void 0!==this.settings){if(!this.typing){try{t=JSON.parse(i.attr(this.settings.typerDataAttr)).targets}catch(t){}if(void 0===t&&(t=o.map(i.attr(this.settings.typerDataAttr).split(","),function(t){return o.trim(t)})),"random"===this.settings.typerOrder)this.typeTo(t[Math.floor(Math.random()*t.length)]);else{if("sequential"!==this.settings.typerOrder)return void this.destroy();this.typeTo(t[this.last]),this.last=this.last<t.length-1?this.last+1:0}}}else this.destroy()},typeTo:function(t){if(void 0!==t){t=this.decodeEntities(t);var i=o(this.element).text(),e=0,s=0;if((this.typing=!0)!==this.settings.highlightEverything){for(;i.charAt(e)===t.charAt(e);)e++;for(;this.rightChars(i,s)===this.rightChars(t,s);)s++}void 0!==t&&(t=t.substring(e,t.length-s+1)),this.oldLeft=i.substring(0,e),this.oldRight=this.rightChars(i,s-1),this.leftStop=e,this.rightStop=i.length-s,this.text=t,this.highlight()}},highlight:function(){var t,i,e,s,h,n=o(this.element);if(void 0!==this.settings){if(this.isNumber(this.highlightPosition)||(this.highlightPosition=this.rightStop+1),this.highlightPosition<=this.leftStop)return t=this,void setTimeout(function(){t.clearText.call(t)},this.settings.clearDelay);i=n.text(),h="ltr"===this.settings.typerDirection?(e="",s=i.substring(0,this.rightStop-this.highlightPosition+1),i.substring(this.rightStop-this.highlightPosition+1)):(e=i.substring(0,this.highlightPosition-1),s=i.substring(this.highlightPosition-1,this.rightStop+1),i.substring(this.rightStop+1)),""===e&&""===s&&""===h&&n.hide();var r=0<this.settings.highlightSpeed?this.spanWithColor("auto"===this.settings.highlightColor?n.css("background-color"):this.settings.highlightColor,"auto"===this.settings.backgroundColor?n.css("color"):this.settings.backgroundColor,s):s;n.html(e+r+h),--this.highlightPosition,t=this,setTimeout(function(){t.highlight.call(t)},this.settings.highlightSpeed)}else this.destroy()},type:function(){var t,i,e=o(this.element);this.text&&0!==this.text.length?(i=this.oldLeft+this.text.charAt(0)+this.oldRight,e.html(i),1===i.length&&e.show(),this.oldLeft=this.oldLeft+this.text.charAt(0),this.text=this.text.substring(1),t=this,setTimeout(function(){t.type.call(t)},this.settings.typeSpeed)):this.clearData()},clearText:function(){var t;o(this.element).find("span").remove(),t=this,setTimeout(function(){t.type.call(t)},this.settings.typeDelay)},spanWithColor:function(t,i,e){return this.settings.inlineHighlightStyle?'<span style="color:'+t+";background-color:"+i+';">'+e+"</span>":"<span>"+e+"</span>"},isNumber:function(t){return!isNaN(parseFloat(t))&&isFinite(t)},rightChars:function(t,i){return i<=0?"":i>t.length?t:t.substring(t.length,t.length-i)},decodeEntities:(i=t.createElement("div"),function(t){return t&&"string"==typeof t&&(t=escape(t).replace(/%26/g,"&").replace(/%23/g,"#").replace(/%3B/g,";"),i.innerHTML=t,i.innerText?(t=i.innerText,i.innerText=""):(t=i.textContent,i.textContent="")),unescape(t)})}),o.fn[e]=function(t){return this.each(function(){o.data(this,"plugin_"+e)||o.data(this,"plugin_"+e,new h(this,t))})}}(jQuery,document);
(function ( $, Themify ) {
	'use strict';
	Themify.on( 'builder_load_module_partial', function (el, type,isLazy ) {
            if(isLazy===true && !el[0].classList.contains('module-typewriter')){
                return;
            }
            const items = Themify.selectWithParent('typewriter-span',el);
            for(let i=items.length-1;i>-1;--i){
                let item=items[i];
                if ( Themify.is_builder_active ) {
                    item.innerText = '';
                    if ( typeof tb_app!=='undefined' && tb_app.activeModel !== null ) {
                        const bg=ThemifyConstructor.getStyleVal('span_background_color');
                        if(bg){
                            tb_app.liveStylingInstance.setLiveStyle('background-color', bg, ThemifyStyles.getStyleOptions('typewriter').span_background_color.selector);
                        }
                    }
                }
                $(item).typer( {
                        highlightSpeed: parseInt( item.getAttribute( 'data-typer-highlight-speed' ) ),
                        typeSpeed: parseInt( item.getAttribute( 'data-typer-type-speed' ) ),
                        clearDelay: parseInt( parseFloat( item.getAttribute( 'data-typer-clear-delay' ) ) * 1000 ),
                        typeDelay: parseInt( parseFloat( item.getAttribute( 'data-typer-type-delay' ) ) * 1000 ),
                        clearOnHighlight: true,
                        typerDataAttr: 'data-typer-targets',
                        typerInterval: parseInt( parseFloat( item.getAttribute( 'data-typer-interval' ) ) * 1000 ),
                        typerOrder: 'sequential',
                        typerDirection: item.getAttribute( 'data-typer-direction' ),
                        typerStartFrom: Themify.is_builder_active ? 0 : 1,
                        inlineHighlightStyle: false,
                } );
            }
        } );
}( jQuery, Themify ));
