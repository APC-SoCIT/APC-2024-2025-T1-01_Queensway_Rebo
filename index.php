<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your E-commerce Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .product img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">YourStore</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1>Welcome to YourStore</h1>
            <p class="lead">Discover our exclusive collection of products</p>
            <a href="#" class="btn btn-primary btn-lg">Shop Now</a>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div id="products" class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Products will be dynamically inserted here -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4">
        <div class="container text-center">
            <p>&copy; 2025 YourStore. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    <!-- Fetch and Display Products -->
    <script>
        fetch('fetch_products.php')
            .then(response => response.json())
            .then(data => {
                let productsContainer = document.getElementById('products');
                data.forEach(product => {
                    let productHTML = `
                        <div class="col">
                            <div class="card product-card h-100">
                                <img src="admin/${product.image}" class="card-img-top" alt="${product.name}">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">₱${product.price}</p>
                                    <p class="card-text">Stock: ${product.quantity}</p>
                                   <a href="product_detail.php?id=${product.id}" class="btn btn-primary">View Details</a>

                                </div>
                            </div>
                        </div>
                    `;
                    productsContainer.innerHTML += productHTML;
                });
            })
            .catch(error => console.error('Error fetching products:', error));
    </script>
</body>
</html>
