(function (Themify) {
    'use strict';
	const args=tbLocalScript['addons']['pro-image'],
		url=args.url+'modules/',
		loaded={},
		ev=Themify.isTouch?'touchstart':'mouseenter',
		image_effects=['glow','rotate','shine','zoomin','zoomout'],
		appearance=['fullwidth_image','circle','rounded'],
		overlay_effects=['partial-overlay','none','flip-vertical','flip-horizontal','fadeIn'],
		filters=['sepia','blur','grayscale','effect-grayscale-reverse'],
	flip_effect = function (el, side) {
        side === 'back'?el.classList.add('image-pro-flip'):el.classList.remove('image-pro-flip');
        setTimeout(function (side) {
            side === 'back'?this.classList.add('image-pro-flipped'):el.classList.remove('image-pro-flipped');
        }.bind(el,side), 1000);
    },
	show_overlay = function( el ) {
		const entranceEffect = el.getAttribute('data-entrance-effect');
		el.addEventListener('mouseleave', _mouseleave, {passive: true, once: true});
		if (entranceEffect === 'flip-horizontal' || entranceEffect === 'flip-vertical') {
			flip_effect(el, 'back');
		} else {
			const overlay = el.getElementsByClassName('image-pro-overlay')[0];
			overlay.style['visibility'] = 'visible';
			if (entranceEffect !== 'none') {
				overlay.style['animationName'] = '';
				const exit = el.getAttribute('data-exit-effect');
				if (exit) {
					overlay.classList.remove(exit);
				}
				overlay.classList.add('wow', 'animated');
			}
			if (entranceEffect) {
				overlay.style['animationName'] = entranceEffect;
				overlay.classList.add(entranceEffect);
			}
		}
	},
	hide_overlay = function( el ) {
		const entranceEffect = el.getAttribute('data-entrance-effect'),
            isLightbox = document.getElementsByClassName('mfp-wrap')[0] !== undefined;
        if (isLightbox === false && (entranceEffect === 'flip-horizontal' || entranceEffect === 'flip-vertical')) {
            flip_effect(el, 'front');
        } else {
            const overlay = el.getElementsByClassName('image-pro-overlay')[0];
            if (isLightbox === true) {
                overlay.style['display'] = 'none';
                overlay.removeAttribute('style');
                overlay.classList.remove('wow');
                if (entranceEffect) {
                    overlay.classList.remove(entranceEffect);
                }
            } else {
                if (entranceEffect === 'none') {
                    overlay.style['visibility'] = 'visible';
                    overlay.classList.add(entranceEffect);
                } else {
                    overlay.style['animationName'] = '';
                    if (entranceEffect) {
                        overlay.classList.remove(entranceEffect);
                    }
                    overlay.classList.add('wow', 'animated');
                    const exit = el.getAttribute('data-exit-effect');
                    if (exit) {
                        overlay.classList.add(exit);
						overlay.style['animationName'] = exit;
                    }
                }
            }
        }
	},
    _mouseleave = function ( e ) {
        hide_overlay( this );
    },
    _mouseEnter = function (e) {
		show_overlay( this );
    };
    if (Themify.is_builder_active) {
		Themify.LoadCss(url+'active.css', args.ver);
        Themify.body.on('click', '.module-pro-image .themify_lightbox', function (e) {
            if (this.getAttribute('target') === '_blank') {
                e.preventDefault();
            }
        });
    }
    Themify.on('builder_load_module_partial', function (el, type, isLazy) {
        if (isLazy === true && !el[0].classList.contains('module-pro-image')) {
            return;
        }
        const items = Themify.selectWithParent('module-pro-image', el);
        for (let i = items.length - 1; i > -1; --i) {
			let cl=items[i].classList;
			for(var j=appearance.length-1;j>-1;--j){
				if(!Themify.cssLazy['tb_pro-image_'+appearance[j]] && cl.contains(appearance[j])){
					Themify.cssLazy['tb_pro-image_'+appearance[j]]=true;
					Themify.LoadCss(url+'appearance/'+appearance[j]+'.css', args.ver);
				}
			}
			for(j=filters.length-1;j>-1;--j){
				if(!loaded['filter_'+filters[j]] && ((filters[j]==='effect-grayscale-reverse' && cl.contains(filters[j])) || cl.contains('filter-'+filters[j]))){
					loaded['filter_'+filters[j]]=true;
					let f=filters[j]==='effect-grayscale-reverse'?'grayscale':filters[j];
					Themify.LoadCss(url+'filters/'+f+'.css', args.ver);
					break;
				}
			}
			for(j=image_effects.length-1;j>-1;--j){
				if(!loaded['image_'+image_effects[j]] && cl.contains('effect-'+image_effects[j])){
					loaded['image_'+image_effects[j]]=true;
					let f=image_effects[j]==='zoomin' || image_effects[j]==='zoomout'?'zoom-in-out':image_effects[j];
					Themify.LoadCss(url+'image-effects/'+f+'.css', args.ver);
					break;
				}
			}
			for(j=overlay_effects.length-1;j>-1;--j){
				if(!loaded['overlay_'+overlay_effects[j]] && cl.contains('entrance-effect-'+overlay_effects[j])){
					loaded['overlay_'+overlay_effects[j]]=true;
					Themify.LoadCss(url+'overlay-effects/'+overlay_effects[j]+'.css', args.ver);
					break;
				}
			}
			if(!loaded['external'] && items[i].getElementsByClassName('image-pro-external')[0]){
				loaded['external']=true;
				Themify.LoadCss(url+'external.css', args.ver);
			}
			if(!loaded['button']&& items[i].getElementsByClassName('image-pro-action-button')[0]){
				loaded['button']=true;
				Themify.LoadCss(url+'button.css', args.ver);
			}
			if ( ! loaded['flipButton'] && Themify.isTouch ) {
				loaded['flipButton'] = true;
				Themify.LoadCss( url + 'flip-button.css', args.ver );
			}
            items[i].removeEventListener(ev, _mouseEnter, {passive: true});
            items[i].addEventListener(ev, _mouseEnter, {passive: true});
        }
    }).loadAnimateCss();

    Themify.body.on( 'click', '.image-pro-flip-button', function(e){
        e.preventDefault();
        e.stopPropagation();
		const el = this.closest( '.module-pro-image' );
		if ( this.classList.contains( 'overlay-visible' ) ) {
			hide_overlay( el );
			this.classList.remove( 'overlay-visible' );
		} else {
			show_overlay( el );
			this.classList.add( 'overlay-visible' );
		}
    } );

}(Themify));