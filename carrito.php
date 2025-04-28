<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <title>Carrito</title>
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            margin: 5px;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background-color: #f9f9f9;
        }
        
        .container {
          width: 50%;
          text-align: center;
        }
        
        .card {
            background: #ffffff;
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 95%;
            height: auto;
            display: flex;
            justify-content: center;
            margin: 0 auto;
        }
        
        .label {
            display: block;
            font-size: 15px;
            color: #333;
            margin-bottom: 5px;
            margin-top: 10px;
        }
        
        .input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 10px;
            background: #f9f9f9;
            transition: border 0.3s;
        }
        
        .input:focus {
            border-color: #ff7f7f;
            outline: none;
            background: #fff;
        }
        
        
        button {
            width: 70%;
            padding: 5px;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background: #ff4d4d;
        }
        
        .content {
            gap: 20px;
            margin: 10px;
        }
        
         h3 {
             font-size:16px;
            text-align: center;
            color: #ff7f7f;
            margin-bottom: 5px;
        }
        
        .button{
            width: 100px;
        }
    </style>
</head>

<body>
    <div id="simulacion" class="container">
        <h3>Simulación de Carrito Diferencial</h3>
        <div class="card">
            <div class="content">
                <label class="label">Voltaje Motor Izquierdo (V)</label>
                <input type="number" id="voltajeIzquierdo" class="input" min="0" max="12" value="0">
                
                <label class="label">Voltaje Motor Derecho (V)</label>
                <input type="number" id="voltajeDerecho" class="input" min="0" max="12" value="0">
                
                <button onclick="simularMovimiento()" style="background-color:#ff7f7f;margin-top:15px;">Aplicar</button>
            </div>
            <div class="container mt-5">
                <h3 style="color: #ff7f7f;">Simulación Gráfica del Vehículo</h3>
            
                <div class="card p-4 my-3">
                    <canvas id="simulationCanvas" width="600" height="400" style="border:1px solid #ccc;"></canvas>
                    <p style="margin-top:10px;">Usa las teclas de flechas (↑ ↓ ← →) para mover el vehículo.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mt-3" style="max-width: 600px;">
        <h3 class="text-danger text-center mb-4">Modelos Cinemático y Dinámico</h3>
    
        <div class="card p-3 mb-3" style="font-size: 14px;">
            <h4 class="mb-2" style="font-size: 18px;">Cinemática</h4>
            <ul class="text-start mb-2">
                <li><b>Posición:</b> \( \dot{x} = v \cos(\theta) \), \( \dot{y} = v \sin(\theta) \)</li>
                <li><b>Orientación:</b> \( \dot{\theta} = \omega \)</li>
                <li><b>Velocidad lineal:</b> \( v = \frac{v_R + v_L}{2} \)</li>
                <li><b>Velocidad angular:</b> \( \omega = \frac{v_R - v_L}{L} \)</li>
            </ul>
        </div>
    
        <div class="card p-3 mb-3" style="font-size: 14px;">
            <h4 class="mb-2" style="font-size: 18px;">Dinámica</h4>
            <ul class="text-start mb-2">
                <li><b>Avance:</b> \( m \dot{v} = F_R + F_L \)</li>
                <li><b>Rotación:</b> \( I_z \dot{\omega} = (F_R - F_L) \times \frac{L}{2} \)</li>
            </ul>
        </div>
    
        <div class="card p-3 text-center" style="font-size: 14px;">
            <h4 class="mb-2" style="font-size: 18px;">Fuerzas (Cuerpo Libre)</h4>
            <div class="center">
                <img src="assets/img/CuerpoLibre.PNG" alt="Diagrama de Cuerpo Libre" class="img-fluid" style="max-width: 30%; height: auto;">
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    
    <script>
        const canvas = document.getElementById("simulationCanvas");
        const ctx = canvas.getContext("2d");
        
        let posX = 300;
        let posY = 200;
        let theta = 0; // orientación (radianes)
        
        let vIzqMax = 0; // velocidad máxima motor izquierdo
        let vDerMax = 0; // velocidad máxima motor derecho
        
        let vIzq = 0;    // velocidad actual izquierda
        let vDer = 0;    // velocidad actual derecha
        
        const L = 50; // distancia entre ruedas
        const keys = {};
        
        function drawVehicle() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(posX, posY);
            ctx.rotate(theta);
            ctx.fillStyle = "blue";
            ctx.fillRect(-20, -10, 40, 20);
            ctx.restore();
        }
        
        function simularMovimiento() {
            const voltajeMax = 12;
            const velMax = 2; // velocidad máxima en pixeles por frame
        
            let voltajeIzq = parseFloat(document.getElementById("voltajeIzquierdo").value);
            let voltajeDer = parseFloat(document.getElementById("voltajeDerecho").value);
        
            voltajeIzq = Math.max(0, Math.min(voltajeIzq, voltajeMax));
            voltajeDer = Math.max(0, Math.min(voltajeDer, voltajeMax));
        
            vIzqMax = (voltajeIzq / voltajeMax) * velMax;
            vDerMax = (voltajeDer / voltajeMax) * velMax;
        }
        
        // Moverse con teclas
        function updateFromKeyboard() {
            if (keys["ArrowUp"]) {
                vIzq = vIzqMax;
                vDer = vDerMax;
            } else if (keys["ArrowDown"]) {
                vIzq = -vIzqMax;
                vDer = -vDerMax;
            } else if (keys["ArrowLeft"]) {
                vIzq = -vIzqMax;
                vDer = vDerMax;
            } else if (keys["ArrowRight"]) {
                vIzq = vIzqMax;
                vDer = -vDerMax;
            } else {
                vIzq = vDer = 0;
            }
        }
        
        // Actualizar posición según vIzq y vDer
        function updatePosition() {
            const dt = 1;
        
            const v = (vIzq + vDer) / 2;
            const omega = (vDer - vIzq) / L;
        
            theta += omega * dt;
            posX += v * Math.cos(theta) * dt;
            posY += v * Math.sin(theta) * dt;
        }
        
        window.addEventListener("keydown", (e) => { keys[e.key] = true; });
        window.addEventListener("keyup", (e) => { keys[e.key] = false; });
        
        function loop() {
            updateFromKeyboard();
            updatePosition();
            drawVehicle();
            requestAnimationFrame(loop);
        }
        
        drawVehicle();
        loop();
        
        </script>
</body>
</html>