<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Ticket</title>
</head>
<body style="background-color: #f5f5f5; font-family: Arial, sans-serif; margin: 0; padding: 20px; text-align: center;">

    <div style="max-width: 600px; background-color: #ffffff; padding: 20px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <!-- Logo -->
        <img src="https://patios.mastic.com.co/img/logo.png" alt="Logo" style="width: 12rem; height: 12rem; margin-bottom: 20px;">

        <!-- Título -->
        <h2 style="color: #333;">Se ha realizado seguimiento sobre su ticket Nro. {{ $numTicket }}</h2>

        <!-- Detalles del ticket -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;"><strong>Empresa:</strong></td>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $company }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;"><strong>Ubicación:</strong></td>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $location }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;"><strong>Categoría:</strong></td>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $category }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;"><strong>Subcategoría:</strong></td>
                <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $category2 }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding: 10px;"><strong>Descripción:</strong></td>
                <td style="text-align: left; padding: 10px;">{{ $text }}</td>
            </tr>            
            <tr>
                <td style="text-align: left; padding: 10px;"><strong>Tipo Seguimiento:</strong></td>
                <td style="text-align: left; padding: 10px;">{{ $type }}</td>
            </tr>
            <tr>
                <td style="text-align: left; padding: 10px;"><strong>Seguimiento:</strong></td>
                <td style="text-align: left; padding: 10px;">{{ $resolve }}</td>
            </tr>
        </table>

        <!-- Botón de acceso -->
        <div style="margin-top: 20px;">
            <a href="https://patios.mastic.com.co/tickets/show/{{ $numTicket }}" target="_blank" style="background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">Ver Ticket</a>
        </div>
    </div>

</body>
</html>
