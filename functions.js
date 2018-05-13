var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];
function inWords (num) {
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
	if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
    return str;
}
//1366*768
function cleanArray(actual) {
  	var newArray = new Array();
  	for (var i = 0; i < actual.length; i++) {
    	if (actual[i]) {
     	 newArray.push(actual[i]);
    	}
  	}
  	return newArray;
}
function capitalLetter(str) {
    str = cleanArray(str.split(" "));
    for (var i = 0, x = str.length; i < x; i++) {
        str[i] = str[i][0].toUpperCase() + str[i].substr(1);
    }
    return str.join(" ");
}
function convertDate(inputFormat) {
	function pad(s) { return (s < 10) ? '0' + s : s; }
 	var d = new Date(inputFormat);
  	return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
}

function readURL(input) {
	if (input.files && input.files[0]) {
    	var reader = new FileReader();

	    reader.onload = function(e) {
	      $('#photo').attr('src', e.target.result);
	    }

	    reader.readAsDataURL(input.files[0]);
	}
}