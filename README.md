# Proyecto Synapse

Este es un proyecto para gestionar usuarios, pedidos, notificaciones y productos. Utiliza PHP para el backend, SQL para la base de datos y HTML, CSS, JS y Bootstrap para el frontend.

## Funcionalidades

- **Panel de registro y login de usuarios**
- **Panel Gestion de usuarios**
- **Panel de usuario**

/Proyecto-Synapse
│
├── /backend                           # Lógica del servidor         
│   ├── AuthController.php             # Controlador para consulta de inicio de sesion
│   ├── CrUserController.php           # Controlador para crear usuarios
│   ├── DBconfig.php                   # Configuracion de la base de datos
│   ├── DelUserController.php          # Controlador para eliminar usuarios
│   ├── DetUserController.php          # Controlador detalles de usuario
│   ├── LiUserController.php           # Controlador para enlistar usuarios
│   ├── LogoutController.php           # Controlador para cerrar la sesion
│   ├── RegController.php              # Controlador para registrarse
│   └── UpUserController.php           # Controlador para actualizar usuario
│
├── /css                               # Estilos CSS del proyecto y Boostrap CSS    
│   ├── bootstrap.min.css              # Boostrap CSS
│   ├── fonts.css                      # Definicion de fons
│   ├── style.css                      # Definicion de estilos para vistas HTML
│   └── variables.css                  # Variables Globales
│
├── /fonts                             # Fuentes personalizadas               
│
├── /icons                             # Iconos del proyecto
│
├── /js                                # Logica del frontend en JavaScript y Boostrap JS
│   ├── bootstrap.bundle.min.js        # Boostrap JS
│   ├── jquery.js                      # jQuery
│   ├── login.js                       # Logica de logeo
│   ├── main.js                        # Logica de funciones principales
│   ├── manage-users.js                # Logica para la manipulacion de usuarios mediante CRUD              
│   ├── register.js                    # Logica de registro
│   └── user-panel.js                  # Logica de panel de usuario    
│
├── /uploads                           # Imagenes subidas por usuarios y imagen por defecto 
│
├── /views                             # Vistas HTML
│   ├── about.php                      # Pagina Sobre nosotros
│   ├── company.php                    # Pagina central para el administrador
│   ├── footer.php                     # Pie de pagina
│   ├── home.php                       # Pagina principal
│   ├── login.php                      # Pagina de inicio de sesión
│   ├── manage-users.php               # Pagina para administracion de usuarios
│   ├── navbar.php                     # Barra de navegación
│   ├── register.php                   # Pagina de registro
│   ├── reviews.php                    # Pagina de reseñas
│   ├── terms.php                      # Pagina de terminos y condiciones
│   └── user-panel.php                 # Panel de usuario
│
└── index.php                          # Entrada principal del proyecto