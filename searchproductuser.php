<?php
  session_start();
  require_once 'connector.php';
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Custom Theme files -->
<!--theme-style-->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Mattress Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--fonts-->
<link href='//fonts.googleapis.com/css?family=Lato:100,300,400,700,900' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'><!--//fonts-->
<!-- start menu -->
<link href="css/memenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/memenu.js"></script>
<script>$(document).ready(function(){$(".memenu").memenu();});</script>
<script src="js/simpleCart.min.js"> </script>
</head>
<body>
<!--header-->
<div class="header">
	<div class="header-top">
		<div class="container">

			<div class="header-left">

				<div class="search-box">
					<div id="sb-search" class="sb-search">
						<form action="searchproductuser.php" method="get">
							<input class="sb-search-input" placeholder="Enter your search term..." type="text" name="search"  id="search">
							<input class="sb-search-submit" type="submit" value="">
							<span class="sb-icon-search"> </span>
						</form>
					</div>
				</div>

<!-- search-scripts -->
					<script src="js/classie.js"></script>
					<script src="js/uisearch.js"></script>
						<script>
							new UISearch( document.getElementById( 'sb-search' ) );
						</script>
					<!-- //search-scripts -->

<div class="ca-r">
  <div class="cart box_1">
    <a href="checkout.html">
    <h3> <div class="total">
      <span class="simpleCart_total"></span> </div>
      <img src="images/cart.png" alt=""/></h3>
    </a>
    <p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>

  </div>
</div>
  <div class="clearfix"> </div>
</div>

</div>
</div>
<div class="container">
<div class="head-top">
<div class="logo">
  <a href="index.html">
<img src="images/logo.png" class="navbar-brand">
</a>
</div>

<div class=" h_menu4">
&nbsp;&nbsp;&nbsp;
<ul class="memenu skyblue">
    <li><a class="color8" href="about.html"><strong>ABOUT US</strong></a></li>
      <li><a class="color1" href="products.html">PRODUCTS</a>
        <div class="mepanel">
    <div class="row">
      <div class="col1">
        <div class="h_nav">
          <ul>
            <li><a href="bananaproducts.html">Banana</a></li>
            <li><a href="cocoaproducts.html">Cocoa</a></li>
            <li><a href="coconutproducts.html">Coconut</a></li>
            <li><a href="herbalproducts.html">Herbal</a></li>
            <li><a href="mangoproducts.html">Mango</a></li>
            <li><a href="pineappleproducts.html">Pineapple</a></li>

          </ul>
        </div>
      </div>

  </li>

<li><a class="color4" href="services.html">SERVICES</a></li>
<li><a class="color6" href="contact.html">CONTACT</a></li>
</ul>
</div>

<div class="clearfix"> </div>
</div>
</div>
</div>
</nav>


<?php
  $search = $_GET['search'];
  $results = mysqli_query ($dbconn,'SELECT * FROM products WHERE name LIKE "%'.$search.'%"');

if($results->num_rows > 0) {

while($rows = mysqli_fetch_array($results)){
echo"




<div class='col-sm-4'>
        <div class='thumbnail'>
      <div class='grid-top simpleCart_shelfItem'>
        <a href='single.html' class='b-link-stripe b-animate-go  thickbox'><img class='img-responsive' src='images/coconutvinegar.png' alt=''>
          <div class='b-wrapper'>
                  <h3 class'b-animate b-from-left    b-delay03 >
                    <span>".$rows['name']."</span>
                  </h3>
                </div>
        </a>
      <p><a href='single.html'>".$rows['name']."</a></p>
      <a href='#' class='item_add'><p class='number item_price'><i> </i>$500.00</p></a>
      </div>
      </div>
      </div>
  ";
}
}
?>


<!----->
<div class="footer w3layouts">
      <div class="container">
    <div class="footer-top-at w3">

      <div class="col-md-3 amet-sed w3l">
      <h4>MORE INFO</h4>
      <ul class="nav-bottom">
          <li><a href="faq.html">FAQS</a></li>
          <li><a href="forwarders.html">Forwarder</a></li>
          <li><a href="contact.html">Location</a></li>
          <li><a href="services.html">Other Services</a></li>

        </ul>
      </div>
      <div class="col-md-3 amet-sed w3ls">
        <h4>PRODUCTS</h4>
        <ul class="nav-bottom">
          <li><a href="bananaproducts.html">Banana</a></li>
          <li><a href="cocoaproducts.html">Cocoa</a></li>
          <li><a href="coconutproducts.html">Coconut</a></li>
          <li><a href="herbalproducts.html">Herbal</a></li>
          <li><a href="mangoproducts.html">Mango</a></li>
          <li><a href="pineappleproducts.html">Pineapple</a></li>
        </ul>

      </div>

      <div class="col-md-3 amet-sed agileits-w3layouts">
      <h4>CONTACT US</h4>
        <p>Lorem Ipsum</p>
        <p>Lorem Ipsum</p>
        <p>Lorem Ipsum</p>
        <div class="social">
          <ul>
            <li><a href="https://www.facebook.com/cocosport.drink"  target="_blank"><i class="facebok"> </i></a></li>


            <li><a href="https://www.instagram.com/llanesfarm/"  target="_blank"><i class="goog"> </i></a></li>
              <div class="clearfix"></div>
          </ul>
        </div>
      </div>
      </div>
      <div class="clearfix"> </div>
    </div>
  </div>
  <div class="footer-class w3-agile">

  </div>
  </div>
</body>
</html>
