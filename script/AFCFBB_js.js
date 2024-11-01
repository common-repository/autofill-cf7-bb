/*   JQUery functions 
______________________________________ */

jQuery(document).ready(function($) {
			if($.browser.mozilla)$("form").attr("autocomplete", "off");
			
			var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				var str = $(this).children("h3").text().replace(/\s/g, "_");
				$(this).attr("id", stripAccents(str).toLowerCase());
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({ fx: { opacity: "toggle", duration: "fast" } });
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			$(".wrap h3, .wrap table").show();
			jQuery(".tinyMCETA").each(function(){
				tinyMCE.execCommand("mceAddControl", false, $(this).attr('id'));
			});
			$('a.toggleVisual').click(
			  function() {
				tinyMCE.execCommand('mceAddControl', false, $(this).attr("name"));
			  }
			);
			
			$('a.toggleHTML').click(
			  function() {
				tinyMCE.execCommand('mceRemoveControl', false, $(this).attr("name"));
			  }
			);
							
		});

/* utilistaire jQuery */
 function stripAccents(chaine) {
      temp = chaine.replace(/[àâä]/gi,"a")
      temp = temp.replace(/[éèêë]/gi,"e")
      temp = temp.replace(/[îï]/gi,"i")
      temp = temp.replace(/[ôö]/gi,"o")
      temp = temp.replace(/[ùûü]/gi,"u")
      return temp
   }








/* end Jquery 
__________________ */

function afcfbb_reset_to_default(selectstr, radiostr, cbstr,cf7_wrap){
	jQuery('#select_string').val(decodeURIComponent((selectstr + '').replace(/\+/g, '%20')));
	jQuery('#radio_string').val(decodeURIComponent((radiostr + '').replace(/\+/g, '%20')));
	jQuery('#checkbox_string').val(decodeURIComponent((cbstr + '').replace(/\+/g, '%20')));
	jQuery('#cf7_wrap').val(decodeURIComponent((cf7_wrap + '').replace(/\+/g, '%20')));
	//tinyMCE.activeEditor.setContent(decodeURIComponent((defaultcontent + '').replace(/\+/g, '%20')));
}

	
/* ______________________________________ */
Node.prototype.insertAfter = function(noeudAInserer, noeudDeReference) {
	if(noeudDeReference.nextSibling) {
		return this.insertBefore(noeudAInserer, noeudDeReference.nextSibling);
	} else {
		return this.appendChild(noeudAInserer);
	}
}