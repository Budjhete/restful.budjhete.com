<!DOCTYPE html>

<html>

	<head>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	</head>

	<body>

		<div class="container">
			<section class="row">
				<div class="col-md-6 col-md-offset-3">

					<h2>Available companies</h2>

					<ul class="list-group">
						<?php foreach ($companies as $company): ?>

							<?php echo HTML::anchor('#', $company['Name'], array('class' => 'list-group-item', 'data-toggle' => 'modal', 'data-target' => '#modal-login-' . $company['Name'])) ?>

						<?php endforeach; ?>

					</ul>
				</div>
			</section>
		</div>

		<?php foreach ($companies as $company): ?>
			<div id="modal-login-<?php echo $company['Name'] ?>" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
							</button>
							<h4 class="modal-title"><?php echo $company['Name'] ?></h4>
						</div>
						<?php echo Form::open() ?>
						<div class="modal-body">
							<div class="">
								<?php echo Form::input('username', Arr::get($company, 'LastUsername')) ?>
								<?php echo Form::password('password') ?>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Login</button>
						</div>
						<?php echo Form::close() ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

	</body>

</html>