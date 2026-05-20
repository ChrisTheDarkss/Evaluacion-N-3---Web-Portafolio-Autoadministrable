# Documento de Uso de Inteligencia Artificial

## Proyecto: Portafolio Estudiantil Interactivo

---

## 1. Herramientas de IA Utilizadas

- **Claude (Anthropic)** — Asistencia en estructura del proyecto, generación de código base y resolución de problemas
- **ChatGPT (OpenAI)** — Consultas puntuales sobre sintaxis PHP y MySQL

---

## 2. Prompts Utilizados (Ejemplos Reales)

### Prompt 1 — Estructura inicial
> "Necesito crear un portafolio web con PHP, MySQL y Bootstrap 5. El proyecto debe tener un sistema de login, dashboard administrativo con CRUD y secciones de biografía, habilidades, tecnologías, proyectos y contacto. ¿Cuál sería la estructura de carpetas recomendada?"

### Prompt 2 — Base de datos
> "Genera el script SQL para las tablas de un portafolio estudiantil: biografía, habilidades, tecnologías, proyectos, mensajes de contacto y usuarios admin. Sin CREATE DATABASE porque la base ya existe."

### Prompt 3 — Endpoint AJAX
> "Necesito un endpoint PHP que reciba un formulario de contacto por POST, valide los campos y guarde en MySQL usando PDO. Que responda en JSON."

### Prompt 4 — Dashboard
> "Crea un panel administrativo con sidebar, navegación entre secciones vía JavaScript y tablas con botones de editar/eliminar que usen AJAX para no recargar la página."

---

## 3. Resultados Generados

- Estructura completa de carpetas y archivos del proyecto
- Script `bd.sql` con todas las tablas y datos de ejemplo
- Archivos PHP con conexión PDO y endpoints REST básicos
- CSS con tema oscuro inspirado en el wireframe Figma
- JavaScript con manejo de AJAX, animaciones y CRUD dinámico

---

## 4. Ajustes Realizados por el Estudiante

- Adaptación del nombre, datos personales y contenido real
- Ajuste de colores y tipografía según el wireframe propio
- Modificación de credenciales de base de datos del servidor teclab
- Corrección de rutas de archivos según estructura del servidor
- Personalización de proyectos con información real
- Ajuste de URLs de producción en README

---

## 5. Reflexión Crítica

### Utilidad
La IA fue muy útil para generar el esqueleto del proyecto rápidamente, especialmente en partes repetitivas como los endpoints CRUD y la estructura HTML. Redujo considerablemente el tiempo de desarrollo inicial.

### Ventajas
- Acelera la escritura de código boilerplate
- Sugiere buenas prácticas (PDO, prepared statements, sanitización)
- Explica el código generado cuando se lo pide
- Útil para resolver errores específicos

### Limitaciones
- El código generado necesita adaptarse al contexto real (servidor, credenciales, estructura)
- No conoce las restricciones específicas del servidor universitario
- Puede generar código que funciona en teoría pero requiere ajustes prácticos
- No reemplaza entender el código: en la presentación hay que explicar cada parte

### Aprendizaje Obtenido
El uso de IA como herramienta de apoyo, no como reemplazo del aprendizaje, permitió enfocarse en entender la lógica del proyecto en lugar de escribir sintaxis repetitiva. Sin embargo, cada fragmento de código generado fue revisado, probado y ajustado manualmente, lo que reforzó la comprensión de PHP, MySQL y JavaScript.
