window.onscroll = function() { scrollFunction() };

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("scrollBtn").style.display = "block";
    } else {
        document.getElementById("scrollBtn").style.display = "none";
    }
}

document.getElementById("scrollBtn").addEventListener("click", function() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});

// cart
let cart = []; 
let closeCart = document.querySelector('#closeCart');
let showCart = document.querySelector('#show_cart');
let cartSection = document.querySelector('.cart-section');

showCart.addEventListener('click', () => {
    displayCart();
});

closeCart.addEventListener('click', () => {
    cartSection.classList.remove('show-cart');
});

function displayCart() {
    cartSection.classList.add('show-cart');
}


    const savedCart = JSON.parse(localStorage.getItem('cart'));
    if (savedCart) {
        cart = savedCart;
    }

    updateCartUI();

    const addToCartButtons = document.querySelectorAll(".addtocart");

    addToCartButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
            // Get product details
            const productCard = button.closest(".product_card");
            const productName = productCard.querySelector(".product_name").textContent;
            const productPrice = parseFloat(productCard.querySelector(".menu-price").textContent.replace("$", ""));
            const productImg = productCard.querySelector("img").src;
            const product_id = button.getAttribute("data-product-id");; 
            const indexInCart = index;
            const product = {
                id: indexInCart,
                productId:product_id,
                name: productName,
                price: productPrice,
                quantity: 1,
                img: productImg
            };

            addProductToCart(product);
            displayCart();
            updateCartUI();
        });
    });

    function addProductToCart(product) {
        const existingProduct = cart.find(item => item.id === product.id);

        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.push(product);
        }

        saveCartToLocalStorage();
    }

    function saveCartToLocalStorage() {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function updateCartUI() {
        const cartProductsContainer = document.querySelector(".cart_products");
        const totalPriceElement = document.getElementById("total_price");
        let total = 0;

        cartProductsContainer.innerHTML = "";

        cart.forEach(product => {
            const cartProduct = document.createElement("div");
            cartProduct.classList.add("card", "flex-row", "p-1", "my-2", "cart_product");

            cartProduct.innerHTML = `
                <img src="${product.img}" class="card-img-left" alt="${product.name}" width=80>
                <div class="card-body">
                    <div class="card-top">
                        <h6 class="card-title">${product.name}</h6>
                        <p class="card-text price">${product.price} EG</p>
                        <div class="remove_product">
                            <i class="fa-solid fa-x" data-id="${product.id}"></i>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <div class="counts">
                            <button class="counts_btns minus" data-id="${product.id}">-</button>
                            <input type="number" inputmode="numeric" name="productCount" min="1" step="1" max="999" class="product_count" value="${product.quantity}" data-id="${product.id}">
                            <button class="counts_btns plus" data-id="${product.id}">+</button>
                        </div>
                        <span class="total_price">${(product.price * product.quantity).toFixed(2)} EG</span>
                    </div>
                </div>
            `;

            cartProduct.querySelector(".minus").addEventListener("click", decreaseQuantity);
            cartProduct.querySelector(".plus").addEventListener("click", increaseQuantity);
            cartProduct.querySelector(".remove_product i").addEventListener("click", removeProduct);

            cartProductsContainer.appendChild(cartProduct);

            total += product.price * product.quantity;
        });

        totalPriceElement.textContent = `${total.toFixed(2)} EG`;
    }

    function decreaseQuantity(event) {
        const productId = parseInt(event.target.getAttribute("data-id"));
        const product = cart.find(item => item.id === productId);

        if (product && product.quantity > 1) {
            product.quantity -= 1;
            updateCartUI();
            saveCartToLocalStorage();
        }
    }

    function increaseQuantity(event) {
        const productId = parseInt(event.target.getAttribute("data-id"));
        const product = cart.find(item => item.id === productId);

        if (product) {
            product.quantity += 1;
            updateCartUI();
            saveCartToLocalStorage();
        }
    }

    function removeProduct(event) {
        const productId = parseInt(event.target.getAttribute("data-id"));
        const productIndex = cart.findIndex(item => item.id === productId);

        if (productIndex > -1) {
            cart.splice(productIndex, 1);
            updateCartUI();
            saveCartToLocalStorage();
        }
    }

// send order 
document.querySelector(".checkout").addEventListener("click", checkOut);

function checkOut() {
    const order = {
        user_id:4,
        products: cart.map(product => ({
            id: product.productId,
            name: product.name,
            price: product.price,
            quantity: product.quantity
        })),
        total: document.getElementById("total_price").textContent,
        room: document.getElementById("room").value,
        notes: document.getElementById("notes").value
    };

  
    fetch('submit_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(order)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.status == 'success') {
            alert('Order placed successfully!');
            cart = [];
            updateCartUI();
            saveCartToLocalStorage();
            location.reload();
        } else {
            alert('Failed to place the order: ' + data.message);
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}

