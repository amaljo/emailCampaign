$(document).ready(function() {
    $('.subscriberIdsAll').click(function(event) {  //on click 
        if (this.checked) { // check select status
            $('.subscriberIDs').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        } else {
            $('.subscriberIDs').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });
        }
    });

});