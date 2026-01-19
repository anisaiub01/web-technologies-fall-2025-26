function searchProduct() {
    let input = document.getElementById('searchBox').value.toLowerCase();
    
    document.cookie = "last_search=" + input + "; path=/; max-age=" + (60 * 60);

    let items = document.getElementsByClassName('product-item');
    let feedback = document.getElementById('searchFeedback');
    let found = false;

    for (let i = 0; i < items.length; i++) {
        let name = items[i].getAttribute('data-name').toLowerCase();
        if (name.includes(input)) {
            items[i].style.display = "flex";
            found = true;
        } else {
            items[i].style.display = "none";
        }
    }
    feedback.innerHTML = (!found && input !== "") ? "Sorry, there is no product you're trying to find." : "";
}

function validateCart() {
    if (document.querySelectorAll('input[name="selected_products[]"]:checked').length === 0) {
        alert("Please select a product first!");
        return false;
    }
    return true;
}