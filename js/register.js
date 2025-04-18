// Starter JavaScript for disabling form submissions if there are invalid fields
(() => {
    'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')
    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();

$(document).ready(function() {
    $('#RegisterForm').submit(function (event) {
        event.preventDefault();
        console.log("Register process started");
        
        // Obtener valores del formulario
        var first_name = $.trim($('input[name="first_name"]').val());
        var last_name = $.trim($('input[name="last_name"]').val());
        var phone = $.trim($('input[name="phone"]').val());
        var country = $.trim($('input[name="country"]').val());
        var city = $.trim($('input[name="city"]').val());
        var birthdate = $.trim($('input[name="birthdate"]').val());
        var username = $.trim($('input[name="username"]').val());
        var email = $.trim($('input[name="email"]').val());
        var password = $.trim($('input[name="password"]').val());
        var agree_terms = $('#agreeTerms').is(':checked'); // Checkbox de términos y condiciones

        // Validación simple (puedes agregar más según tus necesidades)
        if (!first_name || !last_name || !phone || !country || !city || !birthdate || !username || !email || !password) {
            showModal("Please fill in all required fields.");
            return;
        }

        if (!agree_terms) {
            showModal("You must agree to the terms and conditions to register.");
            return;
        }

        // Llamada a la función para registrar usuario
        RegisterUser(first_name, last_name, phone, country, city, birthdate, username, email, password);
    });
});

// Función para registrar un nuevo usuario
function RegisterUser(first_name, last_name, phone, country, city, birthdate, username, email, password) {
    $.ajax({
        url: '../backend/RegController.php',
        method: 'POST',
        data: { 
            first_name: first_name,
            last_name: last_name,
            phone: phone,
            country: country,
            city: city,
            birthdate: birthdate,
            username: username,
            email: email,
            password: password
        },
        dataType: 'json',       
        success: function (response) {
            console.log("Response: ", response);
            var $modal = $('#RegisterModal');
            if (response.status == "success") {
                $modal.find('.modal-title').text("Register response: ");
                $modal.find('.modal-body').text(response.message);
                $modal.on('hidden.bs.modal', function () {
                    window.location.href = "../views/login.php";
                });
                $modal.modal('show');
            } else {
                showModal(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
            alert("An error occurred during registration.");
        }
    });
}

// Función para mostrar el modal de mensajes
function showModal(message) {
    var $modal = $('#RegisterModal');
    $modal.find('.modal-title').text("Register response: ");
    $modal.find('.modal-body').text(message);
    $modal.modal('show');
};
