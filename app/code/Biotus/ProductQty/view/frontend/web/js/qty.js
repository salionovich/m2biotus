document.addEventListener("DOMContentLoaded", function () {
    const qtyContainer = document.getElementById('product-qty-container');
    let productId = qtyContainer.getAttribute('data-product-id');

    const updateQty = () => {
        fetch(`/biotus/ajax/getQty?product_id=${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('product-qty').innerText = data.qty;
            })
            .catch(error => {
                console.error('Error fetching product quantity:', error);
            });
    };

    // Initial quantity update
    updateQty();

    // Event listener for when an attribute is selected
    document.querySelectorAll('.swatch-option').forEach(option => {
        option.addEventListener('click', function () {
            // Get the selected product ID
            productId = qtyContainer.getAttribute('data-product-id');
            updateQty();
        });
    });
});
