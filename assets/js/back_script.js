jQuery( document ).ready(function() {
    jQuery('body').on('click','.addcolumn',function(){
    	//jQuery(".ocscw_chart_tbl tr .first_col").after('<td><a class="addcolumn"><img src= " '+ ocscw_object_name + '/includes/images/plus.png"></a><a class="deletecolumn"><img src= " '+ ocscw_object_name + '/includes/images/delete.png"></a></td>');

        var td = jQuery(this).closest('td');
        var indexa = td.index();
        jQuery('.ococf7_tbl tr:first td:nth-child('+(indexa+1)+')').after('<td><a class="addcolumn"><img src= " '+ apwcf7_name.apwcf7_array_img + '/assets/images/plus-circular-button_1.png"></a><a class="deletecolumn"><img src= " '+ apwcf7_name.apwcf7_array_img + '/assets/image/minus.png"></a></td>');
        


        jQuery(".ococf7_tbl tr").not(':first').each(function(index){
            jQuery(this).find('td:nth-child('+(indexa+1)+')').after("<td><input type='text' name='dis[]'></td>");     
        });
        var total_row = cfway_count_row();
        var total_column = cfway_count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
    });


    jQuery('body').on('click','.addrow',function(){
    	var total_column = cfway_count_col();
        let row = jQuery('<tr></tr>');
        var column = "";
        for (col = 1; col <= total_column; col++) {
            column += '<td><label>price</label><input type="text" name="prices[]"></td>';
            column += '<td><label>Quantity</label><input type="text" name="qunty[]"></td>';
            column += '<td><label>Description</label><input type="text" name="dis[]"></td>';
        }
        column += '<td><a class="addrow"><img src= " '+ apwcf7_name.apwcf7_array_img + '/assets/image/plus-circular-button_1.png"></a><a class="deleterow"><img src= " '+ apwcf7_name.apwcf7_array_img + '/assets/image/minus.png"></a></td>';
        row.append(column);
        jQuery(this).closest('tr').after(row);
    	//jQuery(".ocscw_chart_tbl").append(jQuery(".ocscw_chart_tbl tr:nth-child(2)").clone());
        var total_row = cfway_count_row();
        var total_column = cfway_count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
    });


    function cfway_count_col(){
    	var colCount = 0;
	    jQuery('.ococf7_tbl tr:nth-child(1) td').each(function () {
	       	colCount++;
	    });
	    return colCount - 1;
    }


    function cfway_count_row(){
    	var rowCount = jQuery('.ococf7_tbl tr').length;
    	return rowCount - 1;
    }


    jQuery("body").on('click', '.deletecolumn', function(){
        var td = jQuery(this).closest('td');
        var indexa = td.index();
        jQuery(this).closest('table').find('tr').each(function() {
            this.removeChild(this.cells[ indexa ]);
        });
        var total_row = cfway_count_row();
        var total_column = cfway_count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
        return false;
    });


    jQuery("body").on('click', '.deleterow', function(){
        jQuery(this).parent().parent().remove();
        var total_row = cfway_count_row();
        var total_column = cfway_count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
        return false;
    });

});