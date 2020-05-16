<!doctype html>
<html lang="en" class="h-100">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>COVID-19 в България - статистика</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light h-100 d-flex flex-column">
	<main class="container">
		<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">
			<div class="lh-100">
				<h1 class="lh-100 text-white m-0">COVID-19 в България - статистика</h1>
				<p><small>Информация на база данни от Министерство на здравеопазването</small></p>
			</div>
		</div>
		<div class="my-3 p-3 rounded bg-white shadow-sm">
			<div class="row">
				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-danger text-white">
							<div class="card-info">
								<h4 class="m-0 font-weight-bold" id="confirmed"></h4>
								<p>потвърдени случаи</p>
							</div>
							<div class="icon card-icon pulse-outline">
								<ion-icon name="pulse-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-info text-white">
							<div class="card-info">
								<h4 class="m-0 font-weight-bold" id="newCases"></h4>
								<p>нови случаи за деня</p>
							</div>
							<div class="icon card-icon trending-up-outline">
								<ion-icon name="trending-up-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-secondary text-white">
							<div class="card-info">
								<h5 class="m-0 font-weight-bold" id="hospital"></h5>
								<p>хоспитализирани</p>
							</div>
							<div class="icon card-icon medkit-outline">
								<ion-icon name="medkit-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-warning text-white">
							<div class="card-info">
								<h4 class="m-0 font-weight-bold" id="intensive"></h4>
								<p>критични</p>
							</div>
							<div class="icon card-icon fitness-outline">
								<ion-icon name="fitness-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="my-3 p-3 rounded bg-white shadow-sm">
			<div class="row">

				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-info text-white">
							<div class="card-info">
								<h5 class="m-0 font-weight-bold" id="active"></h5>
								<p>активни</p>
							</div>
							<div class="icon card-icon checkmark-outline">
								<ion-icon name="checkmark-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-success text-white">
							<div class="card-info">
								<h4 class="m-0 font-weight-bold" id="recovered"></h4>
								<p>излекувани</p>
							</div>
							<div class="icon card-icon person-add-outline">
								<ion-icon name="person-add-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-dark text-white">
							<div class="card-info">
								<h4 class="m-0 font-weight-bold" id="deaths"></h4>
								<p>починали</p>
							</div>
							<div class="icon card-icon heart-dislike-circle-outline">
								<ion-icon name="heart-dislike-circle-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card">
						<div class="card-body bg-danger text-white">
							<div class="card-info">
								<h4 class="m-0 font-weight-bold" id="percent"></h4>
								<p>% починали</p>
							</div>
							<div class="icon card-icon information-circle-outline">
								<ion-icon name="information-circle-outline"></ion-icon>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="my-3 p-3 rounded bg-white shadow-sm">
			<div class="row">
				<div class="col">
					Прогноза заразени за дата
				</div>
			</div>
			<form>
				<div class="row">
					<div class="col-md-3">
<!--						<input id="date" type="date" class="form-control m-2" placeholder="First name">-->
						<input id="date" type="text" class="form-control my-2" placeholder="Date" autocomplete="off">

					</div>
					<div class="col-md-9">
						<div class="alert alert-warning" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<div id="prognosis">Изберете дата прогноза</div>
						</div>
					</div>
				</div>
			</form>
		</div>

	</main>
	<footer class="footer mt-auto py-3">
		<div class="container text-center">
			<span class="text-muted">COVID-19 Bulgaria - statistics </span><span id="footer-date" class="text-muted"></span>
		</div>
	</footer>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="app.js"></script>
	<script>
		function equalize(selector) {
			let height = 0;
			$(selector).each(function() {
				if ($(this).height() > height) {
					height = $(this).height();
				}
			});
			$(selector).each(function() {
				$(selector).height(height);
			});
		}
		equalize('.card');
    
	</script>
</body>

</html>
