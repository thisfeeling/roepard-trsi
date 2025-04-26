
# 🚀 Proyecto TRSI

Este es un proyecto para gestionar usuarios, tendencias y estadísticas.  
Está construido con las siguientes tecnologías:

- 🐘 **PHP** para el backend  
- 🛢️ **MySQL** para la base de datos  
- 🎨 **HTML**, **CSS**, **JavaScript** y **Bootstrap** para el frontend  

---

## 🧩 Funcionalidades

- 🔐 Panel de **login** de usuarios  
- 👥 Panel de **gestión de usuarios**  
- 👤 Panel de **usuario individual**

---

## 📁 Estructura del Proyecto

```
/Proyecto-TRSI
│
├── /backend                       # Lógica del servidor
│   ├── AuthController.php         # Inicio de sesión
│   ├── CrUserController.php       # Crear usuarios
│   ├── DBconfig.php               # Configuración de la base de datos
│   ├── DelUserController.php      # Eliminar usuarios
│   ├── DetUserController.php      # Detalles de usuario
│   ├── LiUserController.php       # Listar usuarios
│   ├── LogoutController.php       # Cerrar sesión
│   ├── RegController.php          # Registro de usuarios
│   └── UpUserController.php       # Actualizar usuarios
│
├── /css                           # Estilos del proyecto
│   ├── bootstrap.min.css          # Estilos de Bootstrap
│   ├── fonts.css                  # Definición de fuentes
│   ├── style.css                  # Estilos generales
│   └── variables.css              # Variables globales
│
├── /fonts                         # Fuentes personalizadas
│
├── /icons                         # Iconos utilizados
│
├── /js                            # Lógica del frontend
│   ├── bootstrap.bundle.min.js    # Bootstrap JS
│   ├── jquery.js                  # jQuery
│   ├── login.js                   # Lógica del login
│   ├── main.js                    # Funciones principales
│   ├── manage-users.js            # CRUD de usuarios
│   ├── register.js                # Registro de usuarios
│   └── user-panel.js              # Panel de usuario
│
├── /uploads                       # Imágenes subidas por los usuarios
│
├── /views                         # Vistas del proyecto
│   ├── about.php                  # Sobre nosotros
│   ├── company.php                # Panel del administrador
│   ├── footer.php                 # Pie de página
│   ├── home.php                   # Página principal
│   ├── login.php                  # Página de inicio de sesión
│   ├── manage-users.php           # Administración de usuarios
│   ├── navbar.php                 # Barra de navegación
│   ├── register.php               # Registro de usuario
│   ├── reviews.php                # Reseñas
│   ├── terms.php                  # Términos y condiciones
│   └── user-panel.php             # Panel de usuario
│
└── index.php                      # Punto de entrada principal
```
