<?php

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style>
		html {
			box-sizing: border-box;
			font-family: 'PT Sans', sans-serif;
			-webkit-font-smoothing: antialiased;
		}
		*,
		*:before,
		*:after {
			box-sizing: inherit;
		}
		body {
			background-color: #f3f3f3;
		}
		form {
			width: 100%;
			max-width: 400px;
			margin: 60px auto;
		}
		form input {
			font-size: 30px;
			padding: 0 20px;
			border: 2px solid #ccc;
			width: 100%;
			color: #666;
			line-height: 3;
			border-radius: 7px;
			font-family: 'PT Sans', sans-serif;
			font-weight: bold;
		}
		form input:focus {
			outline: 0;
		}
		form input.error {
			border-color: #ff0000;
		}
		form label.error {
			background-color: #ff0000;
			color: #fff;
			padding: 6px;
			font-size: 11px;
		}

		label {
			color: #999;
			display: block;
			margin-bottom: 10px;
			text-transform: uppercase;
			font-size: 18px;
			font-weight: bold;
			letter-spacing: 0.05em
		}
		form small {
			color: #888;
			font-size: 1em;
			margin-top: 10px;
			display: block;
			align-self: ;
		}
	</style>
</head>
<body>
<form id="form" method="post" action="">
	<label for="amount">Date</label>
	<input type="text" id="date">
	<small>Enter date as Month / Day / Year</small>
	<input class="btn btn-secondary" type="submit" value="submit">
</form>
<script>
    var date = document.getElementById('date');

    function checkValue(str, max) {
        if (str.charAt(0) !== '0' || str == '00') {
            var num = parseInt(str);
            if (isNaN(num) || num <= 0 || num > max) num = 1;
            str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
        };
        return str;
    };

    date.addEventListener('input', function(e) {
        this.type = 'text';
        var input = this.value;
        if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
        var values = input.split('/').map(function(v) {
            return v.replace(/\D/g, '')
        });
        if (values[0]) values[0] = checkValue(values[0], 12);
        if (values[1]) values[1] = checkValue(values[1], 31);
        var output = values.map(function(v, i) {
            return v.length == 2 && i < 2 ? v + ' / ' : v;
        });
        this.value = output.join('').substr(0, 14);
    });

    date.addEventListener('blur', function(e) {
        this.type = 'text';
        var input = this.value;
        var values = input.split('/').map(function(v, i) {
            return v.replace(/\D/g, '')
        });
        var output = '';

        if (values.length == 3) {
            var year = values[2].length !== 4 ? parseInt(values[2]) + 2000 : parseInt(values[2]);
            var month = parseInt(values[0]) - 1;
            var day = parseInt(values[1]);
            var d = new Date(year, month, day);
            if (!isNaN(d)) {
                document.getElementById('result').innerText = d.toString();
                var dates = [d.getMonth() + 1, d.getDate(), d.getFullYear()];
                output = dates.map(function(v) {
                    v = v.toString();
                    return v.length == 1 ? '0' + v : v;
                }).join(' / ');
            };
        };
        this.value = output;
    });
</script>
</body>
</html>
