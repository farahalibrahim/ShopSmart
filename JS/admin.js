//used to show/hide forms based on pressed button
var product_form = document.querySelector(".add_product.form");
var supermarket_form = document.querySelector(".add_supermarket.form");
var tag_form = document.querySelector(".add_tag.form");
var user_form = document.querySelector(".add_user.form");

//show/hide add product form
document.querySelector(".add_product.btn.nav").addEventListener("click", function () {
    product_form.classList.toggle("hidden");
    supermarket_form.classList.add("hidden");
    tag_form.classList.add("hidden");
    user_form.classList.add("hidden");

});
//show/hide add supermarket form
document.querySelector(".add_supermarket.btn.nav").addEventListener("click", function () {
    product_form.classList.add("hidden");
    supermarket_form.classList.toggle("hidden");
    tag_form.classList.add("hidden");
    user_form.classList.add("hidden");

});
//show/hide add tag form
document.querySelector(".add_tag.btn.nav").addEventListener("click", function () {
    product_form.classList.add("hidden");
    supermarket_form.classList.add("hidden");
    tag_form.classList.toggle("hidden");
    user_form.classList.add("hidden");

});
//show/hide add user form
document.querySelector(".add_user.btn.nav").addEventListener("click", function () {
    product_form.classList.add("hidden");
    supermarket_form.classList.add("hidden");
    tag_form.classList.add("hidden");
    user_form.classList.toggle("hidden");

});

//populate categories select
let category_select = document.querySelector("#product_category_spinner");
let category_options = ['Bakery', 'Beverages', 'Cleaning Supplies', 'Dairy', 'Fruits & Vegetables', 'Meat & Chicken', 'Personal Care', 'Snacks'];
for (let i = 0; i < category_options.length; i++) {
    let option = document.createElement("option");
    option.value = category_options[i];
    option.text = category_options[i];
    category_select.appendChild(option);

}
//show/hide img url or file
// get user choice of image src
const url = document.querySelector("#image_url");
const file = document.querySelector("#image_file");
const url_container = document.querySelector("#image_url_container");
const file_container = document.querySelector("#image_file_container");
const url_input = document.querySelector("#image_url_input");
const file_input = document.querySelector("#image_file_input");

// Listen for a change event on the radio buttons
url.addEventListener("change", () => {
    // Show the corresponding input set and hide the other one
    url_container.classList.remove("hidden");
    file_container.classList.add("hidden");

    // Add the 'required' attribute to the URL input and remove it from the file input
    url_input.setAttribute('required', '');
    file_input.removeAttribute('required');
});

file.addEventListener("change", () => {
    url_container.classList.add("hidden");
    file_container.classList.remove("hidden");

    // Add the 'required' attribute to the file input and remove it from the URL input
    file_input.setAttribute('required', '');
    url_input.removeAttribute('required');
});

// show/hide quantity & change label based on user choice of product measurement type
const weight = document.querySelector("#weight");
const piece = document.querySelector("#piece");
const quantity_label = document.querySelector("#weight_piece_label");
const quantity_info = document.querySelector("#quantity_info");
const quantity_input = document.querySelector("#product_quantity");

// Listen for a change event on the radio buttons
weight.addEventListener("change", () => {
    // Update the label text based on the user's choice
    quantity_info.classList.remove("hidden");
    quantity_label.textContent = "Weight:";
    quantity_input.placeholder = "weight in grams";
});

piece.addEventListener("change", () => {
    quantity_info.classList.remove("hidden");
    quantity_label.textContent = "Pieces:";
    quantity_input.placeholder = "";
});