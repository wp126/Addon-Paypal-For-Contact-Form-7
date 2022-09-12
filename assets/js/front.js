jQuery(document).ready(function() {
	if ( jQuery( ".wpcf7-form" ).length ) {
		occostcf7_customradiobtn();
		// occostcf7_formulas();
		function occostcf7_customradiobtn() {
			jQuery("form.wpcf7-form input").each(function () { 
	       		if( jQuery(this).attr("type") == "radio" || jQuery(this).attr("type") == "checkbox" ) {
	       			var inputval = jQuery(this).attr("value");
	       			//console.log(inputval);
	       			if(inputval.indexOf("--") != -1) {
	       			    var inputdata = inputval.split("--");
	       			   	jQuery(this).parent().find('span.wpcf7-list-item-label').text(inputdata[0]);
	       			}
	       		}
	       	})
			jQuery("form.wpcf7-form select option").each(function (i) {
		       	if(jQuery(this).attr("type") === undefined) {
		       		var selectval = jQuery(this).attr("value");
		       		if(selectval.indexOf("--") != -1) {
		       			var selectdata = selectval.split("--");
		       			jQuery(this).text(selectdata[0]);
		       		}		
		       	}
	       	})  
		}
	}

	function costcf7duplicates_type(arr) {
	    var obj = {};
	    var ret_arr = [];
	    for (var i = 0; i < arr.length; i++) {
	        obj[arr[i]] = true;
	    }
	    //console.log(obj);
	    for (var key in obj) {
	    	if("_wpcf7" == key || "_wpcf7_version" == key  || "_wpcf7_locale" == key  || "_wpcf7_unit_tag" == key || "_wpnonce" == key || "undefined" == key  || "_wpcf7_container_post" == key || "_wpcf7_nonce" == key  ){

	    	}else {
	    		ret_arr.push(key);
	    	}
	    }
	    return ret_arr;
	}

	function costcf7wordInString(s, word) {
		return new RegExp( '\\b' + word + '\\b', 'i').test(s);
	}
		
});