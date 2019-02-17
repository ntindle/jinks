<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Sebastian King">

	<link rel="icon" href="/favicon.png" type="image/png" />

    <title>JINKS - Mood-based transaction verification</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/animation.css" rel="stylesheet">
    <link href="css/checkbox/orange.css" rel="stylesheet">
    <link href="css/preview.css" rel="stylesheet">
    <link href="css/authenty.css" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/themes/smoothness/jquery-ui.css">

    <!-- Font Awesome CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	
	<style>
		textarea#description {
			margin-bottom: 10px;
			background-color: #fff;
			padding-left: 20px;
			padding: 16px 16px 25px 16px;
			border-radius: 0;
			border: 2px solid #dedede;
			margin-bottom: 16px;
			color: #999;
			height: 80px;
		}
		select#merchant {
			margin-bottom: 10px;
			background-color: #fff;
			padding-left: 20px;
			padding: 15px;
			border-radius: 0;
			border: 2px solid #dedede;
			margin-bottom: 16px;
			color: #999;
			    height: 55px;
		}
		.split-container {
			display: flex;
		}
		.split {
			flex: 1;
		}
		.split-left {
			margin-left: 50px;
		}
		.split-right {
			margin-left: 25px;
			border-left: 2px solid #e2e2e2;
			padding: 20px 40px 25px 40px;
		}
		.transactions {
			width: 100%;
			height: 100%;
			overflow-y: scroll;
		}
		.authenty.signin-main input {
			transition: all 1s ease-in-out;
		}
		.authenty.signin-main input.format-error {
			border-color: #ea1515;
		}
	</style>
</head>

<body>
    <section id="authenty_preview">
        <section id="signin_main" class="authenty signin-main">
            <div class="section-content" style="min-height: 100vh;">
                <div class="wrap">
                    <div class="container">
                        <div class="row">
                            <div class="form-wrap" data-animation="fadeInUp" data-animation-delay=".8s" style="box-shadow: 0 0 5px 1px #616161;">
                                <div class="title hidden-sm hidden-xs">
                                    <h1>JINKS</h1>
                                    <h5>Mood-based transaction verification</h5>
									<img style="width: 150px;" src="/img/logo.png"/>
									<h6>Saving you money, when you need it most</h6>
                                    <div class="overlay"></div>
                                </div>
								<div id="form_1" class="split-container">
									<div class="split split-left">
										<div class="form-main">
											<div class="form-group">
												<input type="text" id="amount" class="form-control" placeholder="Amount" required="required">
												<select class="form-control" id="merchant" placeholder="Merchant">
												  <option>Choose a merchant...</option>
												  <option value="volvo">Volvo</option>
												  <option value="saab">Saab</option>
												  <option value="mercedes">Mercedes</option>
												  <option value="audi">Audi</option>
												</select>
												<input style="background-color: #e4e4e4;" type="text" id="date" class="form-control" placeholder="Date" value="<?php echo date('Y-m-d'); ?>" required="required" disabled="disabled"/>
												<textarea style="margin-bottom: 10px;" id="description" class="form-control" placeholder="Description" required="required"></textarea>
												<button id="signIn_1" type="submit" class="btn btn-block signin">Test Transaction</button>
											</div>
										</div>
									</div><!-- no whitespace in between inline elements
								 --><div class="split split-right">
										<h4>Purchases</h4>
										<div class="transactions">
											<div class="transaction-list">
												Loading...
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
	</section>

    <!-- js library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.icheck.min.js"></script>
    <script src="js/waypoints.min.js"></script>

    <!-- authenty js -->
    <script src="js/authenty.js"></script>

    <!-- preview scripts -->
    <script src="js/preview/jquery.malihu.PageScroll2id.js"></script>
    <script src="js/preview/jquery.address-1.6.min.js"></script>
    <script src="js/preview/scrollTo.min.js"></script>
    <script src="js/preview/init.js"></script>


    <!-- preview scripts -->
    <script>
        (function($) {

			$(document).ready(function() {
				$.get('/api/get-all-purchases', function(data) {
					console.log(data);
				});
			});
			
			$('#signIn_1').click(function (e) {  
	   
				var amount = $.trim($('#amount').val());
				var merchant = $.trim($('#merchant').val());
				var date = $.trim($('#date').val());
				var description = $.trim($('#description').val());

				do {
					if (amount === '' || isNaN(amount)) {
						$('#form_1 .fa-user').removeClass('success').addClass('fail');
						$('#form_1').addClass('fail');
						$('#amount').addClass('format-error');
					} else if (merchant === '') {
						
					} else {
						$('#form_1 .fa-user').removeClass('fail').addClass('success');
						$('#form_1').removeClass('fail').removeClass('animated');
						return true;
					}
				} while (false);
				setTimeout(function() {
					$('#form_1').removeClass('fail');
				}, 1000);
				
			});
			
			$("#amount, #merchant, #date, #description").on('focus', function(e) {
				$(this).removeClass('format-error');
			});
			
            // get full window size
            $(window).on('load resize', function() {
                var w = $(window).width();
                var h = $(window).height();

                $('section').height(h);
            });

            // scrollTo plugin
            $('#signup_from_1').scrollTo({
                easing: 'easeInOutQuint',
                speed: 1500
            });
            $('#forgot_from_1').scrollTo({
                easing: 'easeInOutQuint',
                speed: 1500
            });
            $('#signup_from_2').scrollTo({
                easing: 'easeInOutQuint',
                speed: 1500
            });
            $('#forgot_from_2').scrollTo({
                easing: 'easeInOutQuint',
                speed: 1500
            });
            $('#forgot_from_3').scrollTo({
                easing: 'easeInOutQuint',
                speed: 1500
            });


            // set focus on input
            var firstInput = $('section').find('input[type=text], input[type=email]').filter(':visible:first');

            if (firstInput != null) {
                firstInput.focus();
            }

            $('section').waypoint(function(direction) {
                var target = $(this).find('input[type=text], input[type=email]').filter(':visible:first');
                target.focus();
            }, {
                offset: 300
            }).waypoint(function(direction) {
                var target = $(this).find('input[type=text], input[type=email]').filter(':visible:first');
                target.focus();
            }, {
                offset: -400
            });


            // animation handler
            $('[data-animation-delay]').each(function() {
                var animationDelay = $(this).data("animation-delay");
                $(this).css({
                    "-webkit-animation-delay": animationDelay,
                    "-moz-animation-delay": animationDelay,
                    "-o-animation-delay": animationDelay,
                    "-ms-animation-delay": animationDelay,
                    "animation-delay": animationDelay
                });
            });

            $('[data-animation]').waypoint(function(direction) {
                if (direction == "down") {
                    $(this).addClass("animated " + $(this).data("animation"));
                }
            }, {
                offset: '90%'
            }).waypoint(function(direction) {
                if (direction == "up") {
                    $(this).removeClass("animated " + $(this).data("animation"));
                }
            }, {
                offset: '100%'
            });

        })(jQuery);
    </script>
</body>

</html>